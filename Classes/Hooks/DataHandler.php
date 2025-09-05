<?php

namespace Visol\Newspermissions\Hooks;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use GeorgRinger\News\Hooks\DataHandlerHook;
use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;
use Visol\Newspermissions\Service\AccessControlService;

/**
 * Hook into tcemain which is used to show preview of news item
 */
class DataHandler extends DataHandlerHook
{
    /**
     * Prevent saving of a news record if the editor doesn't have access to all categories of the news record
     *
     * @param array $fieldArray
     * @param string $table
     * @param int $id
     * @param $parentObject \TYPO3\CMS\Core\DataHandling\DataHandler
     */
    public function processDatamap_preProcessFieldArray(&$fieldArray, $table, $id, $parentObject): void
    {
        if ($table !== 'tx_news_domain_model_news') {
            return;
        }

        if ($this->getBackendUser()->isAdmin()) {
            return;
        }

        if (!is_int($id)) {
            return;
        }

        // check permissions of assigned categories
        $newsRecord = BackendUtilityCore::getRecord($table, $id);
        if (!AccessControlService::userHasCategoryPermissionsForRecord($newsRecord)) {
            $parentObject->log(
                $table,
                $id,
                2,
                0,
                1,
                "processDatamap: Attempt to modify a record from table '%s' without permission.
                Reason: the record has one or more categories assigned that are not defined in
                your BE usergroup.",
                1,
                [$table]
            );
            // unset fieldArray to prevent saving of the record
            $fieldArray = [];
        } elseif (isset($fieldArray['categories']) && strpos($fieldArray['categories'], '|') === false) {
            $deniedCategories = AccessControlService::getAccessDeniedCategories($newsRecord);
            if (is_array($deniedCategories)) {
                foreach ($deniedCategories as $deniedCategory) {
                    $fieldArray['categories'] .= ',' . $deniedCategory['uid'];
                }
                // Check if the categories are not empty,
                if (!empty($fieldArray['categories'])) {
                    $fieldArray['categories'] = trim($fieldArray['categories'], ',');
                }
            }
        }
    }

    /**
     * Prevent deleting/moving of a news record if the editor doesn't have access to all categories of the news record
     *
     * @param string $command
     * @param string $table
     * @param int $id
     * @param string $value
     * @param $parentObject \TYPO3\CMS\Core\DataHandling\DataHandler
     */
    public function processCmdmap_preProcess($command, &$table, $id, $value, $parentObject): void
    {
        if ($table !== 'tx_news_domain_model_news') {
            return;
        }

        if ($this->getBackendUser()->isAdmin()) {
            return;
        }

        if (!is_int($id) || $command === 'undelete') {
            return;
        }

        $newsRecord = BackendUtilityCore::getRecord($table, $id);
        if (!AccessControlService::userHasCategoryPermissionsForRecord($newsRecord)) {
            $parentObject->log(
                $table,
                $id,
                2,
                0,
                1,
                "processCmdmap: Attempt to " . $command . " a record from table '%s' without permission.
                Reason: the record has one or more categories assigned that are not defined in the BE usergroup.",
                1,
                [$table]
            );

            // unset table to prevent saving
            $table = '';
        }
    }
}
