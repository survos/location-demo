<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    'bootstrap' => [
        'version' => '5.3.3',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '5.3.3',
        'type' => 'css',
    ],
    'jquery' => [
        'version' => '3.7.1',
    ],
    'datatables.net-plugins/i18n/en-GB.mjs' => [
        'version' => '1.13.6',
    ],
    'datatables.net-bs5' => [
        'version' => '1.13.8',
    ],
    'datatables.net' => [
        'version' => '1.13.7',
    ],
    'datatables.net-bs5/css/dataTables.bootstrap5.min.css' => [
        'version' => '1.13.8',
        'type' => 'css',
    ],
    'datatables.net-buttons-bs5' => [
        'version' => '2.4.2',
    ],
    'datatables.net-buttons' => [
        'version' => '3.0.0',
    ],
    'datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css' => [
        'version' => '2.4.2',
        'type' => 'css',
    ],
    'datatables.net-select-bs5' => [
        'version' => '1.7.0',
    ],
    'datatables.net-select' => [
        'version' => '2.0.0',
    ],
    'datatables.net-select-bs5/css/select.bootstrap5.min.css' => [
        'version' => '1.7.0',
        'type' => 'css',
    ],
    'datatables.net-scroller-bs5' => [
        'version' => '2.3.0',
    ],
    'datatables.net-scroller' => [
        'version' => '2.3.0',
    ],
    'datatables.net-scroller-bs5/css/scroller.bootstrap5.min.css' => [
        'version' => '2.3.0',
        'type' => 'css',
    ],
    'datatables.net-responsive-bs5' => [
        'version' => '2.5.1',
    ],
    'datatables.net-responsive' => [
        'version' => '2.5.1',
    ],
    'datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css' => [
        'version' => '2.5.1',
        'type' => 'css',
    ],
    'datatables.net-searchpanes-bs5' => [
        'version' => '2.2.0',
    ],
    'datatables.net-searchpanes' => [
        'version' => '2.2.0',
    ],
    'datatables.net-searchpanes-bs5/css/searchPanes.bootstrap5.min.css' => [
        'version' => '2.2.0',
        'type' => 'css',
    ],
];
