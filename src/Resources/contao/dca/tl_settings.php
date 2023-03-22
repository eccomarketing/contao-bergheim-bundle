<?php

use Contao\BackendUser;
use Contao\Controller;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\System;

PaletteManipulator::create()
    ->addLegend('bergheim_legend', 'chmod_legend')
    ->addField(['bergheimPoiTooltipTemplate', 'bergheimPoiTooltipSize', 'bergheimGoogleApiToken'], 'poi_template_legend', PaletteManipulator::POSITION_PREPEND)
    ->applyToPalette('default', 'tl_settings')
;

$GLOBALS['TL_DCA']['tl_settings']['fields']['bergheimPoiTooltipTemplate'] = [
    'inputType'        => 'select',
    'options_callback' => static function ()
    {
        return Controller::getTemplateGroup('poi_tooltip_');
    },
    'eval'             => ['includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50'],
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['bergheimPoiTooltipSize'] = [
    'label'            => &$GLOBALS['TL_LANG']['MSC']['imgSize'],
    'inputType'        => 'imageSize',
    'reference'        => &$GLOBALS['TL_LANG']['MSC'],
    'eval'             => array('rgxp'=>'natural', 'includeBlankOption'=>true, 'nospace'=>true, 'helpwizard'=>true, 'tl_class'=>'w50'),
    'options_callback' => static function ()
    {
        return System::getContainer()->get('contao.image.sizes')->getOptionsForUser(BackendUser::getInstance());
    },
];


$GLOBALS['TL_DCA']['tl_settings']['fields']['bergheimGoogleApiToken'] = [
    'inputType'        => 'text',
    'eval'             => ['tl_class'=>'w50'],
];
