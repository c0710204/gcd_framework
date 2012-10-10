<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "albuminfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$album_edit = NULL; // Initialize page object first

class calbum_edit extends calbum {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{0FEB9E42-0859-45AD-84C6-C2CDD4EB41BE}";

	// Table name
	var $TableName = 'album';

	// Page object name
	var $PageObjName = 'album_edit';

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

		// Table object (album)
		if (!isset($GLOBALS["album"])) {
			$GLOBALS["album"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["album"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'album', TRUE);

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
			$this->Page_Terminate("albumlist.php"); // Invalid key, return to list
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("albumlist.php"); // No matching record, return to list
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
		if (!$this->title->FldIsDetailKey) {
			$this->title->setFormValue($objForm->GetValue("x_title"));
		}
		if (!$this->intro->FldIsDetailKey) {
			$this->intro->setFormValue($objForm->GetValue("x_intro"));
		}
		if (!$this->path->FldIsDetailKey) {
			$this->path->setFormValue($objForm->GetValue("x_path"));
		}
		if (!$this->filename->FldIsDetailKey) {
			$this->filename->setFormValue($objForm->GetValue("x_filename"));
		}
		if (!$this->typeid->FldIsDetailKey) {
			$this->typeid->setFormValue($objForm->GetValue("x_typeid"));
		}
		if (!$this->publish->FldIsDetailKey) {
			$this->publish->setFormValue($objForm->GetValue("x_publish"));
			$this->publish->CurrentValue = ew_UnFormatDateTime($this->publish->CurrentValue, 5);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->title->CurrentValue = $this->title->FormValue;
		$this->intro->CurrentValue = $this->intro->FormValue;
		$this->path->CurrentValue = $this->path->FormValue;
		$this->filename->CurrentValue = $this->filename->FormValue;
		$this->typeid->CurrentValue = $this->typeid->FormValue;
		$this->publish->CurrentValue = $this->publish->FormValue;
		$this->publish->CurrentValue = ew_UnFormatDateTime($this->publish->CurrentValue, 5);
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
		$this->title->setDbValue($rs->fields('title'));
		$this->intro->setDbValue($rs->fields('intro'));
		$this->path->setDbValue($rs->fields('path'));
		$this->filename->setDbValue($rs->fields('filename'));
		$this->typeid->setDbValue($rs->fields('typeid'));
		$this->publish->setDbValue($rs->fields('publish'));
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
		// title
		// intro
		// path
		// filename
		// typeid
		// publish

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// title
			$this->title->ViewValue = $this->title->CurrentValue;
			$this->title->ViewCustomAttributes = "";

			// intro
			$this->intro->ViewValue = $this->intro->CurrentValue;
			$this->intro->ViewCustomAttributes = "";

			// path
			$this->path->ViewValue = $this->path->CurrentValue;
			$this->path->ViewCustomAttributes = "";

			// filename
			$this->filename->ViewValue = $this->filename->CurrentValue;
			$this->filename->ViewCustomAttributes = "";

			// typeid
			$this->typeid->ViewValue = $this->typeid->CurrentValue;
			if (strval($this->typeid->CurrentValue) <> "") {
				$sFilterWrk = "`moduleid`" . ew_SearchString("=", $this->typeid->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `moduleid`, `typename` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `moduletype`";
			$sWhereWrk = "";
			$lookuptblfilter = "`moduleid`=`6`";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->typeid->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->typeid->ViewValue = $this->typeid->CurrentValue;
				}
			} else {
				$this->typeid->ViewValue = NULL;
			}
			$this->typeid->ViewCustomAttributes = "";

			// publish
			$this->publish->ViewValue = $this->publish->CurrentValue;
			$this->publish->ViewValue = ew_FormatDateTime($this->publish->ViewValue, 5);
			$this->publish->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// title
			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";
			$this->title->TooltipValue = "";

			// intro
			$this->intro->LinkCustomAttributes = "";
			$this->intro->HrefValue = "";
			$this->intro->TooltipValue = "";

			// path
			$this->path->LinkCustomAttributes = "";
			$this->path->HrefValue = "";
			$this->path->TooltipValue = "";

			// filename
			$this->filename->LinkCustomAttributes = "";
			$this->filename->HrefValue = "";
			$this->filename->TooltipValue = "";

			// typeid
			$this->typeid->LinkCustomAttributes = "";
			$this->typeid->HrefValue = "";
			$this->typeid->TooltipValue = "";

			// publish
			$this->publish->LinkCustomAttributes = "";
			$this->publish->HrefValue = "";
			$this->publish->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// title
			$this->title->EditCustomAttributes = "";
			$this->title->EditValue = ew_HtmlEncode($this->title->CurrentValue);

			// intro
			$this->intro->EditCustomAttributes = "";
			$this->intro->EditValue = ew_HtmlEncode($this->intro->CurrentValue);

			// path
			$this->path->EditCustomAttributes = "";
			$this->path->EditValue = ew_HtmlEncode($this->path->CurrentValue);

			// filename
			$this->filename->EditCustomAttributes = "";
			$this->filename->EditValue = ew_HtmlEncode($this->filename->CurrentValue);

			// typeid
			$this->typeid->EditCustomAttributes = "";
			$this->typeid->EditValue = ew_HtmlEncode($this->typeid->CurrentValue);
			if (strval($this->typeid->CurrentValue) <> "") {
				$sFilterWrk = "`moduleid`" . ew_SearchString("=", $this->typeid->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `moduleid`, `typename` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `moduletype`";
			$sWhereWrk = "";
			$lookuptblfilter = "`moduleid`=`6`";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->typeid->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->typeid->EditValue = $this->typeid->CurrentValue;
				}
			} else {
				$this->typeid->EditValue = NULL;
			}

			// publish
			$this->publish->EditCustomAttributes = "";
			$this->publish->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->publish->CurrentValue, 5));

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// title
			$this->title->HrefValue = "";

			// intro
			$this->intro->HrefValue = "";

			// path
			$this->path->HrefValue = "";

			// filename
			$this->filename->HrefValue = "";

			// typeid
			$this->typeid->HrefValue = "";

			// publish
			$this->publish->HrefValue = "";
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
		if (!is_null($this->title->FormValue) && $this->title->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->title->FldCaption());
		}
		if (!is_null($this->intro->FormValue) && $this->intro->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->intro->FldCaption());
		}
		if (!is_null($this->path->FormValue) && $this->path->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->path->FldCaption());
		}
		if (!is_null($this->filename->FormValue) && $this->filename->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->filename->FldCaption());
		}
		if (!is_null($this->typeid->FormValue) && $this->typeid->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->typeid->FldCaption());
		}
		if (!ew_CheckInteger($this->typeid->FormValue)) {
			ew_AddMessage($gsFormError, $this->typeid->FldErrMsg());
		}
		if (!is_null($this->publish->FormValue) && $this->publish->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->publish->FldCaption());
		}
		if (!ew_CheckDate($this->publish->FormValue)) {
			ew_AddMessage($gsFormError, $this->publish->FldErrMsg());
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

			// title
			$this->title->SetDbValueDef($rsnew, $this->title->CurrentValue, "", $this->title->ReadOnly);

			// intro
			$this->intro->SetDbValueDef($rsnew, $this->intro->CurrentValue, "", $this->intro->ReadOnly);

			// path
			$this->path->SetDbValueDef($rsnew, $this->path->CurrentValue, "", $this->path->ReadOnly);

			// filename
			$this->filename->SetDbValueDef($rsnew, $this->filename->CurrentValue, "", $this->filename->ReadOnly);

			// typeid
			$this->typeid->SetDbValueDef($rsnew, $this->typeid->CurrentValue, 0, $this->typeid->ReadOnly);

			// publish
			$this->publish->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->publish->CurrentValue, 5), ew_CurrentDate(), $this->publish->ReadOnly);

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
if (!isset($album_edit)) $album_edit = new calbum_edit();

// Page init
$album_edit->Page_Init();

// Page main
$album_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var album_edit = new ew_Page("album_edit");
album_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = album_edit.PageID; // For backward compatibility

// Form object
var falbumedit = new ew_Form("falbumedit");

// Validate form
falbumedit.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_title"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($album->title->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_intro"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($album->intro->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_path"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($album->path->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_filename"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($album->filename->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_typeid"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($album->typeid->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_typeid"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($album->typeid->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_publish"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($album->publish->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_publish"];
		if (elm && !ew_CheckDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($album->publish->FldErrMsg()) ?>");

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
falbumedit.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
falbumedit.ValidateRequired = true;
<?php } else { ?>
falbumedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
falbumedit.Lists["x_typeid"] = {"LinkField":"x_moduleid","Ajax":true,"AutoFill":false,"DisplayFields":["x_typename","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $album->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $album->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $album_edit->ShowPageHeader(); ?>
<?php
$album_edit->ShowMessage();
?>
<form name="falbumedit" id="falbumedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<p>
<input type="hidden" name="t" value="album">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_albumedit" class="ewTable">
<?php if ($album->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $album->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_album_id"><?php echo $album->id->FldCaption() ?></span></td>
		<td<?php echo $album->id->CellAttributes() ?>><span id="el_album_id">
<span<?php echo $album->id->ViewAttributes() ?>>
<?php echo $album->id->EditValue ?></span>
<input type="hidden" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($album->id->CurrentValue) ?>">
</span><?php echo $album->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($album->title->Visible) { // title ?>
	<tr id="r_title"<?php echo $album->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_album_title"><?php echo $album->title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $album->title->CellAttributes() ?>><span id="el_album_title">
<input type="text" name="x_title" id="x_title" size="30" maxlength="100" value="<?php echo $album->title->EditValue ?>"<?php echo $album->title->EditAttributes() ?>>
</span><?php echo $album->title->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($album->intro->Visible) { // intro ?>
	<tr id="r_intro"<?php echo $album->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_album_intro"><?php echo $album->intro->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $album->intro->CellAttributes() ?>><span id="el_album_intro">
<textarea name="x_intro" id="x_intro" cols="35" rows="4"<?php echo $album->intro->EditAttributes() ?>><?php echo $album->intro->EditValue ?></textarea>
</span><?php echo $album->intro->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($album->path->Visible) { // path ?>
	<tr id="r_path"<?php echo $album->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_album_path"><?php echo $album->path->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $album->path->CellAttributes() ?>><span id="el_album_path">
<input type="text" name="x_path" id="x_path" size="30" maxlength="100" value="<?php echo $album->path->EditValue ?>"<?php echo $album->path->EditAttributes() ?>>
</span><?php echo $album->path->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($album->filename->Visible) { // filename ?>
	<tr id="r_filename"<?php echo $album->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_album_filename"><?php echo $album->filename->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $album->filename->CellAttributes() ?>><span id="el_album_filename">
<input type="text" name="x_filename" id="x_filename" size="30" maxlength="100" value="<?php echo $album->filename->EditValue ?>"<?php echo $album->filename->EditAttributes() ?>>
</span><?php echo $album->filename->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($album->typeid->Visible) { // typeid ?>
	<tr id="r_typeid"<?php echo $album->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_album_typeid"><?php echo $album->typeid->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $album->typeid->CellAttributes() ?>><span id="el_album_typeid">
<?php
	$wrkonchange = trim(" " . @$album->typeid->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$album->typeid->EditAttrs["onchange"] = "";
?>
<span id="as_x_typeid" style="white-space: nowrap; z-index: 8940">
	<input type="text" name="sv_x_typeid" id="sv_x_typeid" value="<?php echo $album->typeid->EditValue ?>" size="30"<?php echo $album->typeid->EditAttributes() ?>>&nbsp;<span id="em_x_typeid" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_typeid" style="z-index: 8940"></div>
</span>
<input type="hidden" name="x_typeid" id="x_typeid" value="<?php echo $album->typeid->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `moduleid`, `typename` FROM `moduletype`";
$sWhereWrk = "`typename` LIKE '{query_value}%'";
$lookuptblfilter = "`moduleid`=`6`";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_typeid" id="q_x_typeid" value="s=<?php echo TEAencrypt($sSqlWrk) ?>&fn=<?php echo urlencode($album->typeid->LookupFn) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_typeid", falbumedit, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_typeid") + ar[i] : "";
	return dv;
}
oas.ac.typeAhead = false;
falbumedit.AutoSuggests["x_typeid"] = oas;
</script>
</span><?php echo $album->typeid->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($album->publish->Visible) { // publish ?>
	<tr id="r_publish"<?php echo $album->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_album_publish"><?php echo $album->publish->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $album->publish->CellAttributes() ?>><span id="el_album_publish">
<input type="text" name="x_publish" id="x_publish" value="<?php echo $album->publish->EditValue ?>"<?php echo $album->publish->EditAttributes() ?>>
</span><?php echo $album->publish->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<p>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>">
</form>
<script type="text/javascript">
falbumedit.Init();
</script>
<?php
$album_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$album_edit->Page_Terminate();
?>
