<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoPersonio\Controller\ContentElement;

use Codefog\HasteBundle\FileUploadNormalizer;
use Codefog\HasteBundle\Form\Form;
use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\PageModel;
use Contao\Template;
use Contao\Widget;
use InspiredMinds\ContaoPersonio\Message\PersonioApplicationMessage;
use InspiredMinds\ContaoPersonio\Model\Job;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Terminal42\FineUploaderBundle\Widget\FrontendWidget;

#[AsContentElement(self::TYPE, template: 'ce_personio_job_application')]
class PersonioJobApplicationController extends AbstractContentElementController
{
    public const TYPE = 'personio_job_application';

    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly TranslatorInterface $translator,
        private readonly FileUploadNormalizer $fileUploadNormalizer,
        private readonly string $projectDir,
    ) {
    }

    protected function getResponse(Template $template, ContentModel $model, Request $request): Response
    {
        if (!($job = $request->attributes->get('_content')) instanceof Job) {
            return new Response();
        }

        $form = $this->buildForm($model, $request);

        if ($form->validate()) {
            $data = $form->fetchAll(
                function (string $name, Widget $widget): mixed {
                    if ($widget instanceof FrontendWidget) {
                        if (\is_array($widget->value)) {
                            return array_map(fn (string $value): string => Path::join($this->projectDir, $value), (array) $widget->value);
                        }

                        return Path::join($this->projectDir, $widget->value);
                    }

                    return $widget->value;
                },
            );

            $data['job_position_id'] = (int) $job->id;
            $data['application_date'] = (new \DateTimeImmutable())->format('Y-m-d');

            $this->messageBus->dispatch(new PersonioApplicationMessage($data));

            if ($jumpTo = PageModel::findById($model->jumpTo)) {
                return new RedirectResponse($jumpTo->getAbsoluteUrl());
            }
        }

        $template->form = $form->generate();

        return $template->getResponse();
    }

    private function buildForm(ContentModel $model, Request $request): Form
    {
        $form = new Form(
            'personio-job-application-'.$model->id,
            'POST',
            static fn (): bool => 'personio-job-application-'.$model->id === $request->request->get('FORM_SUBMIT'),
        );

        $form->addFormField('first_name', [
            'label' => $this->translator->trans('first_name', [], 'personio'),
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255],
        ]);

        $form->addFormField('last_name', [
            'label' => $this->translator->trans('last_name', [], 'personio'),
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255],
        ]);

        $form->addFormField('email', [
            'label' => $this->translator->trans('email', [], 'personio'),
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'rgxp' => 'email', 'maxlength' => 255],
        ]);

        $form->addFormField('message', [
            'label' => $this->translator->trans('message', [], 'personio'),
            'inputType' => 'textarea',
        ]);

        $form->addFormField('file_cv', [
            'label' => $this->translator->trans('file_cv', [], 'personio'),
            'inputType' => 'fineUploader',
            'eval' => [
                'multiple' => true,
                'uploaderLimit' => 10,
                'doNotOverwrite' => true,
                'extensions' => 'pdf,pptx,xlsx,docx,doc,xls,ppt,ods,odt,7z,gz,rar,zip,bmp,gif,jpg,png,tif,csv,txt,rtf,mp4,3gp,mov,avi,wmv',
                'maxlength' => 20971520,
            ],
        ]);

        $form->addSubmitFormField($this->translator->trans('submit', [], 'personio'));

        return $form;
    }
}
