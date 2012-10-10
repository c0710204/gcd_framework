<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "memberinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$member_edit = NULL; // Initialize page object first

class cmember_edit extends cmember {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{0FEB9E42-0859-45AD-84C6-C2CDD4EB41BE}";

	// Table name
	var $TableName = 'member';

	// Page object name
	var $PageObjName = 'member_edit';

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

		// Table object (member)
		if (!isset($GLOBALS["member"])) {
			$GLOBALS["member"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["member"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'member', TRUE);

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
			$this->Page_Terminate("memberlist.php"); // Invalid key, return to list
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("memberlist.php"); // No matching record, return to list
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
		if (!$this->_userid->FldIsDetailKey) {
			$this->_userid->setFormValue($objForm->GetValue("x__userid"));
		}
		if (!$this->username->FldIsDetailKey) {
			$this->username->setFormValue($objForm->GetValue("x_username"));
		}
		if (!$this->idtype->FldIsDetailKey) {
			$this->idtype->setFormValue($objForm->GetValue("x_idtype"));
		}
		if (!$this->MasPass->FldIsDetailKey) {
			$this->MasPass->setFormValue($objForm->GetValue("x_MasPass"));
		}
		if (!$this->accessToken->FldIsDetailKey) {
			$this->accessToken->setFormValue($objForm->GetValue("x_accessToken"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->_userid->CurrentValue = $this->_userid->FormValue;
		$this->username->CurrentValue = $this->username->FormValue;
		$this->idtype->CurrentValue = $this->idtype->FormValue;
		$this->MasPass->CurrentValue = $this->MasPass->FormValue;
		$this->accessToken->CurrentValue = $this->accessToken->FormValue;
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
		$this->_userid->setDbValue($rs->fields('userid'));
		$this->username->setDbValue($rs->fields('username'));
		$this->idtype->setDbValue($rs->fields('idtype'));
		$this->MasPass->setDbValue($rs->fields('MasPass'));
		$this->accessToken->setDbValue($rs->fields('accessToken'));
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
		// userid
		// username
		// idtype
		// MasPass
		// accessToken

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// userid
			$this->_userid->ViewValue = $this->_userid->CurrentValue;
			$this->_userid->ViewCustomAttributes = "";

			// username
			$this->username->ViewValue = $this->username->CurrentValue;
			$this->username->ViewCustomAttributes = "";

			// idtype
			$this->idtype->ViewValue = $this->idtype->CurrentValue;
			$this->idtype->ViewCustomAttributes = "";

			// MasPass
			$this->MasPass->ViewValue = $this->MasPass->CurrentValue;
			$this->MasPass->ViewCustomAttributes = "";

			// accessToken
			$this->accessToken->ViewValue = $this->accessToken->CurrentValue;
			$this->accessToken->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// userid
			$this->_userid->LinkCustomAttributes = "";
			$this->_userid->HrefValue = "";
			$this->_userid->TooltipValue = "";

			// username
			$this->username->LinkCustomAttributes = "";
			$this->username->HrefValue = "";
			$this->username->TooltipValue = "";

			// idtype
			$this->idtype->LinkCustomAttributes = "";
			$this->idtype->HrefValue = "";
			$this->idtype->TooltipValue = "";

			// MasPass
			$this->MasPass->LinkCustomAttributes = "";
			$this->MasPass->HrefValue = "";
			$this->MasPass->TooltipValue = "";

			// accessToken
			$this->accessToken->LinkCustomAttributes = "";
			$this->accessToken->HrefValue = "";
			$this->accessToken->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// userid
			$this->_userid->EditCustomAttributes = "";
			$this->_userid->EditValue = ew_HtmlEncode($this->_userid->CurrentValue);

			// username
			$this->username->EditCustomAttributes = "";
			$this->username->EditValue = ew_HtmlEncode($this->username->CurrentValue);

			// idtype
			$this->idtype->EditCustomAttributes = "";
			$this->idtype->EditValue = ew_HtmlEncode($this->idtype->CurrentValue);

			// MasPass
			$this->MasPass->EditCustomAttributes = "";
			$this->MasPass->EditValue = ew_HtmlEncode($this->MasPass->CurrentValue);

			// accessToken
			$this->accessToken->EditCustomAttributes = "";
			$this->accessToken->EditValue = ew_HtmlEncode($this->accessToken->CurrentValue);

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// userid
			$this->_userid->HrefValue = "";

			// username
			$this->username->HrefValue = "";

			// idtype
			$this->idtype->HrefValue = "";

			// MasPass
			$this->MasPass->HrefValue = "";

			// accessToken
			$this->accessToken->HrefValue = "";
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
		if (!is_null($this->_userid->FormValue) && $this->_userid->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->_userid->FldCaption());
		}
		if (!ew_CheckInteger($this->_userid->FormValue)) {
			ew_AddMessage($gsFormError, $this->_userid->FldErrMsg());
		}
		if (!is_null($this->username->FormValue) && $this->username->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->username->FldCaption());
		}
		if (!is_null($this->idtype->FormValue) && $this->idtype->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->idtype->FldCaption());
		}
		if (!ew_CheckInteger($this->idtype->FormValue)) {
			ew_AddMessage($gsFormError, $this->idtype->FldErrMsg());
		}
		if (!is_null($this->MasPass->FormValue) && $this->MasPass->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->MasPass->FldCaption());
		}
		if (!is_null($this->accessToken->FormValue) && $this->accessToken->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->accessToken->FldCaption());
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
			if ($this->accessToken->CurrentValue <> "") { // Check field with unique index
			$sFilterChk = "(`accessToken` = '" . ew_AdjustSql($this->accessToken->CurrentValue) . "')";
			$sFilterChk .= " AND NOT (" . $sFilter . ")";
			$this->CurrentFilter = $sFilterChk;
			$sSqlChk = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rsChk = $conn->Execute($sSqlChk);
			$conn->raiseErrorFn = '';
			if ($rsChk === FALSE) {
				return FALSE;
			} elseif (!$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->accessToken->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->accessToken->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
			$rsChk->Close();
		}
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

			// userid
			$this->_userid->SetDbValueDef($rsnew, $this->_userid->CurrentValue, 0, $this->_userid->ReadOnly);

			// username
			$this->username->SetDbValueDef($rsnew, $this->username->CurrentValue, "", $this->username->ReadOnly);

			// idtype
			$this->idtype->SetDbValueDef($rsnew, $this->idtype->CurrentValue, 0, $this->idtype->ReadOnly);

			// MasPass
			$this->MasPass->SetDbValueDef($rsnew, $this->MasPass->CurrentValue, "", $this->MasPass->ReadOnly);

			// accessToken
			$this->accessToken->SetDbValueDef($rsnew, $this->accessToken->CurrentValue, NULL, $this->accessToken->ReadOnly);

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
if (!isset($member_edit)) $member_edit = new cmember_edit();

// Page init
$member_edit->Page_Init();

// Page main
$member_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var member_edit = new ew_Page("member_edit");
member_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = member_edit.PageID; // For backward compatibility

// Form object
var fmemberedit = new ew_Form("fmemberedit");

// Validate form
fmemberedit.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "__userid"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($member->_userid->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "__userid"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($member->_userid->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_username"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($member->username->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_idtype"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($member->idtype->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_idtype"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($member->idtype->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_MasPass"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($member->MasPass->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_accessToken"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($member->accessToken->FldCaption()) ?>");

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
fmemberedit.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmemberedit.ValidateRequired = true;
<?php } else { ?>
fmemberedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $member->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $member->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $member_edit->ShowPageHeader(); ?>
<?php
$member_edit->ShowMessage();
?>
<form name="fmemberedit" id="fmemberedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<p>
<input type="hidden" name="t" value="member">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_memberedit" class="ewTable">
<?php if ($member->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $member->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_member_id"><?php echo $member->id->FldCaption() ?></span></td>
		<td<?php echo $member->id->CellAttributes() ?>><span id="el_member_id">
<span<?php echo $member->id->ViewAttributes() ?>>
<?php echo $member->id->EditValue ?></span>
<input type="hidden" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($member->id->CurrentValue) ?>">
</span><?php echo $member->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($member->_userid->Visible) { // userid ?>
	<tr id="r__userid"<?php echo $member->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_member__userid"><?php echo $member->_userid->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $member->_userid->CellAttributes() ?>><span id="el_member__userid">
<input type="text" name="x__userid" id="x__userid" size="30" value="<?php echo $member->_userid->EditValue ?>"<?php echo $member->_userid->EditAttributes() ?>>
</span><?php echo $member->_userid->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($member->username->Visible) { // username ?>
	<tr id="r_username"<?php echo $member->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_member_username"><?php echo $member->username->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $member->username->CellAttributes() ?>><span id="el_member_username">
<input type="text" name="x_username" id="x_username" size="30" maxlength="20" value="<?php echo $member->username->EditValue ?>"<?php echo $member->username->EditAttributes() ?>>
</span><?php echo $member->username->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($member->idtype->Visible) { // idtype ?>
	<tr id="r_idtype"<?php echo $member->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_member_idtype"><?php echo $member->idtype->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $member->idtype->CellAttributes() ?>><span id="el_member_idtype">
<input type="text" name="x_idtype" id="x_idtype" size="30" value="<?php echo $member->idtype->EditValue ?>"<?php echo $member->idtype->EditAttributes() ?>>
</span><?php echo $member->idtype->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($member->MasPass->Visible) { // MasPass ?>
	<tr id="r_MasPass"<?php echo $member->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_member_MasPass"><?php echo $member->MasPass->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $member->MasPass->CellAttributes() ?>><span id="el_member_MasPass">
<input type="text" name="x_MasPass" id="x_MasPass" size="30" maxlength="16" value="<?php echo $member->MasPass->EditValue ?>"<?php echo $member->MasPass->EditAttributes() ?>>
</span><?php echo $member->MasPass->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($member->accessToken->Visible) { // accessToken ?>
	<tr id="r_accessToken"<?php echo $member->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_member_accessToken"><?php echo $member->accessToken->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $member->accessToken->CellAttributes() ?>><span id="el_member_accessToken">
<input type="text" name="x_accessToken" id="x_accessToken" size="30" maxlength="40" value="<?php echo $member->accessToken->EditValue ?>"<?php echo $member->accessToken->EditAttributes() ?>>
</span><?php echo $member->accessToken->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<p>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>">
</form>
<script type="text/javascript">
fmemberedit.Init();
</script>
<?php
$member_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$member_edit->Page_Terminate();
?>
