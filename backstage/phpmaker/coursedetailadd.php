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

$coursedetail_add = NULL; // Initialize page object first

class ccoursedetail_add extends ccoursedetail {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{0FEB9E42-0859-45AD-84C6-C2CDD4EB41BE}";

	// Table name
	var $TableName = 'coursedetail';

	// Page object name
	var $PageObjName = 'coursedetail_add';

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

		// Table object (coursedetail)
		if (!isset($GLOBALS["coursedetail"])) {
			$GLOBALS["coursedetail"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["coursedetail"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'coursedetail', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"];

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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("coursedetaillist.php"); // No matching record, return to list
				}
				break;
			case "A": // ' Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "coursedetailview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
		$index = $objForm->Index; // Save form index
		$objForm->Index = -1;
		$confirmPage = (strval($objForm->GetValue("a_confirm")) <> "");
		$objForm->Index = $index; // Restore form index
	}

	// Load default values
	function LoadDefaultValues() {
		$this->courseListId->CurrentValue = NULL;
		$this->courseListId->OldValue = $this->courseListId->CurrentValue;
		$this->courseTeacher->CurrentValue = NULL;
		$this->courseTeacher->OldValue = $this->courseTeacher->CurrentValue;
		$this->coursePlace->CurrentValue = NULL;
		$this->coursePlace->OldValue = $this->coursePlace->CurrentValue;
		$this->courseTime->CurrentValue = NULL;
		$this->courseTime->OldValue = $this->courseTime->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->courseListId->FldIsDetailKey) {
			$this->courseListId->setFormValue($objForm->GetValue("x_courseListId"));
		}
		if (!$this->courseTeacher->FldIsDetailKey) {
			$this->courseTeacher->setFormValue($objForm->GetValue("x_courseTeacher"));
		}
		if (!$this->coursePlace->FldIsDetailKey) {
			$this->coursePlace->setFormValue($objForm->GetValue("x_coursePlace"));
		}
		if (!$this->courseTime->FldIsDetailKey) {
			$this->courseTime->setFormValue($objForm->GetValue("x_courseTime"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->courseListId->CurrentValue = $this->courseListId->FormValue;
		$this->courseTeacher->CurrentValue = $this->courseTeacher->FormValue;
		$this->coursePlace->CurrentValue = $this->coursePlace->FormValue;
		$this->courseTime->CurrentValue = $this->courseTime->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// courseListId
			$this->courseListId->EditCustomAttributes = "";
			$this->courseListId->EditValue = ew_HtmlEncode($this->courseListId->CurrentValue);

			// courseTeacher
			$this->courseTeacher->EditCustomAttributes = "";
			$this->courseTeacher->EditValue = ew_HtmlEncode($this->courseTeacher->CurrentValue);

			// coursePlace
			$this->coursePlace->EditCustomAttributes = "";
			$this->coursePlace->EditValue = ew_HtmlEncode($this->coursePlace->CurrentValue);

			// courseTime
			$this->courseTime->EditCustomAttributes = "";
			$this->courseTime->EditValue = ew_HtmlEncode($this->courseTime->CurrentValue);

			// Edit refer script
			// courseListId

			$this->courseListId->HrefValue = "";

			// courseTeacher
			$this->courseTeacher->HrefValue = "";

			// coursePlace
			$this->coursePlace->HrefValue = "";

			// courseTime
			$this->courseTime->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!is_null($this->courseListId->FormValue) && $this->courseListId->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->courseListId->FldCaption());
		}
		if (!ew_CheckInteger($this->courseListId->FormValue)) {
			ew_AddMessage($gsFormError, $this->courseListId->FldErrMsg());
		}
		if (!is_null($this->courseTeacher->FormValue) && $this->courseTeacher->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->courseTeacher->FldCaption());
		}
		if (!is_null($this->coursePlace->FormValue) && $this->coursePlace->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->coursePlace->FldCaption());
		}
		if (!is_null($this->courseTime->FormValue) && $this->courseTime->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->courseTime->FldCaption());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;
		$rsnew = array();

		// courseListId
		$this->courseListId->SetDbValueDef($rsnew, $this->courseListId->CurrentValue, 0, FALSE);

		// courseTeacher
		$this->courseTeacher->SetDbValueDef($rsnew, $this->courseTeacher->CurrentValue, "", FALSE);

		// coursePlace
		$this->coursePlace->SetDbValueDef($rsnew, $this->coursePlace->CurrentValue, "", FALSE);

		// courseTime
		$this->courseTime->SetDbValueDef($rsnew, $this->courseTime->CurrentValue, "", FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $conn->Execute($this->InsertSQL($rsnew));
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->id->setDbValue($conn->Insert_ID());
			$rsnew['id'] = $this->id->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($coursedetail_add)) $coursedetail_add = new ccoursedetail_add();

// Page init
$coursedetail_add->Page_Init();

// Page main
$coursedetail_add->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var coursedetail_add = new ew_Page("coursedetail_add");
coursedetail_add.PageID = "add"; // Page ID
var EW_PAGE_ID = coursedetail_add.PageID; // For backward compatibility

// Form object
var fcoursedetailadd = new ew_Form("fcoursedetailadd");

// Validate form
fcoursedetailadd.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();	
	if (fobj.a_confirm && fobj.a_confirm.value == "F")
		return true;
	var elm, aelm;
	var rowcnt = 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // rowcnt == 0 => Inline-Add
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = "";
		elm = fobj.elements["x" + infix + "_courseListId"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($coursedetail->courseListId->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_courseListId"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($coursedetail->courseListId->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_courseTeacher"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($coursedetail->courseTeacher->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_coursePlace"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($coursedetail->coursePlace->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_courseTime"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($coursedetail->courseTime->FldCaption()) ?>");

		// Set up row object
		ew_ElementsToRow(fobj, infix);

		// Fire Form_CustomValidate event
		if (!this.Form_CustomValidate(fobj))
			return false;
	}

	// Process detail page
	if (fobj.detailpage && fobj.detailpage.value && ewForms[fobj.detailpage.value])
		return ewForms[fobj.detailpage.value].Validate(fobj);
	return true;
}

// Form_CustomValidate event
fcoursedetailadd.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcoursedetailadd.ValidateRequired = true;
<?php } else { ?>
fcoursedetailadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Add") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $coursedetail->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $coursedetail->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $coursedetail_add->ShowPageHeader(); ?>
<?php
$coursedetail_add->ShowMessage();
?>
<form name="fcoursedetailadd" id="fcoursedetailadd" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<p>
<input type="hidden" name="t" value="coursedetail">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_coursedetailadd" class="ewTable">
<?php if ($coursedetail->courseListId->Visible) { // courseListId ?>
	<tr id="r_courseListId"<?php echo $coursedetail->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_coursedetail_courseListId"><?php echo $coursedetail->courseListId->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $coursedetail->courseListId->CellAttributes() ?>><span id="el_coursedetail_courseListId">
<input type="text" name="x_courseListId" id="x_courseListId" size="30" value="<?php echo $coursedetail->courseListId->EditValue ?>"<?php echo $coursedetail->courseListId->EditAttributes() ?>>
</span><?php echo $coursedetail->courseListId->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($coursedetail->courseTeacher->Visible) { // courseTeacher ?>
	<tr id="r_courseTeacher"<?php echo $coursedetail->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_coursedetail_courseTeacher"><?php echo $coursedetail->courseTeacher->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $coursedetail->courseTeacher->CellAttributes() ?>><span id="el_coursedetail_courseTeacher">
<input type="text" name="x_courseTeacher" id="x_courseTeacher" size="30" maxlength="100" value="<?php echo $coursedetail->courseTeacher->EditValue ?>"<?php echo $coursedetail->courseTeacher->EditAttributes() ?>>
</span><?php echo $coursedetail->courseTeacher->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($coursedetail->coursePlace->Visible) { // coursePlace ?>
	<tr id="r_coursePlace"<?php echo $coursedetail->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_coursedetail_coursePlace"><?php echo $coursedetail->coursePlace->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $coursedetail->coursePlace->CellAttributes() ?>><span id="el_coursedetail_coursePlace">
<input type="text" name="x_coursePlace" id="x_coursePlace" size="30" maxlength="255" value="<?php echo $coursedetail->coursePlace->EditValue ?>"<?php echo $coursedetail->coursePlace->EditAttributes() ?>>
</span><?php echo $coursedetail->coursePlace->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($coursedetail->courseTime->Visible) { // courseTime ?>
	<tr id="r_courseTime"<?php echo $coursedetail->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_coursedetail_courseTime"><?php echo $coursedetail->courseTime->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $coursedetail->courseTime->CellAttributes() ?>><span id="el_coursedetail_courseTime">
<textarea name="x_courseTime" id="x_courseTime" cols="35" rows="4"<?php echo $coursedetail->courseTime->EditAttributes() ?>><?php echo $coursedetail->courseTime->EditValue ?></textarea>
</span><?php echo $coursedetail->courseTime->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<p>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("AddBtn")) ?>">
</form>
<script type="text/javascript">
fcoursedetailadd.Init();
</script>
<?php
$coursedetail_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$coursedetail_add->Page_Terminate();
?>
