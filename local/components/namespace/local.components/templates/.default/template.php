<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?
\Bitrix\Main\UI\Extension::load("ui.vue");
\Bitrix\Main\UI\Extension::load("ui.vue.vuex");
\Bitrix\Main\UI\Extension::load("ui.vue3");
?>

<div id="deals">
    <local.component></local.component>
</div>

