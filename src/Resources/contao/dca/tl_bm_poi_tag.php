<?php

$GLOBALS['TL_DCA']['tl_bm_poi_tag'] = array
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
                'pid,tid' => 'index'
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
        'tid' => array
        (
            'foreignKey'              => 'tl_bm_tag.title',
            'sql'                     => "int(10) unsigned NOT NULL default 0"
        ),
    )
);
