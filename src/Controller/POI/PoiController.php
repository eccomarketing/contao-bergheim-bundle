<?php

namespace Oveleon\ContaoBergheimBundle\Controller\POI;

use Contao\CalendarEventsModel;
use Contao\Config;
use Contao\CoreBundle\Controller\AbstractController;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\FrontendTemplate;
use Contao\StringUtil;
use Contao\System;
use Oveleon\ContaoBergheimBundle\Model\PoiModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/poi", defaults={"_scope": "frontend", "_token_check": false})
 */
class PoiController extends AbstractController
{
    const STATUS_ZERO_RESULTS = 'ZERO_RESULTS';
    const STATUS_OK = 'OK';

    /**
     * ContaoFramework
     */
    protected ContaoFramework $framework;

    /**
     * RequestStack
     */
    protected RequestStack $requestStack;

    protected $requestBody = null;

    public function __construct(ContaoFramework $framework, RequestStack $requestStack)
    {
        $this->framework = $framework;
        $this->requestStack = $requestStack;

        $this->framework->initialize();
    }

    /**
     * @Route("/read/{id}", name="poi_read")
     */
    public function read(int $id): JsonResponse
    {
        $objPoi = PoiModel::findByPk($id);

        if ($objPoi === null)
        {
            return new JsonResponse([
                'status'        => self::STATUS_ZERO_RESULTS,
                'error_message' => 'No results found'
            ]);
        }

        $poiData = StringUtil::deserialize($objPoi->publishedData, true);

        $objTemplate = new FrontendTemplate(Config::get('bergheimPoiTooltipTemplate') ?: 'poi_tooltip_default');
        $objTemplate->setData($poiData);

        if ($poiData['mainImageSRC'])
        {
            $figureBuilder = System::getContainer()
                ->get('contao.image.studio')
                ->createFigureBuilder()
                ->from($poiData['mainImageSRC'])
                ->setSize(Config::get('bergheimPoiTooltipSize'));

            if (null !== ($figure = $figureBuilder->buildIfResourceExists()))
            {
                $objTemplate->addImage = true;

                $figure->applyLegacyTemplateData($objTemplate);
            }
        }

        $cntEvents = CalendarEventsModel::countByPoi($objPoi->id);

        $objTemplate->numberOfEvents = $cntEvents;

        return new JsonResponse([
            'status'  => self::STATUS_OK,
            'id'       => $objPoi->id,
            'template' => $objTemplate->parse()
        ]);
    }
}
