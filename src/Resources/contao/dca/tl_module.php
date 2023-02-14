<?php

use Contao\Backend;
use Contao\BackendUser;
use Contao\Controller;
use Contao\System;

$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'poi_listMode';

// Add palettes to tl_module
$GLOBALS['TL_DCA']['tl_module']['palettes']['poi_map']              = '{title_legend},name,headline,type;{config_legend},map_min,map_max,map_zoom,map_latitude,map_longitude;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['poi_list']             = '{title_legend},name,headline,type;{config_legend},poi_listMode,poi_readerModule,numberOfItems,poi_order,skipFirst,perPage,poi_addTags;{template_legend:hide},poi_template,customTpl;{image_legend:hide},imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['poi_listbranches']     = '{title_legend},name,headline,type;{config_legend},poi_listMode,poi_branches,poi_readerModule,numberOfItems,poi_order,skipFirst,perPage,poi_addTags;{template_legend:hide},poi_template,customTpl;{image_legend:hide},imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['poi_listcategories']   = '{title_legend},name,headline,type;{config_legend},poi_listMode,poi_categories,poi_readerModule,numberOfItems,poi_order,skipFirst,perPage,poi_addTags;{template_legend:hide},poi_template,customTpl;{image_legend:hide},imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['poi_reader']           = '{title_legend},name,headline,type;{config_legend},poi_listMode;{template_legend:hide},poi_template,customTpl;{image_legend:hide},imgSize,poi_imgSize,poi_imgSizeLogo;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['poi_readerbranches']   = '{title_legend},name,headline,type;{config_legend},poi_listMode,poi_branches;{template_legend:hide},poi_template,customTpl;{image_legend:hide},imgSize,poi_imgSize,poi_imgSizeLogo;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['poi_readercategories'] = '{title_legend},name,headline,type;{config_legend},poi_listMode,poi_categories;{template_legend:hide},poi_template,customTpl;{image_legend:hide},imgSize,poi_imgSize,poi_imgSizeLogo;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';

// Add fields to tl_module
$GLOBALS['TL_DCA']['tl_module']['fields']['poi_listMode'] = array
(
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('all', 'branches', 'categories'),
    'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
    'eval'                    => array('submitOnChange'=>true, 'helpwizard'=>true),
    'sql'                     => "varchar(32) COLLATE ascii_bin NOT NULL default 'all'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['poi_branches'] = array
(
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'options_callback'        => array('tl_module_poi', 'getBranches'),
    'eval'                    => array('multiple'=>true, 'mandatory'=>true),
    'sql'                     => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['poi_categories'] = array
(
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'options_callback'        => array('tl_module_poi', 'getPoiCategories'),
    'eval'                    => array('multiple'=>true, 'mandatory'=>true),
    'sql'                     => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['poi_readerModule'] = array
(
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback'        => array('tl_module_poi', 'getReaderModules'),
    'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
    'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
    'sql'                     => "int(10) unsigned NOT NULL default 0"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['poi_template'] = array
(
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback' => static function ()
    {
        return Controller::getTemplateGroup('poi_');
    },
    'eval'                    => array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50'),
    'sql'                     => "varchar(64) COLLATE ascii_bin NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['poi_order'] = array
(
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array('order_date_asc', 'order_date_desc', 'order_headline_asc', 'order_headline_desc', 'order_random'),
    'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "varchar(32) COLLATE ascii_bin NOT NULL default 'order_date_desc'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['poi_imgSize'] = array
(
    'exclude'                 => true,
    'inputType'               => 'imageSize',
    'reference'               => &$GLOBALS['TL_LANG']['MSC'],
    'eval'                    => array('rgxp'=>'natural', 'includeBlankOption'=>true, 'nospace'=>true, 'helpwizard'=>true, 'tl_class'=>'w50'),
    'options_callback' => static function ()
    {
        return System::getContainer()->get('contao.image.sizes')->getOptionsForUser(BackendUser::getInstance());
    },
    'sql'                     => "varchar(128) COLLATE ascii_bin NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['poi_imgSizeLogo'] = array
(
    'exclude'                 => true,
    'inputType'               => 'imageSize',
    'reference'               => &$GLOBALS['TL_LANG']['MSC'],
    'eval'                    => array('rgxp'=>'natural', 'includeBlankOption'=>true, 'nospace'=>true, 'helpwizard'=>true, 'tl_class'=>'w50'),
    'options_callback' => static function ()
    {
        return System::getContainer()->get('contao.image.sizes')->getOptionsForUser(BackendUser::getInstance());
    },
    'sql'                     => "varchar(128) COLLATE ascii_bin NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['poi_addTags'] = array
(
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'w50 m12'),
    'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['map_zoom'] = array
(
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => range(0,20),
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "int(10) unsigned NOT NULL default 13"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['map_max'] = array
(
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => range(0,20),
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "int(10) unsigned NOT NULL default 18"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['map_min'] = array
(
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => range(0,20),
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "int(10) unsigned NOT NULL default 6"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['map_latitude'] = array
(
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('tl_class'=>'w50 clr', 'rgxp' => 'digit'),
    'sql'                     => "varchar(255) NOT NULL default '51.165691'"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['map_longitude'] = array
(
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('tl_class'=>'w50', 'rgxp' => 'digit'),
    'sql'                     => "varchar(255) NOT NULL default '10.451526'"
);


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 */
class tl_module_poi extends Backend
{
    /**
     * Get all branches and return them as array
     *
     * @return array
     */
    public function getBranches()
    {
        $arrBranches = array();
        $objBranches = $this->Database->execute("SELECT id, title FROM tl_bm_branch ORDER BY title");

        while ($objBranches->next())
        {
            $arrBranches[$objBranches->id] = $objBranches->title;
        }

        return $arrBranches;
    }

    /**
     * Get all categories and return them as array
     *
     * @return array
     */
    public function getPoiCategories()
    {
        $arrCategories = array();
        $objCategories = $this->Database->execute("SELECT id, title FROM tl_bm_category ORDER BY title");

        while ($objCategories->next())
        {
            $arrCategories[$objCategories->id] = $objCategories->title;
        }

        return $arrCategories;
    }

    /**
     * Get all poi reader modules and return them as array
     *
     * @return array
     */
    public function getReaderModules()
    {
        $arrModules = array();
        $objModules = $this->Database->execute("SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id WHERE m.type='poi_reader' ORDER BY t.name, m.name");

        while ($objModules->next())
        {
            $arrModules[$objModules->theme][$objModules->id] = $objModules->name . ' (ID ' . $objModules->id . ')';
        }

        return $arrModules;
    }
}
