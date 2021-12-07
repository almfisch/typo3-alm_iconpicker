<?php
defined('TYPO3_MODE') || die();

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][] = [
   'nodeName' => 'iconpickerController',
   'priority' => 30,
   'class' => \Alm\AlmIconpicker\Controller\Wizard\IconpickerController::class
];

$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
   'icon-picker',
   \TYPO3\CMS\Core\Imaging\IconProvider\FontawesomeIconProvider::class,
   array('name' => 'search')
);