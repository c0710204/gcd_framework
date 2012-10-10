<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "courseinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$course_delete = NULL; // Initialize page object first

class ccourse_delete extends ccourse {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{0FEB9E42-0859-45AD-84C6-C2CDD4EB41BE}";

	// Table name
	var $TableName = 'course';

	// Page object name
	var $PageObjName = 'course_delete';

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

		// Table object (course)
		if (!isset($GLOBALS["course"])) {
			$GLOBALS["course"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["course"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'course', TRUE);

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
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("courselist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in course class, courseinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Call Recordset Selecting event
		$this->Recordset_Selecting($this->CurrentFilter);

		// Load List page SQL
		$sSql = $this->SelectSQL();
		if ($offset > -1 && $rowcnt > -1)
			$sSql .= " LIMIT $rowcnt OFFSET $offset";

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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
		$this->courseName->setDbValue($rs->fields('courseName'));
		$this->courseEngName->setDbValue($rs->fields('courseEngName'));
		$this->courseCode->setDbValue($rs->fields('courseCode'));
		$this->courseInfo->setDbValue($rs->fields('courseInfo'));
		$this->courseXs->setDbValue($rs->fields('courseXs'));
		$this->courseXf->setDbValue($rs->fields('courseXf'));
		$this->courseXz->setDbValue($rs->fields('courseXz'));
		$this->courseLb->setDbValue($rs->fields('courseLb'));
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->courseXs->FormValue == $this->courseXs->CurrentValue)
			$this->courseXs->CurrentValue = ew_StrToFloat($this->courseXs->CurrentValue);

		// Convert decimal values if posted back
		if ($this->courseXf->FormValue == $this->courseXf->CurrentValue)
			$this->courseXf->CurrentValue = ew_StrToFloat($this->courseXf->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// courseName
		// courseEngName
		// courseCode
		// courseInfo
		// courseXs
		// courseXf
		// courseXz
		// courseLb

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// courseName
			$this->courseName->ViewValue = $this->courseName->CurrentValue;
			$this->courseName->ViewCustomAttributes = "";

			// courseEngName
			$this->courseEngName->ViewValue = $this->courseEngName->CurrentValue;
			$this->courseEngName->ViewCustomAttributes = "";

			// courseCode
			$this->courseCode->ViewValue = $this->courseCode->CurrentValue;
			$this->courseCode->ViewCustomAttributes = "";

			// courseXs
			$this->courseXs->ViewValue = $this->courseXs->CurrentValue;
			$this->courseXs->ViewCustomAttributes = "";

			// courseXf
			$this->courseXf->ViewValue = $this->courseXf->CurrentValue;
			$this->courseXf->ViewCustomAttributes = "";

			// courseXz
			$this->courseXz->ViewValue = $this->courseXz->CurrentValue;
			$this->courseXz->ViewCustomAttributes = "";

			// courseLb
			$this->courseLb->ViewValue = $this->courseLb->CurrentValue;
			$this->courseLb->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// courseName
			$this->courseName->LinkCustomAttributes = "";
			$this->courseName->HrefValue = "";
			$this->courseName->TooltipValue = "";

			// courseEngName
			$this->courseEngName->LinkCustomAttributes = "";
			$this->courseEngName->HrefValue = "";
			$this->courseEngName->TooltipValue = "";

			// courseCode
			$this->courseCode->LinkCustomAttributes = "";
			$this->courseCode->HrefValue = "";
			$this->courseCode->TooltipValue = "";

			// courseXs
			$this->courseXs->LinkCustomAttributes = "";
			$this->courseXs->HrefValue = "";
			$this->courseXs->TooltipValue = "";

			// courseXf
			$this->courseXf->LinkCustomAttributes = "";
			$this->courseXf->HrefValue = "";
			$this->courseXf->TooltipValue = "";

			// courseXz
			$this->courseXz->LinkCustomAttributes = "";
			$this->courseXz->HrefValue = "";
			$this->courseXz->TooltipValue = "";

			// courseLb
			$this->courseLb->LinkCustomAttributes = "";
			$this->courseLb->HrefValue = "";
			$this->courseLb->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;
		} else {
			$this->LoadRowValues($rs); // Load row values
		}
		$conn->BeginTrans();

		// Clone old rows
		$rsold = ($rs) ? $rs->GetRows() : array();
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id'];
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $conn->Execute($this->DeleteSQL($row)); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($course_delete)) $course_delete = new ccourse_delete();

// Page init
$course_delete->Page_Init();

// Page main
$course_delete->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var course_delete = new ew_Page("course_delete");
course_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = course_delete.PageID; // For backward compatibility

// Form object
var fcoursedelete = new ew_Form("fcoursedelete");

// Form_CustomValidate event
fcoursedelete.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcoursedelete.ValidateRequired = true;
<?php } else { ?>
fcoursedelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($course_delete->Recordset = $course_delete->LoadRecordset())
	$course_deleteTotalRecs = $course_delete->Recordset->RecordCount(); // Get record count
if ($course_deleteTotalRecs <= 0) { // No record found, exit
	if ($course_delete->Recordset)
		$course_delete->Recordset->Close();
	$course_delete->Page_Terminate("courselist.php"); // Return to list
}
?>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Delete") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $course->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $course->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $course_delete->ShowPageHeader(); ?>
<?php
$course_delete->ShowMessage();
?>
<form name="fcoursedelete" id="fcoursedelete" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<p>
<input type="hidden" name="t" value="course">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($course_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_coursedelete" class="ewTable ewTableSeparate">
<?php echo $course->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td valign="top"><span id="elh_course_id" class="course_id"><?php echo $course->id->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_course_courseName" class="course_courseName"><?php echo $course->courseName->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_course_courseEngName" class="course_courseEngName"><?php echo $course->courseEngName->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_course_courseCode" class="course_courseCode"><?php echo $course->courseCode->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_course_courseXs" class="course_courseXs"><?php echo $course->courseXs->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_course_courseXf" class="course_courseXf"><?php echo $course->courseXf->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_course_courseXz" class="course_courseXz"><?php echo $course->courseXz->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_course_courseLb" class="course_courseLb"><?php echo $course->courseLb->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$course_delete->RecCnt = 0;
$i = 0;
while (!$course_delete->Recordset->EOF) {
	$course_delete->RecCnt++;
	$course_delete->RowCnt++;

	// Set row properties
	$course->ResetAttrs();
	$course->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$course_delete->LoadRowValues($course_delete->Recordset);

	// Render row
	$course_delete->RenderRow();
?>
	<tr<?php echo $course->RowAttributes() ?>>
		<td<?php echo $course->id->CellAttributes() ?>><span id="el<?php echo $course_delete->RowCnt ?>_course_id" class="course_id">
<span<?php echo $course->id->ViewAttributes() ?>>
<?php echo $course->id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $course->courseName->CellAttributes() ?>><span id="el<?php echo $course_delete->RowCnt ?>_course_courseName" class="course_courseName">
<span<?php echo $course->courseName->ViewAttributes() ?>>
<?php echo $course->courseName->ListViewValue() ?></span>
</span></td>
		<td<?php echo $course->courseEngName->CellAttributes() ?>><span id="el<?php echo $course_delete->RowCnt ?>_course_courseEngName" class="course_courseEngName">
<span<?php echo $course->courseEngName->ViewAttributes() ?>>
<?php echo $course->courseEngName->ListViewValue() ?></span>
</span></td>
		<td<?php echo $course->courseCode->CellAttributes() ?>><span id="el<?php echo $course_delete->RowCnt ?>_course_courseCode" class="course_courseCode">
<span<?php echo $course->courseCode->ViewAttributes() ?>>
<?php echo $course->courseCode->ListViewValue() ?></span>
</span></td>
		<td<?php echo $course->courseXs->CellAttributes() ?>><span id="el<?php echo $course_delete->RowCnt ?>_course_courseXs" class="course_courseXs">
<span<?php echo $course->courseXs->ViewAttributes() ?>>
<?php echo $course->courseXs->ListViewValue() ?></span>
</span></td>
		<td<?php echo $course->courseXf->CellAttributes() ?>><span id="el<?php echo $course_delete->RowCnt ?>_course_courseXf" class="course_courseXf">
<span<?php echo $course->courseXf->ViewAttributes() ?>>
<?php echo $course->courseXf->ListViewValue() ?></span>
</span></td>
		<td<?php echo $course->courseXz->CellAttributes() ?>><span id="el<?php echo $course_delete->RowCnt ?>_course_courseXz" class="course_courseXz">
<span<?php echo $course->courseXz->ViewAttributes() ?>>
<?php echo $course->courseXz->ListViewValue() ?></span>
</span></td>
		<td<?php echo $course->courseLb->CellAttributes() ?>><span id="el<?php echo $course_delete->RowCnt ?>_course_courseLb" class="course_courseLb">
<span<?php echo $course->courseLb->ViewAttributes() ?>>
<?php echo $course->courseLb->ListViewValue() ?></span>
</span></td>
	</tr>
<?php
	$course_delete->Recordset->MoveNext();
}
$course_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<p>
<input type="submit" name="Action" value="<?php echo ew_BtnCaption($Language->Phrase("DeleteBtn")) ?>">
</form>
<script type="text/javascript">
fcoursedelete.Init();
</script>
<?php
$course_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$course_delete->Page_Terminate();
?>
