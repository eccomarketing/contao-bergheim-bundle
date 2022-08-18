<?php

namespace Oveleon\ContaoBergheimBundle\Model;

use Contao\Model;

/**
 * Reads and writes poi´s
 *
 * @property integer $id
 * @property integer $tstamp
 * @property integer $propertyId
 * @property integer $ownerId
 * @property integer $buyerId
 * @property integer $brokerId
 * @property integer $brokerClientId
 * @property integer $statusId
 * @property integer $systemPropertyId
 * @property string  $fields
 *
 * @method static PoiModel|null findById($id, array $opt=array())
 * @method static PoiModel|null findOneBy($col, $val, array $opt=array())
 * @method static PoiModel|null findOneByTstamp($val, array $opt=array())
 * @method static PoiModel|null findOneByPropertyId($val, array $opt=array())
 * @method static PoiModel|null findOneByOwnerId($val, array $opt=array())
 * @method static PoiModel|null findOneByBrokerId($val, array $opt=array())
 * @method static PoiModel|null findOneByBrokerClientId($val, array $opt=array())
 * @method static PoiModel|null findOneBySystemPropertyId($val, array $opt=array())
 *
 * @method static \Model\Collection|PoiModel[]|PoiModel|null findByTstamp($val, array $opt=array())
 * @method static \Model\Collection|PoiModel[]|PoiModel|null findByPropertyId($val, array $opt=array())
 * @method static \Model\Collection|PoiModel[]|PoiModel|null findByOwnerId($val, array $opt=array())
 * @method static \Model\Collection|PoiModel[]|PoiModel|null findByBrokerId($val, array $opt=array())
 * @method static \Model\Collection|PoiModel[]|PoiModel|null findByBrokerClientId($val, array $opt=array())
 * @method static \Model\Collection|PoiModel[]|PoiModel|null findBySystemPropertyId($val, array $opt=array())
 * @method static \Model\Collection|PoiModel[]|PoiModel|null findMultipleByIds($var, array $opt=array())
 * @method static \Model\Collection|PoiModel[]|PoiModel|null findBy($col, $val, array $opt=array())
 * @method static \Model\Collection|PoiModel[]|PoiModel|null findAll(array $opt=array())
 *
 * @method static integer countById($id, array $opt=array())
 * @method static integer countByTstamp($val, array $opt=array())
 * @method static integer countByOwnerId($val, array $opt=array())
 * @method static integer countByBrokerId($val, array $opt=array())
 * @method static integer countByBrokerClientId($val, array $opt=array())
 *
 * @author Daniele Sciannimanica <https://github.com/doishub>
 */
class PoiModel extends Model
{
    /**
     * Table name
     * @var string
     */
    protected static $strTable = 'tl_bm_poi';
}
