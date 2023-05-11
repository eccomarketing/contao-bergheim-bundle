<?php

declare(strict_types=1);

namespace Oveleon\ContaoBergheimBundle\Controller\FrontendModule;

use Contao\Controller;
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\FilesModel;
use Contao\Model;
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

        $filterSections = StringUtil::deserialize($this->model->poi_filterSections, true);

        $hasBranchFilter = in_array('branch', $filterSections);
        $hasCategoryFilter = in_array('category', $filterSections);

        foreach ($GLOBALS['TL_DCA']['tl_bm_poi']['fields']['type']['options'] ?? [] as $type)
        {
            $types[$type] = $this->translator->trans('tl_bm_poi.' . $type, [], 'contao_default');
        }

        // Collect only used branches, categories and tags
        if ($poiCollection = PoiModel::findPublished())
        {
            foreach ($poiCollection as $poi)
            {
                $poiData = StringUtil::deserialize($poi->publishedData);
                array_push($branches, $poiData['branch']);

                $categories = array_merge($categories, StringUtil::deserialize($poiData['categories'], true));
                $tags = array_merge($tags, StringUtil::deserialize($poiData['tags'], true));
            }

            // Remove all duplicates
            $branches = array_unique($branches);
            $categories = array_unique($categories);
            $tags = array_unique($tags);
        }

        // Create branches filter
        if($hasBranchFilter && $branchesCollection = BranchModel::findMultipleByIds($branches, ['order'=>'title']))
        {
            $branches = [];

            foreach ($branchesCollection as $branch)
            {
                $this->extendIconPath($branch);
                $branches[$branch->id] = $branch;
            }
        }else $branches = [];

        // Create categories filter
        if($hasCategoryFilter && $categoryCollection = CategoryModel::findMultipleByIds($categories, ['order'=>'title']))
        {
            $categories = [];

            foreach ($categoryCollection as $category)
            {
                $this->extendIconPath($category);
                $categories[$category->id] = $category;
            }
        }else $categories = [];

        // Create tag and quick filter
        if($tagCollection = TagModel::findMultipleByIds($tags, ['order'=>'title']))
        {
            $tags = [];

            foreach ($tagCollection as $tag)
            {
                if($tag->favorite)
                {
                    $this->extendIconPath($tag);
                    $quickFilter[$tag->id] = $tag;
                    continue;
                }

                $tags[$tag->id] = $tag;
            }
        }

        $this->template->typeFilterLabel = $this->translator->trans('tl_bm_poi.typeFilterLabel', [], 'contao_default');
        $this->template->branchFilterLabel = $this->translator->trans('tl_bm_poi.branchFilterLabel', [], 'contao_default');
        $this->template->categoryFilterLabel = $this->translator->trans('tl_bm_poi.categoryFilterLabel', [], 'contao_default');
        $this->template->tagFilterLabel = $this->translator->trans('tl_bm_poi.tagFilterLabel', [], 'contao_default');

        $this->template->typeFilter = $types;
        $this->template->hasBranchFilter = $hasBranchFilter;
        $this->template->hasCategoryFilter = $hasCategoryFilter;
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
            'gestureHandling' => true,
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
                'id'         => (string) $poi->id,
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

    /**
     * Extends the icon path to the model.
     */
    protected function extendIconPath(Model $model): void
    {
        $model->hasIcon = false;

        if($model->iconSRC && ($file = FilesModel::findByUuid($model->iconSRC)))
        {
            $model->icon = '/' . $file->path;
            $model->hasIcon = true;
        }
    }
}
