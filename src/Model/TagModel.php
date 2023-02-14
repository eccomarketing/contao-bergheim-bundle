<?php

namespace Oveleon\ContaoBergheimBundle\Model;

use Contao\Model;

/**
 * Reads and writes branches
 *
 * @property integer $id
 * @property integer $tstamp
 * @property string  $title
 * @property string  $alias
 * @property bool    $favorite
 *
 * @method static TagModel|null findById($id, array $opt=array())
 * @method static TagModel|null findByPk($id, array $opt=array())
 * @method static TagModel|null findOneBy($col, $val, array $opt=array())
 * @method static TagModel|null findOneByTstamp($val, array $opt=array())
 * @method static TagModel|null findOneByTitle($val, array $opt=array())
 * @method static TagModel|null findOneByAlias($val, array $opt=array())
 *
 * @method static Model\Collection|TagModel[]|TagModel|null findByTstamp($val, array $opt=array())
 * @method static Model\Collection|TagModel[]|TagModel|null findByTitle($val, array $opt=array())
 * @method static Model\Collection|TagModel[]|TagModel|null findByAlias($val, array $opt=array())
 * @method static Model\Collection|TagModel[]|TagModel|null findMultipleByIds($var, array $opt=array())
 * @method static Model\Collection|TagModel[]|TagModel|null findBy($col, $val, array $opt=array())
 * @method static Model\Collection|TagModel[]|TagModel|null findAll(array $opt=array())
 *
 * @method static integer countById($id, array $opt=array())
 * @method static integer countByTstamp($val, array $opt=array())
 * @method static integer countByTitle($val, array $opt=array())
 * @method static integer countByAlias($val, array $opt=array())
 *
 * @author Fabian Ekert <https://github.com/eki89>
 */
class TagModel extends Model
{
    /**
     * Table name
     * @var string
     */
    protected static $strTable = 'tl_bm_tag';
}
