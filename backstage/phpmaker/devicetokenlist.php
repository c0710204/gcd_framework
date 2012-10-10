<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "devicetokeninfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$devicetoken_list = NULL; // Initialize page object first

class cdevicetoken_list extends cdevicetoken {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{0FEB9E42-0859-45AD-84C6-C2CDD4EB41BE}";

	// Table name
	var $TableName = 'devicetoken';

	// Page object name
	var $PageObjName = 'devicetoken_list';

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

		// Table object (devicetoken)
		if (!isset($GLOBALS["devicetoken"])) {
			$GLOBALS["devicetoken"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["devicetoken"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "devicetokenadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "devicetokendelete.php";
		$this->MultiUpdateUrl = "devicetokenupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'devicetoken', TRUE);

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

			// Hide all options
			if ($this->Export <> "" ||
				$this->CurrentAction == "gridadd" ||
				$this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ExportOptions->HideAllOptions();
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

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->devicetoken, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->devicename, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->deviceplatform, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->deviceuuid, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->deviceversion, $Keyword);
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
			$this->UpdateSort($this->devicetoken); // devicetoken
			$this->UpdateSort($this->devicename); // devicename
			$this->UpdateSort($this->deviceplatform); // deviceplatform
			$this->UpdateSort($this->deviceuuid); // deviceuuid
			$this->UpdateSort($this->deviceversion); // deviceversion
			$this->UpdateSort($this->_userid); // userid
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
				$this->devicetoken->setSort("");
				$this->devicename->setSort("");
				$this->deviceplatform->setSort("");
				$this->deviceuuid->setSort("");
				$this->deviceversion->setSort("");
				$this->_userid->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

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

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if (TRUE)
			$oListOpt->Body = "<a class=\"ewRowLink\" href=\"" . $this->ViewUrl . "\">" . $Language->Phrase("ViewLink") . "</a>";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if (TRUE) {
			$oListOpt->Body = "<a class=\"ewRowLink\" href=\"" . $this->EditUrl . "\">" . $Language->Phrase("EditLink") . "</a>";
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearchKeyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		$this->BasicSearchType = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
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
		$this->devicetoken->setDbValue($rs->fields('devicetoken'));
		$this->devicename->setDbValue($rs->fields('devicename'));
		$this->deviceplatform->setDbValue($rs->fields('deviceplatform'));
		$this->deviceuuid->setDbValue($rs->fields('deviceuuid'));
		$this->deviceversion->setDbValue($rs->fields('deviceversion'));
		$this->_userid->setDbValue($rs->fields('userid'));
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
		// devicetoken
		// devicename
		// deviceplatform
		// deviceuuid
		// deviceversion
		// userid

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// devicetoken
			$this->devicetoken->ViewValue = $this->devicetoken->CurrentValue;
			$this->devicetoken->ViewCustomAttributes = "";

			// devicename
			$this->devicename->ViewValue = $this->devicename->CurrentValue;
			$this->devicename->ViewCustomAttributes = "";

			// deviceplatform
			$this->deviceplatform->ViewValue = $this->deviceplatform->CurrentValue;
			$this->deviceplatform->ViewCustomAttributes = "";

			// deviceuuid
			$this->deviceuuid->ViewValue = $this->deviceuuid->CurrentValue;
			$this->deviceuuid->ViewCustomAttributes = "";

			// deviceversion
			$this->deviceversion->ViewValue = $this->deviceversion->CurrentValue;
			$this->deviceversion->ViewCustomAttributes = "";

			// userid
			$this->_userid->ViewValue = $this->_userid->CurrentValue;
			$this->_userid->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// devicetoken
			$this->devicetoken->LinkCustomAttributes = "";
			$this->devicetoken->HrefValue = "";
			$this->devicetoken->TooltipValue = "";

			// devicename
			$this->devicename->LinkCustomAttributes = "";
			$this->devicename->HrefValue = "";
			$this->devicename->TooltipValue = "";

			// deviceplatform
			$this->deviceplatform->LinkCustomAttributes = "";
			$this->deviceplatform->HrefValue = "";
			$this->deviceplatform->TooltipValue = "";

			// deviceuuid
			$this->deviceuuid->LinkCustomAttributes = "";
			$this->deviceuuid->HrefValue = "";
			$this->deviceuuid->TooltipValue = "";

			// deviceversion
			$this->deviceversion->LinkCustomAttributes = "";
			$this->deviceversion->HrefValue = "";
			$this->deviceversion->TooltipValue = "";

			// userid
			$this->_userid->LinkCustomAttributes = "";
			$this->_userid->HrefValue = "";
			$this->_userid->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
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
if (!isset($devicetoken_list)) $devicetoken_list = new cdevicetoken_list();

// Page init
$devicetoken_list->Page_Init();

// Page main
$devicetoken_list->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var devicetoken_list = new ew_Page("devicetoken_list");
devicetoken_list.PageID = "list"; // Page ID
var EW_PAGE_ID = devicetoken_list.PageID; // For backward compatibility

// Form object
var fdevicetokenlist = new ew_Form("fdevicetokenlist");

// Form_CustomValidate event
fdevicetokenlist.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdevicetokenlist.ValidateRequired = true;
<?php } else { ?>
fdevicetokenlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fdevicetokenlistsrch = new ew_Form("fdevicetokenlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$devicetoken_list->TotalRecs = $devicetoken->SelectRecordCount();
	} else {
		if ($devicetoken_list->Recordset = $devicetoken_list->LoadRecordset())
			$devicetoken_list->TotalRecs = $devicetoken_list->Recordset->RecordCount();
	}
	$devicetoken_list->StartRec = 1;
	if ($devicetoken_list->DisplayRecs <= 0 || ($devicetoken->Export <> "" && $devicetoken->ExportAll)) // Display all records
		$devicetoken_list->DisplayRecs = $devicetoken_list->TotalRecs;
	if (!($devicetoken->Export <> "" && $devicetoken->ExportAll))
		$devicetoken_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$devicetoken_list->Recordset = $devicetoken_list->LoadRecordset($devicetoken_list->StartRec-1, $devicetoken_list->DisplayRecs);
?>
<p style="white-space: nowrap;"><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $devicetoken->TableCaption() ?>&nbsp;&nbsp;</span>
<?php $devicetoken_list->ExportOptions->Render("body"); ?>
</p>
<?php if ($devicetoken->Export == "" && $devicetoken->CurrentAction == "") { ?>
<form name="fdevicetokenlistsrch" id="fdevicetokenlistsrch" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<a href="javascript:fdevicetokenlistsrch.ToggleSearchPanel();" style="text-decoration: none;"><img id="fdevicetokenlistsrch_SearchImage" src="phpimages/collapse.gif" alt="" width="9" height="9" border="0"></a><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("Search") ?></span><br>
<div id="fdevicetokenlistsrch_SearchPanel">
<input type="hidden" name="t" value="devicetoken">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" size="20" value="<?php echo ew_HtmlEncode($devicetoken->getSessionBasicSearchKeyword()) ?>">
	<input type="submit" name="btnsubmit" id="btnsubmit" value="<?php echo ew_BtnCaption($Language->Phrase("QuickSearchBtn")) ?>">&nbsp;
	<a href="<?php echo $devicetoken_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>&nbsp;
</div>
<div id="xsr_2" class="ewRow">
	<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($devicetoken->getSessionBasicSearchType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($devicetoken->getSessionBasicSearchType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($devicetoken->getSessionBasicSearchType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
</div>
</div>
</div>
</form>
<?php } ?>
<?php $devicetoken_list->ShowPageHeader(); ?>
<?php
$devicetoken_list->ShowMessage();
?>
<br>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fdevicetokenlist" id="fdevicetokenlist" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="devicetoken">
<div id="gmp_devicetoken" class="ewGridMiddlePanel">
<?php if ($devicetoken_list->TotalRecs > 0) { ?>
<table cellspacing="0" id="tbl_devicetokenlist" class="ewTable ewTableSeparate">
<?php echo $devicetoken->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$devicetoken_list->RenderListOptions();

// Render list options (header, left)
$devicetoken_list->ListOptions->Render("header", "left");
?>
<?php if ($devicetoken->id->Visible) { // id ?>
	<?php if ($devicetoken->SortUrl($devicetoken->id) == "") { ?>
		<td><span id="elh_devicetoken_id" class="devicetoken_id"><?php echo $devicetoken->id->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $devicetoken->SortUrl($devicetoken->id) ?>',1);"><span id="elh_devicetoken_id" class="devicetoken_id">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $devicetoken->id->FldCaption() ?></td><td style="width: 10px;"><?php if ($devicetoken->id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($devicetoken->id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($devicetoken->devicetoken->Visible) { // devicetoken ?>
	<?php if ($devicetoken->SortUrl($devicetoken->devicetoken) == "") { ?>
		<td><span id="elh_devicetoken_devicetoken" class="devicetoken_devicetoken"><?php echo $devicetoken->devicetoken->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $devicetoken->SortUrl($devicetoken->devicetoken) ?>',1);"><span id="elh_devicetoken_devicetoken" class="devicetoken_devicetoken">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $devicetoken->devicetoken->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($devicetoken->devicetoken->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($devicetoken->devicetoken->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($devicetoken->devicename->Visible) { // devicename ?>
	<?php if ($devicetoken->SortUrl($devicetoken->devicename) == "") { ?>
		<td><span id="elh_devicetoken_devicename" class="devicetoken_devicename"><?php echo $devicetoken->devicename->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $devicetoken->SortUrl($devicetoken->devicename) ?>',1);"><span id="elh_devicetoken_devicename" class="devicetoken_devicename">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $devicetoken->devicename->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($devicetoken->devicename->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($devicetoken->devicename->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($devicetoken->deviceplatform->Visible) { // deviceplatform ?>
	<?php if ($devicetoken->SortUrl($devicetoken->deviceplatform) == "") { ?>
		<td><span id="elh_devicetoken_deviceplatform" class="devicetoken_deviceplatform"><?php echo $devicetoken->deviceplatform->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $devicetoken->SortUrl($devicetoken->deviceplatform) ?>',1);"><span id="elh_devicetoken_deviceplatform" class="devicetoken_deviceplatform">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $devicetoken->deviceplatform->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($devicetoken->deviceplatform->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($devicetoken->deviceplatform->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($devicetoken->deviceuuid->Visible) { // deviceuuid ?>
	<?php if ($devicetoken->SortUrl($devicetoken->deviceuuid) == "") { ?>
		<td><span id="elh_devicetoken_deviceuuid" class="devicetoken_deviceuuid"><?php echo $devicetoken->deviceuuid->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $devicetoken->SortUrl($devicetoken->deviceuuid) ?>',1);"><span id="elh_devicetoken_deviceuuid" class="devicetoken_deviceuuid">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $devicetoken->deviceuuid->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($devicetoken->deviceuuid->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($devicetoken->deviceuuid->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($devicetoken->deviceversion->Visible) { // deviceversion ?>
	<?php if ($devicetoken->SortUrl($devicetoken->deviceversion) == "") { ?>
		<td><span id="elh_devicetoken_deviceversion" class="devicetoken_deviceversion"><?php echo $devicetoken->deviceversion->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $devicetoken->SortUrl($devicetoken->deviceversion) ?>',1);"><span id="elh_devicetoken_deviceversion" class="devicetoken_deviceversion">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $devicetoken->deviceversion->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($devicetoken->deviceversion->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($devicetoken->deviceversion->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($devicetoken->_userid->Visible) { // userid ?>
	<?php if ($devicetoken->SortUrl($devicetoken->_userid) == "") { ?>
		<td><span id="elh_devicetoken__userid" class="devicetoken__userid"><?php echo $devicetoken->_userid->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $devicetoken->SortUrl($devicetoken->_userid) ?>',1);"><span id="elh_devicetoken__userid" class="devicetoken__userid">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $devicetoken->_userid->FldCaption() ?></td><td style="width: 10px;"><?php if ($devicetoken->_userid->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($devicetoken->_userid->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$devicetoken_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($devicetoken->ExportAll && $devicetoken->Export <> "") {
	$devicetoken_list->StopRec = $devicetoken_list->TotalRecs;
} else {

	// Set the last record to display
	if ($devicetoken_list->TotalRecs > $devicetoken_list->StartRec + $devicetoken_list->DisplayRecs - 1)
		$devicetoken_list->StopRec = $devicetoken_list->StartRec + $devicetoken_list->DisplayRecs - 1;
	else
		$devicetoken_list->StopRec = $devicetoken_list->TotalRecs;
}
$devicetoken_list->RecCnt = $devicetoken_list->StartRec - 1;
if ($devicetoken_list->Recordset && !$devicetoken_list->Recordset->EOF) {
	$devicetoken_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $devicetoken_list->StartRec > 1)
		$devicetoken_list->Recordset->Move($devicetoken_list->StartRec - 1);
} elseif (!$devicetoken->AllowAddDeleteRow && $devicetoken_list->StopRec == 0) {
	$devicetoken_list->StopRec = $devicetoken->GridAddRowCount;
}

// Initialize aggregate
$devicetoken->RowType = EW_ROWTYPE_AGGREGATEINIT;
$devicetoken->ResetAttrs();
$devicetoken_list->RenderRow();
while ($devicetoken_list->RecCnt < $devicetoken_list->StopRec) {
	$devicetoken_list->RecCnt++;
	if (intval($devicetoken_list->RecCnt) >= intval($devicetoken_list->StartRec)) {
		$devicetoken_list->RowCnt++;

		// Set up key count
		$devicetoken_list->KeyCount = $devicetoken_list->RowIndex;

		// Init row class and style
		$devicetoken->ResetAttrs();
		$devicetoken->CssClass = "";
		if ($devicetoken->CurrentAction == "gridadd") {
		} else {
			$devicetoken_list->LoadRowValues($devicetoken_list->Recordset); // Load row values
		}
		$devicetoken->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$devicetoken->RowAttrs = array_merge($devicetoken->RowAttrs, array('data-rowindex'=>$devicetoken_list->RowCnt, 'id'=>'r' . $devicetoken_list->RowCnt . '_devicetoken', 'data-rowtype'=>$devicetoken->RowType));

		// Render row
		$devicetoken_list->RenderRow();

			// Render list options
			$devicetoken_list->RenderListOptions();
?>
	<tr<?php echo $devicetoken->RowAttributes() ?>>
<?php

// Render list options (body, left)
$devicetoken_list->ListOptions->Render("body", "left", $devicetoken_list->RowCnt);
?>
	<?php if ($devicetoken->id->Visible) { // id ?>
		<td<?php echo $devicetoken->id->CellAttributes() ?>><span id="el<?php echo $devicetoken_list->RowCnt ?>_devicetoken_id" class="devicetoken_id">
<span<?php echo $devicetoken->id->ViewAttributes() ?>>
<?php echo $devicetoken->id->ListViewValue() ?></span>
<a name="<?php echo $devicetoken_list->PageObjName . "_row_" . $devicetoken_list->RowCnt ?>" id="<?php echo $devicetoken_list->PageObjName . "_row_" . $devicetoken_list->RowCnt ?>"></a></span></td>
	<?php } ?>
	<?php if ($devicetoken->devicetoken->Visible) { // devicetoken ?>
		<td<?php echo $devicetoken->devicetoken->CellAttributes() ?>><span id="el<?php echo $devicetoken_list->RowCnt ?>_devicetoken_devicetoken" class="devicetoken_devicetoken">
<span<?php echo $devicetoken->devicetoken->ViewAttributes() ?>>
<?php echo $devicetoken->devicetoken->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($devicetoken->devicename->Visible) { // devicename ?>
		<td<?php echo $devicetoken->devicename->CellAttributes() ?>><span id="el<?php echo $devicetoken_list->RowCnt ?>_devicetoken_devicename" class="devicetoken_devicename">
<span<?php echo $devicetoken->devicename->ViewAttributes() ?>>
<?php echo $devicetoken->devicename->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($devicetoken->deviceplatform->Visible) { // deviceplatform ?>
		<td<?php echo $devicetoken->deviceplatform->CellAttributes() ?>><span id="el<?php echo $devicetoken_list->RowCnt ?>_devicetoken_deviceplatform" class="devicetoken_deviceplatform">
<span<?php echo $devicetoken->deviceplatform->ViewAttributes() ?>>
<?php echo $devicetoken->deviceplatform->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($devicetoken->deviceuuid->Visible) { // deviceuuid ?>
		<td<?php echo $devicetoken->deviceuuid->CellAttributes() ?>><span id="el<?php echo $devicetoken_list->RowCnt ?>_devicetoken_deviceuuid" class="devicetoken_deviceuuid">
<span<?php echo $devicetoken->deviceuuid->ViewAttributes() ?>>
<?php echo $devicetoken->deviceuuid->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($devicetoken->deviceversion->Visible) { // deviceversion ?>
		<td<?php echo $devicetoken->deviceversion->CellAttributes() ?>><span id="el<?php echo $devicetoken_list->RowCnt ?>_devicetoken_deviceversion" class="devicetoken_deviceversion">
<span<?php echo $devicetoken->deviceversion->ViewAttributes() ?>>
<?php echo $devicetoken->deviceversion->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($devicetoken->_userid->Visible) { // userid ?>
		<td<?php echo $devicetoken->_userid->CellAttributes() ?>><span id="el<?php echo $devicetoken_list->RowCnt ?>_devicetoken__userid" class="devicetoken__userid">
<span<?php echo $devicetoken->_userid->ViewAttributes() ?>>
<?php echo $devicetoken->_userid->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$devicetoken_list->ListOptions->Render("body", "right", $devicetoken_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($devicetoken->CurrentAction <> "gridadd")
		$devicetoken_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($devicetoken->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($devicetoken_list->Recordset)
	$devicetoken_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($devicetoken->CurrentAction <> "gridadd" && $devicetoken->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table border="0" cellspacing="0" cellpadding="0" class="ewPager">
	<tr>
		<td nowrap>
<?php if (!isset($devicetoken_list->Pager)) $devicetoken_list->Pager = new cPrevNextPager($devicetoken_list->StartRec, $devicetoken_list->DisplayRecs, $devicetoken_list->TotalRecs) ?>
<?php if ($devicetoken_list->Pager->RecordCount > 0) { ?>
	<table border="0" cellspacing="0" cellpadding="0"><tr><td><span class="phpmaker"><?php echo $Language->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($devicetoken_list->Pager->FirstButton->Enabled) { ?>
	<td><a href="<?php echo $devicetoken_list->PageUrl() ?>start=<?php echo $devicetoken_list->Pager->FirstButton->Start ?>"><img src="phpimages/first.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/firstdisab.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($devicetoken_list->Pager->PrevButton->Enabled) { ?>
	<td><a href="<?php echo $devicetoken_list->PageUrl() ?>start=<?php echo $devicetoken_list->Pager->PrevButton->Start ?>"><img src="phpimages/prev.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/prevdisab.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" id="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $devicetoken_list->Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($devicetoken_list->Pager->NextButton->Enabled) { ?>
	<td><a href="<?php echo $devicetoken_list->PageUrl() ?>start=<?php echo $devicetoken_list->Pager->NextButton->Start ?>"><img src="phpimages/next.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/nextdisab.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--last page button-->
	<?php if ($devicetoken_list->Pager->LastButton->Enabled) { ?>
	<td><a href="<?php echo $devicetoken_list->PageUrl() ?>start=<?php echo $devicetoken_list->Pager->LastButton->Start ?>"><img src="phpimages/last.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/lastdisab.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
	<td><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $devicetoken_list->Pager->PageCount ?></span></td>
	</tr></table>
	</td>	
	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td>
	<span class="phpmaker"><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $devicetoken_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $devicetoken_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $devicetoken_list->Pager->RecordCount ?></span>
<?php } else { ?>
	<?php if ($devicetoken_list->SearchWhere == "0=101") { ?>
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
<?php if ($devicetoken_list->AddUrl <> "") { ?>
<a class="ewGridLink" href="<?php echo $devicetoken_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>&nbsp;&nbsp;
<?php } ?>
</span>
</div>
</td></tr></table>
<script type="text/javascript">
fdevicetokenlistsrch.Init();
fdevicetokenlist.Init();
</script>
<?php
$devicetoken_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$devicetoken_list->Page_Terminate();
?>
