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

$classtime_list = NULL; // Initialize page object first

class cclasstime_list extends cclasstime {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{0FEB9E42-0859-45AD-84C6-C2CDD4EB41BE}";

	// Table name
	var $TableName = 'classtime';

	// Page object name
	var $PageObjName = 'classtime_list';

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

		// Table object (classtime)
		if (!isset($GLOBALS["classtime"])) {
			$GLOBALS["classtime"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["classtime"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "classtimeadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "classtimedelete.php";
		$this->MultiUpdateUrl = "classtimeupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'classtime', TRUE);

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
		$this->BuildBasicSearchSQL($sWhere, $this->courseId1, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->courseId2, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->courseId3, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->courseId4, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->courseId5, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->courseId6, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->courseId7, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->courseId8, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->courseId9, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->courseId10, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->courseId11, $Keyword);
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
			$this->UpdateSort($this->classroomId); // classroomId
			$this->UpdateSort($this->date); // date
			$this->UpdateSort($this->courseId1); // courseId1
			$this->UpdateSort($this->courseId2); // courseId2
			$this->UpdateSort($this->courseId3); // courseId3
			$this->UpdateSort($this->courseId4); // courseId4
			$this->UpdateSort($this->courseId5); // courseId5
			$this->UpdateSort($this->courseId6); // courseId6
			$this->UpdateSort($this->courseId7); // courseId7
			$this->UpdateSort($this->courseId8); // courseId8
			$this->UpdateSort($this->courseId9); // courseId9
			$this->UpdateSort($this->courseId10); // courseId10
			$this->UpdateSort($this->courseId11); // courseId11
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
				$this->classroomId->setSort("");
				$this->date->setSort("");
				$this->courseId1->setSort("");
				$this->courseId2->setSort("");
				$this->courseId3->setSort("");
				$this->courseId4->setSort("");
				$this->courseId5->setSort("");
				$this->courseId6->setSort("");
				$this->courseId7->setSort("");
				$this->courseId8->setSort("");
				$this->courseId9->setSort("");
				$this->courseId10->setSort("");
				$this->courseId11->setSort("");
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
if (!isset($classtime_list)) $classtime_list = new cclasstime_list();

// Page init
$classtime_list->Page_Init();

// Page main
$classtime_list->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var classtime_list = new ew_Page("classtime_list");
classtime_list.PageID = "list"; // Page ID
var EW_PAGE_ID = classtime_list.PageID; // For backward compatibility

// Form object
var fclasstimelist = new ew_Form("fclasstimelist");

// Form_CustomValidate event
fclasstimelist.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fclasstimelist.ValidateRequired = true;
<?php } else { ?>
fclasstimelist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fclasstimelistsrch = new ew_Form("fclasstimelistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$classtime_list->TotalRecs = $classtime->SelectRecordCount();
	} else {
		if ($classtime_list->Recordset = $classtime_list->LoadRecordset())
			$classtime_list->TotalRecs = $classtime_list->Recordset->RecordCount();
	}
	$classtime_list->StartRec = 1;
	if ($classtime_list->DisplayRecs <= 0 || ($classtime->Export <> "" && $classtime->ExportAll)) // Display all records
		$classtime_list->DisplayRecs = $classtime_list->TotalRecs;
	if (!($classtime->Export <> "" && $classtime->ExportAll))
		$classtime_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$classtime_list->Recordset = $classtime_list->LoadRecordset($classtime_list->StartRec-1, $classtime_list->DisplayRecs);
?>
<p style="white-space: nowrap;"><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $classtime->TableCaption() ?>&nbsp;&nbsp;</span>
<?php $classtime_list->ExportOptions->Render("body"); ?>
</p>
<?php if ($classtime->Export == "" && $classtime->CurrentAction == "") { ?>
<form name="fclasstimelistsrch" id="fclasstimelistsrch" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<a href="javascript:fclasstimelistsrch.ToggleSearchPanel();" style="text-decoration: none;"><img id="fclasstimelistsrch_SearchImage" src="phpimages/collapse.gif" alt="" width="9" height="9" border="0"></a><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("Search") ?></span><br>
<div id="fclasstimelistsrch_SearchPanel">
<input type="hidden" name="t" value="classtime">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" size="20" value="<?php echo ew_HtmlEncode($classtime->getSessionBasicSearchKeyword()) ?>">
	<input type="submit" name="btnsubmit" id="btnsubmit" value="<?php echo ew_BtnCaption($Language->Phrase("QuickSearchBtn")) ?>">&nbsp;
	<a href="<?php echo $classtime_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>&nbsp;
</div>
<div id="xsr_2" class="ewRow">
	<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($classtime->getSessionBasicSearchType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($classtime->getSessionBasicSearchType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($classtime->getSessionBasicSearchType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
</div>
</div>
</div>
</form>
<?php } ?>
<?php $classtime_list->ShowPageHeader(); ?>
<?php
$classtime_list->ShowMessage();
?>
<br>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fclasstimelist" id="fclasstimelist" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="classtime">
<div id="gmp_classtime" class="ewGridMiddlePanel">
<?php if ($classtime_list->TotalRecs > 0) { ?>
<table cellspacing="0" id="tbl_classtimelist" class="ewTable ewTableSeparate">
<?php echo $classtime->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$classtime_list->RenderListOptions();

// Render list options (header, left)
$classtime_list->ListOptions->Render("header", "left");
?>
<?php if ($classtime->id->Visible) { // id ?>
	<?php if ($classtime->SortUrl($classtime->id) == "") { ?>
		<td><span id="elh_classtime_id" class="classtime_id"><?php echo $classtime->id->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $classtime->SortUrl($classtime->id) ?>',1);"><span id="elh_classtime_id" class="classtime_id">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $classtime->id->FldCaption() ?></td><td style="width: 10px;"><?php if ($classtime->id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($classtime->id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($classtime->classroomId->Visible) { // classroomId ?>
	<?php if ($classtime->SortUrl($classtime->classroomId) == "") { ?>
		<td><span id="elh_classtime_classroomId" class="classtime_classroomId"><?php echo $classtime->classroomId->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $classtime->SortUrl($classtime->classroomId) ?>',1);"><span id="elh_classtime_classroomId" class="classtime_classroomId">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $classtime->classroomId->FldCaption() ?></td><td style="width: 10px;"><?php if ($classtime->classroomId->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($classtime->classroomId->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($classtime->date->Visible) { // date ?>
	<?php if ($classtime->SortUrl($classtime->date) == "") { ?>
		<td><span id="elh_classtime_date" class="classtime_date"><?php echo $classtime->date->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $classtime->SortUrl($classtime->date) ?>',1);"><span id="elh_classtime_date" class="classtime_date">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $classtime->date->FldCaption() ?></td><td style="width: 10px;"><?php if ($classtime->date->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($classtime->date->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($classtime->courseId1->Visible) { // courseId1 ?>
	<?php if ($classtime->SortUrl($classtime->courseId1) == "") { ?>
		<td><span id="elh_classtime_courseId1" class="classtime_courseId1"><?php echo $classtime->courseId1->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $classtime->SortUrl($classtime->courseId1) ?>',1);"><span id="elh_classtime_courseId1" class="classtime_courseId1">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $classtime->courseId1->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($classtime->courseId1->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($classtime->courseId1->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($classtime->courseId2->Visible) { // courseId2 ?>
	<?php if ($classtime->SortUrl($classtime->courseId2) == "") { ?>
		<td><span id="elh_classtime_courseId2" class="classtime_courseId2"><?php echo $classtime->courseId2->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $classtime->SortUrl($classtime->courseId2) ?>',1);"><span id="elh_classtime_courseId2" class="classtime_courseId2">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $classtime->courseId2->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($classtime->courseId2->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($classtime->courseId2->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($classtime->courseId3->Visible) { // courseId3 ?>
	<?php if ($classtime->SortUrl($classtime->courseId3) == "") { ?>
		<td><span id="elh_classtime_courseId3" class="classtime_courseId3"><?php echo $classtime->courseId3->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $classtime->SortUrl($classtime->courseId3) ?>',1);"><span id="elh_classtime_courseId3" class="classtime_courseId3">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $classtime->courseId3->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($classtime->courseId3->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($classtime->courseId3->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($classtime->courseId4->Visible) { // courseId4 ?>
	<?php if ($classtime->SortUrl($classtime->courseId4) == "") { ?>
		<td><span id="elh_classtime_courseId4" class="classtime_courseId4"><?php echo $classtime->courseId4->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $classtime->SortUrl($classtime->courseId4) ?>',1);"><span id="elh_classtime_courseId4" class="classtime_courseId4">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $classtime->courseId4->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($classtime->courseId4->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($classtime->courseId4->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($classtime->courseId5->Visible) { // courseId5 ?>
	<?php if ($classtime->SortUrl($classtime->courseId5) == "") { ?>
		<td><span id="elh_classtime_courseId5" class="classtime_courseId5"><?php echo $classtime->courseId5->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $classtime->SortUrl($classtime->courseId5) ?>',1);"><span id="elh_classtime_courseId5" class="classtime_courseId5">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $classtime->courseId5->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($classtime->courseId5->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($classtime->courseId5->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($classtime->courseId6->Visible) { // courseId6 ?>
	<?php if ($classtime->SortUrl($classtime->courseId6) == "") { ?>
		<td><span id="elh_classtime_courseId6" class="classtime_courseId6"><?php echo $classtime->courseId6->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $classtime->SortUrl($classtime->courseId6) ?>',1);"><span id="elh_classtime_courseId6" class="classtime_courseId6">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $classtime->courseId6->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($classtime->courseId6->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($classtime->courseId6->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($classtime->courseId7->Visible) { // courseId7 ?>
	<?php if ($classtime->SortUrl($classtime->courseId7) == "") { ?>
		<td><span id="elh_classtime_courseId7" class="classtime_courseId7"><?php echo $classtime->courseId7->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $classtime->SortUrl($classtime->courseId7) ?>',1);"><span id="elh_classtime_courseId7" class="classtime_courseId7">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $classtime->courseId7->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($classtime->courseId7->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($classtime->courseId7->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($classtime->courseId8->Visible) { // courseId8 ?>
	<?php if ($classtime->SortUrl($classtime->courseId8) == "") { ?>
		<td><span id="elh_classtime_courseId8" class="classtime_courseId8"><?php echo $classtime->courseId8->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $classtime->SortUrl($classtime->courseId8) ?>',1);"><span id="elh_classtime_courseId8" class="classtime_courseId8">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $classtime->courseId8->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($classtime->courseId8->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($classtime->courseId8->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($classtime->courseId9->Visible) { // courseId9 ?>
	<?php if ($classtime->SortUrl($classtime->courseId9) == "") { ?>
		<td><span id="elh_classtime_courseId9" class="classtime_courseId9"><?php echo $classtime->courseId9->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $classtime->SortUrl($classtime->courseId9) ?>',1);"><span id="elh_classtime_courseId9" class="classtime_courseId9">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $classtime->courseId9->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($classtime->courseId9->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($classtime->courseId9->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($classtime->courseId10->Visible) { // courseId10 ?>
	<?php if ($classtime->SortUrl($classtime->courseId10) == "") { ?>
		<td><span id="elh_classtime_courseId10" class="classtime_courseId10"><?php echo $classtime->courseId10->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $classtime->SortUrl($classtime->courseId10) ?>',1);"><span id="elh_classtime_courseId10" class="classtime_courseId10">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $classtime->courseId10->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($classtime->courseId10->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($classtime->courseId10->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($classtime->courseId11->Visible) { // courseId11 ?>
	<?php if ($classtime->SortUrl($classtime->courseId11) == "") { ?>
		<td><span id="elh_classtime_courseId11" class="classtime_courseId11"><?php echo $classtime->courseId11->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $classtime->SortUrl($classtime->courseId11) ?>',1);"><span id="elh_classtime_courseId11" class="classtime_courseId11">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $classtime->courseId11->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($classtime->courseId11->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($classtime->courseId11->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$classtime_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($classtime->ExportAll && $classtime->Export <> "") {
	$classtime_list->StopRec = $classtime_list->TotalRecs;
} else {

	// Set the last record to display
	if ($classtime_list->TotalRecs > $classtime_list->StartRec + $classtime_list->DisplayRecs - 1)
		$classtime_list->StopRec = $classtime_list->StartRec + $classtime_list->DisplayRecs - 1;
	else
		$classtime_list->StopRec = $classtime_list->TotalRecs;
}
$classtime_list->RecCnt = $classtime_list->StartRec - 1;
if ($classtime_list->Recordset && !$classtime_list->Recordset->EOF) {
	$classtime_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $classtime_list->StartRec > 1)
		$classtime_list->Recordset->Move($classtime_list->StartRec - 1);
} elseif (!$classtime->AllowAddDeleteRow && $classtime_list->StopRec == 0) {
	$classtime_list->StopRec = $classtime->GridAddRowCount;
}

// Initialize aggregate
$classtime->RowType = EW_ROWTYPE_AGGREGATEINIT;
$classtime->ResetAttrs();
$classtime_list->RenderRow();
while ($classtime_list->RecCnt < $classtime_list->StopRec) {
	$classtime_list->RecCnt++;
	if (intval($classtime_list->RecCnt) >= intval($classtime_list->StartRec)) {
		$classtime_list->RowCnt++;

		// Set up key count
		$classtime_list->KeyCount = $classtime_list->RowIndex;

		// Init row class and style
		$classtime->ResetAttrs();
		$classtime->CssClass = "";
		if ($classtime->CurrentAction == "gridadd") {
		} else {
			$classtime_list->LoadRowValues($classtime_list->Recordset); // Load row values
		}
		$classtime->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$classtime->RowAttrs = array_merge($classtime->RowAttrs, array('data-rowindex'=>$classtime_list->RowCnt, 'id'=>'r' . $classtime_list->RowCnt . '_classtime', 'data-rowtype'=>$classtime->RowType));

		// Render row
		$classtime_list->RenderRow();

			// Render list options
			$classtime_list->RenderListOptions();
?>
	<tr<?php echo $classtime->RowAttributes() ?>>
<?php

// Render list options (body, left)
$classtime_list->ListOptions->Render("body", "left", $classtime_list->RowCnt);
?>
	<?php if ($classtime->id->Visible) { // id ?>
		<td<?php echo $classtime->id->CellAttributes() ?>><span id="el<?php echo $classtime_list->RowCnt ?>_classtime_id" class="classtime_id">
<span<?php echo $classtime->id->ViewAttributes() ?>>
<?php echo $classtime->id->ListViewValue() ?></span>
<a name="<?php echo $classtime_list->PageObjName . "_row_" . $classtime_list->RowCnt ?>" id="<?php echo $classtime_list->PageObjName . "_row_" . $classtime_list->RowCnt ?>"></a></span></td>
	<?php } ?>
	<?php if ($classtime->classroomId->Visible) { // classroomId ?>
		<td<?php echo $classtime->classroomId->CellAttributes() ?>><span id="el<?php echo $classtime_list->RowCnt ?>_classtime_classroomId" class="classtime_classroomId">
<span<?php echo $classtime->classroomId->ViewAttributes() ?>>
<?php echo $classtime->classroomId->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($classtime->date->Visible) { // date ?>
		<td<?php echo $classtime->date->CellAttributes() ?>><span id="el<?php echo $classtime_list->RowCnt ?>_classtime_date" class="classtime_date">
<span<?php echo $classtime->date->ViewAttributes() ?>>
<?php echo $classtime->date->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($classtime->courseId1->Visible) { // courseId1 ?>
		<td<?php echo $classtime->courseId1->CellAttributes() ?>><span id="el<?php echo $classtime_list->RowCnt ?>_classtime_courseId1" class="classtime_courseId1">
<span<?php echo $classtime->courseId1->ViewAttributes() ?>>
<?php echo $classtime->courseId1->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($classtime->courseId2->Visible) { // courseId2 ?>
		<td<?php echo $classtime->courseId2->CellAttributes() ?>><span id="el<?php echo $classtime_list->RowCnt ?>_classtime_courseId2" class="classtime_courseId2">
<span<?php echo $classtime->courseId2->ViewAttributes() ?>>
<?php echo $classtime->courseId2->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($classtime->courseId3->Visible) { // courseId3 ?>
		<td<?php echo $classtime->courseId3->CellAttributes() ?>><span id="el<?php echo $classtime_list->RowCnt ?>_classtime_courseId3" class="classtime_courseId3">
<span<?php echo $classtime->courseId3->ViewAttributes() ?>>
<?php echo $classtime->courseId3->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($classtime->courseId4->Visible) { // courseId4 ?>
		<td<?php echo $classtime->courseId4->CellAttributes() ?>><span id="el<?php echo $classtime_list->RowCnt ?>_classtime_courseId4" class="classtime_courseId4">
<span<?php echo $classtime->courseId4->ViewAttributes() ?>>
<?php echo $classtime->courseId4->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($classtime->courseId5->Visible) { // courseId5 ?>
		<td<?php echo $classtime->courseId5->CellAttributes() ?>><span id="el<?php echo $classtime_list->RowCnt ?>_classtime_courseId5" class="classtime_courseId5">
<span<?php echo $classtime->courseId5->ViewAttributes() ?>>
<?php echo $classtime->courseId5->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($classtime->courseId6->Visible) { // courseId6 ?>
		<td<?php echo $classtime->courseId6->CellAttributes() ?>><span id="el<?php echo $classtime_list->RowCnt ?>_classtime_courseId6" class="classtime_courseId6">
<span<?php echo $classtime->courseId6->ViewAttributes() ?>>
<?php echo $classtime->courseId6->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($classtime->courseId7->Visible) { // courseId7 ?>
		<td<?php echo $classtime->courseId7->CellAttributes() ?>><span id="el<?php echo $classtime_list->RowCnt ?>_classtime_courseId7" class="classtime_courseId7">
<span<?php echo $classtime->courseId7->ViewAttributes() ?>>
<?php echo $classtime->courseId7->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($classtime->courseId8->Visible) { // courseId8 ?>
		<td<?php echo $classtime->courseId8->CellAttributes() ?>><span id="el<?php echo $classtime_list->RowCnt ?>_classtime_courseId8" class="classtime_courseId8">
<span<?php echo $classtime->courseId8->ViewAttributes() ?>>
<?php echo $classtime->courseId8->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($classtime->courseId9->Visible) { // courseId9 ?>
		<td<?php echo $classtime->courseId9->CellAttributes() ?>><span id="el<?php echo $classtime_list->RowCnt ?>_classtime_courseId9" class="classtime_courseId9">
<span<?php echo $classtime->courseId9->ViewAttributes() ?>>
<?php echo $classtime->courseId9->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($classtime->courseId10->Visible) { // courseId10 ?>
		<td<?php echo $classtime->courseId10->CellAttributes() ?>><span id="el<?php echo $classtime_list->RowCnt ?>_classtime_courseId10" class="classtime_courseId10">
<span<?php echo $classtime->courseId10->ViewAttributes() ?>>
<?php echo $classtime->courseId10->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($classtime->courseId11->Visible) { // courseId11 ?>
		<td<?php echo $classtime->courseId11->CellAttributes() ?>><span id="el<?php echo $classtime_list->RowCnt ?>_classtime_courseId11" class="classtime_courseId11">
<span<?php echo $classtime->courseId11->ViewAttributes() ?>>
<?php echo $classtime->courseId11->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$classtime_list->ListOptions->Render("body", "right", $classtime_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($classtime->CurrentAction <> "gridadd")
		$classtime_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($classtime->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($classtime_list->Recordset)
	$classtime_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($classtime->CurrentAction <> "gridadd" && $classtime->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table border="0" cellspacing="0" cellpadding="0" class="ewPager">
	<tr>
		<td nowrap>
<?php if (!isset($classtime_list->Pager)) $classtime_list->Pager = new cPrevNextPager($classtime_list->StartRec, $classtime_list->DisplayRecs, $classtime_list->TotalRecs) ?>
<?php if ($classtime_list->Pager->RecordCount > 0) { ?>
	<table border="0" cellspacing="0" cellpadding="0"><tr><td><span class="phpmaker"><?php echo $Language->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($classtime_list->Pager->FirstButton->Enabled) { ?>
	<td><a href="<?php echo $classtime_list->PageUrl() ?>start=<?php echo $classtime_list->Pager->FirstButton->Start ?>"><img src="phpimages/first.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/firstdisab.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($classtime_list->Pager->PrevButton->Enabled) { ?>
	<td><a href="<?php echo $classtime_list->PageUrl() ?>start=<?php echo $classtime_list->Pager->PrevButton->Start ?>"><img src="phpimages/prev.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/prevdisab.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" id="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $classtime_list->Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($classtime_list->Pager->NextButton->Enabled) { ?>
	<td><a href="<?php echo $classtime_list->PageUrl() ?>start=<?php echo $classtime_list->Pager->NextButton->Start ?>"><img src="phpimages/next.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/nextdisab.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--last page button-->
	<?php if ($classtime_list->Pager->LastButton->Enabled) { ?>
	<td><a href="<?php echo $classtime_list->PageUrl() ?>start=<?php echo $classtime_list->Pager->LastButton->Start ?>"><img src="phpimages/last.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/lastdisab.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
	<td><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $classtime_list->Pager->PageCount ?></span></td>
	</tr></table>
	</td>	
	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td>
	<span class="phpmaker"><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $classtime_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $classtime_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $classtime_list->Pager->RecordCount ?></span>
<?php } else { ?>
	<?php if ($classtime_list->SearchWhere == "0=101") { ?>
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
<?php if ($classtime_list->AddUrl <> "") { ?>
<a class="ewGridLink" href="<?php echo $classtime_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>&nbsp;&nbsp;
<?php } ?>
</span>
</div>
</td></tr></table>
<script type="text/javascript">
fclasstimelistsrch.Init();
fclasstimelist.Init();
</script>
<?php
$classtime_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$classtime_list->Page_Terminate();
?>
