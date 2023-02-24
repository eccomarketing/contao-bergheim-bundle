<?php

namespace Oveleon\ContaoBergheimBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\StringUtil;
use Contao\Controller;
use Symfony\Contracts\Translation\TranslatorInterface;

class ModuleListener
{
    #[AsCallback(table: 'tl_module', target: 'fields.poi_editable_fields.options', priority: 100)]
    public function getEditableFields(): array
    {
        $return = array();

        Controller::loadLanguageFile('tl_bm_poi');
        Controller::loadDataContainer('tl_bm_poi');

        foreach ($GLOBALS['TL_DCA']['tl_bm_poi']['fields'] as $k=>$v)
        {
            if ($v['eval']['feEditable'] ?? null)
            {
                $return[$k] = $GLOBALS['TL_DCA']['tl_bm_poi']['fields'][$k]['label'][0] ?? '';
            }
        }

        return $return;
    }
}
