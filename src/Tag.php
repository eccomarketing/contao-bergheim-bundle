<?php

namespace Oveleon\ContaoBergheimBundle;

use Contao\Database;
use Contao\DataContainer;
use Contao\System;

class Tag extends System
{
    /**
     * Load the database object
     */
    public function __construct()
    {
        $this->import(Database::class, 'Database');
    }

    /**
     * Auto-generate the tag alias if it has not been set yet
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
            return $this->Database->prepare("SELECT id FROM tl_bm_tag WHERE alias=? AND id!=?")->execute($alias, $dc->id)->numRows > 0;
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
}