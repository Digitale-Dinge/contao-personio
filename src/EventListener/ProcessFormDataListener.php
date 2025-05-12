<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoPersonio\EventListener;

use Codefog\HasteBundle\FileUploadNormalizer;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\Form;
use InspiredMinds\ContaoPersonio\Exception\PersonioApiException;
use InspiredMinds\ContaoPersonio\Message\PersonioApplicationMessage;
use InspiredMinds\ContaoPersonio\Model\Job;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsHook('processFormData')]
class ProcessFormDataListener
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly RequestStack $requestStack,
        private readonly FileUploadNormalizer $fileUploadNormalizer,
        private readonly TranslatorInterface $translator,
        private readonly KernelInterface $kernel,
    ) {
    }

    public function __invoke(array $submittedData, array $formData, array|null $files, array $labels, Form $form): void
    {
        if (!$form->storeInPersonio) {
            return;
        }

        if (!($job = $this->requestStack->getCurrentRequest()?->attributes->get('_content')) instanceof Job) {
            return;
        }

        $submittedData['job_position_id'] = (int) $job->id;
        $submittedData['application_date'] = (new \DateTimeImmutable())->format('Y-m-d');
        $applicationFiles = [];

        foreach ($this->fileUploadNormalizer->normalize($files) as $field => $uploads) {
            foreach ($uploads as $upload) {
                $applicationFiles[$field] ??= [];
                $applicationFiles[$field][] = $upload['tmp_name'];
            }

            unset($submittedData[$field]);
        }

        try {
            $this->messageBus->dispatch(new PersonioApplicationMessage($submittedData, $applicationFiles));
        } catch (\Throwable $e) {
            if (!method_exists($form, 'addError') || $this->kernel->isDebug()) {
                throw $e;
            }

            do {
                if ($e instanceof PersonioApiException) {
                    if ($e->getMessage() !== ($translated = $this->translator->trans($e->getMessage(), [], 'personio'))) {
                        $form->addError($translated);
                    } else {
                        $form->addError($this->translator->trans('ERR.general', [], 'contao_default'));
                    }

                    $form->addError($e->getMessage());

                    return;
                }
            } while ($e = $e->getPrevious());

            throw $e;
        }
    }
}
