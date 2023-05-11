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
use Oveleon\ContaoBergheimBundle\POI;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/api/poi", defaults={"_scope": "frontend", "_token_check": false})
 */
class PoiController extends AbstractController
{
    const STATUS_ZERO_RESULTS = 'ZERO_RESULTS';
    const STATUS_OK = 'OK';

    public function __construct(
        protected ContaoFramework $framework,
        protected RequestStack $requestStack,
        protected TranslatorInterface $translator
    ){}

    /**
     * @Route("/read/{id}", name="poi_read")
     */
    public function read(int $id): JsonResponse
    {
        $this->framework->initialize();

        System::loadLanguageFile('tl_bm_poi');

        $objPoi = PoiModel::findByPk($id);

        if ($objPoi === null)
        {
            return new JsonResponse([
                'status'        => self::STATUS_ZERO_RESULTS,
                'error_message' => 'No results found'
            ]);
        }

        $poiData = StringUtil::deserialize($objPoi->publishedData, true);

        // Declare template var to check if we have valid opening hours
        $hasOpeningHours = false;

        foreach (['openingHoursMonday','openingHoursTuesday','openingHoursWednesday','openingHoursThursday','openingHoursFriday','openingHoursSaturday','openingHoursSunday'] as $field)
        {
            if(!empty(trim($poiData[$field])))
            {
                $hasOpeningHours = true;
                break;
            }
        }

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
        $objTemplate->hasOpeningHours = $hasOpeningHours;
        $objTemplate->detailLink = POI::generateLink($this->translator->trans('tl_bm_poi.detailLinkTitle', [], 'contao_default'), $objPoi);

        return new JsonResponse([
            'status'  => self::STATUS_OK,
            'id'       => $objPoi->id,
            'template' => $objTemplate->parse()
        ]);
    }
}
