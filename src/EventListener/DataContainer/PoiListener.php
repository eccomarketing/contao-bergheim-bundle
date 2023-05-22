<?php

namespace Oveleon\ContaoBergheimBundle\EventListener\DataContainer;

use Contao\Config;
use Contao\Controller;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\StringUtil;
use Oveleon\ContaoBergheimBundle\Model\BranchModel;
use Oveleon\ContaoBergheimBundle\Model\CategoryModel;
use Oveleon\ContaoBergheimBundle\Model\PoiModel;

class PoiListener
{
    #[AsCallback(table: 'tl_bm_poi', target: 'config.onload', priority: 101)]
    public function handleBackOfficeMessages(DataContainer $dc): void
    {
        Controller::loadLanguageFile('tl_bm_poi');
        Controller::loadDataContainer('tl_bm_poi');

        if(!$poi = PoiModel::findByPk($dc->id))
        {
            return;
        }

        // Remove message fields if no messages exists
        if(!$messages = json_decode($poi->messages, true))
        {
            $palette = PaletteManipulator::create();
            $palette->removeField(['messages', 'deleteMessages']);
            $palette->applyToPalette('showcase', $dc->table);
        }

        $GLOBALS['TL_DCA']['tl_bm_poi']['fields']['messages']['eval']['readonly'] = true;
        $GLOBALS['TL_DCA']['tl_bm_poi']['fields']['messages']['eval']['style'] = 'border: 1px solid #e9e582; background-color: #fffff1; padding: 20px; height: auto; outline: 0 none; resize: vertical;';
    }

    #[AsCallback(table: 'tl_bm_poi', target: 'config.onsubmit', priority: 101)]
    public function deleteMessages(DataContainer $dc): void
    {
        Controller::loadLanguageFile('tl_bm_poi');
        Controller::loadDataContainer('tl_bm_poi');

        // Check if messages about to be deleting
        if($dc->activeRecord->deleteMessages)
        {
            $poi = PoiModel::findByPk($dc->id);
            $poi->messages = '';
            $poi->deleteMessages = 0;
            $poi->save();
        }
    }

    #[AsCallback(table: 'tl_bm_poi', target: 'fields.messages.load', priority: 100)]
    public function showMessages(mixed $value, DataContainer $dc): string
    {
        $output = [];

        foreach (json_decode($value ?? '', true) as $message)
        {
            $output[] = vsprintf("%s\n%s", [
                date(Config::get("datimFormat"), $message['tstamp']),
                strip_tags(str_replace(['<br>', '<br/>', '<br />', '</p>'], "\n", html_entity_decode($message['message'])))
            ]);
        }

        return implode("\n\n", $output);
    }

    #[AsCallback(table: 'tl_bm_poi', target: 'fields.categories.options', priority: 100)]
    public function getProjectTypes($dc): array
    {
        // Get branch record by chosen branch id
        if($dc->activeRecord && $dc->activeRecord->branch && ($branch = BranchModel::findByPk($dc->activeRecord->branch)))
        {
            if($existingCategories = CategoryModel::findAll())
            {
                $categoryList = [];
                $allowedCategoryIds = StringUtil::deserialize($branch->categories, true);

                foreach ($existingCategories as $category)
                {
                    if(in_array($category->id, $allowedCategoryIds))
                    {
                        $categoryList[$category->id] = $category->title;
                    }
                }

                return $categoryList;
            }
        }

        return [];
    }
}
