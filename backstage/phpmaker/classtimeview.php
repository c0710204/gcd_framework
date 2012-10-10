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

$classtime_view = NULL; // Initialize page object first

class cclasstime_view extends cclasstime {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{0FEB9E42-0859-45AD-84C6-C2CDD4EB41BE}";

	// Table name
	var $TableName = 'classtime';

	// Page object name
	var $PageObjName = 'classtime_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["id"] <> "") {
			$this->RecKey["id"] = $_GET["id"];
			$KeyUrl .= "&id=" . urlencode($this->RecKey["id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'classtime', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";
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
	var $ExportOptions; // Export options
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["id"] <> "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->RecKey["id"] = $this->id->QueryStringValue;
			} else {
				$sReturnUrl = "classtimelist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "classtimelist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "classtimelist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();

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
if (!isset($classtime_view)) $classtime_view = new cclasstime_view();

// Page init
$classtime_view->Page_Init();

// Page main
$classtime_view->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var classtime_view = new ew_Page("classtime_view");
classtime_view.PageID = "view"; // Page ID
var EW_PAGE_ID = classtime_view.PageID; // For backward compatibility

// Form object
var fclasstimeview = new ew_Form("fclasstimeview");

// Form_CustomValidate event
fclasstimeview.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fclasstimeview.ValidateRequired = true;
<?php } else { ?>
fclasstimeview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("View") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $classtime->TableCaption() ?>&nbsp;&nbsp;</span><?php $classtime_view->ExportOptions->Render("body"); ?>
</p>
<p class="phpmaker">
<a href="<?php echo $classtime_view->ListUrl ?>"><?php echo $Language->Phrase("BackToList") ?></a>&nbsp;
<?php if ($classtime_view->AddUrl <> "") { ?>
<a href="<?php echo $classtime_view->AddUrl ?>"><?php echo $Language->Phrase("ViewPageAddLink") ?></a>&nbsp;
<?php } ?>
<?php if ($classtime_view->EditUrl <> "") { ?>
<a href="<?php echo $classtime_view->EditUrl ?>"><?php echo $Language->Phrase("ViewPageEditLink") ?></a>&nbsp;
<?php } ?>
<?php if ($classtime_view->CopyUrl <> "") { ?>
<a href="<?php echo $classtime_view->CopyUrl ?>"><?php echo $Language->Phrase("ViewPageCopyLink") ?></a>&nbsp;
<?php } ?>
<?php if ($classtime_view->DeleteUrl <> "") { ?>
<a href="<?php echo $classtime_view->DeleteUrl ?>"><?php echo $Language->Phrase("ViewPageDeleteLink") ?></a>&nbsp;
<?php } ?>
</p>
<?php $classtime_view->ShowPageHeader(); ?>
<?php
$classtime_view->ShowMessage();
?>
<p>
<form name="fclasstimeview" id="fclasstimeview" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="classtime">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_classtimeview" class="ewTable">
<?php if ($classtime->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_id"><?php echo $classtime->id->FldCaption() ?></span></td>
		<td<?php echo $classtime->id->CellAttributes() ?>><span id="el_classtime_id">
<span<?php echo $classtime->id->ViewAttributes() ?>>
<?php echo $classtime->id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($classtime->classroomId->Visible) { // classroomId ?>
	<tr id="r_classroomId"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_classroomId"><?php echo $classtime->classroomId->FldCaption() ?></span></td>
		<td<?php echo $classtime->classroomId->CellAttributes() ?>><span id="el_classtime_classroomId">
<span<?php echo $classtime->classroomId->ViewAttributes() ?>>
<?php echo $classtime->classroomId->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($classtime->date->Visible) { // date ?>
	<tr id="r_date"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_date"><?php echo $classtime->date->FldCaption() ?></span></td>
		<td<?php echo $classtime->date->CellAttributes() ?>><span id="el_classtime_date">
<span<?php echo $classtime->date->ViewAttributes() ?>>
<?php echo $classtime->date->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($classtime->courseId1->Visible) { // courseId1 ?>
	<tr id="r_courseId1"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_courseId1"><?php echo $classtime->courseId1->FldCaption() ?></span></td>
		<td<?php echo $classtime->courseId1->CellAttributes() ?>><span id="el_classtime_courseId1">
<span<?php echo $classtime->courseId1->ViewAttributes() ?>>
<?php echo $classtime->courseId1->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($classtime->courseId2->Visible) { // courseId2 ?>
	<tr id="r_courseId2"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_courseId2"><?php echo $classtime->courseId2->FldCaption() ?></span></td>
		<td<?php echo $classtime->courseId2->CellAttributes() ?>><span id="el_classtime_courseId2">
<span<?php echo $classtime->courseId2->ViewAttributes() ?>>
<?php echo $classtime->courseId2->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($classtime->courseId3->Visible) { // courseId3 ?>
	<tr id="r_courseId3"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_courseId3"><?php echo $classtime->courseId3->FldCaption() ?></span></td>
		<td<?php echo $classtime->courseId3->CellAttributes() ?>><span id="el_classtime_courseId3">
<span<?php echo $classtime->courseId3->ViewAttributes() ?>>
<?php echo $classtime->courseId3->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($classtime->courseId4->Visible) { // courseId4 ?>
	<tr id="r_courseId4"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_courseId4"><?php echo $classtime->courseId4->FldCaption() ?></span></td>
		<td<?php echo $classtime->courseId4->CellAttributes() ?>><span id="el_classtime_courseId4">
<span<?php echo $classtime->courseId4->ViewAttributes() ?>>
<?php echo $classtime->courseId4->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($classtime->courseId5->Visible) { // courseId5 ?>
	<tr id="r_courseId5"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_courseId5"><?php echo $classtime->courseId5->FldCaption() ?></span></td>
		<td<?php echo $classtime->courseId5->CellAttributes() ?>><span id="el_classtime_courseId5">
<span<?php echo $classtime->courseId5->ViewAttributes() ?>>
<?php echo $classtime->courseId5->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($classtime->courseId6->Visible) { // courseId6 ?>
	<tr id="r_courseId6"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_courseId6"><?php echo $classtime->courseId6->FldCaption() ?></span></td>
		<td<?php echo $classtime->courseId6->CellAttributes() ?>><span id="el_classtime_courseId6">
<span<?php echo $classtime->courseId6->ViewAttributes() ?>>
<?php echo $classtime->courseId6->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($classtime->courseId7->Visible) { // courseId7 ?>
	<tr id="r_courseId7"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_courseId7"><?php echo $classtime->courseId7->FldCaption() ?></span></td>
		<td<?php echo $classtime->courseId7->CellAttributes() ?>><span id="el_classtime_courseId7">
<span<?php echo $classtime->courseId7->ViewAttributes() ?>>
<?php echo $classtime->courseId7->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($classtime->courseId8->Visible) { // courseId8 ?>
	<tr id="r_courseId8"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_courseId8"><?php echo $classtime->courseId8->FldCaption() ?></span></td>
		<td<?php echo $classtime->courseId8->CellAttributes() ?>><span id="el_classtime_courseId8">
<span<?php echo $classtime->courseId8->ViewAttributes() ?>>
<?php echo $classtime->courseId8->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($classtime->courseId9->Visible) { // courseId9 ?>
	<tr id="r_courseId9"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_courseId9"><?php echo $classtime->courseId9->FldCaption() ?></span></td>
		<td<?php echo $classtime->courseId9->CellAttributes() ?>><span id="el_classtime_courseId9">
<span<?php echo $classtime->courseId9->ViewAttributes() ?>>
<?php echo $classtime->courseId9->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($classtime->courseId10->Visible) { // courseId10 ?>
	<tr id="r_courseId10"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_courseId10"><?php echo $classtime->courseId10->FldCaption() ?></span></td>
		<td<?php echo $classtime->courseId10->CellAttributes() ?>><span id="el_classtime_courseId10">
<span<?php echo $classtime->courseId10->ViewAttributes() ?>>
<?php echo $classtime->courseId10->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($classtime->courseId11->Visible) { // courseId11 ?>
	<tr id="r_courseId11"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_courseId11"><?php echo $classtime->courseId11->FldCaption() ?></span></td>
		<td<?php echo $classtime->courseId11->CellAttributes() ?>><span id="el_classtime_courseId11">
<span<?php echo $classtime->courseId11->ViewAttributes() ?>>
<?php echo $classtime->courseId11->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
</form>
<p>
<script type="text/javascript">
fclasstimeview.Init();
</script>
<?php
$classtime_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$classtime_view->Page_Terminate();
?>
