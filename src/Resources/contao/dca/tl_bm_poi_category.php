<?php

$GLOBALS['TL_DCA']['tl_bm_poi_category'] = array
(
     // Config
    'config' => array
    (
        'dataContainer'               => DC_Table::class,
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'pid,cid' => 'index'
            )
        )
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'pid' => array
        (
            'foreignKey'              => 'tl_bm_poi.title',
            'sql'                     => "int(10) unsigned NOT NULL default 0"
        ),
        'cid' => array
        (
            'foreignKey'              => 'tl_bm_category.title',
            'sql'                     => "int(10) unsigned NOT NULL default 0"
        ),
    )
);
