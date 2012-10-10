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

$modulelist_add = NULL; // Initialize page object first

class cmodulelist_add extends cmodulelist {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{0FEB9E42-0859-45AD-84C6-C2CDD4EB41BE}";

	// Table name
	var $TableName = 'modulelist';

	// Page object name
	var $PageObjName = 'modulelist_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
					$this->Page_Terminate("modulelistlist.php"); // No matching record, return to list
				}
				break;
			case "A": // ' Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "modulelistview.php")
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
		$this->modulename->CurrentValue = NULL;
		$this->modulename->OldValue = $this->modulename->CurrentValue;
		$this->rank->CurrentValue = NULL;
		$this->rank->OldValue = $this->rank->CurrentValue;
		$this->icon->CurrentValue = NULL;
		$this->icon->OldValue = $this->icon->CurrentValue;
		$this->module->CurrentValue = NULL;
		$this->module->OldValue = $this->module->CurrentValue;
		$this->valid->CurrentValue = NULL;
		$this->valid->OldValue = $this->valid->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
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
		$this->LoadOldRecord();
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;
		$rsnew = array();

		// modulename
		$this->modulename->SetDbValueDef($rsnew, $this->modulename->CurrentValue, "", FALSE);

		// rank
		$this->rank->SetDbValueDef($rsnew, $this->rank->CurrentValue, 0, FALSE);

		// icon
		$this->icon->SetDbValueDef($rsnew, $this->icon->CurrentValue, "", FALSE);

		// module
		$this->module->SetDbValueDef($rsnew, $this->module->CurrentValue, "", FALSE);

		// valid
		$this->valid->SetDbValueDef($rsnew, $this->valid->CurrentValue, 0, FALSE);

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
if (!isset($modulelist_add)) $modulelist_add = new cmodulelist_add();

// Page init
$modulelist_add->Page_Init();

// Page main
$modulelist_add->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var modulelist_add = new ew_Page("modulelist_add");
modulelist_add.PageID = "add"; // Page ID
var EW_PAGE_ID = modulelist_add.PageID; // For backward compatibility

// Form object
var fmodulelistadd = new ew_Form("fmodulelistadd");

// Validate form
fmodulelistadd.Validate = function(fobj) {
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
fmodulelistadd.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmodulelistadd.ValidateRequired = true;
<?php } else { ?>
fmodulelistadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Add") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $modulelist->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $modulelist->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $modulelist_add->ShowPageHeader(); ?>
<?php
$modulelist_add->ShowMessage();
?>
<form name="fmodulelistadd" id="fmodulelistadd" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<p>
<input type="hidden" name="t" value="modulelist">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_modulelistadd" class="ewTable">
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
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("AddBtn")) ?>">
</form>
<script type="text/javascript">
fmodulelistadd.Init();
</script>
<?php
$modulelist_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$modulelist_add->Page_Terminate();
?>
