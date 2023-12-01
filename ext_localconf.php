<?php
defined('TYPO3') || die();

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][] = [
   'nodeName' => 'iconpickerController',
   'priority' => 30,
   'class' => \Alm\AlmIconpicker\Controller\Wizard\IconpickerController::class
];