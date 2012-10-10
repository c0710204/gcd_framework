<?php

// Global variable for table object
$classtime = NULL;

//
// Table class for classtime
//
class cclasstime extends cTable {
	var $id;
	var $classroomId;
	var $date;
	var $courseId1;
	var $courseId2;
	var $courseId3;
	var $courseId4;
	var $courseId5;
	var $courseId6;
	var $courseId7;
	var $courseId8;
	var $courseId9;
	var $courseId10;
	var $courseId11;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;
		$this->TableVar = 'classtime';
		$this->TableName = 'classtime';
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
		$this->id = new cField('classtime', 'classtime', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// classroomId
		$this->classroomId = new cField('classtime', 'classtime', 'x_classroomId', 'classroomId', '`classroomId`', '`classroomId`', 3, -1, FALSE, '`classroomId`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->classroomId->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['classroomId'] = &$this->classroomId;

		// date
		$this->date = new cField('classtime', 'classtime', 'x_date', 'date', '`date`', 'DATE_FORMAT(`date`, \'%Y/%m/%d %H:%i:%s\')', 133, 5, FALSE, '`date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->date->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['date'] = &$this->date;

		// courseId1
		$this->courseId1 = new cField('classtime', 'classtime', 'x_courseId1', 'courseId1', '`courseId1`', '`courseId1`', 200, -1, FALSE, '`courseId1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['courseId1'] = &$this->courseId1;

		// courseId2
		$this->courseId2 = new cField('classtime', 'classtime', 'x_courseId2', 'courseId2', '`courseId2`', '`courseId2`', 200, -1, FALSE, '`courseId2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['courseId2'] = &$this->courseId2;

		// courseId3
		$this->courseId3 = new cField('classtime', 'classtime', 'x_courseId3', 'courseId3', '`courseId3`', '`courseId3`', 200, -1, FALSE, '`courseId3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['courseId3'] = &$this->courseId3;

		// courseId4
		$this->courseId4 = new cField('classtime', 'classtime', 'x_courseId4', 'courseId4', '`courseId4`', '`courseId4`', 200, -1, FALSE, '`courseId4`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['courseId4'] = &$this->courseId4;

		// courseId5
		$this->courseId5 = new cField('classtime', 'classtime', 'x_courseId5', 'courseId5', '`courseId5`', '`courseId5`', 200, -1, FALSE, '`courseId5`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['courseId5'] = &$this->courseId5;

		// courseId6
		$this->courseId6 = new cField('classtime', 'classtime', 'x_courseId6', 'courseId6', '`courseId6`', '`courseId6`', 200, -1, FALSE, '`courseId6`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['courseId6'] = &$this->courseId6;

		// courseId7
		$this->courseId7 = new cField('classtime', 'classtime', 'x_courseId7', 'courseId7', '`courseId7`', '`courseId7`', 200, -1, FALSE, '`courseId7`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['courseId7'] = &$this->courseId7;

		// courseId8
		$this->courseId8 = new cField('classtime', 'classtime', 'x_courseId8', 'courseId8', '`courseId8`', '`courseId8`', 200, -1, FALSE, '`courseId8`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['courseId8'] = &$this->courseId8;

		// courseId9
		$this->courseId9 = new cField('classtime', 'classtime', 'x_courseId9', 'courseId9', '`courseId9`', '`courseId9`', 200, -1, FALSE, '`courseId9`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['courseId9'] = &$this->courseId9;

		// courseId10
		$this->courseId10 = new cField('classtime', 'classtime', 'x_courseId10', 'courseId10', '`courseId10`', '`courseId10`', 200, -1, FALSE, '`courseId10`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['courseId10'] = &$this->courseId10;

		// courseId11
		$this->courseId11 = new cField('classtime', 'classtime', 'x_courseId11', 'courseId11', '`courseId11`', '`courseId11`', 200, -1, FALSE, '`courseId11`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['courseId11'] = &$this->courseId11;
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
		return "`classtime`";
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
	var $UpdateTable = "`classtime`";

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
			return "classtimelist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "classtimelist.php";
	}

	// View URL
	function GetViewUrl() {
		return $this->KeyUrl("classtimeview.php", $this->UrlParm());
	}

	// Add URL
	function GetAddUrl() {
		return "classtimeadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("classtimeedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("classtimeadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("classtimedelete.php", $this->UrlParm());
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
		$this->classroomId->setDbValue($rs->fields('classroomId'));
		$this->date->setDbValue($rs->fields('date'));
		$this->courseId1->setDbValue($rs->fields('courseId1'));
		$this->courseId2->setDbValue($rs->fields('courseId2'));
		$this->courseId3->setDbValue($rs->fields('courseId3'));
		$this->courseId4->setDbValue($rs->fields('courseId4'));
		$this->courseId5->setDbValue($rs->fields('courseId5'));
		$this->courseId6->setDbValue($rs->fields('courseId6'));
		$this->courseId7->setDbValue($rs->fields('courseId7'));
		$this->courseId8->setDbValue($rs->fields('courseId8'));
		$this->courseId9->setDbValue($rs->fields('courseId9'));
		$this->courseId10->setDbValue($rs->fields('courseId10'));
		$this->courseId11->setDbValue($rs->fields('courseId11'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id
		// classroomId
		// date
		// courseId1
		// courseId2
		// courseId3
		// courseId4
		// courseId5
		// courseId6
		// courseId7
		// courseId8
		// courseId9
		// courseId10
		// courseId11
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// classroomId
		$this->classroomId->ViewValue = $this->classroomId->CurrentValue;
		$this->classroomId->ViewCustomAttributes = "";

		// date
		$this->date->ViewValue = $this->date->CurrentValue;
		$this->date->ViewValue = ew_FormatDateTime($this->date->ViewValue, 5);
		$this->date->ViewCustomAttributes = "";

		// courseId1
		$this->courseId1->ViewValue = $this->courseId1->CurrentValue;
		$this->courseId1->ViewCustomAttributes = "";

		// courseId2
		$this->courseId2->ViewValue = $this->courseId2->CurrentValue;
		$this->courseId2->ViewCustomAttributes = "";

		// courseId3
		$this->courseId3->ViewValue = $this->courseId3->CurrentValue;
		$this->courseId3->ViewCustomAttributes = "";

		// courseId4
		$this->courseId4->ViewValue = $this->courseId4->CurrentValue;
		$this->courseId4->ViewCustomAttributes = "";

		// courseId5
		$this->courseId5->ViewValue = $this->courseId5->CurrentValue;
		$this->courseId5->ViewCustomAttributes = "";

		// courseId6
		$this->courseId6->ViewValue = $this->courseId6->CurrentValue;
		$this->courseId6->ViewCustomAttributes = "";

		// courseId7
		$this->courseId7->ViewValue = $this->courseId7->CurrentValue;
		$this->courseId7->ViewCustomAttributes = "";

		// courseId8
		$this->courseId8->ViewValue = $this->courseId8->CurrentValue;
		$this->courseId8->ViewCustomAttributes = "";

		// courseId9
		$this->courseId9->ViewValue = $this->courseId9->CurrentValue;
		$this->courseId9->ViewCustomAttributes = "";

		// courseId10
		$this->courseId10->ViewValue = $this->courseId10->CurrentValue;
		$this->courseId10->ViewCustomAttributes = "";

		// courseId11
		$this->courseId11->ViewValue = $this->courseId11->CurrentValue;
		$this->courseId11->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// classroomId
		$this->classroomId->LinkCustomAttributes = "";
		$this->classroomId->HrefValue = "";
		$this->classroomId->TooltipValue = "";

		// date
		$this->date->LinkCustomAttributes = "";
		$this->date->HrefValue = "";
		$this->date->TooltipValue = "";

		// courseId1
		$this->courseId1->LinkCustomAttributes = "";
		$this->courseId1->HrefValue = "";
		$this->courseId1->TooltipValue = "";

		// courseId2
		$this->courseId2->LinkCustomAttributes = "";
		$this->courseId2->HrefValue = "";
		$this->courseId2->TooltipValue = "";

		// courseId3
		$this->courseId3->LinkCustomAttributes = "";
		$this->courseId3->HrefValue = "";
		$this->courseId3->TooltipValue = "";

		// courseId4
		$this->courseId4->LinkCustomAttributes = "";
		$this->courseId4->HrefValue = "";
		$this->courseId4->TooltipValue = "";

		// courseId5
		$this->courseId5->LinkCustomAttributes = "";
		$this->courseId5->HrefValue = "";
		$this->courseId5->TooltipValue = "";

		// courseId6
		$this->courseId6->LinkCustomAttributes = "";
		$this->courseId6->HrefValue = "";
		$this->courseId6->TooltipValue = "";

		// courseId7
		$this->courseId7->LinkCustomAttributes = "";
		$this->courseId7->HrefValue = "";
		$this->courseId7->TooltipValue = "";

		// courseId8
		$this->courseId8->LinkCustomAttributes = "";
		$this->courseId8->HrefValue = "";
		$this->courseId8->TooltipValue = "";

		// courseId9
		$this->courseId9->LinkCustomAttributes = "";
		$this->courseId9->HrefValue = "";
		$this->courseId9->TooltipValue = "";

		// courseId10
		$this->courseId10->LinkCustomAttributes = "";
		$this->courseId10->HrefValue = "";
		$this->courseId10->TooltipValue = "";

		// courseId11
		$this->courseId11->LinkCustomAttributes = "";
		$this->courseId11->HrefValue = "";
		$this->courseId11->TooltipValue = "";

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
				$Doc->ExportCaption($this->classroomId);
				$Doc->ExportCaption($this->date);
				$Doc->ExportCaption($this->courseId1);
				$Doc->ExportCaption($this->courseId2);
				$Doc->ExportCaption($this->courseId3);
				$Doc->ExportCaption($this->courseId4);
				$Doc->ExportCaption($this->courseId5);
				$Doc->ExportCaption($this->courseId6);
				$Doc->ExportCaption($this->courseId7);
				$Doc->ExportCaption($this->courseId8);
				$Doc->ExportCaption($this->courseId9);
				$Doc->ExportCaption($this->courseId10);
				$Doc->ExportCaption($this->courseId11);
			} else {
				$Doc->ExportCaption($this->id);
				$Doc->ExportCaption($this->classroomId);
				$Doc->ExportCaption($this->date);
				$Doc->ExportCaption($this->courseId1);
				$Doc->ExportCaption($this->courseId2);
				$Doc->ExportCaption($this->courseId3);
				$Doc->ExportCaption($this->courseId4);
				$Doc->ExportCaption($this->courseId5);
				$Doc->ExportCaption($this->courseId6);
				$Doc->ExportCaption($this->courseId7);
				$Doc->ExportCaption($this->courseId8);
				$Doc->ExportCaption($this->courseId9);
				$Doc->ExportCaption($this->courseId10);
				$Doc->ExportCaption($this->courseId11);
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
					$Doc->ExportField($this->classroomId);
					$Doc->ExportField($this->date);
					$Doc->ExportField($this->courseId1);
					$Doc->ExportField($this->courseId2);
					$Doc->ExportField($this->courseId3);
					$Doc->ExportField($this->courseId4);
					$Doc->ExportField($this->courseId5);
					$Doc->ExportField($this->courseId6);
					$Doc->ExportField($this->courseId7);
					$Doc->ExportField($this->courseId8);
					$Doc->ExportField($this->courseId9);
					$Doc->ExportField($this->courseId10);
					$Doc->ExportField($this->courseId11);
				} else {
					$Doc->ExportField($this->id);
					$Doc->ExportField($this->classroomId);
					$Doc->ExportField($this->date);
					$Doc->ExportField($this->courseId1);
					$Doc->ExportField($this->courseId2);
					$Doc->ExportField($this->courseId3);
					$Doc->ExportField($this->courseId4);
					$Doc->ExportField($this->courseId5);
					$Doc->ExportField($this->courseId6);
					$Doc->ExportField($this->courseId7);
					$Doc->ExportField($this->courseId8);
					$Doc->ExportField($this->courseId9);
					$Doc->ExportField($this->courseId10);
					$Doc->ExportField($this->courseId11);
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
