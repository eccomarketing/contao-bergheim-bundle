<?php

use Contao\Controller;
use Contao\CoreBundle\DataContainer\PaletteManipulator;

PaletteManipulator::create()
    ->addLegend('poi_template_legend', 'chmod_legend')
    ->addField(['poiTooltipTemplate', 'googleApiToken'], 'poi_template_legend', PaletteManipulator::POSITION_PREPEND)
    ->applyToPalette('default', 'tl_settings')
;

$GLOBALS['TL_DCA']['tl_settings']['fields']['poiTooltipTemplate'] = [
    'inputType'        => 'select',
    'options_callback' => static function ()
    {
        return Controller::getTemplateGroup('poi_tooltip_');
    },
    'eval'             => ['includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50'],
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['googleApiToken'] = [
    'inputType'        => 'text',
    'eval'             => ['tl_class'=>'w50'],
];
