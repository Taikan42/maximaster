<?if (!defined('B_PROLOG_INCLUDED') || (B_PROLOG_INCLUDED !== true)) {
	die();
}

if (!$arResult["NavShowAlways"]) {
	if (
		(0 == $arResult["NavRecordCount"])
		||
		((1 == $arResult["NavPageCount"]) && (false == $arResult["NavShowAll"]))
	) {
		return;
	}
}

$navQueryString      = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$navQueryStringFull  = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");

?>
<div class="pagenavigation">
	<div>
		<?php echo $arResult["NavTitle"] ?>
		<?php echo $arResult["NavFirstRecordShow"] ?>—<?php echo $arResult["NavLastRecordShow"] ?> из <?php echo $arResult["NavRecordCount"] ?>
	</div>

	<div class="numbers">
		<?php while ($arResult["nStartPage"] <= $arResult["nEndPage"]) { ?>
			<?php if ($arResult["nStartPage"] == $arResult["NavPageNomer"]) { ?>
				<span class="active"><?php echo $arResult["nStartPage"] ?></span>
			<?php } elseif ((1 == $arResult["nStartPage"]) && (false == $arResult["bSavePage"])) { ?>
				<a href="<?php echo $arResult["sUrlPath"] ?><?php echo $navQueryStringFull ?>"><?php echo $arResult["nStartPage"] ?></a>
			<?php } else { ?>
				<a href="<?php echo $arResult["sUrlPath"] ?>?<?php echo $navQueryString ?>PAGEN_<?php echo $arResult["NavNum"] ?>=<?php echo $arResult["nStartPage"] ?>"><?php echo $arResult["nStartPage"] ?></a>
			<?php } ?>
			<?php $arResult["nStartPage"]++ ?>
		<?php } ?>
	</div>
</div>
