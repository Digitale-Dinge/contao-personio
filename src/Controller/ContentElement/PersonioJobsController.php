<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoPersonio\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Util\LocaleUtil;
use Contao\PageModel;
use Contao\Template;
use InspiredMinds\ContaoPersonio\Model\Job;
use InspiredMinds\ContaoPersonio\PersonioXml;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement(self::TYPE, template: 'ce_personio_jobs')]
class PersonioJobsController extends AbstractContentElementController
{
    public const TYPE = 'personio_jobs';

    public function __construct(private readonly PersonioXml $personioApi)
    {
    }

    protected function getResponse(Template $template, ContentModel $model, Request $request): Response
    {
        try {
            $template->jobs = $this->personioApi?->getJobs(LocaleUtil::getPrimaryLanguage($request->getLocale()))?->jobs;
        } catch (\Throwable $e) {
            if ($this->container->get('contao.routing.scope_matcher')->isBackendRequest($request)) {
                return new Response('<p class="tl_error">'.$e->getMessage().'</p>');
            }

            return new Response();
        }

        if (!$template->jobs) {
            return new Response();
        }

        $template->getJobDetailUrl = static function (Job $job) use ($model): string|null {
            if (!$job->id || !($jumpTo = PageModel::findById($model->jumpTo))) {
                return null;
            }

            return $jumpTo->getFrontendUrl('/'.$job->id);
        };

        return $template->getResponse();
    }
}
