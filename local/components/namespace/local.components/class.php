<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Engine\Contract\Controllerable;

\Bitrix\Main\Localization\Loc::loadMessages(__FILE__);

class CustomDealsListComponent extends \CBitrixComponent implements Controllerable
{
    public function executeComponent()
    {
        $this->includeComponentTemplate();
    }

    public function configureActions()
    {
        return [
            'getDeals' => [
                'prefilters' => [],
                'postfilters' => [],
            ],
        ];
    }

    protected function listKeysSignedParameters()
    {
        return [
            'SORT_FIELD',
            'SORT_ORDER',
        ];
    }

    public function getDealsAction($sortField = 'DATE_CREATE', $sortOrder = 'DESC')
    {
        \Bitrix\Main\Loader::includeModule('crm');

        $deals = [];
        $res = \Bitrix\Crm\DealTable::getList([
            'select' => [
                'ID',
                'TITLE',
                'STAGE_ID',
                'OPPORTUNITY',
                'CURRENCY_ID',
                'DATE_CREATE',
            ],
            'order' => [$sortField => $sortOrder],
            'filter' => ['=CLOSED' => 'N'],
        ]);

        while ($deal = $res->fetch()) {
            $deals[] = [
                'id' => $deal['ID'],
                'title' => $deal['TITLE'],
                'stage' => $this->getStageName($deal['STAGE_ID']),
                'amount' => $deal['OPPORTUNITY'],
                'currency' => $deal['CURRENCY_ID'],
                'date' => $deal['DATE_CREATE']->format('d.m.Y H:i:s'),
            ];
        }

        return ['deals' => $deals];
    }

    private function getStageName($stageId)
    {
        $stages = \Bitrix\Crm\StatusTable::getList([
            'filter' => ['=ENTITY_ID' => 'DEAL_STAGE'],
        ])->fetchAll();

        foreach ($stages as $stage) {
            if ($stage['STATUS_ID'] === $stageId) {
                return $stage['NAME'];
            }
        }

        return $stageId;
    }
}