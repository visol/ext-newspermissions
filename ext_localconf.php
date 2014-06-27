<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

// Implement the actions hook in the RecordList to manipulate the available icons in the List module
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/class.db_list_extra.inc']['actions']['newspermissions'] = '\Visol\Newspermissions\Hooks\RecordListActionsHook';