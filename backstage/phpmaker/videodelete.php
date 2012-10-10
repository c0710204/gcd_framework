<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "videoinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$video_delete = NULL; // Initialize page object first

class cvideo_delete extends cvideo {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{0FEB9E42-0859-45AD-84C6-C2CDD4EB41BE}";

	// Table name
	var $TableName = 'video';

	// Page object name
	var $PageObjName = 'video_delete';

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

		// Table object (video)
		if (!isset($GLOBALS["video"])) {
			$GLOBALS["video"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["video"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'video', TRUE);

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
			$this->Page_Terminate("videolist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in video class, videoinfo.php

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
		$this->title->setDbValue($rs->fields('title'));
		$this->source->setDbValue($rs->fields('source'));
		$this->intro->setDbValue($rs->fields('intro'));
		$this->cover->setDbValue($rs->fields('cover'));
		$this->publish->setDbValue($rs->fields('publish'));
		$this->time->setDbValue($rs->fields('time'));
		$this->size->setDbValue($rs->fields('size'));
		$this->rate->setDbValue($rs->fields('rate'));
		$this->count->setDbValue($rs->fields('count'));
		$this->typeid->setDbValue($rs->fields('typeid'));
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->rate->FormValue == $this->rate->CurrentValue)
			$this->rate->CurrentValue = ew_StrToFloat($this->rate->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// title
		// source
		// intro
		// cover
		// publish
		// time
		// size
		// rate
		// count
		// typeid

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// title
			$this->title->ViewValue = $this->title->CurrentValue;
			$this->title->ViewCustomAttributes = "";

			// source
			$this->source->ViewValue = $this->source->CurrentValue;
			$this->source->ViewCustomAttributes = "";

			// cover
			$this->cover->ViewValue = $this->cover->CurrentValue;
			$this->cover->ViewCustomAttributes = "";

			// publish
			$this->publish->ViewValue = $this->publish->CurrentValue;
			$this->publish->ViewValue = ew_FormatDateTime($this->publish->ViewValue, 5);
			$this->publish->ViewCustomAttributes = "";

			// time
			$this->time->ViewValue = $this->time->CurrentValue;
			$this->time->ViewCustomAttributes = "";

			// size
			$this->size->ViewValue = $this->size->CurrentValue;
			$this->size->ViewCustomAttributes = "";

			// rate
			$this->rate->ViewValue = $this->rate->CurrentValue;
			$this->rate->ViewCustomAttributes = "";

			// count
			$this->count->ViewValue = $this->count->CurrentValue;
			$this->count->ViewCustomAttributes = "";

			// typeid
			$this->typeid->ViewValue = $this->typeid->CurrentValue;
			if (strval($this->typeid->CurrentValue) <> "") {
				$sFilterWrk = "`moduleid`" . ew_SearchString("=", $this->typeid->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `moduleid`, `typename` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `moduletype`";
			$sWhereWrk = "";
			$lookuptblfilter = "`moduleid`=`5`";
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

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// title
			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";
			$this->title->TooltipValue = "";

			// source
			$this->source->LinkCustomAttributes = "";
			$this->source->HrefValue = "";
			$this->source->TooltipValue = "";

			// cover
			$this->cover->LinkCustomAttributes = "";
			$this->cover->HrefValue = "";
			$this->cover->TooltipValue = "";

			// publish
			$this->publish->LinkCustomAttributes = "";
			$this->publish->HrefValue = "";
			$this->publish->TooltipValue = "";

			// time
			$this->time->LinkCustomAttributes = "";
			$this->time->HrefValue = "";
			$this->time->TooltipValue = "";

			// size
			$this->size->LinkCustomAttributes = "";
			$this->size->HrefValue = "";
			$this->size->TooltipValue = "";

			// rate
			$this->rate->LinkCustomAttributes = "";
			$this->rate->HrefValue = "";
			$this->rate->TooltipValue = "";

			// count
			$this->count->LinkCustomAttributes = "";
			$this->count->HrefValue = "";
			$this->count->TooltipValue = "";

			// typeid
			$this->typeid->LinkCustomAttributes = "";
			$this->typeid->HrefValue = "";
			$this->typeid->TooltipValue = "";
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
if (!isset($video_delete)) $video_delete = new cvideo_delete();

// Page init
$video_delete->Page_Init();

// Page main
$video_delete->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var video_delete = new ew_Page("video_delete");
video_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = video_delete.PageID; // For backward compatibility

// Form object
var fvideodelete = new ew_Form("fvideodelete");

// Form_CustomValidate event
fvideodelete.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvideodelete.ValidateRequired = true;
<?php } else { ?>
fvideodelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fvideodelete.Lists["x_typeid"] = {"LinkField":"x_moduleid","Ajax":true,"AutoFill":false,"DisplayFields":["x_typename","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($video_delete->Recordset = $video_delete->LoadRecordset())
	$video_deleteTotalRecs = $video_delete->Recordset->RecordCount(); // Get record count
if ($video_deleteTotalRecs <= 0) { // No record found, exit
	if ($video_delete->Recordset)
		$video_delete->Recordset->Close();
	$video_delete->Page_Terminate("videolist.php"); // Return to list
}
?>
<p><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Delete") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $video->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $video->getReturnUrl() ?>"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $video_delete->ShowPageHeader(); ?>
<?php
$video_delete->ShowMessage();
?>
<form name="fvideodelete" id="fvideodelete" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<p>
<input type="hidden" name="t" value="video">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($video_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table cellspacing="0" id="tbl_videodelete" class="ewTable ewTableSeparate">
<?php echo $video->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td valign="top"><span id="elh_video_id" class="video_id"><?php echo $video->id->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_video_title" class="video_title"><?php echo $video->title->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_video_source" class="video_source"><?php echo $video->source->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_video_cover" class="video_cover"><?php echo $video->cover->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_video_publish" class="video_publish"><?php echo $video->publish->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_video_time" class="video_time"><?php echo $video->time->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_video_size" class="video_size"><?php echo $video->size->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_video_rate" class="video_rate"><?php echo $video->rate->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_video_count" class="video_count"><?php echo $video->count->FldCaption() ?></span></td>
		<td valign="top"><span id="elh_video_typeid" class="video_typeid"><?php echo $video->typeid->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$video_delete->RecCnt = 0;
$i = 0;
while (!$video_delete->Recordset->EOF) {
	$video_delete->RecCnt++;
	$video_delete->RowCnt++;

	// Set row properties
	$video->ResetAttrs();
	$video->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$video_delete->LoadRowValues($video_delete->Recordset);

	// Render row
	$video_delete->RenderRow();
?>
	<tr<?php echo $video->RowAttributes() ?>>
		<td<?php echo $video->id->CellAttributes() ?>><span id="el<?php echo $video_delete->RowCnt ?>_video_id" class="video_id">
<span<?php echo $video->id->ViewAttributes() ?>>
<?php echo $video->id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $video->title->CellAttributes() ?>><span id="el<?php echo $video_delete->RowCnt ?>_video_title" class="video_title">
<span<?php echo $video->title->ViewAttributes() ?>>
<?php echo $video->title->ListViewValue() ?></span>
</span></td>
		<td<?php echo $video->source->CellAttributes() ?>><span id="el<?php echo $video_delete->RowCnt ?>_video_source" class="video_source">
<span<?php echo $video->source->ViewAttributes() ?>>
<?php echo $video->source->ListViewValue() ?></span>
</span></td>
		<td<?php echo $video->cover->CellAttributes() ?>><span id="el<?php echo $video_delete->RowCnt ?>_video_cover" class="video_cover">
<span<?php echo $video->cover->ViewAttributes() ?>>
<?php echo $video->cover->ListViewValue() ?></span>
</span></td>
		<td<?php echo $video->publish->CellAttributes() ?>><span id="el<?php echo $video_delete->RowCnt ?>_video_publish" class="video_publish">
<span<?php echo $video->publish->ViewAttributes() ?>>
<?php echo $video->publish->ListViewValue() ?></span>
</span></td>
		<td<?php echo $video->time->CellAttributes() ?>><span id="el<?php echo $video_delete->RowCnt ?>_video_time" class="video_time">
<span<?php echo $video->time->ViewAttributes() ?>>
<?php echo $video->time->ListViewValue() ?></span>
</span></td>
		<td<?php echo $video->size->CellAttributes() ?>><span id="el<?php echo $video_delete->RowCnt ?>_video_size" class="video_size">
<span<?php echo $video->size->ViewAttributes() ?>>
<?php echo $video->size->ListViewValue() ?></span>
</span></td>
		<td<?php echo $video->rate->CellAttributes() ?>><span id="el<?php echo $video_delete->RowCnt ?>_video_rate" class="video_rate">
<span<?php echo $video->rate->ViewAttributes() ?>>
<?php echo $video->rate->ListViewValue() ?></span>
</span></td>
		<td<?php echo $video->count->CellAttributes() ?>><span id="el<?php echo $video_delete->RowCnt ?>_video_count" class="video_count">
<span<?php echo $video->count->ViewAttributes() ?>>
<?php echo $video->count->ListViewValue() ?></span>
</span></td>
		<td<?php echo $video->typeid->CellAttributes() ?>><span id="el<?php echo $video_delete->RowCnt ?>_video_typeid" class="video_typeid">
<span<?php echo $video->typeid->ViewAttributes() ?>>
<?php echo $video->typeid->ListViewValue() ?></span>
</span></td>
	</tr>
<?php
	$video_delete->Recordset->MoveNext();
}
$video_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<p>
<input type="submit" name="Action" value="<?php echo ew_BtnCaption($Language->Phrase("DeleteBtn")) ?>">
</form>
<script type="text/javascript">
fvideodelete.Init();
</script>
<?php
$video_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$video_delete->Page_Terminate();
?>
