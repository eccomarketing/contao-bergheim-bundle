<?php

namespace Oveleon\ContaoBergheimBundle\Model;

use Contao\Database;
use Contao\Date;
use Contao\Model;

/**
 * Reads and writes poi´s
 *
 * @property integer           $id
 * @property integer           $tstamp
 * @property string            $type
 * @property string            $title
 * @property integer           $author
 * @property string            $pageTitle
 * @property string            $robots
 * @property string|null       $metaDescription
 * @property string            $subtitle
 * @property string|null       $description
 * @property string|null       $teaser
 * @property string|null       $extraDescription
 * @property string|null       $logoSRC
 * @property string|null       $mainImageSRC
 * @property string|array|null $imagesSRC
 * @property string|array|null $orderSRC
 * @property string            $postal
 * @property string            $city
 * @property string            $street
 * @property string            $houseNumber
 * @property string            $lat
 * @property string            $lng
 * @property string            $company
 * @property string            $phone
 * @property string            $mobile
 * @property string            $email
 * @property string            $website
 * @property string|null       $openingHours
 * @property string|null       $facebookUrl
 * @property string|null       $instagramUrl
 * @property string|null       $tiktokUrl
 * @property string|null       $youtubeUrl
 * @property string|null       $pinterestUrl
 * @property string|null       $xingUrl
 * @property string|null       $linkedinUrl
 * @property integer           $branch
 * @property string|array|null $categories
 * @property string|array|null $tags
 * @property string            $cssClass
 * @property string|boolean    $published
 * @property string            $start
 * @property string            $stop
 * @property string|array|null $publishedData
 *
 * @method static PoiModel|null findById($id, array $opt=array())
 * @method static PoiModel|null findByPk($id, array $opt=array())
 * @method static PoiModel|null findByIdOrAlias($id, array $opt=array())
 * @method static PoiModel|null findOneBy($col, $val, array $opt=array())
 * @method static PoiModel|null findOneByTstamp($val, array $opt=array())
 * @method static PoiModel|null findOneByType($val, array $opt=array())
 * @method static PoiModel|null findOneByTitle($val, array $opt=array())
 * @method static PoiModel|null findOneByAuthor($val, array $opt=array())
 * @method static PoiModel|null findOneByPageTitle($val, array $opt=array())
 * @method static PoiModel|null findOneByRobots($val, array $opt=array())
 * @method static PoiModel|null findOneByMetaDescription($val, array $opt=array())
 * @method static PoiModel|null findOneBySubtitle($val, array $opt=array())
 * @method static PoiModel|null findOneByDescription($val, array $opt=array())
 * @method static PoiModel|null findOneByTeaser($val, array $opt=array())
 * @method static PoiModel|null findOneByExtraDescription($val, array $opt=array())
 * @method static PoiModel|null findOneByLogoSRC($val, array $opt=array())
 * @method static PoiModel|null findOneByMainImageSRC($val, array $opt=array())
 * @method static PoiModel|null findOneByImagesSRC($val, array $opt=array())
 * @method static PoiModel|null findOneByOrderSRC($val, array $opt=array())
 * @method static PoiModel|null findOneByPostal($val, array $opt=array())
 * @method static PoiModel|null findOneByCity($val, array $opt=array())
 * @method static PoiModel|null findOneByStreet($val, array $opt=array())
 * @method static PoiModel|null findOneByHouseNumber($val, array $opt=array())
 * @method static PoiModel|null findOneByLat($val, array $opt=array())
 * @method static PoiModel|null findOneByLng($val, array $opt=array())
 * @method static PoiModel|null findOneByCompany($val, array $opt=array())
 * @method static PoiModel|null findOneByPhone($val, array $opt=array())
 * @method static PoiModel|null findOneByMobile($val, array $opt=array())
 * @method static PoiModel|null findOneByEmail($val, array $opt=array())
 * @method static PoiModel|null findOneByWebsite($val, array $opt=array())
 * @method static PoiModel|null findOneByOpeningHours($val, array $opt=array())
 * @method static PoiModel|null findOneByFacebookUrl($val, array $opt=array())
 * @method static PoiModel|null findOneByInstagramUrl($val, array $opt=array())
 * @method static PoiModel|null findOneByTiktokUrl($val, array $opt=array())
 * @method static PoiModel|null findOneByYoutubeUrl($val, array $opt=array())
 * @method static PoiModel|null findOneByPinterestUrl($val, array $opt=array())
 * @method static PoiModel|null findOneByXingUrl($val, array $opt=array())
 * @method static PoiModel|null findOneByLinkedinUrl($val, array $opt=array())
 * @method static PoiModel|null findOneByBranch($val, array $opt=array())
 * @method static PoiModel|null findOneByCategories($val, array $opt=array())
 * @method static PoiModel|null findOneByCssClass($val, array $opt=array())
 * @method static PoiModel|null findOneByPublished($val, array $opt=array())
 * @method static PoiModel|null findOneByStart($val, array $opt=array())
 * @method static PoiModel|null findOneByStop($val, array $opt=array())
 * @method static PoiModel|null findOneByPublishedData($val, array $opt=array())
 *
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByTstamp($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByType($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByTitle($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByAuthor($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByPageTitle($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByRobots($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByMetaDescription($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findBySubtitle($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByDescription($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByTeaser($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByExtraDescription($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByLogoSRC($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByMainImageSRC($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByImagesSRC($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByOrderSRC($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByPostal($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByCity($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByStreet($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByHouseNumber($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByLat($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByLng($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByCompany($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByPhone($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByMobile($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByEmail($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByWebsite($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByOpeningHours($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByFacebookUrl($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByInstagramUrl($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByTiktokUrl($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByYoutubeUrl($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByPinterestUrl($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByXingUrl($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByLinkedinUrl($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByBranch($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByCategories($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByCssClass($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByPublished($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByStart($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByStop($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findByPublishedData($val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findMultipleByIds($var, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findBy($col, $val, array $opt=array())
 * @method static Model\Collection|PoiModel[]|PoiModel|null findAll(array $opt=array())
 *
 * @method static integer countById($id, array $opt=array())
 * @method static integer countByTstamp($val, array $opt=array())
 * @method static integer countByType($val, array $opt=array())
 * @method static integer countByTitle($val, array $opt=array())
 * @method static integer countByAuthor($val, array $opt=array())
 * @method static integer countByPageTitle($val, array $opt=array())
 * @method static integer countByRobots($val, array $opt=array())
 * @method static integer countByMetaDescription($val, array $opt=array())
 * @method static integer countBySubtitle($val, array $opt=array())
 * @method static integer countByDescription($val, array $opt=array())
 * @method static integer countByTeaser($val, array $opt=array())
 * @method static integer countByExtraDescription($val, array $opt=array())
 * @method static integer countByLogoSRC($val, array $opt=array())
 * @method static integer countByMainImageSRC($val, array $opt=array())
 * @method static integer countByImagesSRC($val, array $opt=array())
 * @method static integer countByOrderSRC($val, array $opt=array())
 * @method static integer countByPostal($val, array $opt=array())
 * @method static integer countByCity($val, array $opt=array())
 * @method static integer countByStreet($val, array $opt=array())
 * @method static integer countByHouseNumber($val, array $opt=array())
 * @method static integer countByLat($val, array $opt=array())
 * @method static integer countByLng($val, array $opt=array())
 * @method static integer countByCompany($val, array $opt=array())
 * @method static integer countByPhone($val, array $opt=array())
 * @method static integer countByMobile($val, array $opt=array())
 * @method static integer countByEmail($val, array $opt=array())
 * @method static integer countByWebsite($val, array $opt=array())
 * @method static integer countByOpeningHours($val, array $opt=array())
 * @method static integer countByFacebookUrl($val, array $opt=array())
 * @method static integer countByInstagramUrl($val, array $opt=array())
 * @method static integer countByTiktokUrl($val, array $opt=array())
 * @method static integer countByYoutubeUrl($val, array $opt=array())
 * @method static integer countByPinterestUrl($val, array $opt=array())
 * @method static integer countByXingUrl($val, array $opt=array())
 * @method static integer countByLinkedinUrl($val, array $opt=array())
 * @method static integer countByBranch($val, array $opt=array())
 * @method static integer countByCategories($val, array $opt=array())
 * @method static integer countByCssClass($val, array $opt=array())
 * @method static integer countByPublished($val, array $opt=array())
 * @method static integer countByStart($val, array $opt=array())
 * @method static integer countByStop($val, array $opt=array())
 * @method static integer countByPublishedData($val, array $opt=array())
 *
 * @author Daniele Sciannimanica <https://github.com/doishub>
 * @author Fabian Ekert <https://github.com/eki89>
 */
class PoiModel extends Model
{
    /**
     * Table name
     * @var string
     */
    protected static $strTable = 'tl_bm_poi';

    /**
     * Find published poi items
     *
     * @param integer $intLimit    An optional limit
     * @param integer $intOffset   An optional offset
     * @param array   $arrOptions  An optional options array
     *
     * @return Model\Collection|PoiModel[]|PoiModel|null A collection of models or null if there are no poi records
     */
    public static function findPublished($intLimit=0, $intOffset=0, array $arrOptions=array())
    {
        $t = static::$strTable;
        $arrColumns = array();

        if (!static::isPreviewMode($arrOptions))
        {
            $time = Date::floorToMinute();
            $arrColumns[] = "$t.published='1' AND ($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'$time')";
        }

        if (!isset($arrOptions['order']))
        {
            $arrOptions['order']  = "$t.tstamp DESC";
        }

        $arrOptions['limit']  = $intLimit;
        $arrOptions['offset'] = $intOffset;

        return static::findBy($arrColumns, null, $arrOptions);
    }

    /**
     * Count published poi items
     *
     * @param array   $arrOptions  An optional options array
     *
     * @return integer The number of poi items
     */
    public static function countPublished(array $arrOptions=array())
    {
        $t = static::$strTable;
        $arrColumns = array();

        if (!static::isPreviewMode($arrOptions))
        {
            $time = Date::floorToMinute();
            $arrColumns[] = "$t.published='1' AND ($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'$time')";
        }

        return static::countBy($arrColumns, null, $arrOptions);
    }

    /**
     * Find a published poi item by its ID or alias
     *
     * @param mixed $varId      The numeric ID or alias name
     * @param array $arrOptions An optional options array
     *
     * @return PoiModel|null The model or null if there are no poi
     */
    public static function findPublishedByIdOrAlias($varId, array $arrOptions=array())
    {
        $t = static::$strTable;
        $arrColumns = !preg_match('/^[1-9]\d*$/', $varId) ? array("BINARY $t.alias=?") : array("$t.id=?");

        if (!static::isPreviewMode($arrOptions))
        {
            $time = Date::floorToMinute();
            $arrColumns[] = "$t.published='1' AND ($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'$time')";
        }

        return static::findOneBy($arrColumns, $varId, $arrOptions);
    }

    /**
     * Find published poi items by tags
     *
     * @param array   $arrTags     An array of tags
     * @param integer $intLimit    An optional limit
     * @param integer $intOffset   An optional offset
     * @param array   $arrOptions  An optional options array
     *
     * @return Model\Collection|PoiModel[]|PoiModel|null A collection of models or null if there are no poi records
     */
    public static function findPublishedByTags(array $arrTags=array(), $intLimit=0, $intOffset=0, array $arrOptions=array())
    {
        $t = static::$strTable;
        $objDatabase = Database::getInstance();

        $query = "SELECT * FROM $t p LEFT JOIN tl_bm_poi_tag pt ON p.id=pt.pid WHERE pt.tid IN (" . implode(',', $arrTags) . ")";

        if (!static::isPreviewMode($arrOptions))
        {
            $time = Date::floorToMinute();
            $query .= " AND p.published='1' AND (p.start='' OR p.start<='$time') AND (p.stop='' OR p.stop>'$time')";
        }

        $query .= " GROUP BY p.id ORDER BY p.tstamp DESC";

        if ($intLimit > 0)
        {
            $query .= " LIMIT " . $intLimit;
        }

        if ($intOffset > 0)
        {
            $query .= " OFFSET " . $intOffset;
        }

        $objResult = $objDatabase->prepare($query)->execute();

        if ($objResult->numRows < 1)
        {
            return null;
        }

        return Model\Collection::createFromDbResult($objResult, $t);
    }

    /**
     * Count published poi items
     *
     * @param array   $arrOptions  An optional options array
     *
     * @return integer The number of poi items
     */
    public static function countPublishedByTags(array $arrTags=array(), array $arrOptions=array())
    {
        $t = static::$strTable;
        $objDatabase = Database::getInstance();

        $query = "SELECT COUNT(*) FROM $t p LEFT JOIN tl_bm_poi_tag pt ON p.id=pt.pid WHERE pt.tid IN (" . implode(',', $arrTags) . ")";

        if (!static::isPreviewMode($arrOptions))
        {
            $time = Date::floorToMinute();
            $query .= " AND p.published='1' AND (p.start='' OR p.start<='$time') AND (p.stop='' OR p.stop>'$time')";
        }

        $query .= " GROUP BY p.id";

        $objResult = $objDatabase->prepare($query)->execute();

        return $objResult->numRows;
    }

    /**
     * Find published poi items by branches
     *
     * @param array   $arrBranches
     * @param integer $intLimit       An optional limit
     * @param integer $intOffset      An optional offset
     * @param array   $arrOptions     An optional options array
     *
     * @return Model\Collection|PoiModel[]|PoiModel|null A collection of models or null if there are no poi records
     */
    public static function findPublishedByBranches(array $arrBranches=array(), $intLimit=0, $intOffset=0, array $arrOptions=array())
    {
        if (empty($arrBranches))
        {
            return null;
        }

        $t = static::$strTable;
        $arrColumns = array("$t.branch IN(" . implode(',', array_map('\intval', $arrBranches)) . ")");

        if (!static::isPreviewMode($arrOptions))
        {
            $time = Date::floorToMinute();
            $arrColumns[] = "$t.published='1' AND ($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'$time')";
        }

        if (!isset($arrOptions['order']))
        {
            $arrOptions['order']  = "$t.tstamp DESC";
        }

        $arrOptions['limit']  = $intLimit;
        $arrOptions['offset'] = $intOffset;

        return static::findBy($arrColumns, null, $arrOptions);
    }

    /**
     * Count published poi items by branches
     *
     * @param array   $arrBranches
     * @param array   $arrOptions     An optional options array
     *
     * @return integer The number of poi items
     */
    public static function countPublishedByBranches(array $arrBranches=array(), array $arrOptions=array())
    {
        if (empty($arrBranches))
        {
            return null;
        }

        $t = static::$strTable;
        $arrColumns = array("$t.branch IN(" . implode(',', array_map('\intval', $arrBranches)) . ")");

        if (!static::isPreviewMode($arrOptions))
        {
            $time = Date::floorToMinute();
            $arrColumns[] = "$t.published='1' AND ($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'$time')";
        }

        return static::countBy($arrColumns, null, $arrOptions);
    }

    /**
     * Find published poi items by branches and tags
     *
     * @param array   $arrBranches
     * @param array   $arrTags
     * @param integer $intLimit       An optional limit
     * @param integer $intOffset      An optional offset
     * @param array   $arrOptions     An optional options array
     *
     * @return Model\Collection|PoiModel[]|PoiModel|null A collection of models or null if there are no poi records
     */
    public static function findPublishedByBranchesAndTags(array $arrBranches=array(), array $arrTags=array(), $intLimit=0, $intOffset=0, array $arrOptions=array())
    {
        if (empty($arrBranches))
        {
            return null;
        }

        $t = static::$strTable;
        $objDatabase = Database::getInstance();

        $query = "SELECT * FROM $t p LEFT JOIN tl_bm_poi_tag pt ON p.id=pt.pid WHERE p.branch IN(" . implode(',', array_map('\intval', $arrBranches)) . ") AND pt.tid IN (" . implode(',', $arrTags) . ")";

        if (!static::isPreviewMode($arrOptions))
        {
            $time = Date::floorToMinute();
            $query .= " AND p.published='1' AND (p.start='' OR p.start<='$time') AND (p.stop='' OR p.stop>'$time')";
        }

        $query .= " GROUP BY p.id ORDER BY p.tstamp DESC";

        if ($intLimit > 0)
        {
            $query .= " LIMIT " . $intLimit;
        }

        if ($intOffset > 0)
        {
            $query .= " OFFSET " . $intOffset;
        }

        $objResult = $objDatabase->prepare($query)->execute();

        if ($objResult->numRows < 1)
        {
            return null;
        }

        return Model\Collection::createFromDbResult($objResult, $t);
    }

    /**
     * Count published poi items by branches and tags
     *
     * @param array   $arrBranches
     * @param array   $arrTags
     * @param array   $arrOptions     An optional options array
     *
     * @return integer The number of poi items
     */
    public static function countPublishedByBranchesAndTags(array $arrBranches=array(), array $arrTags=array(), array $arrOptions=array())
    {
        if (empty($arrBranches))
        {
            return null;
        }

        $t = static::$strTable;
        $objDatabase = Database::getInstance();

        $query = "SELECT COUNT(*) FROM $t p LEFT JOIN tl_bm_poi_tag pt ON p.id=pt.pid WHERE p.branch IN(" . implode(',', array_map('\intval', $arrBranches)) . ") AND pt.tid IN (" . implode(',', $arrTags) . ")";

        if (!static::isPreviewMode($arrOptions))
        {
            $time = Date::floorToMinute();
            $query .= " AND p.published='1' AND (p.start='' OR p.start<='$time') AND (p.stop='' OR p.stop>'$time')";
        }

        $query .= " GROUP BY p.id";

        $objResult = $objDatabase->prepare($query)->execute();

        return $objResult->numRows;
    }

    /**
     * Find published poi items by categories
     *
     * @param array   $arrCategories  An optional limit
     * @param integer $intLimit       An optional limit
     * @param integer $intOffset      An optional offset
     * @param array   $arrOptions     An optional options array
     *
     * @return Model\Collection|PoiModel[]|PoiModel|null A collection of models or null if there are no poi records
     */
    public static function findPublishedByCategories(array $arrCategories=array(), $intLimit=0, $intOffset=0, array $arrOptions=array())
    {
        if (empty($arrCategories))
        {
            return null;
        }

        $t = static::$strTable;
        $objDatabase = Database::getInstance();

        $query = "SELECT * FROM $t p LEFT JOIN tl_bm_poi_category pc ON p.id=pc.pid WHERE pc.cid IN (" . implode(',', $arrCategories) . ")";

        if (!static::isPreviewMode($arrOptions))
        {
            $time = Date::floorToMinute();
            $query .= " AND p.published='1' AND (p.start='' OR p.start<='$time') AND (p.stop='' OR p.stop>'$time')";
        }

        $query .= " GROUP BY p.id";

        if ($intLimit !== 0)
        {
            $query .= " LIMIT " . $intLimit;
        }

        if ($intOffset !== 0)
        {
            $query .= " OFFSET " . $intOffset;
        }

        $objResult = $objDatabase->prepare($query)->execute();

        if ($objResult->numRows < 1)
        {
            return null;
        }

        return Model\Collection::createFromDbResult($objResult, $t);
    }

    /**
     * Count published poi items by categories
     *
     * @param array   $arrCategories  An optional limit
     * @param array   $arrOptions     An optional options array
     *
     * @return integer The number of poi items
     */
    public static function countPublishedByCategories(array $arrCategories=array(), array $arrOptions=array())
    {
        if (empty($arrCategories))
        {
            return null;
        }

        $t = static::$strTable;
        $objDatabase = Database::getInstance();

        $query = "SELECT COUNT(*) FROM $t p LEFT JOIN tl_bm_poi_category pc ON p.id=pc.pid WHERE pc.cid IN (" . implode(',', $arrCategories) . ")";

        if (!static::isPreviewMode($arrOptions))
        {
            $time = Date::floorToMinute();
            $query .= " AND p.published='1' AND (p.start='' OR p.start<='$time') AND (p.stop='' OR p.stop>'$time')";
        }

        $query .= " GROUP BY p.id";

        $objResult = $objDatabase->prepare($query)->execute();

        if ($objResult->numRows < 1)
        {
            return null;
        }

        return $objResult->numRows;
    }

    /**
     * Find published poi items by categories and tags
     *
     * @param array   $arrCategories  An optional limit
     * @param array   $arrTags
     * @param integer $intLimit       An optional limit
     * @param integer $intOffset      An optional offset
     * @param array   $arrOptions     An optional options array
     *
     * @return Model\Collection|PoiModel[]|PoiModel|null A collection of models or null if there are no poi records
     */
    public static function findPublishedByCategoriesAndTags(array $arrCategories=array(), array $arrTags=array(), $intLimit=0, $intOffset=0, array $arrOptions=array())
    {
        if (empty($arrCategories))
        {
            return null;
        }

        $t = static::$strTable;
        $objDatabase = Database::getInstance();

        $query = "SELECT * FROM $t p LEFT JOIN tl_bm_poi_category pc ON p.id=pc.pid LEFT JOIN tl_bm_poi_tag pt ON p.id=pt.pid WHERE pc.cid IN (" . implode(',', $arrCategories) . ") AND pt.tid IN (" . implode(',', $arrTags) . ")";

        if (!static::isPreviewMode($arrOptions))
        {
            $time = Date::floorToMinute();
            $query .= " AND p.published='1' AND (p.start='' OR p.start<='$time') AND (p.stop='' OR p.stop>'$time')";
        }

        $query .= " GROUP BY p.id";

        if ($intLimit !== 0)
        {
            $query .= " LIMIT " . $intLimit;
        }

        if ($intOffset !== 0)
        {
            $query .= " OFFSET " . $intOffset;
        }

        $objResult = $objDatabase->prepare($query)->execute();

        if ($objResult->numRows < 1)
        {
            return null;
        }

        return Model\Collection::createFromDbResult($objResult, $t);
    }

    /**
     * Count published poi items by categories and tags
     *
     * @param array   $arrCategories  An optional limit
     * @param array   $arrTags
     * @param array   $arrOptions     An optional options array
     *
     * @return integer The number of poi items
     */
    public static function countPublishedByCategoriesAndTags(array $arrCategories=array(), array $arrTags=array(), array $arrOptions=array())
    {
        if (empty($arrCategories))
        {
            return null;
        }

        $t = static::$strTable;
        $objDatabase = Database::getInstance();

        $query = "SELECT COUNT(*) FROM $t p LEFT JOIN tl_bm_poi_category pc ON p.id=pc.pid LEFT JOIN tl_bm_poi_tag pt ON p.id=pt.pid WHERE pc.cid IN (" . implode(',', $arrCategories) . ") AND pt.tid IN (" . implode(',', $arrTags) . ")";

        if (!static::isPreviewMode($arrOptions))
        {
            $time = Date::floorToMinute();
            $query .= " AND p.published='1' AND (p.start='' OR p.start<='$time') AND (p.stop='' OR p.stop>'$time')";
        }

        $query .= " GROUP BY p.id";

        $objResult = $objDatabase->prepare($query)->execute();

        if ($objResult->numRows < 1)
        {
            return null;
        }

        return $objResult->numRows;
    }
}
