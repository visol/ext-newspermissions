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
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook to modify control icons for news items
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
        if ($table === 'tx_news_domain_model_news' && !\Visol\Newspermissions\Service\AccessControlService::userHasCategoryPermissionsForRecord($row)) {
            $cells['pasteInto'] = '';
            $cells['pasteAfter'] = '';
            $cells['copy'] = '';
            $cells['cut'] = '';
        }

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
            $spaceIcon = '';

            $cells['edit'] = '
                <div class="btn btn-default" title="' . $GLOBALS['LANG']->sL(self::LLPATH . 'listmodule_editlock', true) . '">
                    ' . $this->getIconFactory()->getIcon('apps-pagetree-drag-place-denied', Icon::SIZE_SMALL) . '             
                </div>';

            $cells['hide'] = $spaceIcon;
            $cells['delete'] = $spaceIcon;
            $cells['viewBig'] = $spaceIcon;
            $cells['history'] = $spaceIcon;
            $cells['new'] = $spaceIcon;

            $cells['primary']['edit'] = $cells['edit'];
            $cells['primary']['hide'] = $cells['hide'];
            $cells['primary']['delete'] = $cells['delete'];

            $cells['secondary']['viewBig'] = $cells['viewBig'];
            $cells['secondary']['history'] = $cells['history'];
            $cells['secondary']['new'] = $cells['new'];
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

    /**
     * Hook from EXT:gridelements to modify entire row of the RecordList
     *
     * @param string $table The current database table
     * @param array $row The current record row
     * @param int $level
     * @param array $theData Data array with rendered content for RecordList
     * @param object $parentObject Instance of calling (parent) object
     */
    public function checkChildren($table, $row, $level, &$theData, &$parentObject)
    {
        if ($table === 'tx_news_domain_model_news' && !\Visol\Newspermissions\Service\AccessControlService::userHasCategoryPermissionsForRecord($row)) {
            $title = $theData['title'];
            $title = preg_replace("/<\\/?a(\\s+.*?>|>)/", "", $title);

            $theData['title'] = $title;
            $theData['__label'] = $title;

            //$theData['_CONTROL_'] = '';
            $theData['_CLIPBOARD_'] = '';
            $theData['_LOCALIZATION_'] = '';
            $theData['_LOCALIZATION_b'] = '';
        }
    }

    /**
     * @return IconFactory
     */
    protected function getIconFactory()
    {
        return GeneralUtility::makeInstance(IconFactory::class);
    }
}
