<?php

declare(strict_types=1);

namespace Oveleon\ContaoBergheimBundle\Controller\FrontendModule;

use Contao\Config;
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\Exception\InternalServerErrorException;
use Contao\CoreBundle\Exception\PageNotFoundException;
use Contao\CoreBundle\Routing\ResponseContext\HtmlHeadBag\HtmlHeadBag;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\Environment;
use Contao\File;
use Contao\FilesModel;
use Contao\FrontendTemplate;
use Contao\Input;
use Contao\ModuleModel;
use Contao\Pagination;
use Contao\StringUtil;
use Contao\System;
use Contao\Template;
use Oveleon\ContaoBergheimBundle\Model\PoiModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @FrontendModule(type=PoiReaderController::TYPE, category="bergheim")
 */
class PoiReaderController extends AbstractFrontendModuleController
{
    /**
     * Frontend Module Type
     */
    public const TYPE = 'poi_reader';

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
     * Template
     */
    protected string $strTemplate = 'mod_poi_reader';

    /**
     * Create Frontend Module
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }


    /**
     * Compile Frontend Module
     */
    protected function compile(): void
    {
        // Set the item from the auto_item parameter
        if (!isset($_GET['items']) && isset($_GET['auto_item']) && Config::get('useAutoItem'))
        {
            Input::setGet('items', Input::get('auto_item'));
        }

        if (!Input::get('items'))
        {
            return;
        }

        $objPoi = PoiModel::findPublishedByIdOrAlias(Input::get('items'));
        $isAccessible = $this->isAccessible($objPoi);

        // The poi item does not exist
        if ($objPoi === null || !$isAccessible)
        {
            throw new PageNotFoundException('Page not found: ' . Environment::get('uri'));
        }

        $arrPoiData = StringUtil::deserialize($objPoi->publishedData, true);
        $objPoi = (object) $arrPoiData;

        // Overwrite the page metadata
        $responseContext = System::getContainer()->get('contao.routing.response_context_accessor')->getResponseContext();

        if ($responseContext && $responseContext->has(HtmlHeadBag::class))
        {
            /** @var HtmlHeadBag $htmlHeadBag */
            $htmlHeadBag = $responseContext->get(HtmlHeadBag::class);
            $htmlDecoder = System::getContainer()->get('contao.string.html_decoder');

            if ($objPoi->pageTitle)
            {
                $htmlHeadBag->setTitle($objPoi->pageTitle); // Already stored decoded
            }
            elseif ($objPoi->title)
            {
                $htmlHeadBag->setTitle($htmlDecoder->inputEncodedToPlainText($objPoi->title));
            }

            if ($objPoi->metaDescription)
            {
                $htmlHeadBag->setMetaDescription($htmlDecoder->inputEncodedToPlainText($objPoi->metaDescription));
            }
            elseif ($objPoi->teaser)
            {
                $htmlHeadBag->setMetaDescription($htmlDecoder->htmlToPlainText($objPoi->teaser));
            }

            if ($objPoi->robots)
            {
                $htmlHeadBag->setMetaRobots($objPoi->robots);
            }
        }

        foreach ($arrPoiData as $name => $value)
        {
            $this->template->{$name} = $value;
        }

        if (!empty($objPoi->cssClass))
        {
            $this->template->class .= ' ' . $objPoi->cssClass;
        }

        $this->template->hasSubTitle = $objPoi->subtitle ? true : false;

        if (!empty($objPoi->street) && !empty($objPoi->postal) && !empty($objPoi->city))
        {
            $this->template->addAddress = true;

            $this->template->address = $objPoi->street . ' ' . $objPoi->houseNumber . '<br>' . $objPoi->postal . ' ' . $objPoi->city;
        }

        if ($objPoi->openingHours !== null)
        {
            $this->template->addOpeningHours = true;
        }

        if ($objPoi->extraDescription !== null)
        {
            $this->template->addExtraDescription = true;
        }

        if ($objPoi->lat && $objPoi->lng)
        {
            $this->template->addMap = true;

            $lat = floatval($objPoi->lat);
            $lng = floatval($objPoi->lng);

            $zoom = 0.003;

            $this->template->lat1 = $lat-$zoom;
            $this->template->lat2 = $lat+$zoom;
            $this->template->lng1 = $lng-$zoom;
            $this->template->lng2 = $lng+$zoom;
        }

        // Override the default image size
        if ($this->model->imgSize)
        {
            $size = StringUtil::deserialize($this->model->imgSize);

            if ($size[0] > 0 || $size[1] > 0 || is_numeric($size[2]) || ($size[2][0] ?? null) === '_')
            {
                $imgSize = $this->model->imgSize;
            }
        }

        $imageSrc = $objPoi->mainImageSRC ?: Config::get('defaultImage'.ucfirst($objPoi->type));

        $figureBuilder = System::getContainer()
            ->get('contao.image.studio')
            ->createFigureBuilder()
            ->from($imageSrc)
            ->setSize($imgSize);

        $figure = $figureBuilder->build();
        $figure->applyLegacyTemplateData($this->template);

        $objFiles = FilesModel::findMultipleByUuids(StringUtil::deserialize($objPoi->imagesSRC, true));

        if ($objFiles !== null)
        {
            $this->template->addImages = true;

            if ($this->model->poi_imgSize)
            {
                $size = StringUtil::deserialize($this->model->poi_imgSize);

                if ($size[0] > 0 || $size[1] > 0 || is_numeric($size[2]) || ($size[2][0] ?? null) === '_')
                {
                    $imgSize = $this->model->poi_imgSize;
                }
            }

            $images = [];

            foreach ($objFiles as $objFile)
            {
                // Continue if the files has been processed or does not exist
                if (isset($images[$objFile->path]) || !file_exists(System::getContainer()->getParameter('kernel.project_dir') . '/' . $objFile->path))
                {
                    continue;
                }

                // Add the image
                $images[$objFile->path] = $objFile;
            }

            $images = array_values($images);

            $body = [];

            $figureBuilder = System::getContainer()
                ->get('contao.image.studio')
                ->createFigureBuilder()
                ->setSize($imgSize)
                ->setLightboxGroupIdentifier('lb' . $this->model->id);

            foreach ($images as $index => $objImage)
            {
                $figure = $figureBuilder
                    ->fromFilesModel($objImage)
                    ->build();

                $cellData = $figure->getLegacyTemplateData();
                $cellData['figure'] = $figure;
                $cellData['class'] = 'col_' . $index;

                $body[] = (object) $cellData;
            }

            $this->template->body = $body;
        }

        if ($objPoi->logoSRC !== null)
        {
            $objLogo = FilesModel::findByUuid($objPoi->logoSRC);

            if ($objLogo !== null)
            {
                $this->template->addLogo = true;

                if ($this->model->poi_imgSizeLogo)
                {
                    $size = StringUtil::deserialize($this->model->poi_imgSizeLogo);

                    if ($size[0] > 0 || $size[1] > 0 || is_numeric($size[2]) || ($size[2][0] ?? null) === '_')
                    {
                        $imgSize = $this->model->poi_imgSizeLogo;
                    }
                }

                $figureBuilder = System::getContainer()
                    ->get('contao.image.studio')
                    ->createFigureBuilder()
                    ->setSize($imgSize)
                    ->setLightboxGroupIdentifier('lb' . $this->model->id);

                $figure = $figureBuilder
                    ->fromFilesModel($objLogo)
                    ->build();

                $this->template->logo = $figure->getLegacyTemplateData();;
            }
        }

        // Tag the poi
        if (System::getContainer()->has('fos_http_cache.http.symfony_response_tagger'))
        {
            $responseTagger = System::getContainer()->get('fos_http_cache.http.symfony_response_tagger');
            $responseTagger->addTags(array('contao.db.tl_bm_poi.' . $objPoi->id));
        }
    }

    /**
     * Check if record can be displayed by reader
     */
    protected function isAccessible(PoiModel $objPoi): bool
    {
        switch ($this->model->poi_listMode)
        {
            case 'branches':
                $arrBranches = StringUtil::deserialize($this->model->poi_branches, true);

                return in_array($objPoi->branch, $arrBranches);
            case 'categories':
                return true; // ToDo: Muss noch überprüft werden.
        }

        return true;
    }

    /**
     * Return the template
     */
    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
    {
        $this->model = $model;
        $this->request = $request;
        $this->template = $template;

        // Compile module
        $this->compile();

        // Return an empty string if "items" is not set (to combine list and reader on same page)
        if (!Input::get('items'))
        {
            return new Response('');;
        }

        return new Response($this->template->parse());
    }
}