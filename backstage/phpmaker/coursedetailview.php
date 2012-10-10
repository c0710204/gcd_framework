<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "coursedetailinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$coursedetail_view = NULL; // Initialize page object first

class ccoursedetail_view extends ccoursedetail {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{0FEB9E42-0859-45AD-84C6-C2CDD4EB41BE}";

	// Table name
	var $TableName = 'coursedetail';

	// Page object name
	var $PageObjName = 'coursedetail_view';

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

		// Table object (coursedetail)
		if (!isset($GLOBALS["coursedetail"])) {
			$GLOBALS["coursedetail"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["coursedetail"];
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
			define("EW_TABLE_NAME", 'coursedetail', TRUE);

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
				$sReturnUrl = "coursedetaillist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "coursedetaillist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "coursedetaillist.php"; // Not page request, return to list
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
		$this->courseListId->setDbValue($rs->fields('courseListId'));
		$this->courseTeacher->setDbValue($rs->fields('courseTeacher'));
		$this->coursePlace->setDbValue($rs->fields('coursePlace'));
		$this->courseTime->setDbValue($rs->fields('courseTime'));
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
		// courseListId
		// courseTeacher
		// coursePlace
		// courseTime

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// courseListId
			$this->courseListId->ViewValue = $this->courseListId->CurrentValue;
			$this->courseListId->ViewCustomAttributes = "";

			// courseTeacher
			$this->courseTeacher->ViewValue = $this->courseTeacher->CurrentValue;
			$this->courseTeacher->ViewCustomAttributes = "";

			// coursePlace
			$this->coursePlace->ViewValue = $this->coursePlace->CurrentValue;
			$this->coursePlace->ViewCustomAttributes = "";

			// courseTime
			$this->courseTime->ViewValue = $this->courseTime->CurrentValue;
			$this->courseTime->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// courseListId
			$this->courseListId->LinkCustomAttributes = "";
			$this->courseListId->HrefValue = "";
			$this->courseListId->TooltipValue = "";

			// courseTeacher
			$this->courseTeacher->LinkCustomAttributes = "";
			$this->courseTeacher->HrefValue = "";
			$this->courseTeacher->TooltipValue = "";

			// coursePlace
			$this->coursePlace->LinkCustomAttributes = "";
			$this->coursePlace->HrefValue = "";
			$this->coursePlace->TooltipValue = "";

			// courseTime
			$this->courseTime->LinkCustomAttributes = "";
			$this->courseTime->HrefValue = "";
			$this->courseTime->TooltipValue = "";
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
if (!isset($coursedetail_view)) $coursedetail_view = new ccoursedetail_view();

// Page init
$coursedetail_view->Page_Init();

// Page main
$coursedetail_view->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var coursedetail_view = new ew_Page("coursedetail_view");
coursedetail_view.PageID = "view"; // Page ID
var EW_PAGE_ID = coursedetail_view.PageID; // For backward compatibility

// Form object
var fcoursedetailview = new ew_Form("fcoursedetailview");

// Form_CustomValidate event
fcoursedetailview.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcoursedetailview.ValidateRequired = true;
<?php } else { ?>
fcoursedetailview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("View") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $coursedetail->TableCaption() ?>&nbsp;&nbsp;</span><?php $coursedetail_view->ExportOptions->Render("body"); ?>
</p>
<p class="phpmaker">
<a href="<?php echo $coursedetail_view->ListUrl ?>"><?php echo $Language->Phrase("BackToList") ?></a>&nbsp;
<?php if ($coursedetail_view->AddUrl <> "") { ?>
<a href="<?php echo $coursedetail_view->AddUrl ?>"><?php echo $Language->Phrase("ViewPageAddLink") ?></a>&nbsp;
<?php } ?>
<?php if ($coursedetail_view->EditUrl <> "") { ?>
<a href="<?php echo $coursedetail_view->EditUrl ?>"><?php echo $Language->Phrase("ViewPageEditLink") ?></a>&nbsp;
<?php } ?>
<?php if ($coursedetail_view->CopyUrl <> "") { ?>
<a href="<?php echo $coursedetail_view->CopyUrl ?>"><?php echo $Language->Phrase("ViewPageCopyLink") ?></a>&nbsp;
<?php } ?>
<?php if ($coursedetail_view->DeleteUrl <> "") { ?>
<a href="<?php echo $coursedetail_view->DeleteUrl ?>"><?php echo $Language->Phrase("ViewPageDeleteLink") ?></a>&nbsp;
<?php } ?>
</p>
<?php $coursedetail_view->ShowPageHeader(); ?>
<?php
$coursedetail_view->ShowMessage();
?>
<p>
<form name="fcoursedetailview" id="fcoursedetailview" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="coursedetail">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_coursedetailview" class="ewTable">
<?php if ($coursedetail->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $coursedetail->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_coursedetail_id"><?php echo $coursedetail->id->FldCaption() ?></span></td>
		<td<?php echo $coursedetail->id->CellAttributes() ?>><span id="el_coursedetail_id">
<span<?php echo $coursedetail->id->ViewAttributes() ?>>
<?php echo $coursedetail->id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($coursedetail->courseListId->Visible) { // courseListId ?>
	<tr id="r_courseListId"<?php echo $coursedetail->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_coursedetail_courseListId"><?php echo $coursedetail->courseListId->FldCaption() ?></span></td>
		<td<?php echo $coursedetail->courseListId->CellAttributes() ?>><span id="el_coursedetail_courseListId">
<span<?php echo $coursedetail->courseListId->ViewAttributes() ?>>
<?php echo $coursedetail->courseListId->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($coursedetail->courseTeacher->Visible) { // courseTeacher ?>
	<tr id="r_courseTeacher"<?php echo $coursedetail->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_coursedetail_courseTeacher"><?php echo $coursedetail->courseTeacher->FldCaption() ?></span></td>
		<td<?php echo $coursedetail->courseTeacher->CellAttributes() ?>><span id="el_coursedetail_courseTeacher">
<span<?php echo $coursedetail->courseTeacher->ViewAttributes() ?>>
<?php echo $coursedetail->courseTeacher->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($coursedetail->coursePlace->Visible) { // coursePlace ?>
	<tr id="r_coursePlace"<?php echo $coursedetail->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_coursedetail_coursePlace"><?php echo $coursedetail->coursePlace->FldCaption() ?></span></td>
		<td<?php echo $coursedetail->coursePlace->CellAttributes() ?>><span id="el_coursedetail_coursePlace">
<span<?php echo $coursedetail->coursePlace->ViewAttributes() ?>>
<?php echo $coursedetail->coursePlace->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($coursedetail->courseTime->Visible) { // courseTime ?>
	<tr id="r_courseTime"<?php echo $coursedetail->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_coursedetail_courseTime"><?php echo $coursedetail->courseTime->FldCaption() ?></span></td>
		<td<?php echo $coursedetail->courseTime->CellAttributes() ?>><span id="el_coursedetail_courseTime">
<span<?php echo $coursedetail->courseTime->ViewAttributes() ?>>
<?php echo $coursedetail->courseTime->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
</form>
<p>
<script type="text/javascript">
fcoursedetailview.Init();
</script>
<?php
$coursedetail_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$coursedetail_view->Page_Terminate();
?>