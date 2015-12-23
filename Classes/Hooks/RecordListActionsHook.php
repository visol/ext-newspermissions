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
use TYPO3\CMS\Backend\Utility\IconUtility;

/**
 * Hook to modify control icons for news items
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class RecordListActionsHook implements \TYPO3\CMS\Recordlist\RecordList\RecordListHookInterface
{

    /**
     * Path to the locallang file
     *
     * @var string
     */
    const LLPATH = 'LLL:EXT:newspermissions/Resources/Private/Language/locallang_be.xlf:';

    /**
     * Modifies Web>List clip icons (copy, cut, paste, etc.) of a displayed row
     *
     * @param string $table The current database table
     * @param array $row The current record row
     * @param array $cells The default clip-icons to get modified
     * @param object $parentObject Instance of calling object
     * @return array The modified clip-icons
     */
    public function makeClip($table, $row, $cells, &$parentObject)
    {
        return $cells;
    }

    /**
     * Modifies Web>List control icons of a displayed row
     *
     * @param string $table The current database table
     * @param array $row The current record row
     * @param array $cells The default control-icons to get modified
     * @param object $parentObject Instance of calling object
     * @return array The modified control-icons
     */
    public function makeControl($table, $row, $cells, &$parentObject)
    {
        if ($table === 'tx_news_domain_model_news' && !\Visol\Newspermissions\Service\AccessControlService::userHasCategoryPermissionsForRecord($row)) {
            $spaceIcon = IconUtility::getSpriteIcon('empty-empty', array('style' => 'background-position: 0 10px;'));
            $cells['edit'] = '<a href="#" title="' . $GLOBALS['LANG']->sL(self::LLPATH . 'listmodule_editlock',
                    true) . '">' . IconUtility::getSpriteIcon('apps-pagetree-drag-place-denied') . '</a>';
            $cells['delete'] = $spaceIcon;
            $cells['hide'] = $spaceIcon;
            $cells['history'] = $spaceIcon;
        }
        return $cells;
    }

    /**
     * Modifies Web>List header row columns/cells
     *
     * @param string $table The current database table
     * @param array $currentIdList Array of the currently displayed uids of the table
     * @param array $headerColumns An array of rendered cells/columns
     * @param object $parentObject Instance of calling (parent) object
     * @return array Array of modified cells/columns
     */
    public function renderListHeader($table, $currentIdList, $headerColumns, &$parentObject)
    {
        return $headerColumns;
    }

    /**
     * Modifies Web>List header row clipboard/action icons
     *
     * @param string $table The current database table
     * @param array $currentIdList Array of the currently displayed uids of the table
     * @param array $cells An array of the current clipboard/action icons
     * @param object $parentObject Instance of calling (parent) object
     * @return array Array of modified clipboard/action icons
     */
    public function renderListHeaderActions($table, $currentIdList, $cells, &$parentObject)
    {
        return $cells;
    }

}