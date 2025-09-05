<?php

declare(strict_types=1);

namespace Visol\Newspermissions\Backend\EventListener;

use TYPO3\CMS\Backend\RecordList\Event\ModifyRecordListRecordActionsEvent;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use Visol\Newspermissions\Service\AccessControlService;

#[AsEventListener(
    identifier: 'newspermissions/recordlist/modify-news-record-actions',
    method: 'modifyRecordActions',
)]
final readonly class ModifyNewsRecordTableActions
{
    public function __construct()
    {
    }

    public function modifyRecordActions(ModifyRecordListRecordActionsEvent $event): void
    {
        $currentTable = $event->getTable();
        if ($currentTable !== 'tx_news_domain_model_news') {
            return;
        }

        if (!AccessControlService::userHasCategoryPermissionsForRecord($event->getRecord())) {
            $event->removeAction('edit');
            $event->removeAction('hide');
            $event->removeAction('delete');
            $event->removeAction('pasteInto');
            $event->removeAction('pasteAfter');
            $event->removeAction('copy');
            $event->removeAction('cut');
        }
    }
}
