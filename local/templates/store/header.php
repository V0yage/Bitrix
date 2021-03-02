<?
    /* @var $APPLICATION */

    use Bitrix\Main\Page\Asset;

    if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	    die();
?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<? $APPLICATION->ShowHead(); ?>
		<title><? $APPLICATION->ShowTitle(); ?></title>
		<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />

        <? // CSS include ?>
        <? Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/css/bootstrap.min.css'); ?>
        <? Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/css/style.css'); ?>

        <? // JS include ?>
        <? Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . '/js/jquery-3.5.1.min.js'); ?>
        <? Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . '/js/bootstrap.min.js'); ?>
        <? Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . '/js/script.js'); ?>
	</head>
	<body>
		<div id="panel">
			<? $APPLICATION->ShowPanel(); ?>
		</div>
	    <div class="container">