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
use Visol\Newspermissions\Service\AccessControlService;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageQueue;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook into FormEngine
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class FormEngine {

	/**
	 * Path to the locallang file
	 *
	 * @var string
	 */
	const LLPATH = 'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:';

	/**
	 * Pre-processing of the whole TCEform
	 *
	 * @param string $table
	 * @param array $row
	 * @param \TYPO3\CMS\Backend\Form\FormEngine $parentObject
	 */
	public function getMainFields_preProcess($table, $row, $parentObject) {
		if ($table !== 'tx_news_domain_model_news') {
			return;
		}
		if (!AccessControlService::userHasCategoryPermissionsForRecord($row)) {
			if (method_exists($parentObject, 'setRenderReadonly')) {
				$parentObject->setRenderReadonly(TRUE);
			} else {
				$parentObject->renderReadonly = TRUE;
			}
			$flashMessageContent = $GLOBALS['LANG']->sL(self::LLPATH . 'record.savingdisabled.content', TRUE);
			$flashMessageContent .= '<ul>';
			$accessDeniedCategories = AccessControlService::getAccessDeniedCategories($row);
			foreach ($accessDeniedCategories as $accessDeniedCategory) {
				$flashMessageContent .= '<li>' . htmlspecialchars($accessDeniedCategory['title']) . ' [' . $accessDeniedCategory['uid'] . ']</li>';
			}
			$flashMessageContent .= '</ul>';

			/** @var FlashMessage $flashMessage */
			$flashMessage = GeneralUtility::makeInstance(
				'TYPO3\CMS\Core\Messaging\FlashMessage',
				$flashMessageContent,
				$GLOBALS['LANG']->sL(self::LLPATH . 'record.savingdisabled.header', TRUE),
				FlashMessage::WARNING
			);

			/** @var \TYPO3\CMS\Core\Messaging\FlashMessageService $flashMessageService */
			$flashMessageService = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessageService');
			/** @var $defaultFlashMessageQueue \TYPO3\CMS\Core\Messaging\FlashMessageQueue */
			$defaultFlashMessageQueue = $flashMessageService->getMessageQueueByIdentifier();
			$defaultFlashMessageQueue->enqueue($flashMessage);
		}
	}

}