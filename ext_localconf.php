<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

// Use the RecordList getTable hook to restrict displayed news to editable news
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/class.db_list_extra.inc']['getTable'][] = '\Visol\Newseditrestriction\Hooks\RecordListDataHook';

