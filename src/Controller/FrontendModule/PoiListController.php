<?php

declare(strict_types=1);

namespace Oveleon\ContaoBergheimBundle\Controller\FrontendModule;

use Contao\Config;
use Contao\Controller;
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\Exception\PageNotFoundException;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\Environment;
use Contao\FrontendTemplate;
use Contao\Input;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\Pagination;
use Contao\StringUtil;
use Contao\System;
use Contao\Template;
use Oveleon\ContaoBergheimBundle\Model\BranchModel;
use Oveleon\ContaoBergheimBundle\Model\PoiModel;
use Oveleon\ContaoBergheimBundle\Model\TagModel;
use Oveleon\ContaoBergheimBundle\POI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @FrontendModule(type=PoiListController::TYPE, category="bergheim")
 */
class PoiListController extends AbstractFrontendModuleController
{
    /**
     * Frontend Module Type
     */
    public const TYPE = 'poi_list';

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
        System::loadLanguageFile('tl_bm_poi');

        $this->list();

        $this->template->empty = $this->translator->trans('tl_bm_poi.list.empty', [], 'contao_default');

        if ($this->model->poi_addTags)
        {
            $this->template->addTags = true;

            $objAllPois = $this->fetchItems(0, 0, false);

            $this->template->tags = $this->getTags($objAllPois);
        }

    }

    /**
     * List poi records and handle pagination
     */
    protected function list(): void
    {
        $limit = null;
        $offset = (int) $this->model->skipFirst;

        // Maximum number of items
        if ($this->model->numberOfItems > 0)
        {
            $limit = $this->model->numberOfItems;
        }

        $this->template->items = [];

        // Get the total number of items
        $intTotal = $this->countItems();

        if ($intTotal < 1)
        {
            return;
        }

        $total = $intTotal - $offset;

        // Split the results
        if ($this->model->perPage > 0 && (!isset($limit) || $this->model->numberOfItems > $this->model->perPage))
        {
            // Adjust the overall limit
            if (isset($limit))
            {
                $total = min($limit, $total);
            }

            // Get the current page
            $id = 'page_poi' . $this->model->id;
            $page = $this->request->get($id, 1);

            // Do not index or cache the page if the page number is outside the range
            if ($page < 1 || $page > max(ceil($total/$this->model->perPage), 1))
            {
                throw new PageNotFoundException('Page not found: ' . Environment::get('uri'));
            }

            // Set limit and offset
            $limit = $this->model->perPage;
            $offset += (max($page, 1) - 1) * $this->model->perPage;
            $skip = (int) $this->model->skipFirst;

            // Overall limit
            if ($offset + $limit > $total + $skip)
            {
                $limit = $total + $skip - $offset;
            }

            // Add the pagination menu
            $objPagination = new Pagination($total, $this->model->perPage, Config::get('maxPaginationLinks'), $id);
            $this->template->pagination = $objPagination->generate("\n  ");
        }

        $objPois = $this->fetchItems((int) ($limit ?: 0), $offset);

        // Add the leads
        if ($objPois !== null)
        {
            $arrPois = [];

            foreach ($objPois as $objPoi)
            {
                $arrPois[] = $this->parseItem($objPoi);
            }

            $this->template->items = $arrPois;
        }
    }

    /**
     * Count poi records
     */
    protected function countItems(): int
    {
        $arrTags = Input::get('tag') ?? [];

        if ($this->model->poi_addTags && count($arrTags) > 0)
        {
            switch ($this->model->poi_listMode)
            {
                case 'branches':
                    $arrBranches = StringUtil::deserialize($this->model->poi_branches, true);

                    return PoiModel::countPublishedByBranchesAndTags($arrBranches, $arrTags);
                case 'categories':
                    $arrCategories = StringUtil::deserialize($this->model->poi_categories, true);

                    return PoiModel::countPublishedByCategoriesAndTags($arrCategories, $arrTags);
            }

            return PoiModel::countPublishedByTags($arrTags);
        }

        switch ($this->model->poi_listMode)
        {
            case 'branches':
                $arrBranches = StringUtil::deserialize($this->model->poi_branches, true);

                return PoiModel::countPublishedByBranches($arrBranches);
            case 'categories':
                $arrCategories = StringUtil::deserialize($this->model->poi_categories, true);

                return PoiModel::countPublishedByCategories($arrCategories);
        }

        return PoiModel::countPublished();
    }

    /**
     * Fetch the matching items
     */
    protected function fetchItems($limit, $offset, $considerTags=true)
    {
        $arrTags = Input::get('tag') ?? [];

        if ($this->model->poi_addTags && $considerTags && count($arrTags) > 0)
        {
            switch ($this->model->poi_listMode)
            {
                case 'branches':
                    $arrBranches = StringUtil::deserialize($this->model->poi_branches, true);

                    return PoiModel::findPublishedByBranchesAndTags($arrBranches, $arrTags, $limit, $offset);
                case 'categories':
                    $arrCategories = StringUtil::deserialize($this->model->poi_categories, true);

                    return PoiModel::findPublishedByCategoriesAndTags($arrCategories, $arrTags, $limit, $offset);
            }

            return PoiModel::findPublishedByTags($arrTags, $limit, $offset);
        }

        switch ($this->model->poi_listMode)
        {
            case 'branches':
                $arrBranches = StringUtil::deserialize($this->model->poi_branches, true);

                return PoiModel::findPublishedByBranches($arrBranches, $limit, $offset);
            case 'categories':
                $arrCategories = StringUtil::deserialize($this->model->poi_categories, true);

                return PoiModel::findPublishedByCategories($arrCategories, $limit, $offset);
        }

        return PoiModel::findPublished($limit, $offset);
    }

    /**
     * Parse a single lead item
     */
    protected function parseItem(PoiModel $objPoi, $strClass=''): string
    {
        $arrPoiData = StringUtil::deserialize($objPoi->publishedData, true);
        $objPoi = (object) $arrPoiData;

        $objTemplate = new FrontendTemplate($this->model->poi_template ?: 'poi_item_default');
        $objTemplate->setData($arrPoiData);

        if ($objPoi->cssClass)
        {
            $strClass = ' ' . $objPoi->cssClass . $strClass;
        }

        $objTemplate->class = $strClass;
        $objTemplate->hasSubTitle = $objPoi->subtitle ? true : false;
        $objTemplate->linkTitle = POI::generateLink($objPoi->title, $objPoi);
        $objTemplate->linkMore = POI::generateLink($GLOBALS['TL_LANG']['MSC']['linkMore'], $objPoi);
        $objTemplate->link = POI::getUrl($objPoi);

        if (empty($objPoi->teaser))
        {
            $objTemplate->teaser = $objPoi->description;
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

        if (null !== ($figure = $figureBuilder->buildIfResourceExists()))
        {
            // Rebuild with link to news article if none is set
            if (!$figure->getLinkHref())
            {
                $linkTitle = StringUtil::specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['poi_readMore'], $objPoi->title), true);

                $figure = $figureBuilder
                    ->setLinkHref($objTemplate->link)
                    ->setLinkAttribute('title', $linkTitle)
                    ->build();
            }

            $figure->applyLegacyTemplateData($objTemplate);
        }

        // Tag the poi
        if (System::getContainer()->has('fos_http_cache.http.symfony_response_tagger'))
        {
            $responseTagger = System::getContainer()->get('fos_http_cache.http.symfony_response_tagger');
            $responseTagger->addTags(array('contao.db.tl_bm_poi.' . $objPoi->id));
        }

        return $objTemplate->parse();
    }

    protected function getTags($objPois): array
    {
        if ($objPois === null)
        {
            return [];
        }

        $arrTags = [];
        $tags = [];

        $objTags = TagModel::findAll();

        foreach ($objTags as $objTag)
        {
            $tags[$objTag->id] = $objTag->title;
        }

        foreach ($objPois as $objPoi)
        {
            $poiTags = StringUtil::deserialize($objPoi->tags, true);
            $activeTags = Input::get('tag') ?? [];

            foreach ($poiTags as $tagID)
            {
                if (!in_array($tagID, $arrTags))
                {
                    $arrTags[$tags[$tagID]]['class'] = in_array($tagID, $activeTags) ? 'active' : '';
                    $arrTags[$tags[$tagID]]['url'] = strtok($this->request->getUri(), '?') . '?tag[0]=' . $tagID;
                }
            }
        }

        // ToDo. Remove reset button with toggle feature of tags
        $arrTags['Reset']['url'] = strtok($this->request->getUri(), '?');
        $arrTags['Reset']['class'] = '';

        return $arrTags;
    }

    /**
     * Return the template
     */
    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
    {
        $this->model = $model;
        $this->request = $request;
        $this->template = $template;

        // Show the poi reader if an item has been selected
        if ($this->model->poi_readerModule > 0 && (isset($_GET['items']) || (Config::get('useAutoItem') && isset($_GET['auto_item']))))
        {
            $controller = $this->container->get('contao.framework')->getAdapter(Controller::class);

            return new Response($controller->getFrontendModule($this->model->poi_readerModule));
        }

        // Compile module
        $this->compile();

        return new Response($this->template->parse());
    }
}
