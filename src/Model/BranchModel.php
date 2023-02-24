<?php

namespace Oveleon\ContaoBergheimBundle\Model;

use Contao\Model;

/**
 * Reads and writes branches
 *
 * @property integer           $id
 * @property integer           $tstamp
 * @property string            $title
 * @property string            $categories
 * @property string|array|null $iconSRC
 *
 * @method static BranchModel|null findById($id, array $opt=array())
 * @method static BranchModel|null findByPk($id, array $opt=array())
 * @method static BranchModel|null findOneBy($col, $val, array $opt=array())
 * @method static BranchModel|null findOneByTstamp($val, array $opt=array())
 * @method static BranchModel|null findOneByTitle($val, array $opt=array())
 * @method static BranchModel|null findOneByIconSRC($val, array $opt=array())
 *
 * @method static Model\Collection|BranchModel[]|BranchModel|null findByTstamp($val, array $opt=array())
 * @method static Model\Collection|BranchModel[]|BranchModel|null findByTitle($val, array $opt=array())
 * @method static Model\Collection|BranchModel[]|BranchModel|null findByIconSRC($val, array $opt=array())
 * @method static Model\Collection|BranchModel[]|BranchModel|null findMultipleByIds($var, array $opt=array())
 * @method static Model\Collection|BranchModel[]|BranchModel|null findBy($col, $val, array $opt=array())
 * @method static Model\Collection|BranchModel[]|BranchModel|null findAll(array $opt=array())
 *
 * @method static integer countById($id, array $opt=array())
 * @method static integer countByTstamp($val, array $opt=array())
 * @method static integer countByTitle($val, array $opt=array())
 * @method static integer countByIconSRC($val, array $opt=array())
 *
 * @author Fabian Ekert <https://github.com/eki89>
 */
class BranchModel extends Model
{
    /**
     * Table name
     * @var string
     */
    protected static $strTable = 'tl_bm_branch';
}
