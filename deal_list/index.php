<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');

global $APPLICATION;
$APPLICATION->SetTitle(GetMessage('Список сделок'));

$APPLICATION->IncludeComponent(
    "namespace:local.components",
    "",
    [],
    false
);

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');