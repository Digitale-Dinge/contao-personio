<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoPersonio\Controller\Page;

use Contao\CoreBundle\DependencyInjection\Attribute\AsPage;
use Contao\CoreBundle\Exception\PageNotFoundException;
use Contao\CoreBundle\Routing\ResponseContext\CoreResponseContextFactory;
use Contao\CoreBundle\Routing\ResponseContext\HtmlHeadBag\HtmlHeadBag;
use Contao\CoreBundle\Util\LocaleUtil;
use Contao\FrontendIndex;
use Contao\Input;
use Contao\PageModel;
use InspiredMinds\ContaoPersonio\PersonioXml;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsPage(self::TYPE)]
class PersonioJobPageController
{
    public const TYPE = 'personio_job_page';

    public function __construct(
        private readonly PersonioXml $personioApi,
        private readonly CoreResponseContextFactory $coreResponseContextFactory,
    ) {
    }

    public function __invoke(Request $request, PageModel $pageModel): Response
    {
        if (!$autoItem = Input::get('auto_item')) {
            throw new PageNotFoundException();
        }

        $jobs = $this->personioApi->getJobs(LocaleUtil::getPrimaryLanguage($request->getLocale()))->jobs;

        foreach ($jobs as $job) {
            if ($job->id === $autoItem) {
                $request->attributes->set('_content', $job);

                $responseContext = $this->coreResponseContextFactory->createContaoWebpageResponseContext($pageModel);
                $responseContext->get(HtmlHeadBag::class)
                    ->setTitle($job->name)
                ;

                return (new FrontendIndex())->renderPage($pageModel);
            }
        }

        throw new PageNotFoundException();
    }
}
