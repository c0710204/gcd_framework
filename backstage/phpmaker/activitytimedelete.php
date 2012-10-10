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

$activitytime_delete = NULL; // Initialize page object first

class cactivitytime_delete extends cactivitytime {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{0FEB9E42-0859-45AD-84C6-C2CDD4EB41BE}";

	// Table name
	var $TableName = 'activitytime';

	// Page object name
	var $PageObjName = 'activitytime_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
			$this->Page_Terminate("activitytimelist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in activitytime class, activitytimeinfo.php

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
if (!isset($activitytime_delete)) $activitytime_delete = new cactivitytime_delete();

// Page init
$activitytime_delete->Page_Init();

// Page main
$activitytime_delete->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var activitytime_delete = new ew_Page("activitytime_delete");
activitytime_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = activitytime_delete.PageID; // For backward compatibility

// Form object
var factivitytimedelete = new ew_Form("factivitytimedelete");

// Form_CustomValidate event
factivitytimedelete.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
factivitytimedelete.ValidateRequired = true;
<?php } else { ?>
factivitytimedelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
factivitytimedelete.Lists["x_addressId"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_roomName","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($activitytime_delete->Recordset = $activitytime_delete->LoadRecordset())
	$activitytime_deleteTotalRecs = $activitytime_delete->Recordset->RecordCount(); // Get record count
if ($activitytime_deleteTotalRecs <= 0) { // No record found, exit
	if ($activitytime_delete->Recordset)
		$activitytime_delete->Recordset->Close();
	$activitytime_delete->Page_Terminate("activitytimelist.php"); // Return to list
}
?>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Delete") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $activitytime->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $activitytime->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $activitytime_delete->ShowPageHeader(); ?>
<?php
$activitytime_delete->ShowMessage();
?>
<form name="factivitytimedelete" id="factivitytimedelete" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<p>
<input type="hidden" name="t" value="activitytime">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($activitytime_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_activitytimedelete" class="ewTable ewTableSeparate">
<?php echo $activitytime->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td valign="top"><span id="elh_activitytime_id" class="activitytime_id"><?php echo $activitytime->id->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_activitytime_startime" class="activitytime_startime"><?php echo $activitytime->startime->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_activitytime_endtime" class="activitytime_endtime"><?php echo $activitytime->endtime->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_activitytime_addressId" class="activitytime_addressId"><?php echo $activitytime->addressId->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_activitytime_address" class="activitytime_address"><?php echo $activitytime->address->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$activitytime_delete->RecCnt = 0;
$i = 0;
while (!$activitytime_delete->Recordset->EOF) {
	$activitytime_delete->RecCnt++;
	$activitytime_delete->RowCnt++;

	// Set row properties
	$activitytime->ResetAttrs();
	$activitytime->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$activitytime_delete->LoadRowValues($activitytime_delete->Recordset);

	// Render row
	$activitytime_delete->RenderRow();
?>
	<tr<?php echo $activitytime->RowAttributes() ?>>
		<td<?php echo $activitytime->id->CellAttributes() ?>><span id="el<?php echo $activitytime_delete->RowCnt ?>_activitytime_id" class="activitytime_id">
<span<?php echo $activitytime->id->ViewAttributes() ?>>
<?php echo $activitytime->id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $activitytime->startime->CellAttributes() ?>><span id="el<?php echo $activitytime_delete->RowCnt ?>_activitytime_startime" class="activitytime_startime">
<span<?php echo $activitytime->startime->ViewAttributes() ?>>
<?php echo $activitytime->startime->ListViewValue() ?></span>
</span></td>
		<td<?php echo $activitytime->endtime->CellAttributes() ?>><span id="el<?php echo $activitytime_delete->RowCnt ?>_activitytime_endtime" class="activitytime_endtime">
<span<?php echo $activitytime->endtime->ViewAttributes() ?>>
<?php echo $activitytime->endtime->ListViewValue() ?></span>
</span></td>
		<td<?php echo $activitytime->addressId->CellAttributes() ?>><span id="el<?php echo $activitytime_delete->RowCnt ?>_activitytime_addressId" class="activitytime_addressId">
<span<?php echo $activitytime->addressId->ViewAttributes() ?>>
<?php echo $activitytime->addressId->ListViewValue() ?></span>
</span></td>
		<td<?php echo $activitytime->address->CellAttributes() ?>><span id="el<?php echo $activitytime_delete->RowCnt ?>_activitytime_address" class="activitytime_address">
<span<?php echo $activitytime->address->ViewAttributes() ?>>
<?php echo $activitytime->address->ListViewValue() ?></span>
</span></td>
	</tr>
<?php
	$activitytime_delete->Recordset->MoveNext();
}
$activitytime_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<p>
<input type="submit" name="Action" value="<?php echo ew_BtnCaption($Language->Phrase("DeleteBtn")) ?>">
</form>
<script type="text/javascript">
factivitytimedelete.Init();
</script>
<?php
$activitytime_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$activitytime_delete->Page_Terminate();
?>