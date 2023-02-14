<?php

declare(strict_types=1);

namespace Oveleon\ContaoBergheimBundle\Controller\FrontendModule;

use Contao\Controller;
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\ModuleModel;
use Contao\StringUtil;
use Contao\Template;
use Oveleon\ContaoBergheimBundle\Model\BranchModel;
use Oveleon\ContaoBergheimBundle\Model\CategoryModel;
use Oveleon\ContaoBergheimBundle\Model\PoiModel;
use Oveleon\ContaoBergheimBundle\Model\TagModel;
use Oveleon\ContaoBergheimBundle\POI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @FrontendModule(type=PoiMapController::TYPE, category="bergheim")
 */
class PoiMapController extends AbstractFrontendModuleController
{
    /**
     * Frontend Module Type
     */
    public const TYPE = 'poi_map';

    /**
     * Translator
     */
    protected TranslatorInterface $translator;

    /**
     * Module Model
     */
    protected ModuleModel $model;

    /**
     * Request
     */
    protected Request $request;

    /**
     * Template
     */
    protected Template $template;

    /**
     * GeoJSON
     */
    private ?array $geoJson = null;

    /**
     * Create Frontend Module
     */
    public function __construct(TranslatorInterface $translator)
    {
        Controller::loadLanguageFile('tl_bm_poi');
        Controller::loadDataContainer('tl_bm_poi');

        $this->translator = $translator;
    }

    /**
     * Return the template
     */
    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
    {
        $this->model = $model;
        $this->request = $request;
        $this->template = $template;

        $this->createMapConfig();
        $this->createFilterWidgets();

        return new Response($this->template->parse());
    }

    /**
     * Create filter widgets
     */
    protected function createFilterWidgets(): void
    {
        // Create type filter
        $types = [];
        $branches = [];
        $categories = [];
        $tags = [];
        $quickFilter = [];

        foreach ($GLOBALS['TL_DCA']['tl_bm_poi']['fields']['type']['options'] ?? [] as $type)
        {
            $types[$type] = $this->translator->trans('tl_bm_poi.' . $type, [], 'contao_default');
        }

        // Create branches filter
        if($branchesCollection = BranchModel::findAll())
        {
            foreach ($branchesCollection as $branch)
            {
                $branches[$branch->id] = $branch->title;
            }
        }

        // Create categories filter
        if($categoryCollection = CategoryModel::findAll())
        {
            foreach ($categoryCollection as $category)
            {
                $categories[$category->id] = $category->title;
            }
        }

        // Create tag and quick filter
        if($tagCollection = TagModel::findAll())
        {
            foreach ($tagCollection as $tag)
            {
                if($tag->favorite)
                {
                    $quickFilter[$tag->id] = $tag->title;
                    continue;
                }

                $tags[$tag->id] = $tag->title;
            }
        }

        $this->template->typeFilterLabel = $this->translator->trans('tl_bm_poi.typeFilterLabel', [], 'contao_default');
        $this->template->branchFilterLabel = $this->translator->trans('tl_bm_poi.branchFilterLabel', [], 'contao_default');
        $this->template->categoryFilterLabel = $this->translator->trans('tl_bm_poi.categoryFilterLabel', [], 'contao_default');
        $this->template->tagFilterLabel = $this->translator->trans('tl_bm_poi.tagFilterLabel', [], 'contao_default');

        $this->template->typeFilter = $types;
        $this->template->branchFilter = $branches;
        $this->template->categoryFilter = $categories;
        $this->template->tagFilter = $tags;
        $this->template->quickFilter = $quickFilter;
    }

    /**
     * Create map config
     */
    protected function createMapConfig(): void
    {
        $poiCollection = PoiModel::findPublished();

        foreach ($poiCollection as $objPoi)
        {
            // Use published data
            $poi = (object) StringUtil::deserialize($objPoi->publishedData, true);

            // Skip if poi has no geo data
            if(!$poi->lat || !$poi->lng)
            {
                continue;
            }

            // Add new feature node to geojson
            $this->addGeoJsonFeature($poi);
        }

        $this->template->mapOptions = json_encode([
            'zoom'    => (int) $this->model->map_zoom ?: 6,
            'maxZoom' => (int) $this->model->map_max ?: 10,
            'minZoom' => (int) $this->model->map_min ?: 4,
            'center' => [
                (float) $this->model->map_latitude ?: 51.165691,
                (float) $this->model->map_longitude ?: 10.451526
            ]
        ]);

        $this->template->geoJson = json_encode($this->geoJson);
    }

    /**
     * Add GeoJSON Feature node
     */
    protected function addGeoJsonFeature($poi): void
    {
        if(null === $this->geoJson)
        {
            $this->geoJson =  [
                'type'     => 'FeatureCollection',
                'features' => []
            ];
        }

        $this->geoJson['features'][] = [
            'type'     => 'Feature',
            'geometry' => [
                'type'        => 'Point',
                'coordinates' => [
                    $poi->lng,
                    $poi->lat
                ]
            ],
            'properties'  => [
                'id'         => $poi->id,
                'type'       => $poi->type,
                'branch'     => $poi->branch,
                'categories' => array_map('intval', StringUtil::deserialize($poi->categories, true)),
                'tags'       => array_map('intval', StringUtil::deserialize($poi->tags, true)),
                'latLng'     => [$poi->lat, $poi->lng],
                'title'      => $poi->title,
                'postal'     => $poi->postal,
                'url'        => POI::getUrl($poi)
            ]
        ];
    }
}