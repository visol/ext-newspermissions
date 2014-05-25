<?php

namespace Visol\Newseditrestriction\Hooks;

class RecordListDataHook implements \TYPO3\CMS\Backend\RecordList\RecordListGetTableHookInterface {

	public function getDBlistQuery($table, $pageId, &$additionalWhereClause, &$selectedFieldsList, &$parentObject){

		/** @var \TYPO3\CMS\Core\Authentication\BackendUserAuthentication $backendUserObject */
		$backendUserObject = $GLOBALS['BE_USER'];
		if (!$backendUserObject->isAdmin()) {
			if ($table === 'tx_news_domain_model_news') {

				$allowedCategories = \Tx_News_Utility_CategoryProvider::getUserMounts();

				/** @var \TYPO3\CMS\Core\Database\DatabaseConnection $databaseConnection */
				$databaseConnection = $GLOBALS['TYPO3_DB'];
				$allowedRecordQuery = $databaseConnection->sql_query('SELECT DISTINCT(uid) FROM tx_news_domain_model_news AS news JOIN tx_news_domain_model_news_category_mm as category_mm ON news.uid = category_mm.uid_local WHERE category_mm.uid_foreign IN (' . $allowedCategories . ');');
				$allowedRecordsArray = $databaseConnection->sql_fetch_row($allowedRecordQuery);
				$allowedRecordsList = implode(',', $allowedRecordsArray);

				$additionalWhereClause .= ' AND uid IN (' . $allowedRecordsList . ')';
			}
		}
	}

}