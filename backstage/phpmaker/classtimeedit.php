<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "classtimeinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$classtime_edit = NULL; // Initialize page object first

class cclasstime_edit extends cclasstime {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{0FEB9E42-0859-45AD-84C6-C2CDD4EB41BE}";

	// Table name
	var $TableName = 'classtime';

	// Page object name
	var $PageObjName = 'classtime_edit';

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

		// Table object (classtime)
		if (!isset($GLOBALS["classtime"])) {
			$GLOBALS["classtime"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["classtime"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'classtime', TRUE);

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
			$this->Page_Terminate("classtimelist.php"); // Invalid key, return to list
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("classtimelist.php"); // No matching record, return to list
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
		if (!$this->classroomId->FldIsDetailKey) {
			$this->classroomId->setFormValue($objForm->GetValue("x_classroomId"));
		}
		if (!$this->date->FldIsDetailKey) {
			$this->date->setFormValue($objForm->GetValue("x_date"));
			$this->date->CurrentValue = ew_UnFormatDateTime($this->date->CurrentValue, 5);
		}
		if (!$this->courseId1->FldIsDetailKey) {
			$this->courseId1->setFormValue($objForm->GetValue("x_courseId1"));
		}
		if (!$this->courseId2->FldIsDetailKey) {
			$this->courseId2->setFormValue($objForm->GetValue("x_courseId2"));
		}
		if (!$this->courseId3->FldIsDetailKey) {
			$this->courseId3->setFormValue($objForm->GetValue("x_courseId3"));
		}
		if (!$this->courseId4->FldIsDetailKey) {
			$this->courseId4->setFormValue($objForm->GetValue("x_courseId4"));
		}
		if (!$this->courseId5->FldIsDetailKey) {
			$this->courseId5->setFormValue($objForm->GetValue("x_courseId5"));
		}
		if (!$this->courseId6->FldIsDetailKey) {
			$this->courseId6->setFormValue($objForm->GetValue("x_courseId6"));
		}
		if (!$this->courseId7->FldIsDetailKey) {
			$this->courseId7->setFormValue($objForm->GetValue("x_courseId7"));
		}
		if (!$this->courseId8->FldIsDetailKey) {
			$this->courseId8->setFormValue($objForm->GetValue("x_courseId8"));
		}
		if (!$this->courseId9->FldIsDetailKey) {
			$this->courseId9->setFormValue($objForm->GetValue("x_courseId9"));
		}
		if (!$this->courseId10->FldIsDetailKey) {
			$this->courseId10->setFormValue($objForm->GetValue("x_courseId10"));
		}
		if (!$this->courseId11->FldIsDetailKey) {
			$this->courseId11->setFormValue($objForm->GetValue("x_courseId11"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->classroomId->CurrentValue = $this->classroomId->FormValue;
		$this->date->CurrentValue = $this->date->FormValue;
		$this->date->CurrentValue = ew_UnFormatDateTime($this->date->CurrentValue, 5);
		$this->courseId1->CurrentValue = $this->courseId1->FormValue;
		$this->courseId2->CurrentValue = $this->courseId2->FormValue;
		$this->courseId3->CurrentValue = $this->courseId3->FormValue;
		$this->courseId4->CurrentValue = $this->courseId4->FormValue;
		$this->courseId5->CurrentValue = $this->courseId5->FormValue;
		$this->courseId6->CurrentValue = $this->courseId6->FormValue;
		$this->courseId7->CurrentValue = $this->courseId7->FormValue;
		$this->courseId8->CurrentValue = $this->courseId8->FormValue;
		$this->courseId9->CurrentValue = $this->courseId9->FormValue;
		$this->courseId10->CurrentValue = $this->courseId10->FormValue;
		$this->courseId11->CurrentValue = $this->courseId11->FormValue;
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
		$this->classroomId->setDbValue($rs->fields('classroomId'));
		$this->date->setDbValue($rs->fields('date'));
		$this->courseId1->setDbValue($rs->fields('courseId1'));
		$this->courseId2->setDbValue($rs->fields('courseId2'));
		$this->courseId3->setDbValue($rs->fields('courseId3'));
		$this->courseId4->setDbValue($rs->fields('courseId4'));
		$this->courseId5->setDbValue($rs->fields('courseId5'));
		$this->courseId6->setDbValue($rs->fields('courseId6'));
		$this->courseId7->setDbValue($rs->fields('courseId7'));
		$this->courseId8->setDbValue($rs->fields('courseId8'));
		$this->courseId9->setDbValue($rs->fields('courseId9'));
		$this->courseId10->setDbValue($rs->fields('courseId10'));
		$this->courseId11->setDbValue($rs->fields('courseId11'));
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
		// classroomId
		// date
		// courseId1
		// courseId2
		// courseId3
		// courseId4
		// courseId5
		// courseId6
		// courseId7
		// courseId8
		// courseId9
		// courseId10
		// courseId11

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// classroomId
			$this->classroomId->ViewValue = $this->classroomId->CurrentValue;
			$this->classroomId->ViewCustomAttributes = "";

			// date
			$this->date->ViewValue = $this->date->CurrentValue;
			$this->date->ViewValue = ew_FormatDateTime($this->date->ViewValue, 5);
			$this->date->ViewCustomAttributes = "";

			// courseId1
			$this->courseId1->ViewValue = $this->courseId1->CurrentValue;
			$this->courseId1->ViewCustomAttributes = "";

			// courseId2
			$this->courseId2->ViewValue = $this->courseId2->CurrentValue;
			$this->courseId2->ViewCustomAttributes = "";

			// courseId3
			$this->courseId3->ViewValue = $this->courseId3->CurrentValue;
			$this->courseId3->ViewCustomAttributes = "";

			// courseId4
			$this->courseId4->ViewValue = $this->courseId4->CurrentValue;
			$this->courseId4->ViewCustomAttributes = "";

			// courseId5
			$this->courseId5->ViewValue = $this->courseId5->CurrentValue;
			$this->courseId5->ViewCustomAttributes = "";

			// courseId6
			$this->courseId6->ViewValue = $this->courseId6->CurrentValue;
			$this->courseId6->ViewCustomAttributes = "";

			// courseId7
			$this->courseId7->ViewValue = $this->courseId7->CurrentValue;
			$this->courseId7->ViewCustomAttributes = "";

			// courseId8
			$this->courseId8->ViewValue = $this->courseId8->CurrentValue;
			$this->courseId8->ViewCustomAttributes = "";

			// courseId9
			$this->courseId9->ViewValue = $this->courseId9->CurrentValue;
			$this->courseId9->ViewCustomAttributes = "";

			// courseId10
			$this->courseId10->ViewValue = $this->courseId10->CurrentValue;
			$this->courseId10->ViewCustomAttributes = "";

			// courseId11
			$this->courseId11->ViewValue = $this->courseId11->CurrentValue;
			$this->courseId11->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// classroomId
			$this->classroomId->LinkCustomAttributes = "";
			$this->classroomId->HrefValue = "";
			$this->classroomId->TooltipValue = "";

			// date
			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";
			$this->date->TooltipValue = "";

			// courseId1
			$this->courseId1->LinkCustomAttributes = "";
			$this->courseId1->HrefValue = "";
			$this->courseId1->TooltipValue = "";

			// courseId2
			$this->courseId2->LinkCustomAttributes = "";
			$this->courseId2->HrefValue = "";
			$this->courseId2->TooltipValue = "";

			// courseId3
			$this->courseId3->LinkCustomAttributes = "";
			$this->courseId3->HrefValue = "";
			$this->courseId3->TooltipValue = "";

			// courseId4
			$this->courseId4->LinkCustomAttributes = "";
			$this->courseId4->HrefValue = "";
			$this->courseId4->TooltipValue = "";

			// courseId5
			$this->courseId5->LinkCustomAttributes = "";
			$this->courseId5->HrefValue = "";
			$this->courseId5->TooltipValue = "";

			// courseId6
			$this->courseId6->LinkCustomAttributes = "";
			$this->courseId6->HrefValue = "";
			$this->courseId6->TooltipValue = "";

			// courseId7
			$this->courseId7->LinkCustomAttributes = "";
			$this->courseId7->HrefValue = "";
			$this->courseId7->TooltipValue = "";

			// courseId8
			$this->courseId8->LinkCustomAttributes = "";
			$this->courseId8->HrefValue = "";
			$this->courseId8->TooltipValue = "";

			// courseId9
			$this->courseId9->LinkCustomAttributes = "";
			$this->courseId9->HrefValue = "";
			$this->courseId9->TooltipValue = "";

			// courseId10
			$this->courseId10->LinkCustomAttributes = "";
			$this->courseId10->HrefValue = "";
			$this->courseId10->TooltipValue = "";

			// courseId11
			$this->courseId11->LinkCustomAttributes = "";
			$this->courseId11->HrefValue = "";
			$this->courseId11->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// classroomId
			$this->classroomId->EditCustomAttributes = "";
			$this->classroomId->EditValue = ew_HtmlEncode($this->classroomId->CurrentValue);

			// date
			$this->date->EditCustomAttributes = "";
			$this->date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date->CurrentValue, 5));

			// courseId1
			$this->courseId1->EditCustomAttributes = "";
			$this->courseId1->EditValue = ew_HtmlEncode($this->courseId1->CurrentValue);

			// courseId2
			$this->courseId2->EditCustomAttributes = "";
			$this->courseId2->EditValue = ew_HtmlEncode($this->courseId2->CurrentValue);

			// courseId3
			$this->courseId3->EditCustomAttributes = "";
			$this->courseId3->EditValue = ew_HtmlEncode($this->courseId3->CurrentValue);

			// courseId4
			$this->courseId4->EditCustomAttributes = "";
			$this->courseId4->EditValue = ew_HtmlEncode($this->courseId4->CurrentValue);

			// courseId5
			$this->courseId5->EditCustomAttributes = "";
			$this->courseId5->EditValue = ew_HtmlEncode($this->courseId5->CurrentValue);

			// courseId6
			$this->courseId6->EditCustomAttributes = "";
			$this->courseId6->EditValue = ew_HtmlEncode($this->courseId6->CurrentValue);

			// courseId7
			$this->courseId7->EditCustomAttributes = "";
			$this->courseId7->EditValue = ew_HtmlEncode($this->courseId7->CurrentValue);

			// courseId8
			$this->courseId8->EditCustomAttributes = "";
			$this->courseId8->EditValue = ew_HtmlEncode($this->courseId8->CurrentValue);

			// courseId9
			$this->courseId9->EditCustomAttributes = "";
			$this->courseId9->EditValue = ew_HtmlEncode($this->courseId9->CurrentValue);

			// courseId10
			$this->courseId10->EditCustomAttributes = "";
			$this->courseId10->EditValue = ew_HtmlEncode($this->courseId10->CurrentValue);

			// courseId11
			$this->courseId11->EditCustomAttributes = "";
			$this->courseId11->EditValue = ew_HtmlEncode($this->courseId11->CurrentValue);

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// classroomId
			$this->classroomId->HrefValue = "";

			// date
			$this->date->HrefValue = "";

			// courseId1
			$this->courseId1->HrefValue = "";

			// courseId2
			$this->courseId2->HrefValue = "";

			// courseId3
			$this->courseId3->HrefValue = "";

			// courseId4
			$this->courseId4->HrefValue = "";

			// courseId5
			$this->courseId5->HrefValue = "";

			// courseId6
			$this->courseId6->HrefValue = "";

			// courseId7
			$this->courseId7->HrefValue = "";

			// courseId8
			$this->courseId8->HrefValue = "";

			// courseId9
			$this->courseId9->HrefValue = "";

			// courseId10
			$this->courseId10->HrefValue = "";

			// courseId11
			$this->courseId11->HrefValue = "";
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
		if (!ew_CheckInteger($this->classroomId->FormValue)) {
			ew_AddMessage($gsFormError, $this->classroomId->FldErrMsg());
		}
		if (!ew_CheckDate($this->date->FormValue)) {
			ew_AddMessage($gsFormError, $this->date->FldErrMsg());
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

			// classroomId
			$this->classroomId->SetDbValueDef($rsnew, $this->classroomId->CurrentValue, NULL, $this->classroomId->ReadOnly);

			// date
			$this->date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date->CurrentValue, 5), NULL, $this->date->ReadOnly);

			// courseId1
			$this->courseId1->SetDbValueDef($rsnew, $this->courseId1->CurrentValue, NULL, $this->courseId1->ReadOnly);

			// courseId2
			$this->courseId2->SetDbValueDef($rsnew, $this->courseId2->CurrentValue, NULL, $this->courseId2->ReadOnly);

			// courseId3
			$this->courseId3->SetDbValueDef($rsnew, $this->courseId3->CurrentValue, NULL, $this->courseId3->ReadOnly);

			// courseId4
			$this->courseId4->SetDbValueDef($rsnew, $this->courseId4->CurrentValue, NULL, $this->courseId4->ReadOnly);

			// courseId5
			$this->courseId5->SetDbValueDef($rsnew, $this->courseId5->CurrentValue, NULL, $this->courseId5->ReadOnly);

			// courseId6
			$this->courseId6->SetDbValueDef($rsnew, $this->courseId6->CurrentValue, NULL, $this->courseId6->ReadOnly);

			// courseId7
			$this->courseId7->SetDbValueDef($rsnew, $this->courseId7->CurrentValue, NULL, $this->courseId7->ReadOnly);

			// courseId8
			$this->courseId8->SetDbValueDef($rsnew, $this->courseId8->CurrentValue, NULL, $this->courseId8->ReadOnly);

			// courseId9
			$this->courseId9->SetDbValueDef($rsnew, $this->courseId9->CurrentValue, NULL, $this->courseId9->ReadOnly);

			// courseId10
			$this->courseId10->SetDbValueDef($rsnew, $this->courseId10->CurrentValue, NULL, $this->courseId10->ReadOnly);

			// courseId11
			$this->courseId11->SetDbValueDef($rsnew, $this->courseId11->CurrentValue, NULL, $this->courseId11->ReadOnly);

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
if (!isset($classtime_edit)) $classtime_edit = new cclasstime_edit();

// Page init
$classtime_edit->Page_Init();

// Page main
$classtime_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var classtime_edit = new ew_Page("classtime_edit");
classtime_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = classtime_edit.PageID; // For backward compatibility

// Form object
var fclasstimeedit = new ew_Form("fclasstimeedit");

// Validate form
fclasstimeedit.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_classroomId"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($classtime->classroomId->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_date"];
		if (elm && !ew_CheckDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($classtime->date->FldErrMsg()) ?>");

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
fclasstimeedit.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fclasstimeedit.ValidateRequired = true;
<?php } else { ?>
fclasstimeedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $classtime->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $classtime->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $classtime_edit->ShowPageHeader(); ?>
<?php
$classtime_edit->ShowMessage();
?>
<form name="fclasstimeedit" id="fclasstimeedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<p>
<input type="hidden" name="t" value="classtime">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_classtimeedit" class="ewTable">
<?php if ($classtime->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_id"><?php echo $classtime->id->FldCaption() ?></span></td>
		<td<?php echo $classtime->id->CellAttributes() ?>><span id="el_classtime_id">
<span<?php echo $classtime->id->ViewAttributes() ?>>
<?php echo $classtime->id->EditValue ?></span>
<input type="hidden" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($classtime->id->CurrentValue) ?>">
</span><?php echo $classtime->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($classtime->classroomId->Visible) { // classroomId ?>
	<tr id="r_classroomId"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_classroomId"><?php echo $classtime->classroomId->FldCaption() ?></span></td>
		<td<?php echo $classtime->classroomId->CellAttributes() ?>><span id="el_classtime_classroomId">
<input type="text" name="x_classroomId" id="x_classroomId" size="30" value="<?php echo $classtime->classroomId->EditValue ?>"<?php echo $classtime->classroomId->EditAttributes() ?>>
</span><?php echo $classtime->classroomId->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($classtime->date->Visible) { // date ?>
	<tr id="r_date"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_date"><?php echo $classtime->date->FldCaption() ?></span></td>
		<td<?php echo $classtime->date->CellAttributes() ?>><span id="el_classtime_date">
<input type="text" name="x_date" id="x_date" value="<?php echo $classtime->date->EditValue ?>"<?php echo $classtime->date->EditAttributes() ?>>
</span><?php echo $classtime->date->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($classtime->courseId1->Visible) { // courseId1 ?>
	<tr id="r_courseId1"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_courseId1"><?php echo $classtime->courseId1->FldCaption() ?></span></td>
		<td<?php echo $classtime->courseId1->CellAttributes() ?>><span id="el_classtime_courseId1">
<input type="text" name="x_courseId1" id="x_courseId1" size="30" maxlength="10" value="<?php echo $classtime->courseId1->EditValue ?>"<?php echo $classtime->courseId1->EditAttributes() ?>>
</span><?php echo $classtime->courseId1->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($classtime->courseId2->Visible) { // courseId2 ?>
	<tr id="r_courseId2"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_courseId2"><?php echo $classtime->courseId2->FldCaption() ?></span></td>
		<td<?php echo $classtime->courseId2->CellAttributes() ?>><span id="el_classtime_courseId2">
<input type="text" name="x_courseId2" id="x_courseId2" size="30" maxlength="10" value="<?php echo $classtime->courseId2->EditValue ?>"<?php echo $classtime->courseId2->EditAttributes() ?>>
</span><?php echo $classtime->courseId2->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($classtime->courseId3->Visible) { // courseId3 ?>
	<tr id="r_courseId3"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_courseId3"><?php echo $classtime->courseId3->FldCaption() ?></span></td>
		<td<?php echo $classtime->courseId3->CellAttributes() ?>><span id="el_classtime_courseId3">
<input type="text" name="x_courseId3" id="x_courseId3" size="30" maxlength="10" value="<?php echo $classtime->courseId3->EditValue ?>"<?php echo $classtime->courseId3->EditAttributes() ?>>
</span><?php echo $classtime->courseId3->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($classtime->courseId4->Visible) { // courseId4 ?>
	<tr id="r_courseId4"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_courseId4"><?php echo $classtime->courseId4->FldCaption() ?></span></td>
		<td<?php echo $classtime->courseId4->CellAttributes() ?>><span id="el_classtime_courseId4">
<input type="text" name="x_courseId4" id="x_courseId4" size="30" maxlength="10" value="<?php echo $classtime->courseId4->EditValue ?>"<?php echo $classtime->courseId4->EditAttributes() ?>>
</span><?php echo $classtime->courseId4->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($classtime->courseId5->Visible) { // courseId5 ?>
	<tr id="r_courseId5"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_courseId5"><?php echo $classtime->courseId5->FldCaption() ?></span></td>
		<td<?php echo $classtime->courseId5->CellAttributes() ?>><span id="el_classtime_courseId5">
<input type="text" name="x_courseId5" id="x_courseId5" size="30" maxlength="10" value="<?php echo $classtime->courseId5->EditValue ?>"<?php echo $classtime->courseId5->EditAttributes() ?>>
</span><?php echo $classtime->courseId5->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($classtime->courseId6->Visible) { // courseId6 ?>
	<tr id="r_courseId6"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_courseId6"><?php echo $classtime->courseId6->FldCaption() ?></span></td>
		<td<?php echo $classtime->courseId6->CellAttributes() ?>><span id="el_classtime_courseId6">
<input type="text" name="x_courseId6" id="x_courseId6" size="30" maxlength="10" value="<?php echo $classtime->courseId6->EditValue ?>"<?php echo $classtime->courseId6->EditAttributes() ?>>
</span><?php echo $classtime->courseId6->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($classtime->courseId7->Visible) { // courseId7 ?>
	<tr id="r_courseId7"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_courseId7"><?php echo $classtime->courseId7->FldCaption() ?></span></td>
		<td<?php echo $classtime->courseId7->CellAttributes() ?>><span id="el_classtime_courseId7">
<input type="text" name="x_courseId7" id="x_courseId7" size="30" maxlength="10" value="<?php echo $classtime->courseId7->EditValue ?>"<?php echo $classtime->courseId7->EditAttributes() ?>>
</span><?php echo $classtime->courseId7->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($classtime->courseId8->Visible) { // courseId8 ?>
	<tr id="r_courseId8"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_courseId8"><?php echo $classtime->courseId8->FldCaption() ?></span></td>
		<td<?php echo $classtime->courseId8->CellAttributes() ?>><span id="el_classtime_courseId8">
<input type="text" name="x_courseId8" id="x_courseId8" size="30" maxlength="10" value="<?php echo $classtime->courseId8->EditValue ?>"<?php echo $classtime->courseId8->EditAttributes() ?>>
</span><?php echo $classtime->courseId8->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($classtime->courseId9->Visible) { // courseId9 ?>
	<tr id="r_courseId9"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_courseId9"><?php echo $classtime->courseId9->FldCaption() ?></span></td>
		<td<?php echo $classtime->courseId9->CellAttributes() ?>><span id="el_classtime_courseId9">
<input type="text" name="x_courseId9" id="x_courseId9" size="30" maxlength="10" value="<?php echo $classtime->courseId9->EditValue ?>"<?php echo $classtime->courseId9->EditAttributes() ?>>
</span><?php echo $classtime->courseId9->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($classtime->courseId10->Visible) { // courseId10 ?>
	<tr id="r_courseId10"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_courseId10"><?php echo $classtime->courseId10->FldCaption() ?></span></td>
		<td<?php echo $classtime->courseId10->CellAttributes() ?>><span id="el_classtime_courseId10">
<input type="text" name="x_courseId10" id="x_courseId10" size="30" maxlength="10" value="<?php echo $classtime->courseId10->EditValue ?>"<?php echo $classtime->courseId10->EditAttributes() ?>>
</span><?php echo $classtime->courseId10->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($classtime->courseId11->Visible) { // courseId11 ?>
	<tr id="r_courseId11"<?php echo $classtime->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_classtime_courseId11"><?php echo $classtime->courseId11->FldCaption() ?></span></td>
		<td<?php echo $classtime->courseId11->CellAttributes() ?>><span id="el_classtime_courseId11">
<input type="text" name="x_courseId11" id="x_courseId11" size="30" maxlength="10" value="<?php echo $classtime->courseId11->EditValue ?>"<?php echo $classtime->courseId11->EditAttributes() ?>>
</span><?php echo $classtime->courseId11->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<p>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>">
</form>
<script type="text/javascript">
fclasstimeedit.Init();
</script>
<?php
$classtime_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$classtime_edit->Page_Terminate();
?>
