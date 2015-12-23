<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

// Implement the actions hook in the RecordList to manipulate the available icons in the List module
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/class.db_list_extra.inc']['actions']['newspermissions'] = 'Visol\\Newspermissions\\Hooks\\RecordListActionsHook';

// FormEngine: Rendering of the whole FormEngine
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getMainFieldsClass']['news'] =
    'Visol\\Newspermissions\\Hooks\\FormEngine';

// Edit restriction for news records
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass']['news'] =
    'Visol\\Newspermissions\\Hooks\\DataHandler';

// Preview of news records
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['news'] =
    'Visol\\Newspermissions\\Hooks\\DataHandler';