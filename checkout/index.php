<?
    /* @var $APPLICATION */

    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
    $APPLICATION->SetPageProperty("title", "Checkout");
    $APPLICATION->SetTitle("Checkout");
?>

<? $APPLICATION->IncludeComponent(
        'checkout',
        '.default',
        array(
            'CACHE_TYPE' => 'A',
            'CACHE_TIME' => 3600,
        ),
        array()
    );
?>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>
