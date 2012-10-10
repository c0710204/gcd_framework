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

$course_list = NULL; // Initialize page object first

class ccourse_list extends ccourse {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{0FEB9E42-0859-45AD-84C6-C2CDD4EB41BE}";

	// Table name
	var $TableName = 'course';

	// Page object name
	var $PageObjName = 'course_list';

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

		// Table object (course)
		if (!isset($GLOBALS["course"])) {
			$GLOBALS["course"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["course"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "courseadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "coursedelete.php";
		$this->MultiUpdateUrl = "courseupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'course', TRUE);

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
		$this->BuildBasicSearchSQL($sWhere, $this->courseName, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->courseEngName, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->courseCode, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->courseInfo, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->courseXz, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->courseLb, $Keyword);
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
			$this->UpdateSort($this->courseName); // courseName
			$this->UpdateSort($this->courseEngName); // courseEngName
			$this->UpdateSort($this->courseCode); // courseCode
			$this->UpdateSort($this->courseXs); // courseXs
			$this->UpdateSort($this->courseXf); // courseXf
			$this->UpdateSort($this->courseXz); // courseXz
			$this->UpdateSort($this->courseLb); // courseLb
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
				$this->courseName->setSort("");
				$this->courseEngName->setSort("");
				$this->courseCode->setSort("");
				$this->courseXs->setSort("");
				$this->courseXf->setSort("");
				$this->courseXz->setSort("");
				$this->courseLb->setSort("");
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
		$this->courseName->setDbValue($rs->fields('courseName'));
		$this->courseEngName->setDbValue($rs->fields('courseEngName'));
		$this->courseCode->setDbValue($rs->fields('courseCode'));
		$this->courseInfo->setDbValue($rs->fields('courseInfo'));
		$this->courseXs->setDbValue($rs->fields('courseXs'));
		$this->courseXf->setDbValue($rs->fields('courseXf'));
		$this->courseXz->setDbValue($rs->fields('courseXz'));
		$this->courseLb->setDbValue($rs->fields('courseLb'));
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
if (!isset($course_list)) $course_list = new ccourse_list();

// Page init
$course_list->Page_Init();

// Page main
$course_list->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var course_list = new ew_Page("course_list");
course_list.PageID = "list"; // Page ID
var EW_PAGE_ID = course_list.PageID; // For backward compatibility

// Form object
var fcourselist = new ew_Form("fcourselist");

// Form_CustomValidate event
fcourselist.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcourselist.ValidateRequired = true;
<?php } else { ?>
fcourselist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fcourselistsrch = new ew_Form("fcourselistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$course_list->TotalRecs = $course->SelectRecordCount();
	} else {
		if ($course_list->Recordset = $course_list->LoadRecordset())
			$course_list->TotalRecs = $course_list->Recordset->RecordCount();
	}
	$course_list->StartRec = 1;
	if ($course_list->DisplayRecs <= 0 || ($course->Export <> "" && $course->ExportAll)) // Display all records
		$course_list->DisplayRecs = $course_list->TotalRecs;
	if (!($course->Export <> "" && $course->ExportAll))
		$course_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$course_list->Recordset = $course_list->LoadRecordset($course_list->StartRec-1, $course_list->DisplayRecs);
?>
<p style="white-space: nowrap;"><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $course->TableCaption() ?>&nbsp;&nbsp;</span>
<?php $course_list->ExportOptions->Render("body"); ?>
</p>
<?php if ($course->Export == "" && $course->CurrentAction == "") { ?>
<form name="fcourselistsrch" id="fcourselistsrch" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<a href="javascript:fcourselistsrch.ToggleSearchPanel();" style="text-decoration: none;"><img id="fcourselistsrch_SearchImage" src="phpimages/collapse.gif" alt="" width="9" height="9" border="0"></a><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("Search") ?></span><br>
<div id="fcourselistsrch_SearchPanel">
<input type="hidden" name="t" value="course">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" size="20" value="<?php echo ew_HtmlEncode($course->getSessionBasicSearchKeyword()) ?>">
	<input type="submit" name="btnsubmit" id="btnsubmit" value="<?php echo ew_BtnCaption($Language->Phrase("QuickSearchBtn")) ?>">&nbsp;
	<a href="<?php echo $course_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>&nbsp;
</div>
<div id="xsr_2" class="ewRow">
	<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($course->getSessionBasicSearchType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($course->getSessionBasicSearchType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($course->getSessionBasicSearchType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
</div>
</div>
</div>
</form>
<?php } ?>
<?php $course_list->ShowPageHeader(); ?>
<?php
$course_list->ShowMessage();
?>
<br>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fcourselist" id="fcourselist" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="course">
<div id="gmp_course" class="ewGridMiddlePanel">
<?php if ($course_list->TotalRecs > 0) { ?>
<table cellspacing="0" id="tbl_courselist" class="ewTable ewTableSeparate">
<?php echo $course->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$course_list->RenderListOptions();

// Render list options (header, left)
$course_list->ListOptions->Render("header", "left");
?>
<?php if ($course->id->Visible) { // id ?>
	<?php if ($course->SortUrl($course->id) == "") { ?>
		<td><span id="elh_course_id" class="course_id"><?php echo $course->id->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $course->SortUrl($course->id) ?>',1);"><span id="elh_course_id" class="course_id">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $course->id->FldCaption() ?></td><td style="width: 10px;"><?php if ($course->id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($course->id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($course->courseName->Visible) { // courseName ?>
	<?php if ($course->SortUrl($course->courseName) == "") { ?>
		<td><span id="elh_course_courseName" class="course_courseName"><?php echo $course->courseName->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $course->SortUrl($course->courseName) ?>',1);"><span id="elh_course_courseName" class="course_courseName">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $course->courseName->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($course->courseName->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($course->courseName->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($course->courseEngName->Visible) { // courseEngName ?>
	<?php if ($course->SortUrl($course->courseEngName) == "") { ?>
		<td><span id="elh_course_courseEngName" class="course_courseEngName"><?php echo $course->courseEngName->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $course->SortUrl($course->courseEngName) ?>',1);"><span id="elh_course_courseEngName" class="course_courseEngName">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $course->courseEngName->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($course->courseEngName->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($course->courseEngName->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($course->courseCode->Visible) { // courseCode ?>
	<?php if ($course->SortUrl($course->courseCode) == "") { ?>
		<td><span id="elh_course_courseCode" class="course_courseCode"><?php echo $course->courseCode->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $course->SortUrl($course->courseCode) ?>',1);"><span id="elh_course_courseCode" class="course_courseCode">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $course->courseCode->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($course->courseCode->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($course->courseCode->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($course->courseXs->Visible) { // courseXs ?>
	<?php if ($course->SortUrl($course->courseXs) == "") { ?>
		<td><span id="elh_course_courseXs" class="course_courseXs"><?php echo $course->courseXs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $course->SortUrl($course->courseXs) ?>',1);"><span id="elh_course_courseXs" class="course_courseXs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $course->courseXs->FldCaption() ?></td><td style="width: 10px;"><?php if ($course->courseXs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($course->courseXs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($course->courseXf->Visible) { // courseXf ?>
	<?php if ($course->SortUrl($course->courseXf) == "") { ?>
		<td><span id="elh_course_courseXf" class="course_courseXf"><?php echo $course->courseXf->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $course->SortUrl($course->courseXf) ?>',1);"><span id="elh_course_courseXf" class="course_courseXf">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $course->courseXf->FldCaption() ?></td><td style="width: 10px;"><?php if ($course->courseXf->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($course->courseXf->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($course->courseXz->Visible) { // courseXz ?>
	<?php if ($course->SortUrl($course->courseXz) == "") { ?>
		<td><span id="elh_course_courseXz" class="course_courseXz"><?php echo $course->courseXz->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $course->SortUrl($course->courseXz) ?>',1);"><span id="elh_course_courseXz" class="course_courseXz">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $course->courseXz->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($course->courseXz->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($course->courseXz->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($course->courseLb->Visible) { // courseLb ?>
	<?php if ($course->SortUrl($course->courseLb) == "") { ?>
		<td><span id="elh_course_courseLb" class="course_courseLb"><?php echo $course->courseLb->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $course->SortUrl($course->courseLb) ?>',1);"><span id="elh_course_courseLb" class="course_courseLb">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $course->courseLb->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($course->courseLb->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($course->courseLb->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$course_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($course->ExportAll && $course->Export <> "") {
	$course_list->StopRec = $course_list->TotalRecs;
} else {

	// Set the last record to display
	if ($course_list->TotalRecs > $course_list->StartRec + $course_list->DisplayRecs - 1)
		$course_list->StopRec = $course_list->StartRec + $course_list->DisplayRecs - 1;
	else
		$course_list->StopRec = $course_list->TotalRecs;
}
$course_list->RecCnt = $course_list->StartRec - 1;
if ($course_list->Recordset && !$course_list->Recordset->EOF) {
	$course_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $course_list->StartRec > 1)
		$course_list->Recordset->Move($course_list->StartRec - 1);
} elseif (!$course->AllowAddDeleteRow && $course_list->StopRec == 0) {
	$course_list->StopRec = $course->GridAddRowCount;
}

// Initialize aggregate
$course->RowType = EW_ROWTYPE_AGGREGATEINIT;
$course->ResetAttrs();
$course_list->RenderRow();
while ($course_list->RecCnt < $course_list->StopRec) {
	$course_list->RecCnt++;
	if (intval($course_list->RecCnt) >= intval($course_list->StartRec)) {
		$course_list->RowCnt++;

		// Set up key count
		$course_list->KeyCount = $course_list->RowIndex;

		// Init row class and style
		$course->ResetAttrs();
		$course->CssClass = "";
		if ($course->CurrentAction == "gridadd") {
		} else {
			$course_list->LoadRowValues($course_list->Recordset); // Load row values
		}
		$course->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$course->RowAttrs = array_merge($course->RowAttrs, array('data-rowindex'=>$course_list->RowCnt, 'id'=>'r' . $course_list->RowCnt . '_course', 'data-rowtype'=>$course->RowType));

		// Render row
		$course_list->RenderRow();

			// Render list options
			$course_list->RenderListOptions();
?>
	<tr<?php echo $course->RowAttributes() ?>>
<?php

// Render list options (body, left)
$course_list->ListOptions->Render("body", "left", $course_list->RowCnt);
?>
	<?php if ($course->id->Visible) { // id ?>
		<td<?php echo $course->id->CellAttributes() ?>><span id="el<?php echo $course_list->RowCnt ?>_course_id" class="course_id">
<span<?php echo $course->id->ViewAttributes() ?>>
<?php echo $course->id->ListViewValue() ?></span>
<a name="<?php echo $course_list->PageObjName . "_row_" . $course_list->RowCnt ?>" id="<?php echo $course_list->PageObjName . "_row_" . $course_list->RowCnt ?>"></a></span></td>
	<?php } ?>
	<?php if ($course->courseName->Visible) { // courseName ?>
		<td<?php echo $course->courseName->CellAttributes() ?>><span id="el<?php echo $course_list->RowCnt ?>_course_courseName" class="course_courseName">
<span<?php echo $course->courseName->ViewAttributes() ?>>
<?php echo $course->courseName->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($course->courseEngName->Visible) { // courseEngName ?>
		<td<?php echo $course->courseEngName->CellAttributes() ?>><span id="el<?php echo $course_list->RowCnt ?>_course_courseEngName" class="course_courseEngName">
<span<?php echo $course->courseEngName->ViewAttributes() ?>>
<?php echo $course->courseEngName->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($course->courseCode->Visible) { // courseCode ?>
		<td<?php echo $course->courseCode->CellAttributes() ?>><span id="el<?php echo $course_list->RowCnt ?>_course_courseCode" class="course_courseCode">
<span<?php echo $course->courseCode->ViewAttributes() ?>>
<?php echo $course->courseCode->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($course->courseXs->Visible) { // courseXs ?>
		<td<?php echo $course->courseXs->CellAttributes() ?>><span id="el<?php echo $course_list->RowCnt ?>_course_courseXs" class="course_courseXs">
<span<?php echo $course->courseXs->ViewAttributes() ?>>
<?php echo $course->courseXs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($course->courseXf->Visible) { // courseXf ?>
		<td<?php echo $course->courseXf->CellAttributes() ?>><span id="el<?php echo $course_list->RowCnt ?>_course_courseXf" class="course_courseXf">
<span<?php echo $course->courseXf->ViewAttributes() ?>>
<?php echo $course->courseXf->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($course->courseXz->Visible) { // courseXz ?>
		<td<?php echo $course->courseXz->CellAttributes() ?>><span id="el<?php echo $course_list->RowCnt ?>_course_courseXz" class="course_courseXz">
<span<?php echo $course->courseXz->ViewAttributes() ?>>
<?php echo $course->courseXz->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($course->courseLb->Visible) { // courseLb ?>
		<td<?php echo $course->courseLb->CellAttributes() ?>><span id="el<?php echo $course_list->RowCnt ?>_course_courseLb" class="course_courseLb">
<span<?php echo $course->courseLb->ViewAttributes() ?>>
<?php echo $course->courseLb->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$course_list->ListOptions->Render("body", "right", $course_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($course->CurrentAction <> "gridadd")
		$course_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($course->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($course_list->Recordset)
	$course_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($course->CurrentAction <> "gridadd" && $course->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table border="0" cellspacing="0" cellpadding="0" class="ewPager">
	<tr>
		<td nowrap>
<?php if (!isset($course_list->Pager)) $course_list->Pager = new cPrevNextPager($course_list->StartRec, $course_list->DisplayRecs, $course_list->TotalRecs) ?>
<?php if ($course_list->Pager->RecordCount > 0) { ?>
	<table border="0" cellspacing="0" cellpadding="0"><tr><td><span class="phpmaker"><?php echo $Language->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($course_list->Pager->FirstButton->Enabled) { ?>
	<td><a href="<?php echo $course_list->PageUrl() ?>start=<?php echo $course_list->Pager->FirstButton->Start ?>"><img src="phpimages/first.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/firstdisab.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($course_list->Pager->PrevButton->Enabled) { ?>
	<td><a href="<?php echo $course_list->PageUrl() ?>start=<?php echo $course_list->Pager->PrevButton->Start ?>"><img src="phpimages/prev.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/prevdisab.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" id="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $course_list->Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($course_list->Pager->NextButton->Enabled) { ?>
	<td><a href="<?php echo $course_list->PageUrl() ?>start=<?php echo $course_list->Pager->NextButton->Start ?>"><img src="phpimages/next.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/nextdisab.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--last page button-->
	<?php if ($course_list->Pager->LastButton->Enabled) { ?>
	<td><a href="<?php echo $course_list->PageUrl() ?>start=<?php echo $course_list->Pager->LastButton->Start ?>"><img src="phpimages/last.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/lastdisab.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
	<td><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $course_list->Pager->PageCount ?></span></td>
	</tr></table>
	</td>	
	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td>
	<span class="phpmaker"><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $course_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $course_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $course_list->Pager->RecordCount ?></span>
<?php } else { ?>
	<?php if ($course_list->SearchWhere == "0=101") { ?>
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
<?php if ($course_list->AddUrl <> "") { ?>
<a class="ewGridLink" href="<?php echo $course_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>&nbsp;&nbsp;
<?php } ?>
</span>
</div>
</td></tr></table>
<script type="text/javascript">
fcourselistsrch.Init();
fcourselist.Init();
</script>
<?php
$course_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$course_list->Page_Terminate();
?>
