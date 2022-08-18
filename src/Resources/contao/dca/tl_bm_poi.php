<?php
use Contao\System;
use Contao\DC_Table;
use Contao\DataContainer;
use Contao\CoreBundle\EventListener\Widget\HttpUrlListener;

System::loadLanguageFile('tl_bm_poi');

$GLOBALS['TL_DCA']['tl_bm_poi'] = array
(
    // Config
    'config' => array
    (
        'dataContainer'               => DC_Table::class,
        'enableVersioning'            => true,
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'type' => 'index'
            )
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => DataContainer::MODE_UNSORTED,
            'flag'                    => 1
        ),
        'label' => array
        (
            'fields'                  => array('id', 'title', 'type'),
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
                'href'                => 'act=paste&amp;mode=copy',
                'icon'                => 'copy.svg',
                'attributes'          => 'onclick="Backend.getScrollOffset()"',
            ),
            'cut' => array
            (
                'href'                => 'act=paste&amp;mode=cut',
                'icon'                => 'cut.svg',
                'attributes'          => 'onclick="Backend.getScrollOffset()"'
            ),
            'delete' => array
            (
                'href'                => 'act=delete',
                'icon'                => 'delete.svg',
                'attributes'          => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null) . '\'))return false;Backend.getScrollOffset()"',
            ),
            'toggle' => array
            (
                'href'                => 'act=toggle&amp;field=invisible',
                'icon'                => 'visible.svg',
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
        '__selector__'                => ['type'],
        'default'                     => '{config_legend},type;',
        'poi'                         => '{config_legend},title,alias,type;{description_legend},subtitle,teaser,description,extraDescription;{image_legend},logoSRC,mainImageSRC,imagesSRC;{address_legend},company,website,zipCode,city,street,houseNumber,phone,mobilePhone,lat,lng,email,openingHours;',
        'showcase'                    => '{config_legend},title,alias,type;{description_legend},subtitle,teaser,description,extraDescription;{image_legend},logoSRC,mainImageSRC,imagesSRC;{address_legend},company,website,zipCode,city,street,houseNumber,phone,mobilePhone,lat,lng,email,openingHours;{social_media_legend},facebookUrl,instagramUrl,tiktokUrl,youtubeUrl,pinterestUrl,xingUrl,linkedinUrl;{connection_legend},branch,categories;',
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
            'sql'                     => "int(10) unsigned NOT NULL default 0"
        ),
        'type' => array
        (
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'select',
            'options'                 => ['poi', 'showcase'],
            'reference'               => &$GLOBALS['TL_LANG']['tl_bm_poi'],
            'eval'                    => ['chosen' => true, 'submitOnChange'=> true, 'tl_class' => 'w50'],
            'sql'                     => [
                'name'      => 'type',
                'type'      => 'string',
                'length'    => 16,
                'default'   => 'text',
                'customSchemaOptions'=> [
                    'collation' => 'ascii_bin'
                ]
            ]
        ),
        'title' => array
        (
            'exclude'                 => true,
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => ['mandatory'=>true, 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'alias' => array
        (
            'exclude'                 => true,
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => ['rgxp'=>'alias', 'doNotCopy'=>true, 'maxlength'=>255, 'tl_class'=>'w50 clr'],
            /*'save_callback' => array
            (
                array('poi', 'generateAlias')
            ),*/
            'sql'                     => "varchar(255) BINARY NOT NULL default ''"
        ),
        'subtitle' => array
        (
            'exclude'                 => true,
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => ['mandatory'=>true, 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'description' => array
        (
            'exclude'                 => true,
            'inputType'               => 'textarea',
            'search'                  => true,
            'eval'                    => ['rte'=>'tinyMCE', 'tl_class'=>'clr'],
            'sql'                     => "text NULL"
        ),
        'teaser' => array
        (
            'exclude'                 => true,
            'inputType'               => 'textarea',
            'search'                  => true,
            'eval'                    => ['rte'=>'tinyMCE', 'tl_class'=>'clr'],
            'sql'                     => "text NULL"
        ),
        'extraDescription' => array
        (
            'exclude'                 => true,
            'inputType'               => 'textarea',
            'search'                  => true,
            'eval'                    => ['rte'=>'tinyMCE', 'tl_class'=>'clr'],
            'sql'                     => "text NULL"
        ),
        'logoSRC' => array
        (
            'exclude'                 => true,
            'inputType'               => 'fileTree',
            'eval'                    => ['filesOnly'=>true, 'fieldType'=>'radio', 'mandatory'=>true, 'tl_class'=>'clr'],
            'sql'                     => "binary(16) NULL"
        ),
        'mainImageSRC' => array
        (
            'exclude'                 => true,
            'inputType'               => 'fileTree',
            'eval'                    => ['filesOnly'=>true, 'fieldType'=>'radio', 'mandatory'=>true, 'tl_class'=>'clr'],
            'sql'                     => "binary(16) NULL"
        ),
        'imagesSRC' => array
        (
            'exclude'                 => true,
            'inputType'               => 'fileTree',
            'eval'                    => ['multiple'=>true, 'fieldType'=>'checkbox', 'orderField'=>'orderSRC', 'files'=>true],
            'sql'                     => "blob NULL",
        ),
        'orderSRC' => array
        (
            'sql'                     => "blob NULL"
        ),
        'zipCode' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'city' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'street' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'houseNumber' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'lat' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'lng' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'company' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'phone' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'mobilePhone' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'email' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['mandatory'=>true, 'maxlength'=>255, 'rgxp'=>'email', 'unique'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'website' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['rgxp'=>HttpUrlListener::RGXP_NAME, 'maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'openingHours' => array
        (
            'exclude'                 => true,
            'inputType'               => 'textarea',
            'search'                  => true,
            'eval'                    => ['rte'=>'tinyMCE', 'tl_class'=>'clr'],
            'sql'                     => "text NULL"
        ),
        'facebookUrl' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['mandatory'=>true, 'rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>2048, 'tl_class'=>'w50'],
            'sql'                     => "text NULL"
        ),
        'instagramUrl' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['mandatory'=>true, 'rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>2048, 'tl_class'=>'w50'],
            'sql'                     => "text NULL"
        ),
        'tiktokUrl' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['mandatory'=>true, 'rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>2048, 'tl_class'=>'w50'],
            'sql'                     => "text NULL"
        ),
        'youtubeUrl' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['mandatory'=>true, 'rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>2048, 'tl_class'=>'w50'],
            'sql'                     => "text NULL"
        ),
        'pinterestUrl' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['mandatory'=>true, 'rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>2048, 'tl_class'=>'w50'],
            'sql'                     => "text NULL"
        ),
        'xingUrl' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['mandatory'=>true, 'rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>2048, 'tl_class'=>'w50'],
            'sql'                     => "text NULL"
        ),
        'linkedinUrl' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['mandatory'=>true, 'rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>2048, 'tl_class'=>'w50'],
            'sql'                     => "text NULL"
        ),
        'branch' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'select',
            'eval'                    => ['chosen'=>true, 'tl_class'=>'w50'],
			'foreignKey'              => 'tl_bm_branch.title',
			'sql'                     => "int(10) unsigned NOT NULL default 0",
			'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
        ),
        'categories' => array
        (
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'foreignKey'              => 'tl_bm_category.title',
            'eval'                    => array('mandatory'=>true, 'multiple'=>true, 'tl_class' => 'clr'),
            'sql'                     => "blob NULL",
            'relation'                => array('type'=>'hasMany', 'load'=>'lazy')
        ),
    )
);
