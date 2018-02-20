<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// Implement the actions hook in the RecordList to manipulate the available icons in the List module
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/class.db_list_extra.inc']['actions']['newspermissions'] = \Visol\Newspermissions\Hooks\RecordListActionsHook::class;

// FormDataProvider: Rendering of the whole FormDataProvider
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][\Visol\Newspermissions\Hooks\FormDataProvider::class] = [
    'depends' => [
        \TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseRowInitializeNew::class,
    ]
];

// Edit restriction for news records
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass']['news'] = \Visol\Newspermissions\Hooks\DataHandler::class;

// Preview of news records
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['news'] = \Visol\Newspermissions\Hooks\DataHandler::class;
