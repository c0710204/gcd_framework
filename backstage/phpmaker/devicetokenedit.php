<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "devicetokeninfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$devicetoken_edit = NULL; // Initialize page object first

class cdevicetoken_edit extends cdevicetoken {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{0FEB9E42-0859-45AD-84C6-C2CDD4EB41BE}";

	// Table name
	var $TableName = 'devicetoken';

	// Page object name
	var $PageObjName = 'devicetoken_edit';

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

		// Table object (devicetoken)
		if (!isset($GLOBALS["devicetoken"])) {
			$GLOBALS["devicetoken"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["devicetoken"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'devicetoken', TRUE);

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
			$this->Page_Terminate("devicetokenlist.php"); // Invalid key, return to list
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("devicetokenlist.php"); // No matching record, return to list
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
		if (!$this->devicetoken->FldIsDetailKey) {
			$this->devicetoken->setFormValue($objForm->GetValue("x_devicetoken"));
		}
		if (!$this->devicename->FldIsDetailKey) {
			$this->devicename->setFormValue($objForm->GetValue("x_devicename"));
		}
		if (!$this->deviceplatform->FldIsDetailKey) {
			$this->deviceplatform->setFormValue($objForm->GetValue("x_deviceplatform"));
		}
		if (!$this->deviceuuid->FldIsDetailKey) {
			$this->deviceuuid->setFormValue($objForm->GetValue("x_deviceuuid"));
		}
		if (!$this->deviceversion->FldIsDetailKey) {
			$this->deviceversion->setFormValue($objForm->GetValue("x_deviceversion"));
		}
		if (!$this->_userid->FldIsDetailKey) {
			$this->_userid->setFormValue($objForm->GetValue("x__userid"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->devicetoken->CurrentValue = $this->devicetoken->FormValue;
		$this->devicename->CurrentValue = $this->devicename->FormValue;
		$this->deviceplatform->CurrentValue = $this->deviceplatform->FormValue;
		$this->deviceuuid->CurrentValue = $this->deviceuuid->FormValue;
		$this->deviceversion->CurrentValue = $this->deviceversion->FormValue;
		$this->_userid->CurrentValue = $this->_userid->FormValue;
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
		$this->devicetoken->setDbValue($rs->fields('devicetoken'));
		$this->devicename->setDbValue($rs->fields('devicename'));
		$this->deviceplatform->setDbValue($rs->fields('deviceplatform'));
		$this->deviceuuid->setDbValue($rs->fields('deviceuuid'));
		$this->deviceversion->setDbValue($rs->fields('deviceversion'));
		$this->_userid->setDbValue($rs->fields('userid'));
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
		// devicetoken
		// devicename
		// deviceplatform
		// deviceuuid
		// deviceversion
		// userid

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// devicetoken
			$this->devicetoken->ViewValue = $this->devicetoken->CurrentValue;
			$this->devicetoken->ViewCustomAttributes = "";

			// devicename
			$this->devicename->ViewValue = $this->devicename->CurrentValue;
			$this->devicename->ViewCustomAttributes = "";

			// deviceplatform
			$this->deviceplatform->ViewValue = $this->deviceplatform->CurrentValue;
			$this->deviceplatform->ViewCustomAttributes = "";

			// deviceuuid
			$this->deviceuuid->ViewValue = $this->deviceuuid->CurrentValue;
			$this->deviceuuid->ViewCustomAttributes = "";

			// deviceversion
			$this->deviceversion->ViewValue = $this->deviceversion->CurrentValue;
			$this->deviceversion->ViewCustomAttributes = "";

			// userid
			$this->_userid->ViewValue = $this->_userid->CurrentValue;
			$this->_userid->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// devicetoken
			$this->devicetoken->LinkCustomAttributes = "";
			$this->devicetoken->HrefValue = "";
			$this->devicetoken->TooltipValue = "";

			// devicename
			$this->devicename->LinkCustomAttributes = "";
			$this->devicename->HrefValue = "";
			$this->devicename->TooltipValue = "";

			// deviceplatform
			$this->deviceplatform->LinkCustomAttributes = "";
			$this->deviceplatform->HrefValue = "";
			$this->deviceplatform->TooltipValue = "";

			// deviceuuid
			$this->deviceuuid->LinkCustomAttributes = "";
			$this->deviceuuid->HrefValue = "";
			$this->deviceuuid->TooltipValue = "";

			// deviceversion
			$this->deviceversion->LinkCustomAttributes = "";
			$this->deviceversion->HrefValue = "";
			$this->deviceversion->TooltipValue = "";

			// userid
			$this->_userid->LinkCustomAttributes = "";
			$this->_userid->HrefValue = "";
			$this->_userid->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// devicetoken
			$this->devicetoken->EditCustomAttributes = "";
			$this->devicetoken->EditValue = ew_HtmlEncode($this->devicetoken->CurrentValue);

			// devicename
			$this->devicename->EditCustomAttributes = "";
			$this->devicename->EditValue = ew_HtmlEncode($this->devicename->CurrentValue);

			// deviceplatform
			$this->deviceplatform->EditCustomAttributes = "";
			$this->deviceplatform->EditValue = ew_HtmlEncode($this->deviceplatform->CurrentValue);

			// deviceuuid
			$this->deviceuuid->EditCustomAttributes = "";
			$this->deviceuuid->EditValue = ew_HtmlEncode($this->deviceuuid->CurrentValue);

			// deviceversion
			$this->deviceversion->EditCustomAttributes = "";
			$this->deviceversion->EditValue = ew_HtmlEncode($this->deviceversion->CurrentValue);

			// userid
			$this->_userid->EditCustomAttributes = "";
			$this->_userid->EditValue = ew_HtmlEncode($this->_userid->CurrentValue);

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// devicetoken
			$this->devicetoken->HrefValue = "";

			// devicename
			$this->devicename->HrefValue = "";

			// deviceplatform
			$this->deviceplatform->HrefValue = "";

			// deviceuuid
			$this->deviceuuid->HrefValue = "";

			// deviceversion
			$this->deviceversion->HrefValue = "";

			// userid
			$this->_userid->HrefValue = "";
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
		if (!is_null($this->devicetoken->FormValue) && $this->devicetoken->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->devicetoken->FldCaption());
		}
		if (!is_null($this->devicename->FormValue) && $this->devicename->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->devicename->FldCaption());
		}
		if (!is_null($this->deviceplatform->FormValue) && $this->deviceplatform->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->deviceplatform->FldCaption());
		}
		if (!is_null($this->deviceuuid->FormValue) && $this->deviceuuid->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->deviceuuid->FldCaption());
		}
		if (!is_null($this->deviceversion->FormValue) && $this->deviceversion->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->deviceversion->FldCaption());
		}
		if (!is_null($this->_userid->FormValue) && $this->_userid->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->_userid->FldCaption());
		}
		if (!ew_CheckInteger($this->_userid->FormValue)) {
			ew_AddMessage($gsFormError, $this->_userid->FldErrMsg());
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

			// devicetoken
			$this->devicetoken->SetDbValueDef($rsnew, $this->devicetoken->CurrentValue, "", $this->devicetoken->ReadOnly);

			// devicename
			$this->devicename->SetDbValueDef($rsnew, $this->devicename->CurrentValue, "", $this->devicename->ReadOnly);

			// deviceplatform
			$this->deviceplatform->SetDbValueDef($rsnew, $this->deviceplatform->CurrentValue, "", $this->deviceplatform->ReadOnly);

			// deviceuuid
			$this->deviceuuid->SetDbValueDef($rsnew, $this->deviceuuid->CurrentValue, "", $this->deviceuuid->ReadOnly);

			// deviceversion
			$this->deviceversion->SetDbValueDef($rsnew, $this->deviceversion->CurrentValue, "", $this->deviceversion->ReadOnly);

			// userid
			$this->_userid->SetDbValueDef($rsnew, $this->_userid->CurrentValue, 0, $this->_userid->ReadOnly);

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
if (!isset($devicetoken_edit)) $devicetoken_edit = new cdevicetoken_edit();

// Page init
$devicetoken_edit->Page_Init();

// Page main
$devicetoken_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var devicetoken_edit = new ew_Page("devicetoken_edit");
devicetoken_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = devicetoken_edit.PageID; // For backward compatibility

// Form object
var fdevicetokenedit = new ew_Form("fdevicetokenedit");

// Validate form
fdevicetokenedit.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_devicetoken"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($devicetoken->devicetoken->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_devicename"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($devicetoken->devicename->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_deviceplatform"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($devicetoken->deviceplatform->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_deviceuuid"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($devicetoken->deviceuuid->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_deviceversion"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($devicetoken->deviceversion->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "__userid"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($devicetoken->_userid->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "__userid"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($devicetoken->_userid->FldErrMsg()) ?>");

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
fdevicetokenedit.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdevicetokenedit.ValidateRequired = true;
<?php } else { ?>
fdevicetokenedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $devicetoken->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $devicetoken->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $devicetoken_edit->ShowPageHeader(); ?>
<?php
$devicetoken_edit->ShowMessage();
?>
<form name="fdevicetokenedit" id="fdevicetokenedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<p>
<input type="hidden" name="t" value="devicetoken">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_devicetokenedit" class="ewTable">
<?php if ($devicetoken->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $devicetoken->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_devicetoken_id"><?php echo $devicetoken->id->FldCaption() ?></span></td>
		<td<?php echo $devicetoken->id->CellAttributes() ?>><span id="el_devicetoken_id">
<span<?php echo $devicetoken->id->ViewAttributes() ?>>
<?php echo $devicetoken->id->EditValue ?></span>
<input type="hidden" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($devicetoken->id->CurrentValue) ?>">
</span><?php echo $devicetoken->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($devicetoken->devicetoken->Visible) { // devicetoken ?>
	<tr id="r_devicetoken"<?php echo $devicetoken->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_devicetoken_devicetoken"><?php echo $devicetoken->devicetoken->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $devicetoken->devicetoken->CellAttributes() ?>><span id="el_devicetoken_devicetoken">
<input type="text" name="x_devicetoken" id="x_devicetoken" size="30" maxlength="40" value="<?php echo $devicetoken->devicetoken->EditValue ?>"<?php echo $devicetoken->devicetoken->EditAttributes() ?>>
</span><?php echo $devicetoken->devicetoken->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($devicetoken->devicename->Visible) { // devicename ?>
	<tr id="r_devicename"<?php echo $devicetoken->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_devicetoken_devicename"><?php echo $devicetoken->devicename->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $devicetoken->devicename->CellAttributes() ?>><span id="el_devicetoken_devicename">
<input type="text" name="x_devicename" id="x_devicename" size="30" maxlength="100" value="<?php echo $devicetoken->devicename->EditValue ?>"<?php echo $devicetoken->devicename->EditAttributes() ?>>
</span><?php echo $devicetoken->devicename->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($devicetoken->deviceplatform->Visible) { // deviceplatform ?>
	<tr id="r_deviceplatform"<?php echo $devicetoken->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_devicetoken_deviceplatform"><?php echo $devicetoken->deviceplatform->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $devicetoken->deviceplatform->CellAttributes() ?>><span id="el_devicetoken_deviceplatform">
<input type="text" name="x_deviceplatform" id="x_deviceplatform" size="30" maxlength="100" value="<?php echo $devicetoken->deviceplatform->EditValue ?>"<?php echo $devicetoken->deviceplatform->EditAttributes() ?>>
</span><?php echo $devicetoken->deviceplatform->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($devicetoken->deviceuuid->Visible) { // deviceuuid ?>
	<tr id="r_deviceuuid"<?php echo $devicetoken->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_devicetoken_deviceuuid"><?php echo $devicetoken->deviceuuid->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $devicetoken->deviceuuid->CellAttributes() ?>><span id="el_devicetoken_deviceuuid">
<input type="text" name="x_deviceuuid" id="x_deviceuuid" size="30" maxlength="100" value="<?php echo $devicetoken->deviceuuid->EditValue ?>"<?php echo $devicetoken->deviceuuid->EditAttributes() ?>>
</span><?php echo $devicetoken->deviceuuid->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($devicetoken->deviceversion->Visible) { // deviceversion ?>
	<tr id="r_deviceversion"<?php echo $devicetoken->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_devicetoken_deviceversion"><?php echo $devicetoken->deviceversion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $devicetoken->deviceversion->CellAttributes() ?>><span id="el_devicetoken_deviceversion">
<input type="text" name="x_deviceversion" id="x_deviceversion" size="30" maxlength="10" value="<?php echo $devicetoken->deviceversion->EditValue ?>"<?php echo $devicetoken->deviceversion->EditAttributes() ?>>
</span><?php echo $devicetoken->deviceversion->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($devicetoken->_userid->Visible) { // userid ?>
	<tr id="r__userid"<?php echo $devicetoken->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_devicetoken__userid"><?php echo $devicetoken->_userid->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $devicetoken->_userid->CellAttributes() ?>><span id="el_devicetoken__userid">
<input type="text" name="x__userid" id="x__userid" size="30" value="<?php echo $devicetoken->_userid->EditValue ?>"<?php echo $devicetoken->_userid->EditAttributes() ?>>
</span><?php echo $devicetoken->_userid->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<p>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>">
</form>
<script type="text/javascript">
fdevicetokenedit.Init();
</script>
<?php
$devicetoken_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$devicetoken_edit->Page_Terminate();
?>
