<?php

declare(strict_types=1);

use Oveleon\ContaoBergheimBundle\Model\BranchModel;
use Oveleon\ContaoBergheimBundle\Model\CategoryModel;
use Oveleon\ContaoBergheimBundle\Model\PoiModel;
use Oveleon\ContaoBergheimBundle\Model\TagModel;

/**
 * This file is part of Contao Bergheim Bundle.
 *
 * @author Daniele Sciannimanica <https://github.com/doishub>
 * @author Fabian Ekert <https://github.com/doishub>
 */

// Back end modules
$GLOBALS['BE_MOD']['bergheim'] = [
    'poi' => [
        'tables' => ['tl_bm_poi']
    ],
    'branch' => [
        'tables' => ['tl_bm_branch']
    ],
    'category' => [
        'tables' => ['tl_bm_category']
    ],
    'tag' => [
        'tables' => ['tl_bm_tag']
    ],
    'config' => [
        'tables' => ['tl_bm_config'],
        'hideInNavigation' => true
    ]
];

// Models
$GLOBALS['TL_MODELS']['tl_bm_branch']   = BranchModel::class;
$GLOBALS['TL_MODELS']['tl_bm_category'] = CategoryModel::class;
$GLOBALS['TL_MODELS']['tl_bm_poi']      = PoiModel::class;
$GLOBALS['TL_MODELS']['tl_bm_tag']      = TagModel::class;
