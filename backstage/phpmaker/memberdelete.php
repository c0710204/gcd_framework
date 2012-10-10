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

$member_delete = NULL; // Initialize page object first

class cmember_delete extends cmember {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{0FEB9E42-0859-45AD-84C6-C2CDD4EB41BE}";

	// Table name
	var $TableName = 'member';

	// Page object name
	var $PageObjName = 'member_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
			$this->Page_Terminate("memberlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in member class, memberinfo.php

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
if (!isset($member_delete)) $member_delete = new cmember_delete();

// Page init
$member_delete->Page_Init();

// Page main
$member_delete->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var member_delete = new ew_Page("member_delete");
member_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = member_delete.PageID; // For backward compatibility

// Form object
var fmemberdelete = new ew_Form("fmemberdelete");

// Form_CustomValidate event
fmemberdelete.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmemberdelete.ValidateRequired = true;
<?php } else { ?>
fmemberdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($member_delete->Recordset = $member_delete->LoadRecordset())
	$member_deleteTotalRecs = $member_delete->Recordset->RecordCount(); // Get record count
if ($member_deleteTotalRecs <= 0) { // No record found, exit
	if ($member_delete->Recordset)
		$member_delete->Recordset->Close();
	$member_delete->Page_Terminate("memberlist.php"); // Return to list
}
?>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Delete") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $member->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $member->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $member_delete->ShowPageHeader(); ?>
<?php
$member_delete->ShowMessage();
?>
<form name="fmemberdelete" id="fmemberdelete" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<p>
<input type="hidden" name="t" value="member">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($member_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_memberdelete" class="ewTable ewTableSeparate">
<?php echo $member->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td valign="top"><span id="elh_member_id" class="member_id"><?php echo $member->id->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_member__userid" class="member__userid"><?php echo $member->_userid->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_member_username" class="member_username"><?php echo $member->username->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_member_idtype" class="member_idtype"><?php echo $member->idtype->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_member_MasPass" class="member_MasPass"><?php echo $member->MasPass->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_member_accessToken" class="member_accessToken"><?php echo $member->accessToken->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$member_delete->RecCnt = 0;
$i = 0;
while (!$member_delete->Recordset->EOF) {
	$member_delete->RecCnt++;
	$member_delete->RowCnt++;

	// Set row properties
	$member->ResetAttrs();
	$member->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$member_delete->LoadRowValues($member_delete->Recordset);

	// Render row
	$member_delete->RenderRow();
?>
	<tr<?php echo $member->RowAttributes() ?>>
		<td<?php echo $member->id->CellAttributes() ?>><span id="el<?php echo $member_delete->RowCnt ?>_member_id" class="member_id">
<span<?php echo $member->id->ViewAttributes() ?>>
<?php echo $member->id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $member->_userid->CellAttributes() ?>><span id="el<?php echo $member_delete->RowCnt ?>_member__userid" class="member__userid">
<span<?php echo $member->_userid->ViewAttributes() ?>>
<?php echo $member->_userid->ListViewValue() ?></span>
</span></td>
		<td<?php echo $member->username->CellAttributes() ?>><span id="el<?php echo $member_delete->RowCnt ?>_member_username" class="member_username">
<span<?php echo $member->username->ViewAttributes() ?>>
<?php echo $member->username->ListViewValue() ?></span>
</span></td>
		<td<?php echo $member->idtype->CellAttributes() ?>><span id="el<?php echo $member_delete->RowCnt ?>_member_idtype" class="member_idtype">
<span<?php echo $member->idtype->ViewAttributes() ?>>
<?php echo $member->idtype->ListViewValue() ?></span>
</span></td>
		<td<?php echo $member->MasPass->CellAttributes() ?>><span id="el<?php echo $member_delete->RowCnt ?>_member_MasPass" class="member_MasPass">
<span<?php echo $member->MasPass->ViewAttributes() ?>>
<?php echo $member->MasPass->ListViewValue() ?></span>
</span></td>
		<td<?php echo $member->accessToken->CellAttributes() ?>><span id="el<?php echo $member_delete->RowCnt ?>_member_accessToken" class="member_accessToken">
<span<?php echo $member->accessToken->ViewAttributes() ?>>
<?php echo $member->accessToken->ListViewValue() ?></span>
</span></td>
	</tr>
<?php
	$member_delete->Recordset->MoveNext();
}
$member_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<p>
<input type="submit" name="Action" value="<?php echo ew_BtnCaption($Language->Phrase("DeleteBtn")) ?>">
</form>
<script type="text/javascript">
fmemberdelete.Init();
</script>
<?php
$member_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$member_delete->Page_Terminate();
?>
