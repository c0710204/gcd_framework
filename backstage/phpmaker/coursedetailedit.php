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

$coursedetail_edit = NULL; // Initialize page object first

class ccoursedetail_edit extends ccoursedetail {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{0FEB9E42-0859-45AD-84C6-C2CDD4EB41BE}";

	// Table name
	var $TableName = 'coursedetail';

	// Page object name
	var $PageObjName = 'coursedetail_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
			$this->Page_Terminate("coursedetaillist.php"); // Invalid key, return to list
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("coursedetaillist.php"); // No matching record, return to list
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
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

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
			// id

			$this->id->HrefValue = "";

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

			// courseListId
			$this->courseListId->SetDbValueDef($rsnew, $this->courseListId->CurrentValue, 0, $this->courseListId->ReadOnly);

			// courseTeacher
			$this->courseTeacher->SetDbValueDef($rsnew, $this->courseTeacher->CurrentValue, "", $this->courseTeacher->ReadOnly);

			// coursePlace
			$this->coursePlace->SetDbValueDef($rsnew, $this->coursePlace->CurrentValue, "", $this->coursePlace->ReadOnly);

			// courseTime
			$this->courseTime->SetDbValueDef($rsnew, $this->courseTime->CurrentValue, "", $this->courseTime->ReadOnly);

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
if (!isset($coursedetail_edit)) $coursedetail_edit = new ccoursedetail_edit();

// Page init
$coursedetail_edit->Page_Init();

// Page main
$coursedetail_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var coursedetail_edit = new ew_Page("coursedetail_edit");
coursedetail_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = coursedetail_edit.PageID; // For backward compatibility

// Form object
var fcoursedetailedit = new ew_Form("fcoursedetailedit");

// Validate form
fcoursedetailedit.Validate = function(fobj) {
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
fcoursedetailedit.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcoursedetailedit.ValidateRequired = true;
<?php } else { ?>
fcoursedetailedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $coursedetail->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $coursedetail->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $coursedetail_edit->ShowPageHeader(); ?>
<?php
$coursedetail_edit->ShowMessage();
?>
<form name="fcoursedetailedit" id="fcoursedetailedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<p>
<input type="hidden" name="t" value="coursedetail">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_coursedetailedit" class="ewTable">
<?php if ($coursedetail->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $coursedetail->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_coursedetail_id"><?php echo $coursedetail->id->FldCaption() ?></span></td>
		<td<?php echo $coursedetail->id->CellAttributes() ?>><span id="el_coursedetail_id">
<span<?php echo $coursedetail->id->ViewAttributes() ?>>
<?php echo $coursedetail->id->EditValue ?></span>
<input type="hidden" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($coursedetail->id->CurrentValue) ?>">
</span><?php echo $coursedetail->id->CustomMsg ?></td>
	</tr>
<?php } ?>
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
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>">
</form>
<script type="text/javascript">
fcoursedetailedit.Init();
</script>
<?php
$coursedetail_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$coursedetail_edit->Page_Terminate();
?>
