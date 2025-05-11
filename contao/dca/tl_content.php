<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

use Doctrine\DBAL\Platforms\MySQLPlatform;
use InspiredMinds\ContaoPersonio\Controller\ContentElement\PersonioJobApplicationController;
use InspiredMinds\ContaoPersonio\Controller\ContentElement\PersonioJobController;
use InspiredMinds\ContaoPersonio\Controller\ContentElement\PersonioJobsController;
use InspiredMinds\ContaoPersonio\PersonioApi;

$GLOBALS['TL_DCA']['tl_content']['fields']['jumpTo'] = [
    'exclude' => true,
    'inputType' => 'pageTree',
    'foreignKey' => 'tl_page.title',
    'eval' => ['fieldType' => 'radio'],
    'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
    'relation' => ['type' => 'hasOne', 'load' => 'lazy'],
];

$GLOBALS['TL_DCA']['tl_content']['fields']['personio_applicationFields'] = [
    'exclude' => true,
    'inputType' => 'checkboxWizard',
    'options' => array_merge(PersonioApi::$standardApplicationFields, PersonioApi::$systemApplicationAttributes),
    'reference' => &$GLOBALS['TL_LANG']['MSC']['personioFields'],
    'eval' => ['multiple' => true],
    'sql' => ['type' => 'blob', 'length' => MySQLPlatform::LENGTH_LIMIT_BLOB, 'notnull' => false, 'default' => serialize(['first_name', 'last_name', 'email'])],
];

$GLOBALS['TL_DCA']['tl_content']['palettes'][PersonioJobsController::TYPE] = '{type_legend},type,headline;{config_legend},jumpTo;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes'][PersonioJobController::TYPE] = '{type_legend},type,headline;{config_legend},jumpTo;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes'][PersonioJobApplicationController::TYPE] = '{type_legend},type,headline;{config_legend},personio_applicationFields,jumpTo;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';
