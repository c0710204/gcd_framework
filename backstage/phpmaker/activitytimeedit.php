<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "activitytimeinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$activitytime_edit = NULL; // Initialize page object first

class cactivitytime_edit extends cactivitytime {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{0FEB9E42-0859-45AD-84C6-C2CDD4EB41BE}";

	// Table name
	var $TableName = 'activitytime';

	// Page object name
	var $PageObjName = 'activitytime_edit';

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

		// Table object (activitytime)
		if (!isset($GLOBALS["activitytime"])) {
			$GLOBALS["activitytime"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["activitytime"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'activitytime', TRUE);

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
			$this->Page_Terminate("activitytimelist.php"); // Invalid key, return to list
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("activitytimelist.php"); // No matching record, return to list
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
		if (!$this->startime->FldIsDetailKey) {
			$this->startime->setFormValue($objForm->GetValue("x_startime"));
			$this->startime->CurrentValue = ew_UnFormatDateTime($this->startime->CurrentValue, 5);
		}
		if (!$this->endtime->FldIsDetailKey) {
			$this->endtime->setFormValue($objForm->GetValue("x_endtime"));
			$this->endtime->CurrentValue = ew_UnFormatDateTime($this->endtime->CurrentValue, 5);
		}
		if (!$this->addressId->FldIsDetailKey) {
			$this->addressId->setFormValue($objForm->GetValue("x_addressId"));
		}
		if (!$this->address->FldIsDetailKey) {
			$this->address->setFormValue($objForm->GetValue("x_address"));
		}
		if (!$this->activityid->FldIsDetailKey) {
			$this->activityid->setFormValue($objForm->GetValue("x_activityid"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->startime->CurrentValue = $this->startime->FormValue;
		$this->startime->CurrentValue = ew_UnFormatDateTime($this->startime->CurrentValue, 5);
		$this->endtime->CurrentValue = $this->endtime->FormValue;
		$this->endtime->CurrentValue = ew_UnFormatDateTime($this->endtime->CurrentValue, 5);
		$this->addressId->CurrentValue = $this->addressId->FormValue;
		$this->address->CurrentValue = $this->address->FormValue;
		$this->activityid->CurrentValue = $this->activityid->FormValue;
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
		$this->startime->setDbValue($rs->fields('startime'));
		$this->endtime->setDbValue($rs->fields('endtime'));
		$this->addressId->setDbValue($rs->fields('addressId'));
		if (array_key_exists('EV__addressId', $rs->fields)) {
			$this->addressId->VirtualValue = $rs->fields('EV__addressId'); // Set up virtual field value
		} else {
			$this->addressId->VirtualValue = ""; // Clear value
		}
		$this->address->setDbValue($rs->fields('address'));
		$this->activityid->setDbValue($rs->fields('activityid'));
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
		// startime
		// endtime
		// addressId
		// address
		// activityid

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// startime
			$this->startime->ViewValue = $this->startime->CurrentValue;
			$this->startime->ViewValue = ew_FormatDateTime($this->startime->ViewValue, 5);
			$this->startime->ViewCustomAttributes = "";

			// endtime
			$this->endtime->ViewValue = $this->endtime->CurrentValue;
			$this->endtime->ViewValue = ew_FormatDateTime($this->endtime->ViewValue, 5);
			$this->endtime->ViewCustomAttributes = "";

			// addressId
			if ($this->addressId->VirtualValue <> "") {
				$this->addressId->ViewValue = $this->addressId->VirtualValue;
			} else {
				$this->addressId->ViewValue = $this->addressId->CurrentValue;
			if (strval($this->addressId->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->addressId->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id`, `roomName` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `classroom`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->addressId->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->addressId->ViewValue = $this->addressId->CurrentValue;
				}
			} else {
				$this->addressId->ViewValue = NULL;
			}
			}
			$this->addressId->ViewCustomAttributes = "";

			// address
			$this->address->ViewValue = $this->address->CurrentValue;
			$this->address->ViewCustomAttributes = "";

			// activityid
			if (strval($this->activityid->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->activityid->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `activity`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->activityid->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->activityid->ViewValue = $this->activityid->CurrentValue;
				}
			} else {
				$this->activityid->ViewValue = NULL;
			}
			$this->activityid->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// startime
			$this->startime->LinkCustomAttributes = "";
			$this->startime->HrefValue = "";
			$this->startime->TooltipValue = "";

			// endtime
			$this->endtime->LinkCustomAttributes = "";
			$this->endtime->HrefValue = "";
			$this->endtime->TooltipValue = "";

			// addressId
			$this->addressId->LinkCustomAttributes = "";
			$this->addressId->HrefValue = "";
			$this->addressId->TooltipValue = "";

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";
			$this->address->TooltipValue = "";

			// activityid
			$this->activityid->LinkCustomAttributes = "";
			$this->activityid->HrefValue = "";
			$this->activityid->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// startime
			$this->startime->EditCustomAttributes = "";
			$this->startime->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->startime->CurrentValue, 5));

			// endtime
			$this->endtime->EditCustomAttributes = "";
			$this->endtime->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->endtime->CurrentValue, 5));

			// addressId
			$this->addressId->EditCustomAttributes = "";
			$this->addressId->EditValue = ew_HtmlEncode($this->addressId->CurrentValue);

			// address
			$this->address->EditCustomAttributes = "";
			$this->address->EditValue = ew_HtmlEncode($this->address->CurrentValue);

			// activityid
			$this->activityid->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `id`, `title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `activity`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->activityid->EditValue = $arwrk;

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// startime
			$this->startime->HrefValue = "";

			// endtime
			$this->endtime->HrefValue = "";

			// addressId
			$this->addressId->HrefValue = "";

			// address
			$this->address->HrefValue = "";

			// activityid
			$this->activityid->HrefValue = "";
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
		if (!is_null($this->startime->FormValue) && $this->startime->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->startime->FldCaption());
		}
		if (!ew_CheckDate($this->startime->FormValue)) {
			ew_AddMessage($gsFormError, $this->startime->FldErrMsg());
		}
		if (!is_null($this->endtime->FormValue) && $this->endtime->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->endtime->FldCaption());
		}
		if (!ew_CheckDate($this->endtime->FormValue)) {
			ew_AddMessage($gsFormError, $this->endtime->FldErrMsg());
		}
		if (!is_null($this->addressId->FormValue) && $this->addressId->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->addressId->FldCaption());
		}
		if (!is_null($this->activityid->FormValue) && $this->activityid->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->activityid->FldCaption());
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

			// startime
			$this->startime->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->startime->CurrentValue, 5), ew_CurrentDate(), $this->startime->ReadOnly);

			// endtime
			$this->endtime->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->endtime->CurrentValue, 5), ew_CurrentDate(), $this->endtime->ReadOnly);

			// addressId
			$this->addressId->SetDbValueDef($rsnew, $this->addressId->CurrentValue, 0, $this->addressId->ReadOnly);

			// address
			$this->address->SetDbValueDef($rsnew, $this->address->CurrentValue, NULL, $this->address->ReadOnly);

			// activityid
			$this->activityid->SetDbValueDef($rsnew, $this->activityid->CurrentValue, 0, $this->activityid->ReadOnly);

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
if (!isset($activitytime_edit)) $activitytime_edit = new cactivitytime_edit();

// Page init
$activitytime_edit->Page_Init();

// Page main
$activitytime_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var activitytime_edit = new ew_Page("activitytime_edit");
activitytime_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = activitytime_edit.PageID; // For backward compatibility

// Form object
var factivitytimeedit = new ew_Form("factivitytimeedit");

// Validate form
factivitytimeedit.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_startime"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($activitytime->startime->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_startime"];
		if (elm && !ew_CheckDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($activitytime->startime->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_endtime"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($activitytime->endtime->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_endtime"];
		if (elm && !ew_CheckDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($activitytime->endtime->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_addressId"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($activitytime->addressId->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_activityid"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($activitytime->activityid->FldCaption()) ?>");

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
factivitytimeedit.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
factivitytimeedit.ValidateRequired = true;
<?php } else { ?>
factivitytimeedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
factivitytimeedit.Lists["x_addressId"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_roomName","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
factivitytimeedit.Lists["x_activityid"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_title","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $activitytime->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $activitytime->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $activitytime_edit->ShowPageHeader(); ?>
<?php
$activitytime_edit->ShowMessage();
?>
<form name="factivitytimeedit" id="factivitytimeedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<p>
<input type="hidden" name="t" value="activitytime">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_activitytimeedit" class="ewTable">
<?php if ($activitytime->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $activitytime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_activitytime_id"><?php echo $activitytime->id->FldCaption() ?></span></td>
		<td<?php echo $activitytime->id->CellAttributes() ?>><span id="el_activitytime_id">
<span<?php echo $activitytime->id->ViewAttributes() ?>>
<?php echo $activitytime->id->EditValue ?></span>
<input type="hidden" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($activitytime->id->CurrentValue) ?>">
</span><?php echo $activitytime->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($activitytime->startime->Visible) { // startime ?>
	<tr id="r_startime"<?php echo $activitytime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_activitytime_startime"><?php echo $activitytime->startime->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $activitytime->startime->CellAttributes() ?>><span id="el_activitytime_startime">
<input type="text" name="x_startime" id="x_startime" value="<?php echo $activitytime->startime->EditValue ?>"<?php echo $activitytime->startime->EditAttributes() ?>>
<?php if (!$activitytime->startime->ReadOnly && !$activitytime->startime->Disabled && @$activitytime->startime->EditAttrs["readonly"] == "" && @$activitytime->startime->EditAttrs["disabled"] == "") { ?>
&nbsp;<img src="phpimages/calendar.png" id="factivitytimeedit$x_startime$" name="factivitytimeedit$x_startime$" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" class="ewCalendar">
<script type="text/javascript">
ew_CreateCalendar("factivitytimeedit", "x_startime", "%Y/%m/%d");
</script>
<?php } ?>
</span><?php echo $activitytime->startime->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($activitytime->endtime->Visible) { // endtime ?>
	<tr id="r_endtime"<?php echo $activitytime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_activitytime_endtime"><?php echo $activitytime->endtime->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $activitytime->endtime->CellAttributes() ?>><span id="el_activitytime_endtime">
<input type="text" name="x_endtime" id="x_endtime" value="<?php echo $activitytime->endtime->EditValue ?>"<?php echo $activitytime->endtime->EditAttributes() ?>>
<?php if (!$activitytime->endtime->ReadOnly && !$activitytime->endtime->Disabled && @$activitytime->endtime->EditAttrs["readonly"] == "" && @$activitytime->endtime->EditAttrs["disabled"] == "") { ?>
&nbsp;<img src="phpimages/calendar.png" id="factivitytimeedit$x_endtime$" name="factivitytimeedit$x_endtime$" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" class="ewCalendar">
<script type="text/javascript">
ew_CreateCalendar("factivitytimeedit", "x_endtime", "%Y/%m/%d");
</script>
<?php } ?>
</span><?php echo $activitytime->endtime->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($activitytime->addressId->Visible) { // addressId ?>
	<tr id="r_addressId"<?php echo $activitytime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_activitytime_addressId"><?php echo $activitytime->addressId->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $activitytime->addressId->CellAttributes() ?>><span id="el_activitytime_addressId">
<?php
	$wrkonchange = trim(" " . @$activitytime->addressId->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$activitytime->addressId->EditAttrs["onchange"] = "";
?>
<span id="as_x_addressId" style="white-space: nowrap; z-index: 8960">
	<input type="text" name="sv_x_addressId" id="sv_x_addressId" value="<?php echo $activitytime->addressId->EditValue ?>" size="30"<?php echo $activitytime->addressId->EditAttributes() ?>>&nbsp;<span id="em_x_addressId" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_addressId" style="z-index: 8960"></div>
</span>
<input type="hidden" name="x_addressId" id="x_addressId" value="<?php echo $activitytime->addressId->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `roomName` FROM `classroom`";
$sWhereWrk = "`roomName` LIKE '{query_value}%'";
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_addressId" id="q_x_addressId" value="s=<?php echo TEAencrypt($sSqlWrk) ?>&fn=<?php echo urlencode($activitytime->addressId->LookupFn) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_addressId", factivitytimeedit, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_addressId") + ar[i] : "";
	return dv;
}
oas.ac.typeAhead = false;
factivitytimeedit.AutoSuggests["x_addressId"] = oas;
</script>
</span><?php echo $activitytime->addressId->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($activitytime->address->Visible) { // address ?>
	<tr id="r_address"<?php echo $activitytime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_activitytime_address"><?php echo $activitytime->address->FldCaption() ?></span></td>
		<td<?php echo $activitytime->address->CellAttributes() ?>><span id="el_activitytime_address">
<textarea name="x_address" id="x_address" cols="undefined" rows="undefined"<?php echo $activitytime->address->EditAttributes() ?>><?php echo $activitytime->address->EditValue ?></textarea>
</span><?php echo $activitytime->address->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($activitytime->activityid->Visible) { // activityid ?>
	<tr id="r_activityid"<?php echo $activitytime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_activitytime_activityid"><?php echo $activitytime->activityid->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $activitytime->activityid->CellAttributes() ?>><span id="el_activitytime_activityid">
<select id="x_activityid" name="x_activityid"<?php echo $activitytime->activityid->EditAttributes() ?>>
<?php
if (is_array($activitytime->activityid->EditValue)) {
	$arwrk = $activitytime->activityid->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($activitytime->activityid->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
factivitytimeedit.Lists["x_activityid"].Options = <?php echo (is_array($activitytime->activityid->EditValue)) ? ew_ArrayToJson($activitytime->activityid->EditValue, 1) : "[]" ?>;
</script>
</span><?php echo $activitytime->activityid->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<p>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>">
</form>
<script type="text/javascript">
factivitytimeedit.Init();
</script>
<?php
$activitytime_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$activitytime_edit->Page_Terminate();
?>
