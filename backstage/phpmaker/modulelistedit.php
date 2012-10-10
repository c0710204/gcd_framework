<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "modulelistinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$modulelist_edit = NULL; // Initialize page object first

class cmodulelist_edit extends cmodulelist {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{0FEB9E42-0859-45AD-84C6-C2CDD4EB41BE}";

	// Table name
	var $TableName = 'modulelist';

	// Page object name
	var $PageObjName = 'modulelist_edit';

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

		// Table object (modulelist)
		if (!isset($GLOBALS["modulelist"])) {
			$GLOBALS["modulelist"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["modulelist"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'modulelist', TRUE);

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
			$this->Page_Terminate("modulelistlist.php"); // Invalid key, return to list
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("modulelistlist.php"); // No matching record, return to list
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
		if (!$this->modulename->FldIsDetailKey) {
			$this->modulename->setFormValue($objForm->GetValue("x_modulename"));
		}
		if (!$this->rank->FldIsDetailKey) {
			$this->rank->setFormValue($objForm->GetValue("x_rank"));
		}
		if (!$this->icon->FldIsDetailKey) {
			$this->icon->setFormValue($objForm->GetValue("x_icon"));
		}
		if (!$this->module->FldIsDetailKey) {
			$this->module->setFormValue($objForm->GetValue("x_module"));
		}
		if (!$this->valid->FldIsDetailKey) {
			$this->valid->setFormValue($objForm->GetValue("x_valid"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->modulename->CurrentValue = $this->modulename->FormValue;
		$this->rank->CurrentValue = $this->rank->FormValue;
		$this->icon->CurrentValue = $this->icon->FormValue;
		$this->module->CurrentValue = $this->module->FormValue;
		$this->valid->CurrentValue = $this->valid->FormValue;
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
		$this->modulename->setDbValue($rs->fields('modulename'));
		$this->rank->setDbValue($rs->fields('rank'));
		$this->icon->setDbValue($rs->fields('icon'));
		$this->module->setDbValue($rs->fields('module'));
		$this->valid->setDbValue($rs->fields('valid'));
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
		// modulename
		// rank
		// icon
		// module
		// valid

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// modulename
			$this->modulename->ViewValue = $this->modulename->CurrentValue;
			$this->modulename->ViewCustomAttributes = "";

			// rank
			$this->rank->ViewValue = $this->rank->CurrentValue;
			$this->rank->ViewCustomAttributes = "";

			// icon
			$this->icon->ViewValue = $this->icon->CurrentValue;
			$this->icon->ViewCustomAttributes = "";

			// module
			$this->module->ViewValue = $this->module->CurrentValue;
			$this->module->ViewCustomAttributes = "";

			// valid
			$this->valid->ViewValue = $this->valid->CurrentValue;
			$this->valid->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// modulename
			$this->modulename->LinkCustomAttributes = "";
			$this->modulename->HrefValue = "";
			$this->modulename->TooltipValue = "";

			// rank
			$this->rank->LinkCustomAttributes = "";
			$this->rank->HrefValue = "";
			$this->rank->TooltipValue = "";

			// icon
			$this->icon->LinkCustomAttributes = "";
			$this->icon->HrefValue = "";
			$this->icon->TooltipValue = "";

			// module
			$this->module->LinkCustomAttributes = "";
			$this->module->HrefValue = "";
			$this->module->TooltipValue = "";

			// valid
			$this->valid->LinkCustomAttributes = "";
			$this->valid->HrefValue = "";
			$this->valid->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// modulename
			$this->modulename->EditCustomAttributes = "";
			$this->modulename->EditValue = ew_HtmlEncode($this->modulename->CurrentValue);

			// rank
			$this->rank->EditCustomAttributes = "";
			$this->rank->EditValue = ew_HtmlEncode($this->rank->CurrentValue);

			// icon
			$this->icon->EditCustomAttributes = "";
			$this->icon->EditValue = ew_HtmlEncode($this->icon->CurrentValue);

			// module
			$this->module->EditCustomAttributes = "";
			$this->module->EditValue = ew_HtmlEncode($this->module->CurrentValue);

			// valid
			$this->valid->EditCustomAttributes = "";
			$this->valid->EditValue = ew_HtmlEncode($this->valid->CurrentValue);

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// modulename
			$this->modulename->HrefValue = "";

			// rank
			$this->rank->HrefValue = "";

			// icon
			$this->icon->HrefValue = "";

			// module
			$this->module->HrefValue = "";

			// valid
			$this->valid->HrefValue = "";
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
		if (!is_null($this->modulename->FormValue) && $this->modulename->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->modulename->FldCaption());
		}
		if (!is_null($this->rank->FormValue) && $this->rank->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->rank->FldCaption());
		}
		if (!ew_CheckInteger($this->rank->FormValue)) {
			ew_AddMessage($gsFormError, $this->rank->FldErrMsg());
		}
		if (!is_null($this->icon->FormValue) && $this->icon->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->icon->FldCaption());
		}
		if (!is_null($this->module->FormValue) && $this->module->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->module->FldCaption());
		}
		if (!is_null($this->valid->FormValue) && $this->valid->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->valid->FldCaption());
		}
		if (!ew_CheckInteger($this->valid->FormValue)) {
			ew_AddMessage($gsFormError, $this->valid->FldErrMsg());
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

			// modulename
			$this->modulename->SetDbValueDef($rsnew, $this->modulename->CurrentValue, "", $this->modulename->ReadOnly);

			// rank
			$this->rank->SetDbValueDef($rsnew, $this->rank->CurrentValue, 0, $this->rank->ReadOnly);

			// icon
			$this->icon->SetDbValueDef($rsnew, $this->icon->CurrentValue, "", $this->icon->ReadOnly);

			// module
			$this->module->SetDbValueDef($rsnew, $this->module->CurrentValue, "", $this->module->ReadOnly);

			// valid
			$this->valid->SetDbValueDef($rsnew, $this->valid->CurrentValue, 0, $this->valid->ReadOnly);

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
if (!isset($modulelist_edit)) $modulelist_edit = new cmodulelist_edit();

// Page init
$modulelist_edit->Page_Init();

// Page main
$modulelist_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var modulelist_edit = new ew_Page("modulelist_edit");
modulelist_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = modulelist_edit.PageID; // For backward compatibility

// Form object
var fmodulelistedit = new ew_Form("fmodulelistedit");

// Validate form
fmodulelistedit.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_modulename"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($modulelist->modulename->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_rank"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($modulelist->rank->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_rank"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($modulelist->rank->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_icon"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($modulelist->icon->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_module"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($modulelist->module->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_valid"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($modulelist->valid->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_valid"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($modulelist->valid->FldErrMsg()) ?>");

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
fmodulelistedit.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmodulelistedit.ValidateRequired = true;
<?php } else { ?>
fmodulelistedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $modulelist->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $modulelist->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $modulelist_edit->ShowPageHeader(); ?>
<?php
$modulelist_edit->ShowMessage();
?>
<form name="fmodulelistedit" id="fmodulelistedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<p>
<input type="hidden" name="t" value="modulelist">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_modulelistedit" class="ewTable">
<?php if ($modulelist->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $modulelist->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_modulelist_id"><?php echo $modulelist->id->FldCaption() ?></span></td>
		<td<?php echo $modulelist->id->CellAttributes() ?>><span id="el_modulelist_id">
<span<?php echo $modulelist->id->ViewAttributes() ?>>
<?php echo $modulelist->id->EditValue ?></span>
<input type="hidden" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($modulelist->id->CurrentValue) ?>">
</span><?php echo $modulelist->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($modulelist->modulename->Visible) { // modulename ?>
	<tr id="r_modulename"<?php echo $modulelist->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_modulelist_modulename"><?php echo $modulelist->modulename->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $modulelist->modulename->CellAttributes() ?>><span id="el_modulelist_modulename">
<input type="text" name="x_modulename" id="x_modulename" size="30" maxlength="20" value="<?php echo $modulelist->modulename->EditValue ?>"<?php echo $modulelist->modulename->EditAttributes() ?>>
</span><?php echo $modulelist->modulename->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($modulelist->rank->Visible) { // rank ?>
	<tr id="r_rank"<?php echo $modulelist->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_modulelist_rank"><?php echo $modulelist->rank->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $modulelist->rank->CellAttributes() ?>><span id="el_modulelist_rank">
<input type="text" name="x_rank" id="x_rank" size="30" value="<?php echo $modulelist->rank->EditValue ?>"<?php echo $modulelist->rank->EditAttributes() ?>>
</span><?php echo $modulelist->rank->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($modulelist->icon->Visible) { // icon ?>
	<tr id="r_icon"<?php echo $modulelist->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_modulelist_icon"><?php echo $modulelist->icon->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $modulelist->icon->CellAttributes() ?>><span id="el_modulelist_icon">
<input type="text" name="x_icon" id="x_icon" size="30" maxlength="20" value="<?php echo $modulelist->icon->EditValue ?>"<?php echo $modulelist->icon->EditAttributes() ?>>
</span><?php echo $modulelist->icon->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($modulelist->module->Visible) { // module ?>
	<tr id="r_module"<?php echo $modulelist->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_modulelist_module"><?php echo $modulelist->module->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $modulelist->module->CellAttributes() ?>><span id="el_modulelist_module">
<input type="text" name="x_module" id="x_module" size="30" maxlength="20" value="<?php echo $modulelist->module->EditValue ?>"<?php echo $modulelist->module->EditAttributes() ?>>
</span><?php echo $modulelist->module->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($modulelist->valid->Visible) { // valid ?>
	<tr id="r_valid"<?php echo $modulelist->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_modulelist_valid"><?php echo $modulelist->valid->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $modulelist->valid->CellAttributes() ?>><span id="el_modulelist_valid">
<input type="text" name="x_valid" id="x_valid" size="30" value="<?php echo $modulelist->valid->EditValue ?>"<?php echo $modulelist->valid->EditAttributes() ?>>
</span><?php echo $modulelist->valid->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<p>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>">
</form>
<script type="text/javascript">
fmodulelistedit.Init();
</script>
<?php
$modulelist_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$modulelist_edit->Page_Terminate();
?>
