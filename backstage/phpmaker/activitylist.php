<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "activityinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$activity_list = NULL; // Initialize page object first

class cactivity_list extends cactivity {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{0FEB9E42-0859-45AD-84C6-C2CDD4EB41BE}";

	// Table name
	var $TableName = 'activity';

	// Page object name
	var $PageObjName = 'activity_list';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// Table object (activity)
		if (!isset($GLOBALS["activity"])) {
			$GLOBALS["activity"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["activity"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "activityadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "activitydelete.php";
		$this->MultiUpdateUrl = "activityupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'activity', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Create form object
		$objForm = new cFormObj();

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $RestoreSearch;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";
		if ($this->IsPageRequest()) { // Validate request

			// Handle reset command
			$this->ResetCmd();

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to grid edit mode
				if ($this->CurrentAction == "gridedit")
					$this->GridEditMode();

				// Switch to inline edit mode
				if ($this->CurrentAction == "edit")
					$this->InlineEditMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Grid Update
					if (($this->CurrentAction == "gridupdate" || $this->CurrentAction == "gridoverwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridedit") {
						if ($this->ValidateGridForm()) {
							$this->GridUpdate();
						} else {
							$this->setFailureMessage($gsFormError);
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridedit"; // Stay in Grid Edit mode
						}
					}

					// Inline Update
					if (($this->CurrentAction == "update" || $this->CurrentAction == "overwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "edit")
						$this->InlineUpdate();
				}
			}

			// Hide all options
			if ($this->Export <> "" ||
				$this->CurrentAction == "gridadd" ||
				$this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ExportOptions->HideAllOptions();
			}

			// Show grid delete link for grid add / grid edit
			if ($this->AllowAddDeleteRow) {
				if ($this->CurrentAction == "gridadd" ||
					$this->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
			}

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Restore search parms from Session
			$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if ($sSrchBasic == "" && $sSrchAdvanced == "") {

			// Load basic search from default
			$this->BasicSearchKeyword = $this->BasicSearchKeywordDefault;
			$this->BasicSearchType = $this->BasicSearchTypeDefault;
			$this->setSessionBasicSearchType($this->BasicSearchTypeDefault);
			if ($this->BasicSearchKeyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->SearchWhere <> "") {
			if ($sSrchBasic == "")
				$this->ResetBasicSearchParms();
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			if (!$this->RestoreSearch) {
				$this->StartRec = 1; // Reset start record counter
				$this->setStartRecordNumber($this->StartRec);
			}

		//} else {
		} elseif ($this->RestoreSearch) {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";
	}

	//  Exit inline mode
	function ClearInlineMode() {
		$this->setKey("id", ""); // Clear inline edit key
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Grid Edit mode
	function GridEditMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridedit"; // Enable grid edit
	}

	// Switch to Inline Edit mode
	function InlineEditMode() {
		global $Security, $Language;
		$bInlineEdit = TRUE;
		if (@$_GET["id"] <> "") {
			$this->id->setQueryStringValue($_GET["id"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {
				$this->setKey("id", $this->id->CurrentValue); // Set up inline edit key
				$_SESSION[EW_SESSION_INLINE_MODE] = "edit"; // Enable inline edit
			}
		}
	}

	// Perform update to Inline Edit record
	function InlineUpdate() {
		global $Language, $objForm, $gsFormError;
		$objForm->Index = 1; 
		$this->LoadFormValues(); // Get form values

		// Validate form
		$bInlineUpdate = TRUE;
		if (!$this->ValidateForm()) {	
			$bInlineUpdate = FALSE; // Form error, reset action
			$this->setFailureMessage($gsFormError);
		} else {
			$bInlineUpdate = FALSE;
			$rowkey = strval($objForm->GetValue("k_key"));
			if ($this->SetupKeyValues($rowkey)) { // Set up key values
				if ($this->CheckInlineEditKey()) { // Check key
					$this->SendEmail = TRUE; // Send email on update success
					$bInlineUpdate = $this->EditRow(); // Update record
				} else {
					$bInlineUpdate = FALSE;
				}
			}
		}
		if ($bInlineUpdate) { // Update success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
			$this->EventCancelled = TRUE; // Cancel event
			$this->CurrentAction = "edit"; // Stay in edit mode
		}
	}

	// Check Inline Edit key
	function CheckInlineEditKey() {

		//CheckInlineEditKey = True
		if (strval($this->getKey("id")) <> strval($this->id->CurrentValue))
			return FALSE;
		return TRUE;
	}

	// Perform update to grid
	function GridUpdate() {
		global $conn, $Language, $objForm, $gsFormError;
		$bGridUpdate = TRUE;

		// Begin transaction
		$conn->BeginTrans();

		// Get old recordset
		$this->CurrentFilter = $this->BuildKeyFilter();
		$sSql = $this->SQL();
		if ($rs = $conn->Execute($sSql)) {
			$rsold = $rs->GetRows();
			$rs->Close();
		}
		$sKey = "";

		// Update row index and get row key
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue("key_count"));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Update all rows based on key
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
			$objForm->Index = $rowindex;
			$rowkey = strval($objForm->GetValue("k_key"));
			$rowaction = strval($objForm->GetValue("k_action"));

			// Load all values and keys
			if ($rowaction <> "insertdelete") { // Skip insert then deleted rows
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
					$bGridUpdate = $this->SetupKeyValues($rowkey); // Set up key values
				} else {
					$bGridUpdate = TRUE;
				}

				// Skip empty row
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// No action required
				// Validate form and insert/update/delete record

				} elseif ($bGridUpdate) {
					if ($rowaction == "delete") {
						$this->CurrentFilter = $this->KeyFilter();
						$bGridUpdate = $this->DeleteRows(); // Delete this row
					} else if (!$this->ValidateForm()) {
						$bGridUpdate = FALSE; // Form error, reset action
						$this->setFailureMessage($gsFormError);
					} else {
						if ($rowaction == "insert") {
							$bGridUpdate = $this->AddRow(); // Insert this row
						} else {
							if ($rowkey <> "") {
								$this->SendEmail = FALSE; // Do not send email on update success
								$bGridUpdate = $this->EditRow(); // Update this row
							}
						} // End update
					}
				}
				if ($bGridUpdate) {
					if ($sKey <> "") $sKey .= ", ";
					$sKey .= $rowkey;
				} else {
					break;
				}
			}
		}
		if ($bGridUpdate) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "gridedit"; // Stay in Grid Edit mode
		}
		return $bGridUpdate;
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue("k_key"));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue("k_key"));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if ($objForm->HasValue("x_title") && $objForm->HasValue("o_title") && $this->title->CurrentValue <> $this->title->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_tel") && $objForm->HasValue("o_tel") && $this->tel->CurrentValue <> $this->tel->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_website") && $objForm->HasValue("o_website") && $this->website->CurrentValue <> $this->website->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_typeid") && $objForm->HasValue("o_typeid") && $this->typeid->CurrentValue <> $this->typeid->OldValue)
			return FALSE;
		return TRUE;
	}

	// Validate grid form
	function ValidateGridForm() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue("key_count"));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Validate all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue("k_action"));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else if (!$this->ValidateForm()) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	// Restore form values for current row
	function RestoreCurrentRowFormValues($idx) {
		global $objForm;

		// Get row based on current index
		$objForm->Index = $idx;
		$this->LoadFormValues(); // Load form values
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->title, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->intro, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->tel, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->website, $Keyword);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $Keyword) {
		if ($Keyword == EW_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NULL";
		} elseif ($Keyword == EW_NOT_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NOT NULL";
		} else {
			$sFldExpression = ($Fld->FldVirtualExpression <> $Fld->FldExpression) ? $Fld->FldVirtualExpression : $Fld->FldBasicSearchExpression;
			$sWrk = $sFldExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING));
		}
		if ($Where <> "") $Where .= " OR ";
		$Where .= $sWrk;
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere() {
		global $Security;
		$sSearchStr = "";
		$sSearchKeyword = $this->BasicSearchKeyword;
		$sSearchType = $this->BasicSearchType;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				while (strpos($sSearch, "  ") !== FALSE)
					$sSearch = str_replace("  ", " ", $sSearch);
				$arKeyword = explode(" ", trim($sSearch));
				foreach ($arKeyword as $sKeyword) {
					if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
					$sSearchStr .= "(" . $this->BasicSearchSQL($sKeyword) . ")";
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($sSearch);
			}
		}
		if ($sSearchKeyword <> "") {
			$this->setSessionBasicSearchKeyword($sSearchKeyword);
			$this->setSessionBasicSearchType($sSearchType);
		}
		return $sSearchStr;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->setSessionBasicSearchKeyword("");
		$this->setSessionBasicSearchType($this->BasicSearchTypeDefault);
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$bRestore = TRUE;
		if ($this->BasicSearchKeyword <> "") $bRestore = FALSE;
		$this->RestoreSearch = $bRestore;
		if ($bRestore) {

			// Restore basic search values
			$this->BasicSearchKeyword = $this->getSessionBasicSearchKeyword();
			if ($this->getSessionBasicSearchType() == "") $this->setSessionBasicSearchType("=");
			$this->BasicSearchType = $this->getSessionBasicSearchType();
		}
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id); // id
			$this->UpdateSort($this->title); // title
			$this->UpdateSort($this->tel); // tel
			$this->UpdateSort($this->website); // website
			$this->UpdateSort($this->typeid); // typeid
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->SqlOrderBy() <> "") {
				$sOrderBy = $this->SqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// cmd=reset (Reset search parameters)
	// cmd=resetall (Reset search and master/detail parameters)
	// cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Get reset command
		if (@$_GET["cmd"] <> "") {
			$sCmd = $_GET["cmd"];

			// Reset search criteria
			if (strtolower($sCmd) == "reset" || strtolower($sCmd) == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if (strtolower($sCmd) == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->id->setSort("");
				$this->title->setSort("");
				$this->tel->setSort("");
				$this->website->setSort("");
				$this->typeid->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "griddelete"
		if ($this->AllowAddDeleteRow) {
			$item = &$this->ListOptions->Add("griddelete");
			$item->CssStyle = "white-space: nowrap;";
			$item->OnLeft = FALSE;
			$item->Visible = FALSE; // Default hidden
		}

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// Call ListOptions_Load event
		$this->ListOptions_Load();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Set up row action and key
		if (is_numeric($this->RowIndex)) {
			$objForm->Index = $this->RowIndex;
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_action\" id=\"k" . $this->RowIndex . "_action\" value=\"" . $this->RowAction . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue("k_key");
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_blankrow\" id=\"k" . $this->RowIndex . "_blankrow\" value=\"1\">";
		}

		// "delete"
		if ($this->AllowAddDeleteRow) {
			if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$oListOpt = &$this->ListOptions->Items["griddelete"];
				$oListOpt->Body = "<a class=\"ewGridLink\" href=\"javascript:void(0);\" onclick=\"ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
			}
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($this->CurrentAction == "edit" && $this->RowType == EW_ROWTYPE_EDIT) { // Inline-Edit
			$this->ListOptions->CustomItem = "edit"; // Show edit column only
				$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
					"<a class=\"ewGridLink\" href=\"\" onclick=\"return ewForms['factivitylist'].Submit('" . $this->PageName() . "#" . $this->PageObjName . "_row_" . $this->RowCnt . "');\">" . $Language->Phrase("UpdateLink") . "</a>&nbsp;" .
					"<a class=\"ewGridLink\" href=\"" . $this->PageUrl() . "a=cancel\">" . $Language->Phrase("CancelLink") . "</a>" .
					"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"update\"></div>";
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->id->CurrentValue) . "\">";
			return;
		}

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if (TRUE)
			$oListOpt->Body = "<a class=\"ewRowLink\" href=\"" . $this->ViewUrl . "\">" . $Language->Phrase("ViewLink") . "</a>";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if (TRUE) {
			$oListOpt->Body = "<a class=\"ewRowLink\" href=\"" . $this->EditUrl . "\">" . $Language->Phrase("EditLink") . "</a>";
			$oListOpt->Body .= "<span class=\"ewSeparator\">&nbsp;|&nbsp;</span>";
			$oListOpt->Body .= "<a class=\"ewRowLink\" href=\"" . $this->InlineEditUrl . "#" . $this->PageObjName . "_row_" . $this->RowCnt . "\">" . $Language->Phrase("InlineEditLink") . "</a>";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (TRUE) {
			$oListOpt->Body = "<a class=\"ewRowLink\" href=\"" . $this->CopyUrl . "\">" . $Language->Phrase("CopyLink") . "</a>";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if (TRUE)
			$oListOpt->Body = "<a class=\"ewRowLink\"" . "" . " href=\"" . $this->DeleteUrl . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . $this->id->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Load default values
	function LoadDefaultValues() {
		$this->id->CurrentValue = NULL;
		$this->id->OldValue = $this->id->CurrentValue;
		$this->title->CurrentValue = NULL;
		$this->title->OldValue = $this->title->CurrentValue;
		$this->tel->CurrentValue = NULL;
		$this->tel->OldValue = $this->tel->CurrentValue;
		$this->website->CurrentValue = NULL;
		$this->website->OldValue = $this->website->CurrentValue;
		$this->typeid->CurrentValue = NULL;
		$this->typeid->OldValue = $this->typeid->CurrentValue;
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearchKeyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		$this->BasicSearchType = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->title->FldIsDetailKey) {
			$this->title->setFormValue($objForm->GetValue("x_title"));
		}
		if (!$this->tel->FldIsDetailKey) {
			$this->tel->setFormValue($objForm->GetValue("x_tel"));
		}
		if (!$this->website->FldIsDetailKey) {
			$this->website->setFormValue($objForm->GetValue("x_website"));
		}
		if (!$this->typeid->FldIsDetailKey) {
			$this->typeid->setFormValue($objForm->GetValue("x_typeid"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id->CurrentValue = $this->id->FormValue;
		$this->title->CurrentValue = $this->title->FormValue;
		$this->tel->CurrentValue = $this->tel->FormValue;
		$this->website->CurrentValue = $this->website->FormValue;
		$this->typeid->CurrentValue = $this->typeid->FormValue;
	}

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
		$this->intro->setDbValue($rs->fields('intro'));
		$this->tel->setDbValue($rs->fields('tel'));
		$this->website->setDbValue($rs->fields('website'));
		$this->typeid->setDbValue($rs->fields('typeid'));
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// title
		// intro
		// tel
		// website
		// typeid

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// title
			$this->title->ViewValue = $this->title->CurrentValue;
			$this->title->ViewCustomAttributes = "";

			// tel
			$this->tel->ViewValue = $this->tel->CurrentValue;
			$this->tel->ViewCustomAttributes = "";

			// website
			$this->website->ViewValue = $this->website->CurrentValue;
			$this->website->ViewCustomAttributes = "";

			// typeid
			if (strval($this->typeid->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->typeid->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id`, `typename` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `moduletype`";
			$sWhereWrk = "";
			$lookuptblfilter = "`moduleid`=4";
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

			// tel
			$this->tel->LinkCustomAttributes = "";
			$this->tel->HrefValue = "";
			$this->tel->TooltipValue = "";

			// website
			$this->website->LinkCustomAttributes = "";
			$this->website->HrefValue = "";
			$this->website->TooltipValue = "";

			// typeid
			$this->typeid->LinkCustomAttributes = "";
			$this->typeid->HrefValue = "";
			$this->typeid->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id
			// title

			$this->title->EditCustomAttributes = "";
			$this->title->EditValue = ew_HtmlEncode($this->title->CurrentValue);

			// tel
			$this->tel->EditCustomAttributes = "";
			$this->tel->EditValue = ew_HtmlEncode($this->tel->CurrentValue);

			// website
			$this->website->EditCustomAttributes = "";
			$this->website->EditValue = ew_HtmlEncode($this->website->CurrentValue);

			// typeid
			$this->typeid->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `id`, `typename` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `moduletype`";
			$sWhereWrk = "";
			$lookuptblfilter = "`moduleid`=4";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->typeid->EditValue = $arwrk;

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// title
			$this->title->HrefValue = "";

			// tel
			$this->tel->HrefValue = "";

			// website
			$this->website->HrefValue = "";

			// typeid
			$this->typeid->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// title
			$this->title->EditCustomAttributes = "";
			$this->title->EditValue = ew_HtmlEncode($this->title->CurrentValue);

			// tel
			$this->tel->EditCustomAttributes = "";
			$this->tel->EditValue = ew_HtmlEncode($this->tel->CurrentValue);

			// website
			$this->website->EditCustomAttributes = "";
			$this->website->EditValue = ew_HtmlEncode($this->website->CurrentValue);

			// typeid
			$this->typeid->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `id`, `typename` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `moduletype`";
			$sWhereWrk = "";
			$lookuptblfilter = "`moduleid`=4";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->typeid->EditValue = $arwrk;

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// title
			$this->title->HrefValue = "";

			// tel
			$this->tel->HrefValue = "";

			// website
			$this->website->HrefValue = "";

			// typeid
			$this->typeid->HrefValue = "";
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
		if (!is_null($this->tel->FormValue) && $this->tel->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->tel->FldCaption());
		}
		if (!is_null($this->website->FormValue) && $this->website->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->website->FldCaption());
		}
		if (!is_null($this->typeid->FormValue) && $this->typeid->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->typeid->FldCaption());
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
		} else {
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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

			// tel
			$this->tel->SetDbValueDef($rsnew, $this->tel->CurrentValue, "", $this->tel->ReadOnly);

			// website
			$this->website->SetDbValueDef($rsnew, $this->website->CurrentValue, "", $this->website->ReadOnly);

			// typeid
			$this->typeid->SetDbValueDef($rsnew, $this->typeid->CurrentValue, 0, $this->typeid->ReadOnly);

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

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;
		$rsnew = array();

		// title
		$this->title->SetDbValueDef($rsnew, $this->title->CurrentValue, "", FALSE);

		// tel
		$this->tel->SetDbValueDef($rsnew, $this->tel->CurrentValue, "", FALSE);

		// website
		$this->website->SetDbValueDef($rsnew, $this->website->CurrentValue, "", FALSE);

		// typeid
		$this->typeid->SetDbValueDef($rsnew, $this->typeid->CurrentValue, 0, FALSE);

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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($activity_list)) $activity_list = new cactivity_list();

// Page init
$activity_list->Page_Init();

// Page main
$activity_list->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var activity_list = new ew_Page("activity_list");
activity_list.PageID = "list"; // Page ID
var EW_PAGE_ID = activity_list.PageID; // For backward compatibility

// Form object
var factivitylist = new ew_Form("factivitylist");

// Validate form
factivitylist.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();	
	if (fobj.a_confirm && fobj.a_confirm.value == "F")
		return true;
	var elm, aelm;
	var rowcnt = (fobj.key_count) ? Number(fobj.key_count.value) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // rowcnt == 0 => Inline-Add
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = (fobj.key_count) ? String(i) : "";
		elm = fobj.elements["x" + infix + "_title"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($activity->title->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_tel"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($activity->tel->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_website"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($activity->website->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_typeid"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($activity->typeid->FldCaption()) ?>");

		// Set up row object
		ew_ElementsToRow(fobj, infix);

		// Fire Form_CustomValidate event
		if (!this.Form_CustomValidate(fobj))
			return false;
	}
	return true;
}

// Form_CustomValidate event
factivitylist.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
factivitylist.ValidateRequired = true;
<?php } else { ?>
factivitylist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
factivitylist.Lists["x_typeid"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_typename","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var factivitylistsrch = new ew_Form("factivitylistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$activity_list->TotalRecs = $activity->SelectRecordCount();
	} else {
		if ($activity_list->Recordset = $activity_list->LoadRecordset())
			$activity_list->TotalRecs = $activity_list->Recordset->RecordCount();
	}
	$activity_list->StartRec = 1;
	if ($activity_list->DisplayRecs <= 0 || ($activity->Export <> "" && $activity->ExportAll)) // Display all records
		$activity_list->DisplayRecs = $activity_list->TotalRecs;
	if (!($activity->Export <> "" && $activity->ExportAll))
		$activity_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$activity_list->Recordset = $activity_list->LoadRecordset($activity_list->StartRec-1, $activity_list->DisplayRecs);
?>
<p style="white-space: nowrap;"><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $activity->TableCaption() ?>&nbsp;&nbsp;</span>
<?php $activity_list->ExportOptions->Render("body"); ?>
</p>
<?php if ($activity->Export == "" && $activity->CurrentAction == "") { ?>
<form name="factivitylistsrch" id="factivitylistsrch" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<a href="javascript:factivitylistsrch.ToggleSearchPanel();" style="text-decoration: none;"><img id="factivitylistsrch_SearchImage" src="phpimages/collapse.gif" alt="" width="9" height="9" border="0"></a><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("Search") ?></span><br>
<div id="factivitylistsrch_SearchPanel">
<input type="hidden" name="t" value="activity">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" size="20" value="<?php echo ew_HtmlEncode($activity->getSessionBasicSearchKeyword()) ?>">
	<input type="submit" name="btnsubmit" id="btnsubmit" value="<?php echo ew_BtnCaption($Language->Phrase("QuickSearchBtn")) ?>">&nbsp;
	<a href="<?php echo $activity_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>&nbsp;
</div>
<div id="xsr_2" class="ewRow">
	<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($activity->getSessionBasicSearchType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($activity->getSessionBasicSearchType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($activity->getSessionBasicSearchType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
</div>
</div>
</div>
</form>
<?php } ?>
<?php $activity_list->ShowPageHeader(); ?>
<?php
$activity_list->ShowMessage();
?>
<br>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="factivitylist" id="factivitylist" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="activity">
<div id="gmp_activity" class="ewGridMiddlePanel">
<?php if ($activity_list->TotalRecs > 0) { ?>
<table cellspacing="0" id="tbl_activitylist" class="ewTable ewTableSeparate">
<?php echo $activity->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$activity_list->RenderListOptions();

// Render list options (header, left)
$activity_list->ListOptions->Render("header", "left");
?>
<?php if ($activity->id->Visible) { // id ?>
	<?php if ($activity->SortUrl($activity->id) == "") { ?>
		<td><span id="elh_activity_id" class="activity_id"><?php echo $activity->id->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $activity->SortUrl($activity->id) ?>',1);"><span id="elh_activity_id" class="activity_id">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $activity->id->FldCaption() ?></td><td style="width: 10px;"><?php if ($activity->id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($activity->id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($activity->title->Visible) { // title ?>
	<?php if ($activity->SortUrl($activity->title) == "") { ?>
		<td><span id="elh_activity_title" class="activity_title"><?php echo $activity->title->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $activity->SortUrl($activity->title) ?>',1);"><span id="elh_activity_title" class="activity_title">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $activity->title->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($activity->title->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($activity->title->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($activity->tel->Visible) { // tel ?>
	<?php if ($activity->SortUrl($activity->tel) == "") { ?>
		<td><span id="elh_activity_tel" class="activity_tel"><?php echo $activity->tel->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $activity->SortUrl($activity->tel) ?>',1);"><span id="elh_activity_tel" class="activity_tel">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $activity->tel->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($activity->tel->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($activity->tel->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($activity->website->Visible) { // website ?>
	<?php if ($activity->SortUrl($activity->website) == "") { ?>
		<td><span id="elh_activity_website" class="activity_website"><?php echo $activity->website->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $activity->SortUrl($activity->website) ?>',1);"><span id="elh_activity_website" class="activity_website">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $activity->website->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($activity->website->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($activity->website->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($activity->typeid->Visible) { // typeid ?>
	<?php if ($activity->SortUrl($activity->typeid) == "") { ?>
		<td><span id="elh_activity_typeid" class="activity_typeid"><?php echo $activity->typeid->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $activity->SortUrl($activity->typeid) ?>',1);"><span id="elh_activity_typeid" class="activity_typeid">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $activity->typeid->FldCaption() ?></td><td style="width: 10px;"><?php if ($activity->typeid->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($activity->typeid->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$activity_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($activity->ExportAll && $activity->Export <> "") {
	$activity_list->StopRec = $activity_list->TotalRecs;
} else {

	// Set the last record to display
	if ($activity_list->TotalRecs > $activity_list->StartRec + $activity_list->DisplayRecs - 1)
		$activity_list->StopRec = $activity_list->StartRec + $activity_list->DisplayRecs - 1;
	else
		$activity_list->StopRec = $activity_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue("key_count") && ($activity->CurrentAction == "gridadd" || $activity->CurrentAction == "gridedit" || $activity->CurrentAction == "F")) {
		$activity_list->KeyCount = $objForm->GetValue("key_count");
		$activity_list->StopRec = $activity_list->KeyCount;
	}
}
$activity_list->RecCnt = $activity_list->StartRec - 1;
if ($activity_list->Recordset && !$activity_list->Recordset->EOF) {
	$activity_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $activity_list->StartRec > 1)
		$activity_list->Recordset->Move($activity_list->StartRec - 1);
} elseif (!$activity->AllowAddDeleteRow && $activity_list->StopRec == 0) {
	$activity_list->StopRec = $activity->GridAddRowCount;
}

// Initialize aggregate
$activity->RowType = EW_ROWTYPE_AGGREGATEINIT;
$activity->ResetAttrs();
$activity_list->RenderRow();
$activity_list->EditRowCnt = 0;
if ($activity->CurrentAction == "edit")
	$activity_list->RowIndex = 1;
if ($activity->CurrentAction == "gridedit")
	$activity_list->RowIndex = 0;
while ($activity_list->RecCnt < $activity_list->StopRec) {
	$activity_list->RecCnt++;
	if (intval($activity_list->RecCnt) >= intval($activity_list->StartRec)) {
		$activity_list->RowCnt++;
		if ($activity->CurrentAction == "gridadd" || $activity->CurrentAction == "gridedit" || $activity->CurrentAction == "F") {
			$activity_list->RowIndex++;
			$objForm->Index = $activity_list->RowIndex;
			if ($objForm->HasValue("k_action"))
				$activity_list->RowAction = strval($objForm->GetValue("k_action"));
			elseif ($activity->CurrentAction == "gridadd")
				$activity_list->RowAction = "insert";
			else
				$activity_list->RowAction = "";
		}

		// Set up key count
		$activity_list->KeyCount = $activity_list->RowIndex;

		// Init row class and style
		$activity->ResetAttrs();
		$activity->CssClass = "";
		if ($activity->CurrentAction == "gridadd") {
			$activity_list->LoadDefaultValues(); // Load default values
		} else {
			$activity_list->LoadRowValues($activity_list->Recordset); // Load row values
		}
		$activity->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($activity->CurrentAction == "edit") {
			if ($activity_list->CheckInlineEditKey() && $activity_list->EditRowCnt == 0) { // Inline edit
				$activity->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($activity->CurrentAction == "gridedit") { // Grid edit
			if ($activity->EventCancelled) {
				$activity_list->RestoreCurrentRowFormValues($activity_list->RowIndex); // Restore form values
			}
			if ($activity_list->RowAction == "insert")
				$activity->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$activity->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($activity->CurrentAction == "edit" && $activity->RowType == EW_ROWTYPE_EDIT && $activity->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$activity_list->RestoreFormValues(); // Restore form values
		}
		if ($activity->CurrentAction == "gridedit" && ($activity->RowType == EW_ROWTYPE_EDIT || $activity->RowType == EW_ROWTYPE_ADD) && $activity->EventCancelled) // Update failed
			$activity_list->RestoreCurrentRowFormValues($activity_list->RowIndex); // Restore form values
		if ($activity->RowType == EW_ROWTYPE_EDIT) // Edit row
			$activity_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$activity->RowAttrs = array_merge($activity->RowAttrs, array('data-rowindex'=>$activity_list->RowCnt, 'id'=>'r' . $activity_list->RowCnt . '_activity', 'data-rowtype'=>$activity->RowType));

		// Render row
		$activity_list->RenderRow();

		// Skip delete row / empty row for confirm page
		if ($activity_list->RowAction <> "delete" && $activity_list->RowAction <> "insertdelete" && !($activity_list->RowAction == "insert" && $activity->CurrentAction == "F" && $activity_list->EmptyRow())) {

			// Render list options
			$activity_list->RenderListOptions();
?>
	<tr<?php echo $activity->RowAttributes() ?>>
<?php

// Render list options (body, left)
$activity_list->ListOptions->Render("body", "left", $activity_list->RowCnt);
?>
	<?php if ($activity->id->Visible) { // id ?>
		<td<?php echo $activity->id->CellAttributes() ?>><span id="el<?php echo $activity_list->RowCnt ?>_activity_id" class="activity_id">
<?php if ($activity->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" name="o<?php echo $activity_list->RowIndex ?>_id" id="o<?php echo $activity_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($activity->id->OldValue) ?>">
<?php } ?>
<?php if ($activity->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span<?php echo $activity->id->ViewAttributes() ?>>
<?php echo $activity->id->EditValue ?></span>
<input type="hidden" name="x<?php echo $activity_list->RowIndex ?>_id" id="x<?php echo $activity_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($activity->id->CurrentValue) ?>">
<?php } ?>
<?php if ($activity->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $activity->id->ViewAttributes() ?>>
<?php echo $activity->id->ListViewValue() ?></span>
<?php } ?>
<a name="<?php echo $activity_list->PageObjName . "_row_" . $activity_list->RowCnt ?>" id="<?php echo $activity_list->PageObjName . "_row_" . $activity_list->RowCnt ?>"></a></span></td>
	<?php } ?>
	<?php if ($activity->title->Visible) { // title ?>
		<td<?php echo $activity->title->CellAttributes() ?>><span id="el<?php echo $activity_list->RowCnt ?>_activity_title" class="activity_title">
<?php if ($activity->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $activity_list->RowIndex ?>_title" id="x<?php echo $activity_list->RowIndex ?>_title" size="30" maxlength="100" value="<?php echo $activity->title->EditValue ?>"<?php echo $activity->title->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $activity_list->RowIndex ?>_title" id="o<?php echo $activity_list->RowIndex ?>_title" value="<?php echo ew_HtmlEncode($activity->title->OldValue) ?>">
<?php } ?>
<?php if ($activity->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $activity_list->RowIndex ?>_title" id="x<?php echo $activity_list->RowIndex ?>_title" size="30" maxlength="100" value="<?php echo $activity->title->EditValue ?>"<?php echo $activity->title->EditAttributes() ?>>
<?php } ?>
<?php if ($activity->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $activity->title->ViewAttributes() ?>>
<?php echo $activity->title->ListViewValue() ?></span>
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($activity->tel->Visible) { // tel ?>
		<td<?php echo $activity->tel->CellAttributes() ?>><span id="el<?php echo $activity_list->RowCnt ?>_activity_tel" class="activity_tel">
<?php if ($activity->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $activity_list->RowIndex ?>_tel" id="x<?php echo $activity_list->RowIndex ?>_tel" size="30" maxlength="20" value="<?php echo $activity->tel->EditValue ?>"<?php echo $activity->tel->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $activity_list->RowIndex ?>_tel" id="o<?php echo $activity_list->RowIndex ?>_tel" value="<?php echo ew_HtmlEncode($activity->tel->OldValue) ?>">
<?php } ?>
<?php if ($activity->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $activity_list->RowIndex ?>_tel" id="x<?php echo $activity_list->RowIndex ?>_tel" size="30" maxlength="20" value="<?php echo $activity->tel->EditValue ?>"<?php echo $activity->tel->EditAttributes() ?>>
<?php } ?>
<?php if ($activity->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $activity->tel->ViewAttributes() ?>>
<?php echo $activity->tel->ListViewValue() ?></span>
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($activity->website->Visible) { // website ?>
		<td<?php echo $activity->website->CellAttributes() ?>><span id="el<?php echo $activity_list->RowCnt ?>_activity_website" class="activity_website">
<?php if ($activity->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $activity_list->RowIndex ?>_website" id="x<?php echo $activity_list->RowIndex ?>_website" size="30" maxlength="100" value="<?php echo $activity->website->EditValue ?>"<?php echo $activity->website->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $activity_list->RowIndex ?>_website" id="o<?php echo $activity_list->RowIndex ?>_website" value="<?php echo ew_HtmlEncode($activity->website->OldValue) ?>">
<?php } ?>
<?php if ($activity->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $activity_list->RowIndex ?>_website" id="x<?php echo $activity_list->RowIndex ?>_website" size="30" maxlength="100" value="<?php echo $activity->website->EditValue ?>"<?php echo $activity->website->EditAttributes() ?>>
<?php } ?>
<?php if ($activity->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $activity->website->ViewAttributes() ?>>
<?php echo $activity->website->ListViewValue() ?></span>
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($activity->typeid->Visible) { // typeid ?>
		<td<?php echo $activity->typeid->CellAttributes() ?>><span id="el<?php echo $activity_list->RowCnt ?>_activity_typeid" class="activity_typeid">
<?php if ($activity->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<select id="x<?php echo $activity_list->RowIndex ?>_typeid" name="x<?php echo $activity_list->RowIndex ?>_typeid"<?php echo $activity->typeid->EditAttributes() ?>>
<?php
if (is_array($activity->typeid->EditValue)) {
	$arwrk = $activity->typeid->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($activity->typeid->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
factivitylist.Lists["x_typeid"].Options = <?php echo (is_array($activity->typeid->EditValue)) ? ew_ArrayToJson($activity->typeid->EditValue, 1) : "[]" ?>;
</script>
<input type="hidden" name="o<?php echo $activity_list->RowIndex ?>_typeid" id="o<?php echo $activity_list->RowIndex ?>_typeid" value="<?php echo ew_HtmlEncode($activity->typeid->OldValue) ?>">
<?php } ?>
<?php if ($activity->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<select id="x<?php echo $activity_list->RowIndex ?>_typeid" name="x<?php echo $activity_list->RowIndex ?>_typeid"<?php echo $activity->typeid->EditAttributes() ?>>
<?php
if (is_array($activity->typeid->EditValue)) {
	$arwrk = $activity->typeid->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($activity->typeid->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
factivitylist.Lists["x_typeid"].Options = <?php echo (is_array($activity->typeid->EditValue)) ? ew_ArrayToJson($activity->typeid->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php if ($activity->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $activity->typeid->ViewAttributes() ?>>
<?php echo $activity->typeid->ListViewValue() ?></span>
<?php } ?>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$activity_list->ListOptions->Render("body", "right", $activity_list->RowCnt);
?>
	</tr>
<?php if ($activity->RowType == EW_ROWTYPE_ADD || $activity->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
factivitylist.UpdateOpts(<?php echo $activity_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($activity->CurrentAction <> "gridadd")
		if (!$activity_list->Recordset->EOF) $activity_list->Recordset->MoveNext();
}
?>
<?php
	if ($activity->CurrentAction == "gridadd" || $activity->CurrentAction == "gridedit") {
		$activity_list->RowIndex = '$rowindex$';
		$activity_list->LoadDefaultValues();

		// Set row properties
		$activity->ResetAttrs();
		$activity->RowAttrs = array_merge($activity->RowAttrs, array('data-rowindex'=>$activity_list->RowIndex, 'id'=>'r0_activity', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($activity->RowAttrs["class"], "ewTemplate");
		$activity->RowType = EW_ROWTYPE_ADD;

		// Render row
		$activity_list->RenderRow();

		// Render list options
		$activity_list->RenderListOptions();
		$activity_list->StartRowCnt = 0;
?>
	<tr<?php echo $activity->RowAttributes() ?>>
<?php

// Render list options (body, left)
$activity_list->ListOptions->Render("body", "left", $activity_list->RowIndex);
?>
	<?php if ($activity->id->Visible) { // id ?>
		<td><span id="el$rowindex$_activity_id" class="activity_id">
<input type="hidden" name="o<?php echo $activity_list->RowIndex ?>_id" id="o<?php echo $activity_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($activity->id->OldValue) ?>">
</span></td>
	<?php } ?>
	<?php if ($activity->title->Visible) { // title ?>
		<td><span id="el$rowindex$_activity_title" class="activity_title">
<input type="text" name="x<?php echo $activity_list->RowIndex ?>_title" id="x<?php echo $activity_list->RowIndex ?>_title" size="30" maxlength="100" value="<?php echo $activity->title->EditValue ?>"<?php echo $activity->title->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $activity_list->RowIndex ?>_title" id="o<?php echo $activity_list->RowIndex ?>_title" value="<?php echo ew_HtmlEncode($activity->title->OldValue) ?>">
</span></td>
	<?php } ?>
	<?php if ($activity->tel->Visible) { // tel ?>
		<td><span id="el$rowindex$_activity_tel" class="activity_tel">
<input type="text" name="x<?php echo $activity_list->RowIndex ?>_tel" id="x<?php echo $activity_list->RowIndex ?>_tel" size="30" maxlength="20" value="<?php echo $activity->tel->EditValue ?>"<?php echo $activity->tel->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $activity_list->RowIndex ?>_tel" id="o<?php echo $activity_list->RowIndex ?>_tel" value="<?php echo ew_HtmlEncode($activity->tel->OldValue) ?>">
</span></td>
	<?php } ?>
	<?php if ($activity->website->Visible) { // website ?>
		<td><span id="el$rowindex$_activity_website" class="activity_website">
<input type="text" name="x<?php echo $activity_list->RowIndex ?>_website" id="x<?php echo $activity_list->RowIndex ?>_website" size="30" maxlength="100" value="<?php echo $activity->website->EditValue ?>"<?php echo $activity->website->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $activity_list->RowIndex ?>_website" id="o<?php echo $activity_list->RowIndex ?>_website" value="<?php echo ew_HtmlEncode($activity->website->OldValue) ?>">
</span></td>
	<?php } ?>
	<?php if ($activity->typeid->Visible) { // typeid ?>
		<td><span id="el$rowindex$_activity_typeid" class="activity_typeid">
<select id="x<?php echo $activity_list->RowIndex ?>_typeid" name="x<?php echo $activity_list->RowIndex ?>_typeid"<?php echo $activity->typeid->EditAttributes() ?>>
<?php
if (is_array($activity->typeid->EditValue)) {
	$arwrk = $activity->typeid->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($activity->typeid->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
factivitylist.Lists["x_typeid"].Options = <?php echo (is_array($activity->typeid->EditValue)) ? ew_ArrayToJson($activity->typeid->EditValue, 1) : "[]" ?>;
</script>
<input type="hidden" name="o<?php echo $activity_list->RowIndex ?>_typeid" id="o<?php echo $activity_list->RowIndex ?>_typeid" value="<?php echo ew_HtmlEncode($activity->typeid->OldValue) ?>">
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$activity_list->ListOptions->Render("body", "right", $activity_list->RowCnt);
?>
<script type="text/javascript">
factivitylist.UpdateOpts(<?php echo $activity_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($activity->CurrentAction == "edit") { ?>
<input type="hidden" name="key_count" id="key_count" value="<?php echo $activity_list->KeyCount ?>">
<?php } ?>
<?php if ($activity->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="key_count" id="key_count" value="<?php echo $activity_list->KeyCount ?>">
<?php echo $activity_list->MultiSelectKey ?>
<?php } ?>
<?php if ($activity->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($activity_list->Recordset)
	$activity_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($activity->CurrentAction <> "gridadd" && $activity->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table border="0" cellspacing="0" cellpadding="0" class="ewPager">
	<tr>
		<td nowrap>
<?php if (!isset($activity_list->Pager)) $activity_list->Pager = new cPrevNextPager($activity_list->StartRec, $activity_list->DisplayRecs, $activity_list->TotalRecs) ?>
<?php if ($activity_list->Pager->RecordCount > 0) { ?>
	<table border="0" cellspacing="0" cellpadding="0"><tr><td><span class="phpmaker"><?php echo $Language->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($activity_list->Pager->FirstButton->Enabled) { ?>
	<td><a href="<?php echo $activity_list->PageUrl() ?>start=<?php echo $activity_list->Pager->FirstButton->Start ?>"><img src="phpimages/first.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/firstdisab.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($activity_list->Pager->PrevButton->Enabled) { ?>
	<td><a href="<?php echo $activity_list->PageUrl() ?>start=<?php echo $activity_list->Pager->PrevButton->Start ?>"><img src="phpimages/prev.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/prevdisab.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" id="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $activity_list->Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($activity_list->Pager->NextButton->Enabled) { ?>
	<td><a href="<?php echo $activity_list->PageUrl() ?>start=<?php echo $activity_list->Pager->NextButton->Start ?>"><img src="phpimages/next.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/nextdisab.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--last page button-->
	<?php if ($activity_list->Pager->LastButton->Enabled) { ?>
	<td><a href="<?php echo $activity_list->PageUrl() ?>start=<?php echo $activity_list->Pager->LastButton->Start ?>"><img src="phpimages/last.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/lastdisab.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
	<td><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $activity_list->Pager->PageCount ?></span></td>
	</tr></table>
	</td>	
	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td>
	<span class="phpmaker"><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $activity_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $activity_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $activity_list->Pager->RecordCount ?></span>
<?php } else { ?>
	<?php if ($activity_list->SearchWhere == "0=101") { ?>
	<span class="phpmaker"><?php echo $Language->Phrase("EnterSearchCriteria") ?></span>
	<?php } else { ?>
	<span class="phpmaker"><?php echo $Language->Phrase("NoRecord") ?></span>
	<?php } ?>
<?php } ?>
		</td>
	</tr>
</table>
</form>
<?php } ?>
<span class="phpmaker">
<?php if ($activity->CurrentAction <> "gridadd" && $activity->CurrentAction <> "gridedit") { // Not grid add/edit mode ?>
<?php if ($activity_list->AddUrl <> "") { ?>
<a class="ewGridLink" href="<?php echo $activity_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>&nbsp;&nbsp;
<?php } ?>
<?php if ($activity_list->TotalRecs > 0 && $activity_list->GridEditUrl <> "") { ?>
<a class="ewGridLink" href="<?php echo $activity_list->GridEditUrl ?>"><?php echo $Language->Phrase("GridEditLink") ?></a>&nbsp;&nbsp;
<?php } ?>
<?php } else { // Grid add/edit mode ?>
<?php if ($activity->CurrentAction == "gridedit") { ?>
<?php if ($activity->AllowAddDeleteRow) { ?>
<a class="ewGridLink" href="javascript:void(0);" onclick="ew_AddGridRow(this);"><?php echo $Language->Phrase("AddBlankRow") ?></a>&nbsp;&nbsp;
<?php } ?>
<a class="ewGridLink" href="" onclick="return ewForms['factivitylist'].Submit();"><?php echo $Language->Phrase("GridSaveLink") ?></a>&nbsp;&nbsp;
<a class="ewGridLink" href="<?php echo $activity_list->PageUrl() ?>a=cancel"><?php echo $Language->Phrase("GridCancelLink") ?></a>&nbsp;&nbsp;
<?php } ?>
<?php } ?>
</span>
</div>
</td></tr></table>
<script type="text/javascript">
factivitylistsrch.Init();
factivitylist.Init();
</script>
<?php
$activity_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$activity_list->Page_Terminate();
?>
