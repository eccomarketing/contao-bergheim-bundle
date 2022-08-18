<?php

declare(strict_types=1);

/**
 * This file is part of Contao Bergheim Bundle.
 *
 * @author      Daniele Sciannimanica  <https://github.com/doishub>
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
    ]
];

// Models
$GLOBALS['TL_MODELS']['tl_bm_poi'] = 'Oveleon\ContaoBergheimBundle\PoiModel';
