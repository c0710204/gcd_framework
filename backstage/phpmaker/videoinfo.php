<?php

// Global variable for table object
$video = NULL;

//
// Table class for video
//
class cvideo extends cTable {
	var $id;
	var $title;
	var $source;
	var $intro;
	var $cover;
	var $publish;
	var $time;
	var $size;
	var $rate;
	var $count;
	var $typeid;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;
		$this->TableVar = 'video';
		$this->TableName = 'video';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row

		// id
		$this->id = new cField('video', 'video', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// title
		$this->title = new cField('video', 'video', 'x_title', 'title', '`title`', '`title`', 200, -1, FALSE, '`title`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['title'] = &$this->title;

		// source
		$this->source = new cField('video', 'video', 'x_source', 'source', '`source`', '`source`', 200, -1, FALSE, '`source`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['source'] = &$this->source;

		// intro
		$this->intro = new cField('video', 'video', 'x_intro', 'intro', '`intro`', '`intro`', 201, -1, FALSE, '`intro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['intro'] = &$this->intro;

		// cover
		$this->cover = new cField('video', 'video', 'x_cover', 'cover', '`cover`', '`cover`', 200, -1, FALSE, '`cover`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['cover'] = &$this->cover;

		// publish
		$this->publish = new cField('video', 'video', 'x_publish', 'publish', '`publish`', 'DATE_FORMAT(`publish`, \'%Y/%m/%d %H:%i:%s\')', 135, 5, FALSE, '`publish`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->publish->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['publish'] = &$this->publish;

		// time
		$this->time = new cField('video', 'video', 'x_time', 'time', '`time`', '`time`', 3, -1, FALSE, '`time`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->time->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['time'] = &$this->time;

		// size
		$this->size = new cField('video', 'video', 'x_size', 'size', '`size`', '`size`', 3, -1, FALSE, '`size`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->size->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['size'] = &$this->size;

		// rate
		$this->rate = new cField('video', 'video', 'x_rate', 'rate', '`rate`', '`rate`', 4, -1, FALSE, '`rate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->rate->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['rate'] = &$this->rate;

		// count
		$this->count = new cField('video', 'video', 'x_count', 'count', '`count`', '`count`', 3, -1, FALSE, '`count`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->count->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['count'] = &$this->count;

		// typeid
		$this->typeid = new cField('video', 'video', 'x_typeid', 'typeid', '`typeid`', '`typeid`', 3, -1, FALSE, '`typeid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->typeid->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['typeid'] = &$this->typeid;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`video`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlWhere() { // Where
		$sWhere = "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "";
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		return TRUE;
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(),
			$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->SqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . substr($sSql, 13);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$origFilter = $this->CurrentFilter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`video`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, strlen($names)-1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, strlen($values)-1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// UPDATE statement
	function UpdateSQL(&$rs) {
		global $conn;
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, strlen($sql)-1);
		if ($this->CurrentFilter <> "")	$sql .= " WHERE " . $this->CurrentFilter;
		return $sql;
	}

	// DELETE statement
	function DeleteSQL(&$rs) {
		$SQL = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		$SQL .= ew_QuotedName('id') . '=' . ew_QuotedValue($rs['id'], $this->id->FldDataType) . ' AND ';
		if (substr($SQL, -5) == " AND ") $SQL = substr($SQL, 0, strlen($SQL)-5);
		if ($this->CurrentFilter <> "")	$SQL .= " AND " . $this->CurrentFilter;
		return $SQL;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id` = @id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "videolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "videolist.php";
	}

	// View URL
	function GetViewUrl() {
		return $this->KeyUrl("videoview.php", $this->UrlParm());
	}

	// Add URL
	function GetAddUrl() {
		return "videoadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("videoedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("videoadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("videodelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id->CurrentValue)) {
			$sUrl .= "id=" . urlencode($this->id->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["id"]; // id

			//return $arKeys; // do not return yet, so the values will also be checked by the following code
		}

		// check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->id->setDbValue($rs->fields('id'));
		$this->title->setDbValue($rs->fields('title'));
		$this->source->setDbValue($rs->fields('source'));
		$this->intro->setDbValue($rs->fields('intro'));
		$this->cover->setDbValue($rs->fields('cover'));
		$this->publish->setDbValue($rs->fields('publish'));
		$this->time->setDbValue($rs->fields('time'));
		$this->size->setDbValue($rs->fields('size'));
		$this->rate->setDbValue($rs->fields('rate'));
		$this->count->setDbValue($rs->fields('count'));
		$this->typeid->setDbValue($rs->fields('typeid'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id
		// title
		// source
		// intro
		// cover
		// publish
		// time
		// size
		// rate
		// count
		// typeid
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// title
		$this->title->ViewValue = $this->title->CurrentValue;
		$this->title->ViewCustomAttributes = "";

		// source
		$this->source->ViewValue = $this->source->CurrentValue;
		$this->source->ViewCustomAttributes = "";

		// intro
		$this->intro->ViewValue = $this->intro->CurrentValue;
		$this->intro->ViewCustomAttributes = "";

		// cover
		$this->cover->ViewValue = $this->cover->CurrentValue;
		$this->cover->ViewCustomAttributes = "";

		// publish
		$this->publish->ViewValue = $this->publish->CurrentValue;
		$this->publish->ViewValue = ew_FormatDateTime($this->publish->ViewValue, 5);
		$this->publish->ViewCustomAttributes = "";

		// time
		$this->time->ViewValue = $this->time->CurrentValue;
		$this->time->ViewCustomAttributes = "";

		// size
		$this->size->ViewValue = $this->size->CurrentValue;
		$this->size->ViewCustomAttributes = "";

		// rate
		$this->rate->ViewValue = $this->rate->CurrentValue;
		$this->rate->ViewCustomAttributes = "";

		// count
		$this->count->ViewValue = $this->count->CurrentValue;
		$this->count->ViewCustomAttributes = "";

		// typeid
		$this->typeid->ViewValue = $this->typeid->CurrentValue;
		if (strval($this->typeid->CurrentValue) <> "") {
			$sFilterWrk = "`moduleid`" . ew_SearchString("=", $this->typeid->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `moduleid`, `typename` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `moduletype`";
		$sWhereWrk = "";
		$lookuptblfilter = "`moduleid`=`5`";
		if (strval($lookuptblfilter) <> "") {
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
		}
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->typeid->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->typeid->ViewValue = $this->typeid->CurrentValue;
			}
		} else {
			$this->typeid->ViewValue = NULL;
		}
		$this->typeid->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// title
		$this->title->LinkCustomAttributes = "";
		$this->title->HrefValue = "";
		$this->title->TooltipValue = "";

		// source
		$this->source->LinkCustomAttributes = "";
		$this->source->HrefValue = "";
		$this->source->TooltipValue = "";

		// intro
		$this->intro->LinkCustomAttributes = "";
		$this->intro->HrefValue = "";
		$this->intro->TooltipValue = "";

		// cover
		$this->cover->LinkCustomAttributes = "";
		$this->cover->HrefValue = "";
		$this->cover->TooltipValue = "";

		// publish
		$this->publish->LinkCustomAttributes = "";
		$this->publish->HrefValue = "";
		$this->publish->TooltipValue = "";

		// time
		$this->time->LinkCustomAttributes = "";
		$this->time->HrefValue = "";
		$this->time->TooltipValue = "";

		// size
		$this->size->LinkCustomAttributes = "";
		$this->size->HrefValue = "";
		$this->size->TooltipValue = "";

		// rate
		$this->rate->LinkCustomAttributes = "";
		$this->rate->HrefValue = "";
		$this->rate->TooltipValue = "";

		// count
		$this->count->LinkCustomAttributes = "";
		$this->count->HrefValue = "";
		$this->count->TooltipValue = "";

		// typeid
		$this->typeid->LinkCustomAttributes = "";
		$this->typeid->HrefValue = "";
		$this->typeid->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
	}

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;

		// Write header
		$Doc->ExportTableHeader();
		if ($Doc->Horizontal) { // Horizontal format, write header
			$Doc->BeginExportRow();
			if ($ExportPageType == "view") {
				$Doc->ExportCaption($this->id);
				$Doc->ExportCaption($this->title);
				$Doc->ExportCaption($this->source);
				$Doc->ExportCaption($this->intro);
				$Doc->ExportCaption($this->cover);
				$Doc->ExportCaption($this->publish);
				$Doc->ExportCaption($this->time);
				$Doc->ExportCaption($this->size);
				$Doc->ExportCaption($this->rate);
				$Doc->ExportCaption($this->count);
				$Doc->ExportCaption($this->typeid);
			} else {
				$Doc->ExportCaption($this->id);
				$Doc->ExportCaption($this->title);
				$Doc->ExportCaption($this->source);
				$Doc->ExportCaption($this->cover);
				$Doc->ExportCaption($this->publish);
				$Doc->ExportCaption($this->time);
				$Doc->ExportCaption($this->size);
				$Doc->ExportCaption($this->rate);
				$Doc->ExportCaption($this->count);
				$Doc->ExportCaption($this->typeid);
			}
			$Doc->EndExportRow();
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
				if ($ExportPageType == "view") {
					$Doc->ExportField($this->id);
					$Doc->ExportField($this->title);
					$Doc->ExportField($this->source);
					$Doc->ExportField($this->intro);
					$Doc->ExportField($this->cover);
					$Doc->ExportField($this->publish);
					$Doc->ExportField($this->time);
					$Doc->ExportField($this->size);
					$Doc->ExportField($this->rate);
					$Doc->ExportField($this->count);
					$Doc->ExportField($this->typeid);
				} else {
					$Doc->ExportField($this->id);
					$Doc->ExportField($this->title);
					$Doc->ExportField($this->source);
					$Doc->ExportField($this->cover);
					$Doc->ExportField($this->publish);
					$Doc->ExportField($this->time);
					$Doc->ExportField($this->size);
					$Doc->ExportField($this->rate);
					$Doc->ExportField($this->count);
					$Doc->ExportField($this->typeid);
				}
				$Doc->EndExportRow();
			}
			$Recordset->MoveNext();
		}
		$Doc->ExportTableFooter();
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
