<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <? use Bitrix\Main\Page\Asset;
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/reset.css");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/style.css");
    CJSCore::Init(array("jquery"));
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/scripts/zoomsl-3.0.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/scripts/script.js");?>
    <? $APPLICATION->ShowHead() ?>
    <title><? $APPLICATION->ShowTitle() ?></title>
</head>
<body>
<? $APPLICATION->ShowPanel(); ?>
<header>
    <a href="/"><p>Приветствие</p></a>
    <div class="mobile-icon">
        <div class="navigate navigate-static" id="head-navigate">
            <? $APPLICATION->IncludeComponent(
                "bitrix:menu",
                "top",
                Array(
                    "ALLOW_MULTI_SELECT" => "N",
                    "CHILD_MENU_TYPE" => "left",
                    "DELAY" => "N",
                    "MAX_LEVEL" => "1",
                    "MENU_CACHE_GET_VARS" => array(""),
                    "MENU_CACHE_TIME" => "3600",
                    "MENU_CACHE_TYPE" => "N",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "ROOT_MENU_TYPE" => "top",
                    "USE_EXT" => "N"
                )
            ); ?>
        </div>
    </div>
</header>
<section class="flex-container">
    <div class="flex-block left-block">
        <h2>Левый сайд бар-меню</h2>
        <? $APPLICATION->IncludeComponent(
            "bitrix:menu",
            "tree1",
            array(
                "ALLOW_MULTI_SELECT" => "N",
                "CHILD_MENU_TYPE" => "left",
                "DELAY" => "N",
                "MAX_LEVEL" => "3",
                "MENU_CACHE_GET_VARS" => array(),
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_TYPE" => "N",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "ROOT_MENU_TYPE" => "Left-block",
                "USE_EXT" => "Y",
                "COMPONENT_TEMPLATE" => "tree1"
            ),
            false
        ); ?>
    </div>
    <div class="flex-block">
        <h1><?= $APPLICATION->ShowTitle(false); ?></h1>