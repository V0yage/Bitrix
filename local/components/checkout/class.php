<?php

use Bitrix\Main\SystemException;
use Bitrix\Main\Loader;
use \Bitrix\Iblock\ElementTable;
use \Bitrix\Iblock\Iblock;
use Bitrix\Main\FileTable;
use \Bitrix\Main\Application;
use \Bitrix\Main\Context;

class Checkout extends CBitrixComponent
{
    public function checkModules()
    {
        if (!Loader::includeModule('iblock')) {
            throw new SystemException('Не удалось загрузить модуль iblock');
        }
    }

    public function executeComponent()
    {
        try
        {
            $this->checkModules();

            $request = Context::getCurrent()->getRequest();

            if ($request->isPost()) {
                $orderData = $request->getPostList()->toArray();
                $this->arResult['ORDER_STATUS'] = $this->appendOrder($orderData);
                $this->resetCart();
            } else {
                $this->fillCart();

                $session = Application::getInstance()->getSession();

                $this->arResult['CART'] = [
                    'qty' => $session['cart_qty'],
                    'sum' => $session['cart_sum'],
                ];
                $this->arResult['DELIVERY'] = $this->getDeliveryList();
                $this->arResult['PAYMENTS'] = $this->getPaymentsList();
            }

            $this->includeComponentTemplate();
        }
        catch (SystemException $e)
        {
            ShowError($e->getMessage());
        }
    }

    private function getFileName($fileId)
    {
        $file = FileTable::getById($fileId)->fetchObject();
        $logoPath = '/upload/' . $file->getSubdir() . '/' . $file->getFileName();

        return $logoPath;
    }

    private function getDeadlineText($daysCount) {
        $label = 'дней';
        $remainder = $daysCount % 100;

        if (!($remainder > 9 && $remainder < 15)) {
            $remainder = $daysCount % 10;
            switch ($remainder) {
                case 1: $label = 'день'; break;
                case 2: case 3: case 4: $label = 'дня'; break;
            }

        }

        return "$daysCount $label";
    }

    private function getDeliveryList()
    {
        $iblockId = 1;
        $deliveryEntity = Iblock::wakeUp($iblockId)->getEntityDataClass();

        $deliveryServices= $deliveryEntity::getList([
            'select' => [
                'ID', 'NAME',
                'DEADLINE_' => 'DEADLINE', 'COST_' => 'COST', 'LOGO_' => 'LOGO',
            ],
            'cache' => [
                'ttl' => 3600,
            ],
            'filter' => [
                'ACTIVE' => 'Y',
            ],
        ])->fetchCollection();

        $services = [];
        foreach ($deliveryServices as $service) {
            $logoId = $service->getLogo()->getValue();

            $services[] = [
                'ID' => $service->getId(),
                'NAME' => $service->getName(),
                'DEADLINE' => $this->getDeadlineText($service->getDeadline()->getValue()),
                'COST' => $service->getCost()->getValue(),
                'LOGO' => $this->getFileName($logoId),
            ];
        }

        return $services;
    }

    private function getPaymentsList()
    {
        $iblockId = 2;
        $deliveryEntity = Iblock::wakeUp($iblockId)->getEntityDataClass();

        $paymentServices= $deliveryEntity::getList([
            'select' => [
                'ID', 'NAME',
                'LOGO_' => 'LOGO', 'URL_' => 'URL',
            ],
            'cache' => [
                'ttl' => 3600,
            ],
            'filter' => [
                'ACTIVE' => 'Y',
            ],
        ])->fetchCollection();

        $services = [];
        foreach ($paymentServices as $service) {
            $logoId = $service->getLogo()->getValue();

            $services[] = [
                'ID' => $service->getId(),
                'NAME' => $service->getName(),
                'URL' => $service->getUrl()->getValue(),
                'LOGO' => $this->getFileName($logoId),
            ];
        }

        return $services;
    }

    private function appendOrder($orderInfo)
    {
        $iblockId = 3;
        $orderClass = Iblock::wakeUp($iblockId)->getEntityDataClass();

        $order = $orderClass::createObject();
        $this->multiplePropertiesAssign($order, $orderInfo);

        return $order->save();
    }

    private function fillCart()
    {
        $cartCapacity = rand(5, 20);
        $cart = [];
        $sum = 0;
        $qty = 0;
        for ($i = 0; $i < $cartCapacity; $i++) {
           $product = [
               'name' => "product$i",
               'cost' => rand(1, 1000),
               'qty' => rand(5, 10),
           ];
            $cart[] = $product;
            $sum += $product['cost'] * $product['qty'];
            $qty += $product['qty'];
        }

        $session = Application::getInstance()->getSession();
        $session->set('cart', $cart);
        $session->set('cart_sum', $sum);
        $session->set('cart_qty', $qty);
    }

    private function resetCart()
    {
        $session = Application::getInstance()->getSession();
        $session->remove('cart');
        $session->remove('cart_sum');
        $session->remove('cart_qty');
    }

    private function multiplePropertiesAssign($model, $propsList)
    {
        foreach ($propsList as $name => $value) {
            $methodName = 'set' . ucfirst(strtolower($name));
            $model->$methodName($value);
        }
        return $model->save();
    }
}