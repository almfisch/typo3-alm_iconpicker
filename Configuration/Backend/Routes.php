<?php

return [
    'wizard_iconpicker' => [
        'path' => '/wizard/iconpicker',
        'target' => \Alm\AlmIconpicker\Controller\Wizard\IconpickerController::class . '::mainAction'
    ],
    'popup_iconpicker' => [
        'path' => '/iconpicker',
        'target' => \Alm\AlmIconpicker\Controller\IconpickerPopupController::class . '::mainAction'
    ],
];