<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "moduletypeinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$moduletype_edit = NULL; // Initialize page object first

class cmoduletype_edit extends cmoduletype {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{0FEB9E42-0859-45AD-84C6-C2CDD4EB41BE}";

	// Table name
	var $TableName = 'moduletype';

	// Page object name
	var $PageObjName = 'moduletype_edit';

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

		// Table object (moduletype)
		if (!isset($GLOBALS["moduletype"])) {
			$GLOBALS["moduletype"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["moduletype"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'moduletype', TRUE);

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
			$this->Page_Terminate("moduletypelist.php"); // Invalid key, return to list
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("moduletypelist.php"); // No matching record, return to list
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
		if (!$this->moduleid->FldIsDetailKey) {
			$this->moduleid->setFormValue($objForm->GetValue("x_moduleid"));
		}
		if (!$this->typename->FldIsDetailKey) {
			$this->typename->setFormValue($objForm->GetValue("x_typename"));
		}
		if (!$this->coverid->FldIsDetailKey) {
			$this->coverid->setFormValue($objForm->GetValue("x_coverid"));
		}
		if (!$this->publish->FldIsDetailKey) {
			$this->publish->setFormValue($objForm->GetValue("x_publish"));
			$this->publish->CurrentValue = ew_UnFormatDateTime($this->publish->CurrentValue, 5);
		}
		if (!$this->amount->FldIsDetailKey) {
			$this->amount->setFormValue($objForm->GetValue("x_amount"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->moduleid->CurrentValue = $this->moduleid->FormValue;
		$this->typename->CurrentValue = $this->typename->FormValue;
		$this->coverid->CurrentValue = $this->coverid->FormValue;
		$this->publish->CurrentValue = $this->publish->FormValue;
		$this->publish->CurrentValue = ew_UnFormatDateTime($this->publish->CurrentValue, 5);
		$this->amount->CurrentValue = $this->amount->FormValue;
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
		$this->moduleid->setDbValue($rs->fields('moduleid'));
		$this->typename->setDbValue($rs->fields('typename'));
		$this->coverid->setDbValue($rs->fields('coverid'));
		$this->publish->setDbValue($rs->fields('publish'));
		$this->amount->setDbValue($rs->fields('amount'));
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
		// moduleid
		// typename
		// coverid
		// publish
		// amount

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// moduleid
			$this->moduleid->ViewValue = $this->moduleid->CurrentValue;
			$this->moduleid->ViewCustomAttributes = "";

			// typename
			$this->typename->ViewValue = $this->typename->CurrentValue;
			$this->typename->ViewCustomAttributes = "";

			// coverid
			$this->coverid->ViewValue = $this->coverid->CurrentValue;
			$this->coverid->ViewCustomAttributes = "";

			// publish
			$this->publish->ViewValue = $this->publish->CurrentValue;
			$this->publish->ViewValue = ew_FormatDateTime($this->publish->ViewValue, 5);
			$this->publish->ViewCustomAttributes = "";

			// amount
			$this->amount->ViewValue = $this->amount->CurrentValue;
			$this->amount->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// moduleid
			$this->moduleid->LinkCustomAttributes = "";
			$this->moduleid->HrefValue = "";
			$this->moduleid->TooltipValue = "";

			// typename
			$this->typename->LinkCustomAttributes = "";
			$this->typename->HrefValue = "";
			$this->typename->TooltipValue = "";

			// coverid
			$this->coverid->LinkCustomAttributes = "";
			$this->coverid->HrefValue = "";
			$this->coverid->TooltipValue = "";

			// publish
			$this->publish->LinkCustomAttributes = "";
			$this->publish->HrefValue = "";
			$this->publish->TooltipValue = "";

			// amount
			$this->amount->LinkCustomAttributes = "";
			$this->amount->HrefValue = "";
			$this->amount->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// moduleid
			$this->moduleid->EditCustomAttributes = "";
			$this->moduleid->EditValue = ew_HtmlEncode($this->moduleid->CurrentValue);

			// typename
			$this->typename->EditCustomAttributes = "";
			$this->typename->EditValue = ew_HtmlEncode($this->typename->CurrentValue);

			// coverid
			$this->coverid->EditCustomAttributes = "";
			$this->coverid->EditValue = ew_HtmlEncode($this->coverid->CurrentValue);

			// publish
			$this->publish->EditCustomAttributes = "";
			$this->publish->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->publish->CurrentValue, 5));

			// amount
			$this->amount->EditCustomAttributes = "";
			$this->amount->EditValue = ew_HtmlEncode($this->amount->CurrentValue);

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// moduleid
			$this->moduleid->HrefValue = "";

			// typename
			$this->typename->HrefValue = "";

			// coverid
			$this->coverid->HrefValue = "";

			// publish
			$this->publish->HrefValue = "";

			// amount
			$this->amount->HrefValue = "";
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
		if (!is_null($this->moduleid->FormValue) && $this->moduleid->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->moduleid->FldCaption());
		}
		if (!ew_CheckInteger($this->moduleid->FormValue)) {
			ew_AddMessage($gsFormError, $this->moduleid->FldErrMsg());
		}
		if (!is_null($this->typename->FormValue) && $this->typename->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->typename->FldCaption());
		}
		if (!is_null($this->coverid->FormValue) && $this->coverid->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->coverid->FldCaption());
		}
		if (!ew_CheckInteger($this->coverid->FormValue)) {
			ew_AddMessage($gsFormError, $this->coverid->FldErrMsg());
		}
		if (!is_null($this->publish->FormValue) && $this->publish->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->publish->FldCaption());
		}
		if (!ew_CheckDate($this->publish->FormValue)) {
			ew_AddMessage($gsFormError, $this->publish->FldErrMsg());
		}
		if (!is_null($this->amount->FormValue) && $this->amount->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->amount->FldCaption());
		}
		if (!ew_CheckInteger($this->amount->FormValue)) {
			ew_AddMessage($gsFormError, $this->amount->FldErrMsg());
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

			// moduleid
			$this->moduleid->SetDbValueDef($rsnew, $this->moduleid->CurrentValue, 0, $this->moduleid->ReadOnly);

			// typename
			$this->typename->SetDbValueDef($rsnew, $this->typename->CurrentValue, "", $this->typename->ReadOnly);

			// coverid
			$this->coverid->SetDbValueDef($rsnew, $this->coverid->CurrentValue, 0, $this->coverid->ReadOnly);

			// publish
			$this->publish->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->publish->CurrentValue, 5), ew_CurrentDate(), $this->publish->ReadOnly);

			// amount
			$this->amount->SetDbValueDef($rsnew, $this->amount->CurrentValue, 0, $this->amount->ReadOnly);

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
if (!isset($moduletype_edit)) $moduletype_edit = new cmoduletype_edit();

// Page init
$moduletype_edit->Page_Init();

// Page main
$moduletype_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var moduletype_edit = new ew_Page("moduletype_edit");
moduletype_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = moduletype_edit.PageID; // For backward compatibility

// Form object
var fmoduletypeedit = new ew_Form("fmoduletypeedit");

// Validate form
fmoduletypeedit.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_moduleid"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($moduletype->moduleid->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_moduleid"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($moduletype->moduleid->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_typename"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($moduletype->typename->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_coverid"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($moduletype->coverid->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_coverid"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($moduletype->coverid->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_publish"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($moduletype->publish->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_publish"];
		if (elm && !ew_CheckDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($moduletype->publish->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_amount"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($moduletype->amount->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_amount"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($moduletype->amount->FldErrMsg()) ?>");

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
fmoduletypeedit.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmoduletypeedit.ValidateRequired = true;
<?php } else { ?>
fmoduletypeedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $moduletype->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $moduletype->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $moduletype_edit->ShowPageHeader(); ?>
<?php
$moduletype_edit->ShowMessage();
?>
<form name="fmoduletypeedit" id="fmoduletypeedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<p>
<input type="hidden" name="t" value="moduletype">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_moduletypeedit" class="ewTable">
<?php if ($moduletype->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $moduletype->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_moduletype_id"><?php echo $moduletype->id->FldCaption() ?></span></td>
		<td<?php echo $moduletype->id->CellAttributes() ?>><span id="el_moduletype_id">
<span<?php echo $moduletype->id->ViewAttributes() ?>>
<?php echo $moduletype->id->EditValue ?></span>
<input type="hidden" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($moduletype->id->CurrentValue) ?>">
</span><?php echo $moduletype->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($moduletype->moduleid->Visible) { // moduleid ?>
	<tr id="r_moduleid"<?php echo $moduletype->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_moduletype_moduleid"><?php echo $moduletype->moduleid->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $moduletype->moduleid->CellAttributes() ?>><span id="el_moduletype_moduleid">
<input type="text" name="x_moduleid" id="x_moduleid" size="30" value="<?php echo $moduletype->moduleid->EditValue ?>"<?php echo $moduletype->moduleid->EditAttributes() ?>>
</span><?php echo $moduletype->moduleid->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($moduletype->typename->Visible) { // typename ?>
	<tr id="r_typename"<?php echo $moduletype->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_moduletype_typename"><?php echo $moduletype->typename->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $moduletype->typename->CellAttributes() ?>><span id="el_moduletype_typename">
<input type="text" name="x_typename" id="x_typename" size="30" maxlength="50" value="<?php echo $moduletype->typename->EditValue ?>"<?php echo $moduletype->typename->EditAttributes() ?>>
</span><?php echo $moduletype->typename->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($moduletype->coverid->Visible) { // coverid ?>
	<tr id="r_coverid"<?php echo $moduletype->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_moduletype_coverid"><?php echo $moduletype->coverid->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $moduletype->coverid->CellAttributes() ?>><span id="el_moduletype_coverid">
<input type="text" name="x_coverid" id="x_coverid" size="30" value="<?php echo $moduletype->coverid->EditValue ?>"<?php echo $moduletype->coverid->EditAttributes() ?>>
</span><?php echo $moduletype->coverid->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($moduletype->publish->Visible) { // publish ?>
	<tr id="r_publish"<?php echo $moduletype->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_moduletype_publish"><?php echo $moduletype->publish->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $moduletype->publish->CellAttributes() ?>><span id="el_moduletype_publish">
<input type="text" name="x_publish" id="x_publish" value="<?php echo $moduletype->publish->EditValue ?>"<?php echo $moduletype->publish->EditAttributes() ?>>
</span><?php echo $moduletype->publish->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($moduletype->amount->Visible) { // amount ?>
	<tr id="r_amount"<?php echo $moduletype->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_moduletype_amount"><?php echo $moduletype->amount->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $moduletype->amount->CellAttributes() ?>><span id="el_moduletype_amount">
<input type="text" name="x_amount" id="x_amount" size="30" value="<?php echo $moduletype->amount->EditValue ?>"<?php echo $moduletype->amount->EditAttributes() ?>>
</span><?php echo $moduletype->amount->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<p>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>">
</form>
<script type="text/javascript">
fmoduletypeedit.Init();
</script>
<?php
$moduletype_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$moduletype_edit->Page_Terminate();
?>
