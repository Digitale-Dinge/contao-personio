<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

use InspiredMinds\ContaoPersonio\Controller\ContentElement\PersonioJobApplicationController;
use InspiredMinds\ContaoPersonio\Controller\ContentElement\PersonioJobController;
use InspiredMinds\ContaoPersonio\Controller\ContentElement\PersonioJobsController;
use InspiredMinds\ContaoPersonio\Controller\Page\PersonioJobPageController;

$GLOBALS['TL_LANG']['CTE'][PersonioJobsController::TYPE] = ['Personio Jobs', 'Zeigte eine Liste an offenen Stellen von Personio.'];
$GLOBALS['TL_LANG']['CTE'][PersonioJobController::TYPE] = ['Personio Job', 'Zeigt die Details einer offenen Stelle von Personio.'];
$GLOBALS['TL_LANG']['CTE'][PersonioJobApplicationController::TYPE] = ['Personio Bewerbung', 'Zeigt ein Formular für die Bewerbung bei einer offenen Stelle von Personio.'];
$GLOBALS['TL_LANG']['PTY'][PersonioJobPageController::TYPE] = ['Personio Job', 'Detailseiten-Typ für offene Stellen von Personio.'];
