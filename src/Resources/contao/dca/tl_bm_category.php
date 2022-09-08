<?php

use Contao\DC_Table;
use Contao\DataContainer;

$GLOBALS['TL_DCA']['tl_bm_category'] = array
(
    // Config
    'config' => array
    (
        'dataContainer'               => DC_Table::class,
        'enableVersioning'            => true,
        'markAsCopy'                  => 'title',
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary'
            )
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => DataContainer::MODE_SORTABLE,
            'fields'                  => array('title'),
            'panelLayout'             => 'sort,search,limit',
        ),
        'label' => array
        (
            'fields'                  => array('title', 'tstamp'),
            'showColumns'             => true
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'href'                => 'act=edit',
                'icon'                => 'edit.svg',
            ),
            'copy' => array
            (
                'href'                => 'act=copy',
                'icon'                => 'copy.svg'
            ),
            'delete' => array
            (
                'href'                => 'act=delete',
                'icon'                => 'delete.svg',
                'attributes'          => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null) . '\'))return false;Backend.getScrollOffset()"',
            ),
            'show' => array
            (
                'href'                => 'act=show',
                'icon'                => 'show.svg'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{title_legend},title;{icon_legend},iconSRC;',
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
            'sorting'                 => true,
            'flag'                    => DataContainer::SORT_DAY_DESC,
            'eval'                    => array('rgxp'=>'datim'),
            'sql'                     => "int(10) unsigned NOT NULL default 0"
        ),
        'title' => array
        (
            'exclude'                 => true,
            'inputType'               => 'text',
            'sorting'                 => true,
            'search'                  => true,
            'eval'                    => ['mandatory'=>true, 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'iconSRC' => array
        (
            'exclude'                 => true,
            'inputType'               => 'fileTree',
            'eval'                    => ['filesOnly'=>true, 'fieldType'=>'radio', 'mandatory'=>true, 'tl_class'=>'clr'],
            'sql'                     => "binary(16) NULL"
        )
    )
);
