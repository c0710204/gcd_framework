<?php

// Menu
define("EW_MENUBAR_CLASSNAME", "ewMenuBarVertical", TRUE);
define("EW_MENUBAR_ITEM_CLASSNAME", "", TRUE);
define("EW_MENUBAR_ITEM_LABEL_CLASSNAME", "", TRUE);
define("EW_MENU_CLASSNAME", "ewMenuBarVertical", TRUE);
define("EW_MENU_ITEM_CLASSNAME", "", TRUE);
define("EW_MENU_ITEM_LABEL_CLASSNAME", "", TRUE);
?>
<?php

// Menu Rendering event
function Menu_Rendering(&$Menu) {

	// Change menu items here
}

// MenuItem Adding event
function MenuItem_Adding(&$Item) {

	//var_dump($Item);
	// Return FALSE if menu item not allowed

	return TRUE;
}
?>
<!-- Begin Main Menu -->
<div class="phpmaker">
<?php

// Generate all menu items
$RootMenu = new cMenu("RootMenu");
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(1, $Language->MenuPhrase("1", "MenuText"), "activitylist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(2, $Language->MenuPhrase("2", "MenuText"), "activitytimelist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(3, $Language->MenuPhrase("3", "MenuText"), "buildinglist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(4, $Language->MenuPhrase("4", "MenuText"), "classroomlist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(5, $Language->MenuPhrase("5", "MenuText"), "classtimelist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(6, $Language->MenuPhrase("6", "MenuText"), "collegelist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(7, $Language->MenuPhrase("7", "MenuText"), "courselist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(8, $Language->MenuPhrase("8", "MenuText"), "coursedetaillist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(9, $Language->MenuPhrase("9", "MenuText"), "courselistlist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(10, $Language->MenuPhrase("10", "MenuText"), "introlist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(11, $Language->MenuPhrase("11", "MenuText"), "majorlist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(12, $Language->MenuPhrase("12", "MenuText"), "memberlist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(13, $Language->MenuPhrase("13", "MenuText"), "modulelistlist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(14, $Language->MenuPhrase("14", "MenuText"), "moduleupdatelist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(15, $Language->MenuPhrase("15", "MenuText"), "yellowpagelist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(19, $Language->MenuPhrase("19", "MenuText"), "devicetokenlist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(20, $Language->MenuPhrase("20", "MenuText"), "moduletypelist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(21, $Language->MenuPhrase("21", "MenuText"), "albumlist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(22, $Language->MenuPhrase("22", "MenuText"), "videolist.php", -1, "", TRUE, FALSE);
$RootMenu->Render();
?>
</div>
<!-- End Main Menu -->
