<?php

declare(strict_types=1);

namespace Oveleon\ContaoBergheimBundle\Controller\FrontendModule;

use Contao\Controller;
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\Exception\AccessDeniedException;
use Contao\CoreBundle\Exception\RedirectResponseException;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\Message;
use Contao\ModuleModel;
use Contao\StringUtil;
use Contao\Template;
use Oveleon\ContaoBergheimBundle\Model\PoiModel;
use Oveleon\ContaoBergheimBundle\POI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Haste\Form\Form;

/**
 * @FrontendModule(type=PoiFormController::TYPE, category="bergheim")
 */
class PoiFormController extends AbstractFrontendModuleController
{
    /**
     * Frontend Module Type
     */
    public const TYPE = 'poi_form';

    /**
     * Module Model
     */
    protected ModuleModel $model;

    /**
     * Form
     */
    protected Form $form;

    /**
     * User
     */
    protected UserInterface $user;

    /**
     * POI Model
     */
    protected ?PoiModel $poi;

    /**
     * Request
     */
    protected Request $request;

    /**
     * Template
     */
    protected Template $template;

    /**
     * Create Frontend Module
     */
    public function __construct(
        protected Security $security,
        protected TranslatorInterface $translator
    ){
        Controller::loadLanguageFile('tl_bm_poi');
        Controller::loadDataContainer('tl_bm_poi');
    }

    /**
     * Return the template
     */
    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
    {
        if(!$user = $this->security->getUser())
        {
            throw new AccessDeniedException('Please log in to view this section.');
        }

        $this->user = $user;
        $this->model = $model;
        $this->request = $request;
        $this->template = $template;

        $this->poi = PoiModel::findOneByAuthor($user->id);
        $this->form = new Form(self::TYPE . $model->id, 'POST', fn($form) => \Input::post('FORM_SUBMIT') === $form->getFormId());
        $this->form->bindModel($this->poi);

        $this->createEditForm();
        $this->validateForm();

        $this->template->hasMessages = Message::hasMessages();
        $this->template->messages = Message::generate();
        $this->template->formId = $this->form->getFormId();

        return new Response($this->template->parse());
    }

    /**
     * Create filter widgets
     */
    protected function createEditForm(): void
    {
        // Check if a model exists or create a new one
        if(!$this->poi)
        {
            // Create a new POI and set defaults
            $this->poi = new PoiModel();
            $this->poi->tstamp = time();
            $this->poi->author = $this->user->id;
            $this->poi->type = 'showcase';
            $this->poi->robots = 'index,follow';
            $this->poi->save();
        }

        // Bind poi model
        $this->form->bindModel($this->poi);

        // Get editable fields
        $editable = StringUtil::deserialize($this->model->poi_editable_fields, true);

        // Add default hidden fields
        $this->form->addContaoHiddenFields();

        // Modify poi fields and add them to the form
        $this->form->addFieldsFromDca('tl_bm_poi', static function(string &$fieldName, array &$fieldConfig) use ($editable) {
            // Skip fields without input type or without editable
            if (!isset($fieldConfig['inputType']) || !isset($fieldConfig['eval']['feEditable']))
            {
                return false;
            }

            // Modify unknown input types
            switch ($fieldConfig['inputType'])
            {
                case 'fileTree':
                    $fieldConfig['inputType'] = 'fineUploader';

                    $fieldConfig['eval']['extensions'] = implode(",", Controller::getContainer()->getParameter('contao.image.valid_extensions'));
                    $fieldConfig['eval']['addToDbafs'] = 1;
                    $fieldConfig['eval']['useHomeDir'] = 1;
                    $fieldConfig['eval']['uploaderLimit'] = 20;
                    $fieldConfig['eval']['doNotOverwrite'] = 1;
                    $fieldConfig['eval']['storeFile'] = 1;
                    $fieldConfig['eval']['sortable'] = 1;
                    $fieldConfig['eval']['directUpload'] = false;
                    $fieldConfig['eval']['multiple'] = $fieldConfig['eval']['multiple'] ?? false;
                    break;

                case 'checkboxWizard':
                    $fieldConfig['inputType'] = 'checkbox';
                    break;
            }

            // Modify editors
            if(isset($fieldConfig['eval']['rte']) && ('tinyMCE' === $fieldConfig['eval']['rte'] || 'tinyMCE_poi' === $fieldConfig['eval']['rte']))
            {
                $fieldConfig['eval']['class'] = 'editor';
            }

            // Set submit on change class
            if(isset($fieldConfig['eval']['submitOnChange']))
            {
                $fieldConfig['eval']['class'] = 'submit';
            }

            if (in_array($fieldName, $editable))
            {
                return true;
            }

            return false;
        });

        // Add submit field
        $this->form->addSubmitFormField('btn_submit', $this->translator->trans('tl_bm_poi.form.submit', [], 'contao_default'));

        // Render form
        $this->template->form = $this->form->generate();
    }

    /**
     * Validate the form
     *
     * @throws \Exception
     */
    protected function validateForm(): void
    {
        if ($this->form->validate())
        {
            // Handle messages
            if($this->form->hasFormField('message') && $message = $this->form->fetch('message'))
            {
                $messages = json_decode($this->poi->messages ?? '', true);
                $messages[] = [
                    'message' => $message,
                    'tstamp'  => time()
                ];

                $this->poi->messages = json_encode($messages);
                $this->poi->message = '';
            }

            // Determine geo data if necessary
            if (empty($this->poi->lat) || empty($this->poi->lng))
            {
                if ($geoData = POI::determineGeoData($this->poi->street, $this->poi->houseNumber, $this->poi->postal, $this->poi->city))
                {
                    $this->poi->lat = $geoData['lat'];
                    $this->poi->lng = $geoData['lng'];
                }
            }

            // Save model
            $this->poi->dirty = 1;
            $this->poi->save();

            // Show message
            Message::addConfirmation($this->translator->trans('tl_bm_poi.form.success', [], 'contao_default'));

            // Reload
            throw new RedirectResponseException($this->request->getRequestUri(),Response::HTTP_MOVED_PERMANENTLY);
        }
        elseif($this->form->isSubmitted())
        {
            foreach ($this->form->getWidgets() as $widget)
            {
                if($widget->hasErrors())
                {
                    // Show error message
                    Message::addError($widget->getErrorsAsString());
                }
            }

            // Reload
            throw new RedirectResponseException($this->request->getRequestUri(),Response::HTTP_MOVED_PERMANENTLY);
        }
    }
}
