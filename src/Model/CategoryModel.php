<?php

namespace Oveleon\ContaoBergheimBundle\Model;

use Contao\Model;

/**
 * Reads and writes categories
 *
 * @property integer $id
 * @property integer $tstamp
 * @property string  $title
 *
 * @method static CategoryModel|null findById($id, array $opt=array())
 * @method static CategoryModel|null findByPk($id, array $opt=array())
 * @method static CategoryModel|null findOneBy($col, $val, array $opt=array())
 * @method static CategoryModel|null findOneByTstamp($val, array $opt=array())
 * @method static CategoryModel|null findOneByTitle($val, array $opt=array())
 *
 * @method static Model\Collection|CategoryModel[]|CategoryModel|null findByTstamp($val, array $opt=array())
 * @method static Model\Collection|CategoryModel[]|CategoryModel|null findByTitle($val, array $opt=array())
 * @method static Model\Collection|CategoryModel[]|CategoryModel|null findMultipleByIds($var, array $opt=array())
 * @method static Model\Collection|CategoryModel[]|CategoryModel|null findBy($col, $val, array $opt=array())
 * @method static Model\Collection|CategoryModel[]|CategoryModel|null findAll(array $opt=array())
 *
 * @method static integer countById($id, array $opt=array())
 * @method static integer countByTstamp($val, array $opt=array())
 * @method static integer countByTitle($val, array $opt=array())
 *
 * @author Fabian Ekert <https://github.com/eki89>
 */
class CategoryModel extends Model
{
    /**
     * Table name
     * @var string
     */
    protected static $strTable = 'tl_bm_category';
}
