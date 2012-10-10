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

$course_edit = NULL; // Initialize page object first

class ccourse_edit extends ccourse {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{0FEB9E42-0859-45AD-84C6-C2CDD4EB41BE}";

	// Table name
	var $TableName = 'course';

	// Page object name
	var $PageObjName = 'course_edit';

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

		// Table object (course)
		if (!isset($GLOBALS["course"])) {
			$GLOBALS["course"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["course"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'course', TRUE);

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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["id"] <> "")
			$this->id->setQueryStringValue($_GET["id"]);
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id->CurrentValue == "")
			$this->Page_Terminate("courselist.php"); // Invalid key, return to list
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("courselist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
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

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->courseName->FldIsDetailKey) {
			$this->courseName->setFormValue($objForm->GetValue("x_courseName"));
		}
		if (!$this->courseEngName->FldIsDetailKey) {
			$this->courseEngName->setFormValue($objForm->GetValue("x_courseEngName"));
		}
		if (!$this->courseCode->FldIsDetailKey) {
			$this->courseCode->setFormValue($objForm->GetValue("x_courseCode"));
		}
		if (!$this->courseInfo->FldIsDetailKey) {
			$this->courseInfo->setFormValue($objForm->GetValue("x_courseInfo"));
		}
		if (!$this->courseXs->FldIsDetailKey) {
			$this->courseXs->setFormValue($objForm->GetValue("x_courseXs"));
		}
		if (!$this->courseXf->FldIsDetailKey) {
			$this->courseXf->setFormValue($objForm->GetValue("x_courseXf"));
		}
		if (!$this->courseXz->FldIsDetailKey) {
			$this->courseXz->setFormValue($objForm->GetValue("x_courseXz"));
		}
		if (!$this->courseLb->FldIsDetailKey) {
			$this->courseLb->setFormValue($objForm->GetValue("x_courseLb"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->courseName->CurrentValue = $this->courseName->FormValue;
		$this->courseEngName->CurrentValue = $this->courseEngName->FormValue;
		$this->courseCode->CurrentValue = $this->courseCode->FormValue;
		$this->courseInfo->CurrentValue = $this->courseInfo->FormValue;
		$this->courseXs->CurrentValue = $this->courseXs->FormValue;
		$this->courseXf->CurrentValue = $this->courseXf->FormValue;
		$this->courseXz->CurrentValue = $this->courseXz->FormValue;
		$this->courseLb->CurrentValue = $this->courseLb->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// courseName
			$this->courseName->EditCustomAttributes = "";
			$this->courseName->EditValue = ew_HtmlEncode($this->courseName->CurrentValue);

			// courseEngName
			$this->courseEngName->EditCustomAttributes = "";
			$this->courseEngName->EditValue = ew_HtmlEncode($this->courseEngName->CurrentValue);

			// courseCode
			$this->courseCode->EditCustomAttributes = "";
			$this->courseCode->EditValue = ew_HtmlEncode($this->courseCode->CurrentValue);

			// courseInfo
			$this->courseInfo->EditCustomAttributes = "";
			$this->courseInfo->EditValue = ew_HtmlEncode($this->courseInfo->CurrentValue);

			// courseXs
			$this->courseXs->EditCustomAttributes = "";
			$this->courseXs->EditValue = ew_HtmlEncode($this->courseXs->CurrentValue);
			if (strval($this->courseXs->EditValue) <> "") $this->courseXs->EditValue = ew_FormatNumber($this->courseXs->EditValue, -2, -1, -2, 0);

			// courseXf
			$this->courseXf->EditCustomAttributes = "";
			$this->courseXf->EditValue = ew_HtmlEncode($this->courseXf->CurrentValue);
			if (strval($this->courseXf->EditValue) <> "") $this->courseXf->EditValue = ew_FormatNumber($this->courseXf->EditValue, -2, -1, -2, 0);

			// courseXz
			$this->courseXz->EditCustomAttributes = "";
			$this->courseXz->EditValue = ew_HtmlEncode($this->courseXz->CurrentValue);

			// courseLb
			$this->courseLb->EditCustomAttributes = "";
			$this->courseLb->EditValue = ew_HtmlEncode($this->courseLb->CurrentValue);

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// courseName
			$this->courseName->HrefValue = "";

			// courseEngName
			$this->courseEngName->HrefValue = "";

			// courseCode
			$this->courseCode->HrefValue = "";

			// courseInfo
			$this->courseInfo->HrefValue = "";

			// courseXs
			$this->courseXs->HrefValue = "";

			// courseXf
			$this->courseXf->HrefValue = "";

			// courseXz
			$this->courseXz->HrefValue = "";

			// courseLb
			$this->courseLb->HrefValue = "";
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
		if (!is_null($this->courseEngName->FormValue) && $this->courseEngName->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->courseEngName->FldCaption());
		}
		if (!is_null($this->courseXs->FormValue) && $this->courseXs->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->courseXs->FldCaption());
		}
		if (!ew_CheckNumber($this->courseXs->FormValue)) {
			ew_AddMessage($gsFormError, $this->courseXs->FldErrMsg());
		}
		if (!is_null($this->courseXf->FormValue) && $this->courseXf->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->courseXf->FldCaption());
		}
		if (!ew_CheckNumber($this->courseXf->FormValue)) {
			ew_AddMessage($gsFormError, $this->courseXf->FldErrMsg());
		}
		if (!is_null($this->courseXz->FormValue) && $this->courseXz->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->courseXz->FldCaption());
		}
		if (!is_null($this->courseLb->FormValue) && $this->courseLb->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->courseLb->FldCaption());
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

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$rsnew = array();

			// courseName
			$this->courseName->SetDbValueDef($rsnew, $this->courseName->CurrentValue, NULL, $this->courseName->ReadOnly);

			// courseEngName
			$this->courseEngName->SetDbValueDef($rsnew, $this->courseEngName->CurrentValue, "", $this->courseEngName->ReadOnly);

			// courseCode
			$this->courseCode->SetDbValueDef($rsnew, $this->courseCode->CurrentValue, NULL, $this->courseCode->ReadOnly);

			// courseInfo
			$this->courseInfo->SetDbValueDef($rsnew, $this->courseInfo->CurrentValue, NULL, $this->courseInfo->ReadOnly);

			// courseXs
			$this->courseXs->SetDbValueDef($rsnew, $this->courseXs->CurrentValue, 0, $this->courseXs->ReadOnly);

			// courseXf
			$this->courseXf->SetDbValueDef($rsnew, $this->courseXf->CurrentValue, 0, $this->courseXf->ReadOnly);

			// courseXz
			$this->courseXz->SetDbValueDef($rsnew, $this->courseXz->CurrentValue, "", $this->courseXz->ReadOnly);

			// courseLb
			$this->courseLb->SetDbValueDef($rsnew, $this->courseLb->CurrentValue, "", $this->courseLb->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $conn->Execute($this->UpdateSQL($rsnew));
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
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
if (!isset($course_edit)) $course_edit = new ccourse_edit();

// Page init
$course_edit->Page_Init();

// Page main
$course_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var course_edit = new ew_Page("course_edit");
course_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = course_edit.PageID; // For backward compatibility

// Form object
var fcourseedit = new ew_Form("fcourseedit");

// Validate form
fcourseedit.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_courseEngName"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($course->courseEngName->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_courseXs"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($course->courseXs->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_courseXs"];
		if (elm && !ew_CheckNumber(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($course->courseXs->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_courseXf"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($course->courseXf->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_courseXf"];
		if (elm && !ew_CheckNumber(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($course->courseXf->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_courseXz"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($course->courseXz->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_courseLb"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($course->courseLb->FldCaption()) ?>");

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
fcourseedit.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcourseedit.ValidateRequired = true;
<?php } else { ?>
fcourseedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $course->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $course->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $course_edit->ShowPageHeader(); ?>
<?php
$course_edit->ShowMessage();
?>
<form name="fcourseedit" id="fcourseedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<p>
<input type="hidden" name="t" value="course">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_courseedit" class="ewTable">
<?php if ($course->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $course->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_course_id"><?php echo $course->id->FldCaption() ?></span></td>
		<td<?php echo $course->id->CellAttributes() ?>><span id="el_course_id">
<span<?php echo $course->id->ViewAttributes() ?>>
<?php echo $course->id->EditValue ?></span>
<input type="hidden" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($course->id->CurrentValue) ?>">
</span><?php echo $course->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($course->courseName->Visible) { // courseName ?>
	<tr id="r_courseName"<?php echo $course->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_course_courseName"><?php echo $course->courseName->FldCaption() ?></span></td>
		<td<?php echo $course->courseName->CellAttributes() ?>><span id="el_course_courseName">
<input type="text" name="x_courseName" id="x_courseName" size="30" maxlength="100" value="<?php echo $course->courseName->EditValue ?>"<?php echo $course->courseName->EditAttributes() ?>>
</span><?php echo $course->courseName->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($course->courseEngName->Visible) { // courseEngName ?>
	<tr id="r_courseEngName"<?php echo $course->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_course_courseEngName"><?php echo $course->courseEngName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $course->courseEngName->CellAttributes() ?>><span id="el_course_courseEngName">
<input type="text" name="x_courseEngName" id="x_courseEngName" size="30" maxlength="250" value="<?php echo $course->courseEngName->EditValue ?>"<?php echo $course->courseEngName->EditAttributes() ?>>
</span><?php echo $course->courseEngName->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($course->courseCode->Visible) { // courseCode ?>
	<tr id="r_courseCode"<?php echo $course->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_course_courseCode"><?php echo $course->courseCode->FldCaption() ?></span></td>
		<td<?php echo $course->courseCode->CellAttributes() ?>><span id="el_course_courseCode">
<input type="text" name="x_courseCode" id="x_courseCode" size="30" maxlength="10" value="<?php echo $course->courseCode->EditValue ?>"<?php echo $course->courseCode->EditAttributes() ?>>
</span><?php echo $course->courseCode->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($course->courseInfo->Visible) { // courseInfo ?>
	<tr id="r_courseInfo"<?php echo $course->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_course_courseInfo"><?php echo $course->courseInfo->FldCaption() ?></span></td>
		<td<?php echo $course->courseInfo->CellAttributes() ?>><span id="el_course_courseInfo">
<textarea name="x_courseInfo" id="x_courseInfo" cols="35" rows="4"<?php echo $course->courseInfo->EditAttributes() ?>><?php echo $course->courseInfo->EditValue ?></textarea>
</span><?php echo $course->courseInfo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($course->courseXs->Visible) { // courseXs ?>
	<tr id="r_courseXs"<?php echo $course->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_course_courseXs"><?php echo $course->courseXs->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $course->courseXs->CellAttributes() ?>><span id="el_course_courseXs">
<input type="text" name="x_courseXs" id="x_courseXs" size="30" value="<?php echo $course->courseXs->EditValue ?>"<?php echo $course->courseXs->EditAttributes() ?>>
</span><?php echo $course->courseXs->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($course->courseXf->Visible) { // courseXf ?>
	<tr id="r_courseXf"<?php echo $course->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_course_courseXf"><?php echo $course->courseXf->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $course->courseXf->CellAttributes() ?>><span id="el_course_courseXf">
<input type="text" name="x_courseXf" id="x_courseXf" size="30" value="<?php echo $course->courseXf->EditValue ?>"<?php echo $course->courseXf->EditAttributes() ?>>
</span><?php echo $course->courseXf->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($course->courseXz->Visible) { // courseXz ?>
	<tr id="r_courseXz"<?php echo $course->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_course_courseXz"><?php echo $course->courseXz->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $course->courseXz->CellAttributes() ?>><span id="el_course_courseXz">
<input type="text" name="x_courseXz" id="x_courseXz" size="30" maxlength="10" value="<?php echo $course->courseXz->EditValue ?>"<?php echo $course->courseXz->EditAttributes() ?>>
</span><?php echo $course->courseXz->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($course->courseLb->Visible) { // courseLb ?>
	<tr id="r_courseLb"<?php echo $course->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_course_courseLb"><?php echo $course->courseLb->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $course->courseLb->CellAttributes() ?>><span id="el_course_courseLb">
<input type="text" name="x_courseLb" id="x_courseLb" size="30" maxlength="30" value="<?php echo $course->courseLb->EditValue ?>"<?php echo $course->courseLb->EditAttributes() ?>>
</span><?php echo $course->courseLb->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<p>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>">
</form>
<script type="text/javascript">
fcourseedit.Init();
</script>
<?php
$course_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$course_edit->Page_Terminate();
?>
