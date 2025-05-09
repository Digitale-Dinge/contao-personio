<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

use InspiredMinds\ContaoPersonio\Controller\ContentElement\PersonioJobApplicationController;
use InspiredMinds\ContaoPersonio\Controller\ContentElement\PersonioJobController;
use InspiredMinds\ContaoPersonio\Controller\ContentElement\PersonioJobsController;
use InspiredMinds\ContaoPersonio\Controller\Page\PersonioJobPageController;

$GLOBALS['TL_LANG']['CTE'][PersonioJobsController::TYPE] = ['Personio jobs', 'Shows a list of jobs from Personio.'];
$GLOBALS['TL_LANG']['CTE'][PersonioJobController::TYPE] = ['Personio job', 'Shows the details of a job from Personio.'];
$GLOBALS['TL_LANG']['CTE'][PersonioJobApplicationController::TYPE] = ['Personio job application', 'Shows a form for applying to a job from Personio.'];
$GLOBALS['TL_LANG']['PTY'][PersonioJobPageController::TYPE] = ['Personio Job', 'Detail page type for Personio jobs.'];
