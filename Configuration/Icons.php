<?php

declare(strict_types=1);

return [
    'module_alm_iconpicker' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
        'source' => 'EXT:alm_iconpicker/Resources/Public/Icons/alm_iconpicker.png',
        'spinning' => false,
    ],
    'module_alm_iconpicker_popup' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:alm_iconpicker/Resources/Public/Icons/alm_iconpicker_popup.svg',
        'spinning' => false,
    ],
];