<?php

namespace Oveleon\ContaoBergheimBundle;

use Contao\Config;
use Contao\Database;
use Contao\DataContainer;
use Contao\Environment;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\System;
use Oveleon\ContaoBergheimBundle\Model\BranchModel;
use Oveleon\ContaoBergheimBundle\Model\CategoryModel;

class POI extends System
{
    /**
     * Load the database object
     */
    public function __construct()
    {
        $this->import(Database::class, 'Database');
    }

    /**
     * Auto-generate the poi alias if it has not been set yet
     *
     * @param mixed         $varValue
     * @param DataContainer $dc
     *
     * @return string
     *
     * @throws Exception
     */
    public function generateAlias($varValue, DataContainer $dc)
    {
        $aliasExists = function (string $alias) use ($dc): bool
        {
            return $this->Database->prepare("SELECT id FROM tl_bm_poi WHERE alias=? AND id!=?")->execute($alias, $dc->id)->numRows > 0;
        };

        // Generate alias if there is none
        if (!$varValue)
        {
            $varValue = System::getContainer()->get('contao.slug')->generate($dc->activeRecord->title);
        }
        elseif (preg_match('/^[1-9]\d*$/', $varValue))
        {
            throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasNumeric'], $varValue));
        }
        elseif ($aliasExists($varValue))
        {
            throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
        }

        return $varValue;
    }

    /**
     * Delete related poi relations
     *
     * @param DataContainer $dc
     */
    public function deleteRelations(DataContainer $dc): void
    {
        $this->Database->prepare("DELETE FROM tl_bm_poi_category WHERE pid=?")
            ->execute($dc->activeRecord->id);

        $this->Database->prepare("DELETE FROM tl_bm_poi_tag WHERE pid=?")
            ->execute($dc->activeRecord->id);
    }

    /**
     * Create and clean up poi relations
     *
     * @param DataContainer $dc
     */
    public function createAndCleanUpRelations(DataContainer $dc): void
    {
        $arrCategories = StringUtil::deserialize($dc->activeRecord->categories, true);

        $objPoiCategory = $this->Database->prepare("SELECT id, pid, cid FROM tl_bm_poi_category WHERE pid=?")
            ->execute($dc->activeRecord->id);

        while ($objPoiCategory->next())
        {
            if (false !== ($index = array_search($objPoiCategory->cid, $arrCategories)))
            {
                // Record exists: No clean up required
                unset($arrCategories[$index]);
            }
            else
            {
                // Category removed from poi: Related record must be deleted
                $this->Database->prepare("DELETE FROM tl_bm_poi_category WHERE id=?")
                    ->execute($objPoiCategory->id);
            }
        }

        // Category added in poi: Related record must be created
        foreach ($arrCategories as $categoryId)
        {
            $this->Database->prepare("INSERT INTO tl_bm_poi_category (pid, cid) VALUES (?, ?)")
                ->execute($dc->activeRecord->id, intval($categoryId));
        }

        $arrTags = StringUtil::deserialize($dc->activeRecord->tags, true);

        $objPoiTag = $this->Database->prepare("SELECT id, pid, tid FROM tl_bm_poi_tag WHERE pid=?")
            ->execute($dc->activeRecord->id);

        while ($objPoiTag->next())
        {
            if (false !== ($index = array_search($objPoiTag->tid, $arrTags)))
            {
                // Record exists: No clean up required
                unset($arrTags[$index]);
            }
            else
            {
                // Tag removed from poi: Related record must be deleted
                $this->Database->prepare("DELETE FROM tl_bm_poi_tag WHERE id=?")
                    ->execute($objPoiTag->id);
            }
        }

        // Tag added in poi: Related record must be created
        foreach ($arrTags as $tagId)
        {
            $this->Database->prepare("INSERT INTO tl_bm_poi_tag (pid, tid) VALUES (?, ?)")
                ->execute($dc->activeRecord->id, intval($tagId));
        }
    }

    /**
     * Save all data of record in publishData
     *
     * @param DataContainer $dc
     */
    public function publishRecord(DataContainer $dc): void
    {
        if ($dc->activeRecord->publishData || empty($dc->activeRecord->publishedData))
        {
            $arrData = $dc->activeRecord->row();

            $this->Database->prepare("UPDATE tl_bm_poi SET publishedData=?, publishData='', dirty='' WHERE id=?")
                ->execute(serialize($arrData), $dc->activeRecord->id);
        }
        else
        {
            $this->Database->prepare("UPDATE tl_bm_poi SET dirty='1' WHERE id=?")
                ->execute($dc->activeRecord->id);
        }
    }

    public static function getUrl($objPoi): string
    {
        $objBranch = BranchModel::findByPk($objPoi->branch);
        $objPage = $objBranch->getRelated('jumpTo');

        if (!$objPage instanceof PageModel)
        {
            $strPoiUrl = StringUtil::ampersand(Environment::get('request'));
        }
        else
        {
            $params = (Config::get('useAutoItem') ? '/' : '/items/') . ($objPoi->alias ?: $objPoi->id);

            $strPoiUrl = StringUtil::ampersand($objPage->getFrontendUrl($params));
        }

        return $strPoiUrl;
    }
}
