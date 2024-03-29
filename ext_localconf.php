<?php

use Visol\Newspermissions\Hooks\DataHandler;
use Visol\Newspermissions\Hooks\RecordListActionsHook;
use Visol\Newspermissions\Hooks\FormDataProvider;
use TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseRowInitializeNew;
if (!defined('TYPO3')) {
    die('Access denied.');
}

// Implement the actions hook in the RecordList to manipulate the available icons in the List module
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/class.db_list_extra.inc']['actions']['newspermissions'] = RecordListActionsHook::class;

// FormDataProvider: Rendering of the whole FormDataProvider
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][FormDataProvider::class] = [
    'depends' => [
        DatabaseRowInitializeNew::class,
    ]
];

// Edit restriction for news records
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass']['news'] = DataHandler::class;

// Preview of news records
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['news'] = DataHandler::class;
