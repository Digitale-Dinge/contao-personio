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
$GLOBALS['TL_LANG']['MSC']['personioFields'] = [
    'first_name' => 'Vorname',
    'last_name' => 'Nachname',
    'email' => 'E-Mail',
    'message' => 'Nachricht',
    'cv' => 'Lebenslauf',
    'cover-letter' => 'Motivationschreiben',
    'employment-reference' => 'Arbeitszeugnis',
    'certificate' => 'Zertifikat',
    'work-sample' => 'Arbeitsprobe',
    'other' => 'Anderes',
    'birthday' => 'Geburtstag',
    'gender' => 'Geschlecht',
    'location' => 'Ort',
    'phone' => 'Telefonnummer',
    'available_from' => 'Verfügbar ab',
    'salary_expectations' => 'Erwartetes Gehalt',
];
$GLOBALS['TL_LANG']['MSC']['personioGenderOptions'] = [
    'male' => 'Männlich',
    'female' => 'Weiblich',
    'diverse' => 'Divers',
    'undefined' => 'Undefiniert',
];
