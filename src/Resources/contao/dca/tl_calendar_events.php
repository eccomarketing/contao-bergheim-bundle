<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

// Add fields to tl_calendar_events
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['poi'] = [
    'exclude'                 => true,
    'inputType'               => 'select',
    'foreignKey'              => 'tl_bm_poi.title',
    'eval'                    => array('doNotCopy'=>true, 'chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
    'sql'                     => "int(10) unsigned NOT NULL default 0",
    'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
];

// Extend the default palettes
PaletteManipulator::create()
    ->addField('poi', 'location', PaletteManipulator::POSITION_BEFORE)
    ->applyToPalette('default', 'tl_calendar_events')
    ->applyToPalette('internal', 'tl_calendar_events')
    ->applyToPalette('article', 'tl_calendar_events')
    ->applyToPalette('external', 'tl_calendar_events')
;
