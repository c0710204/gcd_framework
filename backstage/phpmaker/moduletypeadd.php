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

$moduletype_add = NULL; // Initialize page object first

class cmoduletype_add extends cmoduletype {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{0FEB9E42-0859-45AD-84C6-C2CDD4EB41BE}";

	// Table name
	var $TableName = 'moduletype';

	// Page object name
	var $PageObjName = 'moduletype_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
					$this->Page_Terminate("moduletypelist.php"); // No matching record, return to list
				}
				break;
			case "A": // ' Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "moduletypeview.php")
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
		$this->moduleid->CurrentValue = NULL;
		$this->moduleid->OldValue = $this->moduleid->CurrentValue;
		$this->typename->CurrentValue = NULL;
		$this->typename->OldValue = $this->typename->CurrentValue;
		$this->coverid->CurrentValue = NULL;
		$this->coverid->OldValue = $this->coverid->CurrentValue;
		$this->publish->CurrentValue = NULL;
		$this->publish->OldValue = $this->publish->CurrentValue;
		$this->amount->CurrentValue = NULL;
		$this->amount->OldValue = $this->amount->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
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
		$this->LoadOldRecord();
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;
		$rsnew = array();

		// moduleid
		$this->moduleid->SetDbValueDef($rsnew, $this->moduleid->CurrentValue, 0, FALSE);

		// typename
		$this->typename->SetDbValueDef($rsnew, $this->typename->CurrentValue, "", FALSE);

		// coverid
		$this->coverid->SetDbValueDef($rsnew, $this->coverid->CurrentValue, 0, FALSE);

		// publish
		$this->publish->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->publish->CurrentValue, 5), ew_CurrentDate(), FALSE);

		// amount
		$this->amount->SetDbValueDef($rsnew, $this->amount->CurrentValue, 0, FALSE);

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
if (!isset($moduletype_add)) $moduletype_add = new cmoduletype_add();

// Page init
$moduletype_add->Page_Init();

// Page main
$moduletype_add->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var moduletype_add = new ew_Page("moduletype_add");
moduletype_add.PageID = "add"; // Page ID
var EW_PAGE_ID = moduletype_add.PageID; // For backward compatibility

// Form object
var fmoduletypeadd = new ew_Form("fmoduletypeadd");

// Validate form
fmoduletypeadd.Validate = function(fobj) {
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
fmoduletypeadd.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmoduletypeadd.ValidateRequired = true;
<?php } else { ?>
fmoduletypeadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Add") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $moduletype->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $moduletype->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $moduletype_add->ShowPageHeader(); ?>
<?php
$moduletype_add->ShowMessage();
?>
<form name="fmoduletypeadd" id="fmoduletypeadd" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<p>
<input type="hidden" name="t" value="moduletype">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_moduletypeadd" class="ewTable">
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
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("AddBtn")) ?>">
</form>
<script type="text/javascript">
fmoduletypeadd.Init();
</script>
<?php
$moduletype_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$moduletype_add->Page_Terminate();
?>
