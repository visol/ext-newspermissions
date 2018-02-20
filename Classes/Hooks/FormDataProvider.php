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
use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use Visol\Newspermissions\Service\AccessControlService;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook into FormDataProvider
 *
 * @package TYPO3
 */
class FormDataProvider implements FormDataProviderInterface
{

    /**
     * Path to the locallang file
     *
     * @var string
     */
    const LLPATH = 'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:';

    /**
     * Set all fields from TCA to readonly on missing permissions
     *
     * @param array $result Initialized result array
     * @return array Result filled with more data
     */
    public function addData(array $result)
    {
        if ($result['tableName'] !== 'tx_news_domain_model_news') {
            return $result;
        }

        if (!\Visol\Newspermissions\Service\AccessControlService::userHasCategoryPermissionsForRecord($result['databaseRow'])) {
            foreach ($result['processedTca']['columns'] as $fieldName => $fieldData) {
                $result['processedTca']['columns'][$fieldName]['config']['readOnly'] = 1;
            }

            $this->enqueueFlashMessage($result['databaseRow']);
        }

        return $result;
    }

    /**
     * @param $row
     */
    protected function enqueueFlashMessage($row)
    {
        // TODO: Format output of flashMessage.
        // HTML has been removed in TYPO3 7.4. See https://docs.typo3.org/typo3cms/extensions/core/7.6/Changelog/7.4/Breaking-67546-CleanupFlashMessageRendering.html

        $flashMessageContent = $GLOBALS['LANG']->sL(self::LLPATH . 'record.savingdisabled.content', true);
        $accessDeniedCategories = AccessControlService::getAccessDeniedCategories($row);
        foreach ($accessDeniedCategories as $accessDeniedCategory) {
            $flashMessageContent .= $accessDeniedCategory['title'] . ' [' . $accessDeniedCategory['uid'] . '] ';
        }

        /** @var FlashMessage $flashMessage */
        $flashMessage = GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Messaging\FlashMessage::class,
            $flashMessageContent,
            $GLOBALS['LANG']->sL(self::LLPATH . 'record.savingdisabled.header', true),
            FlashMessage::WARNING
        );

        /** @var \TYPO3\CMS\Core\Messaging\FlashMessageService $flashMessageService */
        $flashMessageService = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);

        /** @var $defaultFlashMessageQueue \TYPO3\CMS\Core\Messaging\FlashMessageQueue */
        $defaultFlashMessageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $defaultFlashMessageQueue->enqueue($flashMessage);
    }
}
