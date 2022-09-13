<?php

use Contao\Backend;
use Contao\DC_Table;
use Contao\DataContainer;
use Contao\CoreBundle\EventListener\Widget\HttpUrlListener;
use Oveleon\ContaoBergheimBundle\POI;
use Oveleon\ContaoBergheimBundle\Tag;

$GLOBALS['TL_DCA']['tl_bm_poi'] = array
(
    // Config
    'config' => array
    (
        'dataContainer'               => DC_Table::class,
        'switchToEdit'                => true,
        'enableVersioning'            => true,
        'markAsCopy'                  => 'title',
        'ondelete_callback' => array
        (
            array(POI::class, 'deleteRelations')
        ),
        'onsubmit_callback' => array
        (
            array(POI::class, 'createAndCleanUpRelations'),
            array(POI::class, 'publishRecord')
        ),
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'alias' => 'index',
                'type,published,start,stop' => 'index'
            )
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => DataContainer::MODE_SORTABLE,
            'fields'                  => array('tstamp'),
            'panelLayout'             => 'filter;sort,search,limit',
        ),
        'label' => array
        (
            'fields'                  => array('title', 'alias', 'type', 'tstamp', 'dirty'),
            'showColumns'             => true
        ),
        'global_operations' => array
        (
            'config' => array
            (
                'href'                => 'do=config',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset()"'
            ),
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
                'icon'                => 'edit.svg'
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
            'toggle' => array
            (
                'href'                => 'act=toggle&amp;field=published',
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
        'poi'                         => '{title_legend},title,author,alias,type;{meta_legend},pageTitle,robots,metaDescription,serpPreview;{description_legend},subtitle,teaser,description,extraDescription;{image_legend},mainImageSRC,imagesSRC;{contact_legend},postal,city,street,houseNumber;{geodata_legend:hide},lat,lng;{connection_legend:hide},branch,categories,tags;{expert_legend:hide},cssClass;{publish_legend},published,publishData,start,stop',
        'showcase'                    => '{title_legend},title,author,alias,type;{meta_legend},pageTitle,robots,metaDescription,serpPreview;{description_legend},subtitle,teaser,description,extraDescription;{image_legend},logoSRC,mainImageSRC,imagesSRC;{contact_legend},company,postal,city,street,houseNumber,phone,mobile,email,website,openingHours;{geodata_legend:hide},lat,lng;{social_media_legend:hide},facebookUrl,instagramUrl,tiktokUrl,youtubeUrl,pinterestUrl,xingUrl,linkedinUrl;{connection_legend:hide},branch,categories,tags;{expert_legend:hide},cssClass;{publish_legend},published,publishData,start,stop',
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
        'type' => array
        (
            'exclude'                 => true,
            'filter'                  => true,
            'sorting'                 => true,
            'inputType'               => 'select',
            'options'                 => ['poi', 'showcase'],
            'reference'               => &$GLOBALS['TL_LANG']['tl_bm_poi'],
            'eval'                    => ['chosen' => true, 'submitOnChange'=> true, 'tl_class'=>'w50'],
            'sql'                     => [
                'name'      => 'type',
                'type'      => 'string',
                'length'    => 16,
                'default'   => 'showcase',
                'customSchemaOptions'=> [
                    'collation' => 'ascii_bin'
                ]
            ]
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
        'alias' => array
        (
            'exclude'                 => true,
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => ['rgxp'=>'alias', 'doNotCopy'=>true, 'maxlength'=>255, 'tl_class'=>'w50 clr'],
            'save_callback' => array
            (
                array(POI::class, 'generateAlias')
            ),
            'sql'                     => "varchar(255) BINARY NOT NULL default ''"
        ),
        'author' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'filter'                  => true,
            'sorting'                 => true,
            'flag'                    => DataContainer::SORT_ASC,
            'inputType'               => 'select',
            'foreignKey'              => 'tl_member.username',
            'eval'                    => array('doNotCopy'=>true, 'chosen'=>true, /*'mandatory'=>true, */'includeBlankOption'=>true, 'tl_class'=>'w50'),
            'sql'                     => "int(10) unsigned NOT NULL default 0",
            'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
        ),
        'pageTitle' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'decodeEntities'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'robots' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'select',
            'options'                 => array('index,follow', 'index,nofollow', 'noindex,follow', 'noindex,nofollow'),
            'eval'                    => array('tl_class'=>'w50', 'includeBlankOption' => true),
            'sql'                     => "varchar(32) NOT NULL default ''"
        ),
        'metaDescription' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'textarea',
            'eval'                    => array('style'=>'height:60px', 'decodeEntities'=>true, 'tl_class'=>'clr'),
            'sql'                     => "text NULL"
        ),
        /*'serpPreview' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['MSC']['serpPreview'],
            'exclude'                 => true,
            'inputType'               => 'serpPreview',
            'eval'                    => array('url_callback'=>array('tl_bm_poi', 'getSerpUrl'), 'title_tag_callback'=>array('tl_bm_poi', 'getTitleTag'), 'titleFields'=>array('pageTitle', 'title'), 'descriptionFields'=>array('metaDescription', 'teaser')),
            'sql'                     => null
        ),*/
        'subtitle' => array
        (
            'exclude'                 => true,
            'inputType'               => 'text',
            'search'                  => true,
            'eval'                    => ['decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'description' => array
        (
            'exclude'                 => true,
            'inputType'               => 'textarea',
            'eval'                    => ['mandatory'=>true, 'rte'=>'tinyMCE', 'tl_class'=>'clr'],
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
            'eval'                    => ['rte'=>'tinyMCE', 'tl_class'=>'clr'],
            'sql'                     => "text NULL"
        ),
        'logoSRC' => array
        (
            'exclude'                 => true,
            'inputType'               => 'fileTree',
            'eval'                    => ['filesOnly'=>true, 'fieldType'=>'radio', 'tl_class'=>'clr'],
            'sql'                     => "binary(16) NULL"
        ),
        'mainImageSRC' => array
        (
            'exclude'                 => true,
            'inputType'               => 'fileTree',
            'eval'                    => ['filesOnly'=>true, 'fieldType'=>'radio', 'tl_class'=>'clr'],
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
            'label'                   => &$GLOBALS['TL_LANG']['MSC']['sortOrder'],
            'sql'                     => "blob NULL"
        ),
        'postal' => array
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
            'sql'                     => "varchar(255) NOT NULL default 'Bergheim'"
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
            'inputType'               => 'text',
            'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'lat' => array
        (
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'lng' => array
        (
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'company' => array
        (
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => ['maxlength'=>255],
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
        'mobile' => array
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
            'eval'                    => ['maxlength'=>255, 'rgxp'=>'email', 'unique'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50'],
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
            'eval'                    => ['rte'=>'tinyMCE', 'tl_class'=>'clr'],
            'sql'                     => "text NULL"
        ),
        'facebookUrl' => array
        (
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => ['rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>2048],
            'sql'                     => "text NULL"
        ),
        'instagramUrl' => array
        (
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => ['rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>2048],
            'sql'                     => "text NULL"
        ),
        'tiktokUrl' => array
        (
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => ['rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>2048],
            'sql'                     => "text NULL"
        ),
        'youtubeUrl' => array
        (
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => ['rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>2048],
            'sql'                     => "text NULL"
        ),
        'pinterestUrl' => array
        (
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => ['rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>2048],
            'sql'                     => "text NULL"
        ),
        'xingUrl' => array
        (
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => ['rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>2048],
            'sql'                     => "text NULL"
        ),
        'linkedinUrl' => array
        (
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => ['rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>2048],
            'sql'                     => "text NULL"
        ),
        'branch' => array
        (
            'exclude'                 => true,
            'inputType'               => 'select',
            'eval'                    => ['mandatory'=>true, 'chosen'=>true, 'tl_class'=>'w50'],
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
            'eval'                    => array('multiple'=>true, 'tl_class' => 'clr'),
            'sql'                     => "blob NULL",
            'relation'                => array('type'=>'hasMany', 'load'=>'lazy')
        ),
        'tags' => array
        (
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'foreignKey'              => 'tl_bm_tag.title',
            'eval'                    => array('multiple'=>true, 'tl_class' => 'clr'),
            'sql'                     => "blob NULL",
            'relation'                => array('type'=>'hasMany', 'load'=>'lazy')
        ),
        'cssClass' => array
        (
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'published' => array
        (
            'exclude'                 => true,
            'toggle'                  => true,
            'filter'                  => true,
            'flag'                    => DataContainer::SORT_INITIAL_LETTER_ASC,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'publishData' => array
        (
            'exclude'                 => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        'start' => array
        (
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                     => "varchar(10) NOT NULL default ''"
        ),
        'stop' => array
        (
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                     => "varchar(10) NOT NULL default ''"
        ),
        'publishedData' => array
        (
            'sql'                     => "blob NULL"
        ),
        'dirty' => array
        (
            'filter'                  => true,
            'sql'                     => "char(1) NOT NULL default ''"
        )
    )
);

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 */
class tl_bm_poi extends Backend
{
    /**
     * Return the SERP URL
     *
     * @return string
     */
    public function getSerpUrl()
    {
        return '';
    }

    /**
     * Return the title tag from the associated page layout
     *
     * @return string
     */
    public function getTitleTag()
    {
        return '';
    }
}
