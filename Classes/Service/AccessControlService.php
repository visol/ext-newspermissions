<?php

namespace Visol\Newspermissions\Service;

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

use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Service for access control related stuff
 */
class AccessControlService extends \GeorgRinger\News\Service\AccessControlService
{
    /**
     * Check if a user has access to all categories of a news record
     */
    public static function userHasCategoryPermissionsForRecord(array $newsRecord): bool
    {
        if (self::getBackendUser()->isAdmin()) {
            // an admin may edit all news
            return true;
        }

        $settings = GeneralUtility::makeInstance(EmConfiguration::class);
        if (!$settings->getCategoryBeGroupTceFormsRestriction()) {
            return true;
        }
        // If there are any categories with denied access, the user has no permission
        return count(self::getAccessDeniedCategories($newsRecord)) === 0;
    }

    /**
     * Get an array with the uid and title of all categories the user doesn't have access to
     *
     * In EXT:news, this method was changed to also include sub-categories of the categories a user has permission
     * to use. Since this is not the use-case of this extension, this method is overridden.
     *
     * The method userHasCategoryPermissionsForRecord is unchanged, but needs to be copied because it is static.
     */
    public static function getAccessDeniedCategories(array $newsRecord): array
    {
        if (self::getBackendUser()->isAdmin()) {
            // an admin may edit all news so no categories without access
            return [];
        }

        // no category mounts set means access to all
        $backendUserCategories = self::getBackendUser()->getCategoryMountPoints();
        if ($backendUserCategories === []) {
            return [];
        }

        $newsRecordCategories = self::getCategoriesForNewsRecord($newsRecord);

        // Remove categories the user has access to
        foreach ($newsRecordCategories as $key => $newsRecordCategory) {
            if (in_array($newsRecordCategory['uid'], $backendUserCategories)) {
                unset($newsRecordCategories[$key]);
            }
        }

        return $newsRecordCategories;
    }
}
