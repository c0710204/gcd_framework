<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "courseinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$course_view = NULL; // Initialize page object first

class ccourse_view extends ccourse {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{0FEB9E42-0859-45AD-84C6-C2CDD4EB41BE}";

	// Table name
	var $TableName = 'course';

	// Page object name
	var $PageObjName = 'course_view';

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

		// Table object (course)
		if (!isset($GLOBALS["course"])) {
			$GLOBALS["course"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["course"];
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
			define("EW_TABLE_NAME", 'course', TRUE);

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
				$sReturnUrl = "courselist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "courselist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "courselist.php"; // Not page request, return to list
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
		$this->courseName->setDbValue($rs->fields('courseName'));
		$this->courseEngName->setDbValue($rs->fields('courseEngName'));
		$this->courseCode->setDbValue($rs->fields('courseCode'));
		$this->courseInfo->setDbValue($rs->fields('courseInfo'));
		$this->courseXs->setDbValue($rs->fields('courseXs'));
		$this->courseXf->setDbValue($rs->fields('courseXf'));
		$this->courseXz->setDbValue($rs->fields('courseXz'));
		$this->courseLb->setDbValue($rs->fields('courseLb'));
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

		// Convert decimal values if posted back
		if ($this->courseXs->FormValue == $this->courseXs->CurrentValue)
			$this->courseXs->CurrentValue = ew_StrToFloat($this->courseXs->CurrentValue);

		// Convert decimal values if posted back
		if ($this->courseXf->FormValue == $this->courseXf->CurrentValue)
			$this->courseXf->CurrentValue = ew_StrToFloat($this->courseXf->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// courseName
		// courseEngName
		// courseCode
		// courseInfo
		// courseXs
		// courseXf
		// courseXz
		// courseLb

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// courseName
			$this->courseName->ViewValue = $this->courseName->CurrentValue;
			$this->courseName->ViewCustomAttributes = "";

			// courseEngName
			$this->courseEngName->ViewValue = $this->courseEngName->CurrentValue;
			$this->courseEngName->ViewCustomAttributes = "";

			// courseCode
			$this->courseCode->ViewValue = $this->courseCode->CurrentValue;
			$this->courseCode->ViewCustomAttributes = "";

			// courseInfo
			$this->courseInfo->ViewValue = $this->courseInfo->CurrentValue;
			$this->courseInfo->ViewCustomAttributes = "";

			// courseXs
			$this->courseXs->ViewValue = $this->courseXs->CurrentValue;
			$this->courseXs->ViewCustomAttributes = "";

			// courseXf
			$this->courseXf->ViewValue = $this->courseXf->CurrentValue;
			$this->courseXf->ViewCustomAttributes = "";

			// courseXz
			$this->courseXz->ViewValue = $this->courseXz->CurrentValue;
			$this->courseXz->ViewCustomAttributes = "";

			// courseLb
			$this->courseLb->ViewValue = $this->courseLb->CurrentValue;
			$this->courseLb->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// courseName
			$this->courseName->LinkCustomAttributes = "";
			$this->courseName->HrefValue = "";
			$this->courseName->TooltipValue = "";

			// courseEngName
			$this->courseEngName->LinkCustomAttributes = "";
			$this->courseEngName->HrefValue = "";
			$this->courseEngName->TooltipValue = "";

			// courseCode
			$this->courseCode->LinkCustomAttributes = "";
			$this->courseCode->HrefValue = "";
			$this->courseCode->TooltipValue = "";

			// courseInfo
			$this->courseInfo->LinkCustomAttributes = "";
			$this->courseInfo->HrefValue = "";
			$this->courseInfo->TooltipValue = "";

			// courseXs
			$this->courseXs->LinkCustomAttributes = "";
			$this->courseXs->HrefValue = "";
			$this->courseXs->TooltipValue = "";

			// courseXf
			$this->courseXf->LinkCustomAttributes = "";
			$this->courseXf->HrefValue = "";
			$this->courseXf->TooltipValue = "";

			// courseXz
			$this->courseXz->LinkCustomAttributes = "";
			$this->courseXz->HrefValue = "";
			$this->courseXz->TooltipValue = "";

			// courseLb
			$this->courseLb->LinkCustomAttributes = "";
			$this->courseLb->HrefValue = "";
			$this->courseLb->TooltipValue = "";
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
if (!isset($course_view)) $course_view = new ccourse_view();

// Page init
$course_view->Page_Init();

// Page main
$course_view->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var course_view = new ew_Page("course_view");
course_view.PageID = "view"; // Page ID
var EW_PAGE_ID = course_view.PageID; // For backward compatibility

// Form object
var fcourseview = new ew_Form("fcourseview");

// Form_CustomValidate event
fcourseview.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcourseview.ValidateRequired = true;
<?php } else { ?>
fcourseview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("View") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $course->TableCaption() ?>&nbsp;&nbsp;</span><?php $course_view->ExportOptions->Render("body"); ?>
</p>
<p class="phpmaker">
<a href="<?php echo $course_view->ListUrl ?>"><?php echo $Language->Phrase("BackToList") ?></a>&nbsp;
<?php if ($course_view->AddUrl <> "") { ?>
<a href="<?php echo $course_view->AddUrl ?>"><?php echo $Language->Phrase("ViewPageAddLink") ?></a>&nbsp;
<?php } ?>
<?php if ($course_view->EditUrl <> "") { ?>
<a href="<?php echo $course_view->EditUrl ?>"><?php echo $Language->Phrase("ViewPageEditLink") ?></a>&nbsp;
<?php } ?>
<?php if ($course_view->CopyUrl <> "") { ?>
<a href="<?php echo $course_view->CopyUrl ?>"><?php echo $Language->Phrase("ViewPageCopyLink") ?></a>&nbsp;
<?php } ?>
<?php if ($course_view->DeleteUrl <> "") { ?>
<a href="<?php echo $course_view->DeleteUrl ?>"><?php echo $Language->Phrase("ViewPageDeleteLink") ?></a>&nbsp;
<?php } ?>
</p>
<?php $course_view->ShowPageHeader(); ?>
<?php
$course_view->ShowMessage();
?>
<p>
<form name="fcourseview" id="fcourseview" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="course">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_courseview" class="ewTable">
<?php if ($course->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $course->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_course_id"><?php echo $course->id->FldCaption() ?></span></td>
		<td<?php echo $course->id->CellAttributes() ?>><span id="el_course_id">
<span<?php echo $course->id->ViewAttributes() ?>>
<?php echo $course->id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($course->courseName->Visible) { // courseName ?>
	<tr id="r_courseName"<?php echo $course->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_course_courseName"><?php echo $course->courseName->FldCaption() ?></span></td>
		<td<?php echo $course->courseName->CellAttributes() ?>><span id="el_course_courseName">
<span<?php echo $course->courseName->ViewAttributes() ?>>
<?php echo $course->courseName->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($course->courseEngName->Visible) { // courseEngName ?>
	<tr id="r_courseEngName"<?php echo $course->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_course_courseEngName"><?php echo $course->courseEngName->FldCaption() ?></span></td>
		<td<?php echo $course->courseEngName->CellAttributes() ?>><span id="el_course_courseEngName">
<span<?php echo $course->courseEngName->ViewAttributes() ?>>
<?php echo $course->courseEngName->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($course->courseCode->Visible) { // courseCode ?>
	<tr id="r_courseCode"<?php echo $course->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_course_courseCode"><?php echo $course->courseCode->FldCaption() ?></span></td>
		<td<?php echo $course->courseCode->CellAttributes() ?>><span id="el_course_courseCode">
<span<?php echo $course->courseCode->ViewAttributes() ?>>
<?php echo $course->courseCode->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($course->courseInfo->Visible) { // courseInfo ?>
	<tr id="r_courseInfo"<?php echo $course->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_course_courseInfo"><?php echo $course->courseInfo->FldCaption() ?></span></td>
		<td<?php echo $course->courseInfo->CellAttributes() ?>><span id="el_course_courseInfo">
<span<?php echo $course->courseInfo->ViewAttributes() ?>>
<?php echo $course->courseInfo->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($course->courseXs->Visible) { // courseXs ?>
	<tr id="r_courseXs"<?php echo $course->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_course_courseXs"><?php echo $course->courseXs->FldCaption() ?></span></td>
		<td<?php echo $course->courseXs->CellAttributes() ?>><span id="el_course_courseXs">
<span<?php echo $course->courseXs->ViewAttributes() ?>>
<?php echo $course->courseXs->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($course->courseXf->Visible) { // courseXf ?>
	<tr id="r_courseXf"<?php echo $course->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_course_courseXf"><?php echo $course->courseXf->FldCaption() ?></span></td>
		<td<?php echo $course->courseXf->CellAttributes() ?>><span id="el_course_courseXf">
<span<?php echo $course->courseXf->ViewAttributes() ?>>
<?php echo $course->courseXf->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($course->courseXz->Visible) { // courseXz ?>
	<tr id="r_courseXz"<?php echo $course->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_course_courseXz"><?php echo $course->courseXz->FldCaption() ?></span></td>
		<td<?php echo $course->courseXz->CellAttributes() ?>><span id="el_course_courseXz">
<span<?php echo $course->courseXz->ViewAttributes() ?>>
<?php echo $course->courseXz->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($course->courseLb->Visible) { // courseLb ?>
	<tr id="r_courseLb"<?php echo $course->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_course_courseLb"><?php echo $course->courseLb->FldCaption() ?></span></td>
		<td<?php echo $course->courseLb->CellAttributes() ?>><span id="el_course_courseLb">
<span<?php echo $course->courseLb->ViewAttributes() ?>>
<?php echo $course->courseLb->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
</form>
<p>
<script type="text/javascript">
fcourseview.Init();
</script>
<?php
$course_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$course_view->Page_Terminate();
?>
