<?php

use Contao\Config;
use Contao\DC_File;

$GLOBALS['TL_DCA']['tl_bm_config'] = array
(
    // Config
    'config' => array
    (
        'dataContainer'               => DC_File::class,
        'closed'                      => true
    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{default_image_legend},defaultImagePoi,defaultImageShowcase;',
    ),

    // Fields
    'fields' => array
    (
        'defaultImagePoi' => array
        (
            'exclude'                 => true,
            'inputType'               => 'fileTree',
            'eval'                    => array('fieldType'=>'radio', 'filesOnly'=>true, 'isGallery'=>true, 'extensions'=>Config::get('validImageTypes'), 'tl_class'=>'w50'),
        ),
        'defaultImageShowcase' => array
        (
            'exclude'                 => true,
            'inputType'               => 'fileTree',
            'eval'                    => array('fieldType'=>'radio', 'filesOnly'=>true, 'isGallery'=>true, 'extensions'=>Config::get('validImageTypes'), 'tl_class'=>'w50'),
        ),
    )
);
