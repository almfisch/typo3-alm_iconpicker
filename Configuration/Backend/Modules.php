<?php

/**
 * Definitions for modules provided by EXT:alm_iconpicker
 */
return [
    'web_alm_iconpicker' => [
        'position' => ['after' => 'web_info'],
        'access' => 'user',
        'extensionName' => 'AlmIconpicker',
        'iconIdentifier' => 'module_alm_iconpicker',
        'labels' => 'LLL:EXT:alm_iconpicker/Resources/Private/Language/locallang.xlf',
        'controllerActions' => [
            Alm\AlmIconpicker\Controller\IconpickerModuleController::class => [
                'index',
            ],
        ],
    ],
];