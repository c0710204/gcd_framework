<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "classtimeinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$classtime_delete = NULL; // Initialize page object first

class cclasstime_delete extends cclasstime {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{0FEB9E42-0859-45AD-84C6-C2CDD4EB41BE}";

	// Table name
	var $TableName = 'classtime';

	// Page object name
	var $PageObjName = 'classtime_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			$html .= "<p class=\"ewMessage\">" . $sMessage . "</p>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewWarningIcon\"></td><td class=\"ewWarningMessage\">" . $sWarningMessage . "</td></tr></table>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewSuccessIcon\"></td><td class=\"ewSuccessMessage\">" . $sSuccessMessage . "</td></tr></table>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewErrorIcon\"></td><td class=\"ewErrorMessage\">" . $sErrorMessage . "</td></tr></table>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}		
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p class=\"phpmaker\">" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Fotoer exists, display
			echo "<p class=\"phpmaker\">" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (classtime)
		if (!isset($GLOBALS["classtime"])) {
			$GLOBALS["classtime"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["classtime"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'classtime', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"];
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("classtimelist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in classtime class, classtimeinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Call Recordset Selecting event
		$this->Recordset_Selecting($this->CurrentFilter);

		// Load List page SQL
		$sSql = $this->SelectSQL();
		if ($offset > -1 && $rowcnt > -1)
			$sSql .= " LIMIT $rowcnt OFFSET $offset";

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
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

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;
		} else {
			$this->LoadRowValues($rs); // Load row values
		}
		$conn->BeginTrans();

		// Clone old rows
		$rsold = ($rs) ? $rs->GetRows() : array();
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id'];
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $conn->Execute($this->DeleteSQL($row)); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($classtime_delete)) $classtime_delete = new cclasstime_delete();

// Page init
$classtime_delete->Page_Init();

// Page main
$classtime_delete->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var classtime_delete = new ew_Page("classtime_delete");
classtime_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = classtime_delete.PageID; // For backward compatibility

// Form object
var fclasstimedelete = new ew_Form("fclasstimedelete");

// Form_CustomValidate event
fclasstimedelete.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fclasstimedelete.ValidateRequired = true;
<?php } else { ?>
fclasstimedelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($classtime_delete->Recordset = $classtime_delete->LoadRecordset())
	$classtime_deleteTotalRecs = $classtime_delete->Recordset->RecordCount(); // Get record count
if ($classtime_deleteTotalRecs <= 0) { // No record found, exit
	if ($classtime_delete->Recordset)
		$classtime_delete->Recordset->Close();
	$classtime_delete->Page_Terminate("classtimelist.php"); // Return to list
}
?>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Delete") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $classtime->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $classtime->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $classtime_delete->ShowPageHeader(); ?>
<?php
$classtime_delete->ShowMessage();
?>
<form name="fclasstimedelete" id="fclasstimedelete" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<p>
<input type="hidden" name="t" value="classtime">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($classtime_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_classtimedelete" class="ewTable ewTableSeparate">
<?php echo $classtime->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td valign="top"><span id="elh_classtime_id" class="classtime_id"><?php echo $classtime->id->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_classtime_classroomId" class="classtime_classroomId"><?php echo $classtime->classroomId->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_classtime_date" class="classtime_date"><?php echo $classtime->date->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_classtime_courseId1" class="classtime_courseId1"><?php echo $classtime->courseId1->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_classtime_courseId2" class="classtime_courseId2"><?php echo $classtime->courseId2->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_classtime_courseId3" class="classtime_courseId3"><?php echo $classtime->courseId3->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_classtime_courseId4" class="classtime_courseId4"><?php echo $classtime->courseId4->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_classtime_courseId5" class="classtime_courseId5"><?php echo $classtime->courseId5->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_classtime_courseId6" class="classtime_courseId6"><?php echo $classtime->courseId6->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_classtime_courseId7" class="classtime_courseId7"><?php echo $classtime->courseId7->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_classtime_courseId8" class="classtime_courseId8"><?php echo $classtime->courseId8->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_classtime_courseId9" class="classtime_courseId9"><?php echo $classtime->courseId9->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_classtime_courseId10" class="classtime_courseId10"><?php echo $classtime->courseId10->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_classtime_courseId11" class="classtime_courseId11"><?php echo $classtime->courseId11->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$classtime_delete->RecCnt = 0;
$i = 0;
while (!$classtime_delete->Recordset->EOF) {
	$classtime_delete->RecCnt++;
	$classtime_delete->RowCnt++;

	// Set row properties
	$classtime->ResetAttrs();
	$classtime->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$classtime_delete->LoadRowValues($classtime_delete->Recordset);

	// Render row
	$classtime_delete->RenderRow();
?>
	<tr<?php echo $classtime->RowAttributes() ?>>
		<td<?php echo $classtime->id->CellAttributes() ?>><span id="el<?php echo $classtime_delete->RowCnt ?>_classtime_id" class="classtime_id">
<span<?php echo $classtime->id->ViewAttributes() ?>>
<?php echo $classtime->id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $classtime->classroomId->CellAttributes() ?>><span id="el<?php echo $classtime_delete->RowCnt ?>_classtime_classroomId" class="classtime_classroomId">
<span<?php echo $classtime->classroomId->ViewAttributes() ?>>
<?php echo $classtime->classroomId->ListViewValue() ?></span>
</span></td>
		<td<?php echo $classtime->date->CellAttributes() ?>><span id="el<?php echo $classtime_delete->RowCnt ?>_classtime_date" class="classtime_date">
<span<?php echo $classtime->date->ViewAttributes() ?>>
<?php echo $classtime->date->ListViewValue() ?></span>
</span></td>
		<td<?php echo $classtime->courseId1->CellAttributes() ?>><span id="el<?php echo $classtime_delete->RowCnt ?>_classtime_courseId1" class="classtime_courseId1">
<span<?php echo $classtime->courseId1->ViewAttributes() ?>>
<?php echo $classtime->courseId1->ListViewValue() ?></span>
</span></td>
		<td<?php echo $classtime->courseId2->CellAttributes() ?>><span id="el<?php echo $classtime_delete->RowCnt ?>_classtime_courseId2" class="classtime_courseId2">
<span<?php echo $classtime->courseId2->ViewAttributes() ?>>
<?php echo $classtime->courseId2->ListViewValue() ?></span>
</span></td>
		<td<?php echo $classtime->courseId3->CellAttributes() ?>><span id="el<?php echo $classtime_delete->RowCnt ?>_classtime_courseId3" class="classtime_courseId3">
<span<?php echo $classtime->courseId3->ViewAttributes() ?>>
<?php echo $classtime->courseId3->ListViewValue() ?></span>
</span></td>
		<td<?php echo $classtime->courseId4->CellAttributes() ?>><span id="el<?php echo $classtime_delete->RowCnt ?>_classtime_courseId4" class="classtime_courseId4">
<span<?php echo $classtime->courseId4->ViewAttributes() ?>>
<?php echo $classtime->courseId4->ListViewValue() ?></span>
</span></td>
		<td<?php echo $classtime->courseId5->CellAttributes() ?>><span id="el<?php echo $classtime_delete->RowCnt ?>_classtime_courseId5" class="classtime_courseId5">
<span<?php echo $classtime->courseId5->ViewAttributes() ?>>
<?php echo $classtime->courseId5->ListViewValue() ?></span>
</span></td>
		<td<?php echo $classtime->courseId6->CellAttributes() ?>><span id="el<?php echo $classtime_delete->RowCnt ?>_classtime_courseId6" class="classtime_courseId6">
<span<?php echo $classtime->courseId6->ViewAttributes() ?>>
<?php echo $classtime->courseId6->ListViewValue() ?></span>
</span></td>
		<td<?php echo $classtime->courseId7->CellAttributes() ?>><span id="el<?php echo $classtime_delete->RowCnt ?>_classtime_courseId7" class="classtime_courseId7">
<span<?php echo $classtime->courseId7->ViewAttributes() ?>>
<?php echo $classtime->courseId7->ListViewValue() ?></span>
</span></td>
		<td<?php echo $classtime->courseId8->CellAttributes() ?>><span id="el<?php echo $classtime_delete->RowCnt ?>_classtime_courseId8" class="classtime_courseId8">
<span<?php echo $classtime->courseId8->ViewAttributes() ?>>
<?php echo $classtime->courseId8->ListViewValue() ?></span>
</span></td>
		<td<?php echo $classtime->courseId9->CellAttributes() ?>><span id="el<?php echo $classtime_delete->RowCnt ?>_classtime_courseId9" class="classtime_courseId9">
<span<?php echo $classtime->courseId9->ViewAttributes() ?>>
<?php echo $classtime->courseId9->ListViewValue() ?></span>
</span></td>
		<td<?php echo $classtime->courseId10->CellAttributes() ?>><span id="el<?php echo $classtime_delete->RowCnt ?>_classtime_courseId10" class="classtime_courseId10">
<span<?php echo $classtime->courseId10->ViewAttributes() ?>>
<?php echo $classtime->courseId10->ListViewValue() ?></span>
</span></td>
		<td<?php echo $classtime->courseId11->CellAttributes() ?>><span id="el<?php echo $classtime_delete->RowCnt ?>_classtime_courseId11" class="classtime_courseId11">
<span<?php echo $classtime->courseId11->ViewAttributes() ?>>
<?php echo $classtime->courseId11->ListViewValue() ?></span>
</span></td>
	</tr>
<?php
	$classtime_delete->Recordset->MoveNext();
}
$classtime_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<p>
<input type="submit" name="Action" value="<?php echo ew_BtnCaption($Language->Phrase("DeleteBtn")) ?>">
</form>
<script type="text/javascript">
fclasstimedelete.Init();
</script>
<?php
$classtime_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$classtime_delete->Page_Terminate();
?>
