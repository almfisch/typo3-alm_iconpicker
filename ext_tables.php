<?php
if(!defined('TYPO3_MODE'))
{
	die ('Access denied.');
}

if('BE' === TYPO3_MODE)
{
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'AlmIconpicker',
		'tools',
		'iconpickerModule',
		'',
		array(
			\Alm\AlmIconpicker\Controller\IconpickerModuleController::class => 'index',
		),
		array(
			'access' => 'user,group',
			'icon' => 'EXT:alm_iconpicker/Resources/Public/Icons/alm_iconpicker.png',
			'labels' => 'LLL:EXT:alm_iconpicker/Resources/Private/Language/locallang.xlf'
		)
	);
}
