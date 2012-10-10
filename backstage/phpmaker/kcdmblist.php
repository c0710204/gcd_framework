<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "kcdmbinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$kcdmb_list = NULL; // Initialize page object first

class ckcdmb_list extends ckcdmb {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{0FEB9E42-0859-45AD-84C6-C2CDD4EB41BE}";

	// Table name
	var $TableName = 'kcdmb';

	// Page object name
	var $PageObjName = 'kcdmb_list';

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

		// Table object (kcdmb)
		if (!isset($GLOBALS["kcdmb"])) {
			$GLOBALS["kcdmb"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["kcdmb"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "kcdmbadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "kcdmbdelete.php";
		$this->MultiUpdateUrl = "kcdmbupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'kcdmb', TRUE);

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
		if (count($arrKeyFlds) >= 0) {
		}
		return TRUE;
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->kcdm, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->kczwmc, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->kcywmc, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->xf, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->zxs, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->zs, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->yxyq, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->kcjj, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->jxdg, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->bs1, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->bs2, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->qzxs, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->ksnrjbz, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->sfwyb, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->kclb, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->kkbmdm, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->zhxs, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->yxj, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->pksj, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->pkyq, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->xs, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->kczyzyjmd, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->zycks, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->kthkcdm, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->xlcc, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->gzlxs, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->khfs, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->kcys, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->tkbj, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->bz, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->kcxz, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->skfsmc, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->axbxrw, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->typk, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->sykkbmdm, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->bsfbj, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->temp1, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->temp2, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->temp3, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->temp4, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->temp5, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->temp6, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->temp7, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->temp8, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->temp9, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->temp10, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->syxfyq, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->kcgs, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->kkxdm, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->xkfl, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->bs11, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->kcsjxs, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->xtkxs, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->knsjxs, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->kwsjxs, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->jys, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->sftykw, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->kcjc, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->kwxs, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->xkdx, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->jsxm, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->bs3, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->xfjs, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->zhxsjs, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->jkxsjs, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->syxsjs, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->sjxsjs, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->sfxssy, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->kcjsztdw, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->bs4, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->syzy, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->lrsj, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->kcmcpy, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->xqdm, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->kcqmc, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->ksxsmc, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->sfbysjkc, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->bs5, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->nj, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->cjlrr, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->sftsbx, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->dxdgdz, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->kcfl, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->kcywjj, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->sjlrzgh, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->kcjjdz, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->yqdm, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->yqmc, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->bsyz, $Keyword);
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
			$this->UpdateSort($this->kcdm); // kcdm
			$this->UpdateSort($this->kczwmc); // kczwmc
			$this->UpdateSort($this->kcywmc); // kcywmc
			$this->UpdateSort($this->xf); // xf
			$this->UpdateSort($this->zxs); // zxs
			$this->UpdateSort($this->zs); // zs
			$this->UpdateSort($this->yxyq); // yxyq
			$this->UpdateSort($this->bs1); // bs1
			$this->UpdateSort($this->bs2); // bs2
			$this->UpdateSort($this->qzxs); // qzxs
			$this->UpdateSort($this->sfwyb); // sfwyb
			$this->UpdateSort($this->zdkkrs); // zdkkrs
			$this->UpdateSort($this->kclb); // kclb
			$this->UpdateSort($this->kkbmdm); // kkbmdm
			$this->UpdateSort($this->zhxs); // zhxs
			$this->UpdateSort($this->yxj); // yxj
			$this->UpdateSort($this->pksj); // pksj
			$this->UpdateSort($this->pkyq); // pkyq
			$this->UpdateSort($this->xs); // xs
			$this->UpdateSort($this->kthkcdm); // kthkcdm
			$this->UpdateSort($this->xlcc); // xlcc
			$this->UpdateSort($this->gzlxs); // gzlxs
			$this->UpdateSort($this->khfs); // khfs
			$this->UpdateSort($this->kcys); // kcys
			$this->UpdateSort($this->tkbj); // tkbj
			$this->UpdateSort($this->llxs); // llxs
			$this->UpdateSort($this->syxs); // syxs
			$this->UpdateSort($this->sjxs); // sjxs
			$this->UpdateSort($this->bz); // bz
			$this->UpdateSort($this->kcxz); // kcxz
			$this->UpdateSort($this->zcfy); // zcfy
			$this->UpdateSort($this->cxfy); // cxfy
			$this->UpdateSort($this->fxfy); // fxfy
			$this->UpdateSort($this->syxmsyq); // syxmsyq
			$this->UpdateSort($this->skfsmc); // skfsmc
			$this->UpdateSort($this->axbxrw); // axbxrw
			$this->UpdateSort($this->typk); // typk
			$this->UpdateSort($this->sykkbmdm); // sykkbmdm
			$this->UpdateSort($this->bsfbj); // bsfbj
			$this->UpdateSort($this->syxfyq); // syxfyq
			$this->UpdateSort($this->kcgs); // kcgs
			$this->UpdateSort($this->kkxdm); // kkxdm
			$this->UpdateSort($this->xkfl); // xkfl
			$this->UpdateSort($this->bs11); // bs11
			$this->UpdateSort($this->kcsjxs); // kcsjxs
			$this->UpdateSort($this->xtkxs); // xtkxs
			$this->UpdateSort($this->knsjxs); // knsjxs
			$this->UpdateSort($this->kwsjxs); // kwsjxs
			$this->UpdateSort($this->ytxs); // ytxs
			$this->UpdateSort($this->scjssjxs); // scjssjxs
			$this->UpdateSort($this->sxxs); // sxxs
			$this->UpdateSort($this->ksxs); // ksxs
			$this->UpdateSort($this->bsxs); // bsxs
			$this->UpdateSort($this->shdcxs); // shdcxs
			$this->UpdateSort($this->jys); // jys
			$this->UpdateSort($this->sftykw); // sftykw
			$this->UpdateSort($this->kcjc); // kcjc
			$this->UpdateSort($this->kwxs); // kwxs
			$this->UpdateSort($this->xkdx); // xkdx
			$this->UpdateSort($this->jsxm); // jsxm
			$this->UpdateSort($this->bs3); // bs3
			$this->UpdateSort($this->xfjs); // xfjs
			$this->UpdateSort($this->zhxsjs); // zhxsjs
			$this->UpdateSort($this->jkxsjs); // jkxsjs
			$this->UpdateSort($this->syxsjs); // syxsjs
			$this->UpdateSort($this->sjxsjs); // sjxsjs
			$this->UpdateSort($this->sfxssy); // sfxssy
			$this->UpdateSort($this->kcjsztdw); // kcjsztdw
			$this->UpdateSort($this->bs4); // bs4
			$this->UpdateSort($this->syzy); // syzy
			$this->UpdateSort($this->lrsj); // lrsj
			$this->UpdateSort($this->kcmcpy); // kcmcpy
			$this->UpdateSort($this->xqdm); // xqdm
			$this->UpdateSort($this->kcqmc); // kcqmc
			$this->UpdateSort($this->ksxsmc); // ksxsmc
			$this->UpdateSort($this->sfbysjkc); // sfbysjkc
			$this->UpdateSort($this->bs5); // bs5
			$this->UpdateSort($this->nj); // nj
			$this->UpdateSort($this->cjlrr); // cjlrr
			$this->UpdateSort($this->sftsbx); // sftsbx
			$this->UpdateSort($this->dxdgdz); // dxdgdz
			$this->UpdateSort($this->kcfl); // kcfl
			$this->UpdateSort($this->sjlrzgh); // sjlrzgh
			$this->UpdateSort($this->kcjjdz); // kcjjdz
			$this->UpdateSort($this->yqdm); // yqdm
			$this->UpdateSort($this->yqmc); // yqmc
			$this->UpdateSort($this->bsyz); // bsyz
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
				$this->kcdm->setSort("");
				$this->kczwmc->setSort("");
				$this->kcywmc->setSort("");
				$this->xf->setSort("");
				$this->zxs->setSort("");
				$this->zs->setSort("");
				$this->yxyq->setSort("");
				$this->bs1->setSort("");
				$this->bs2->setSort("");
				$this->qzxs->setSort("");
				$this->sfwyb->setSort("");
				$this->zdkkrs->setSort("");
				$this->kclb->setSort("");
				$this->kkbmdm->setSort("");
				$this->zhxs->setSort("");
				$this->yxj->setSort("");
				$this->pksj->setSort("");
				$this->pkyq->setSort("");
				$this->xs->setSort("");
				$this->kthkcdm->setSort("");
				$this->xlcc->setSort("");
				$this->gzlxs->setSort("");
				$this->khfs->setSort("");
				$this->kcys->setSort("");
				$this->tkbj->setSort("");
				$this->llxs->setSort("");
				$this->syxs->setSort("");
				$this->sjxs->setSort("");
				$this->bz->setSort("");
				$this->kcxz->setSort("");
				$this->zcfy->setSort("");
				$this->cxfy->setSort("");
				$this->fxfy->setSort("");
				$this->syxmsyq->setSort("");
				$this->skfsmc->setSort("");
				$this->axbxrw->setSort("");
				$this->typk->setSort("");
				$this->sykkbmdm->setSort("");
				$this->bsfbj->setSort("");
				$this->syxfyq->setSort("");
				$this->kcgs->setSort("");
				$this->kkxdm->setSort("");
				$this->xkfl->setSort("");
				$this->bs11->setSort("");
				$this->kcsjxs->setSort("");
				$this->xtkxs->setSort("");
				$this->knsjxs->setSort("");
				$this->kwsjxs->setSort("");
				$this->ytxs->setSort("");
				$this->scjssjxs->setSort("");
				$this->sxxs->setSort("");
				$this->ksxs->setSort("");
				$this->bsxs->setSort("");
				$this->shdcxs->setSort("");
				$this->jys->setSort("");
				$this->sftykw->setSort("");
				$this->kcjc->setSort("");
				$this->kwxs->setSort("");
				$this->xkdx->setSort("");
				$this->jsxm->setSort("");
				$this->bs3->setSort("");
				$this->xfjs->setSort("");
				$this->zhxsjs->setSort("");
				$this->jkxsjs->setSort("");
				$this->syxsjs->setSort("");
				$this->sjxsjs->setSort("");
				$this->sfxssy->setSort("");
				$this->kcjsztdw->setSort("");
				$this->bs4->setSort("");
				$this->syzy->setSort("");
				$this->lrsj->setSort("");
				$this->kcmcpy->setSort("");
				$this->xqdm->setSort("");
				$this->kcqmc->setSort("");
				$this->ksxsmc->setSort("");
				$this->sfbysjkc->setSort("");
				$this->bs5->setSort("");
				$this->nj->setSort("");
				$this->cjlrr->setSort("");
				$this->sftsbx->setSort("");
				$this->dxdgdz->setSort("");
				$this->kcfl->setSort("");
				$this->sjlrzgh->setSort("");
				$this->kcjjdz->setSort("");
				$this->yqdm->setSort("");
				$this->yqmc->setSort("");
				$this->bsyz->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Call ListOptions_Load event
		$this->ListOptions_Load();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();
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
		$this->kcdm->setDbValue($rs->fields('kcdm'));
		$this->kczwmc->setDbValue($rs->fields('kczwmc'));
		$this->kcywmc->setDbValue($rs->fields('kcywmc'));
		$this->xf->setDbValue($rs->fields('xf'));
		$this->zxs->setDbValue($rs->fields('zxs'));
		$this->zs->setDbValue($rs->fields('zs'));
		$this->yxyq->setDbValue($rs->fields('yxyq'));
		$this->kcjj->setDbValue($rs->fields('kcjj'));
		$this->jxdg->setDbValue($rs->fields('jxdg'));
		$this->bs1->setDbValue($rs->fields('bs1'));
		$this->bs2->setDbValue($rs->fields('bs2'));
		$this->qzxs->setDbValue($rs->fields('qzxs'));
		$this->ksnrjbz->setDbValue($rs->fields('ksnrjbz'));
		$this->sfwyb->setDbValue($rs->fields('sfwyb'));
		$this->zdkkrs->setDbValue($rs->fields('zdkkrs'));
		$this->kclb->setDbValue($rs->fields('kclb'));
		$this->kkbmdm->setDbValue($rs->fields('kkbmdm'));
		$this->zhxs->setDbValue($rs->fields('zhxs'));
		$this->yxj->setDbValue($rs->fields('yxj'));
		$this->pksj->setDbValue($rs->fields('pksj'));
		$this->pkyq->setDbValue($rs->fields('pkyq'));
		$this->xs->setDbValue($rs->fields('xs'));
		$this->kczyzyjmd->setDbValue($rs->fields('kczyzyjmd'));
		$this->zycks->setDbValue($rs->fields('zycks'));
		$this->kthkcdm->setDbValue($rs->fields('kthkcdm'));
		$this->xlcc->setDbValue($rs->fields('xlcc'));
		$this->gzlxs->setDbValue($rs->fields('gzlxs'));
		$this->khfs->setDbValue($rs->fields('khfs'));
		$this->kcys->setDbValue($rs->fields('kcys'));
		$this->tkbj->setDbValue($rs->fields('tkbj'));
		$this->llxs->setDbValue($rs->fields('llxs'));
		$this->syxs->setDbValue($rs->fields('syxs'));
		$this->sjxs->setDbValue($rs->fields('sjxs'));
		$this->bz->setDbValue($rs->fields('bz'));
		$this->kcxz->setDbValue($rs->fields('kcxz'));
		$this->zcfy->setDbValue($rs->fields('zcfy'));
		$this->cxfy->setDbValue($rs->fields('cxfy'));
		$this->fxfy->setDbValue($rs->fields('fxfy'));
		$this->syxmsyq->setDbValue($rs->fields('syxmsyq'));
		$this->skfsmc->setDbValue($rs->fields('skfsmc'));
		$this->axbxrw->setDbValue($rs->fields('axbxrw'));
		$this->typk->setDbValue($rs->fields('typk'));
		$this->sykkbmdm->setDbValue($rs->fields('sykkbmdm'));
		$this->bsfbj->setDbValue($rs->fields('bsfbj'));
		$this->temp1->setDbValue($rs->fields('temp1'));
		$this->temp2->setDbValue($rs->fields('temp2'));
		$this->temp3->setDbValue($rs->fields('temp3'));
		$this->temp4->setDbValue($rs->fields('temp4'));
		$this->temp5->setDbValue($rs->fields('temp5'));
		$this->temp6->setDbValue($rs->fields('temp6'));
		$this->temp7->setDbValue($rs->fields('temp7'));
		$this->temp8->setDbValue($rs->fields('temp8'));
		$this->temp9->setDbValue($rs->fields('temp9'));
		$this->temp10->setDbValue($rs->fields('temp10'));
		$this->syxfyq->setDbValue($rs->fields('syxfyq'));
		$this->kcgs->setDbValue($rs->fields('kcgs'));
		$this->kkxdm->setDbValue($rs->fields('kkxdm'));
		$this->xkfl->setDbValue($rs->fields('xkfl'));
		$this->bs11->setDbValue($rs->fields('bs11'));
		$this->kcsjxs->setDbValue($rs->fields('kcsjxs'));
		$this->xtkxs->setDbValue($rs->fields('xtkxs'));
		$this->knsjxs->setDbValue($rs->fields('knsjxs'));
		$this->kwsjxs->setDbValue($rs->fields('kwsjxs'));
		$this->ytxs->setDbValue($rs->fields('ytxs'));
		$this->scjssjxs->setDbValue($rs->fields('scjssjxs'));
		$this->sxxs->setDbValue($rs->fields('sxxs'));
		$this->ksxs->setDbValue($rs->fields('ksxs'));
		$this->bsxs->setDbValue($rs->fields('bsxs'));
		$this->shdcxs->setDbValue($rs->fields('shdcxs'));
		$this->jys->setDbValue($rs->fields('jys'));
		$this->sftykw->setDbValue($rs->fields('sftykw'));
		$this->kcjc->setDbValue($rs->fields('kcjc'));
		$this->kwxs->setDbValue($rs->fields('kwxs'));
		$this->xkdx->setDbValue($rs->fields('xkdx'));
		$this->jsxm->setDbValue($rs->fields('jsxm'));
		$this->bs3->setDbValue($rs->fields('bs3'));
		$this->xfjs->setDbValue($rs->fields('xfjs'));
		$this->zhxsjs->setDbValue($rs->fields('zhxsjs'));
		$this->jkxsjs->setDbValue($rs->fields('jkxsjs'));
		$this->syxsjs->setDbValue($rs->fields('syxsjs'));
		$this->sjxsjs->setDbValue($rs->fields('sjxsjs'));
		$this->sfxssy->setDbValue($rs->fields('sfxssy'));
		$this->kcjsztdw->setDbValue($rs->fields('kcjsztdw'));
		$this->bs4->setDbValue($rs->fields('bs4'));
		$this->syzy->setDbValue($rs->fields('syzy'));
		$this->lrsj->setDbValue($rs->fields('lrsj'));
		$this->kcmcpy->setDbValue($rs->fields('kcmcpy'));
		$this->xqdm->setDbValue($rs->fields('xqdm'));
		$this->kcqmc->setDbValue($rs->fields('kcqmc'));
		$this->ksxsmc->setDbValue($rs->fields('ksxsmc'));
		$this->sfbysjkc->setDbValue($rs->fields('sfbysjkc'));
		$this->bs5->setDbValue($rs->fields('bs5'));
		$this->nj->setDbValue($rs->fields('nj'));
		$this->cjlrr->setDbValue($rs->fields('cjlrr'));
		$this->sftsbx->setDbValue($rs->fields('sftsbx'));
		$this->dxdgdz->setDbValue($rs->fields('dxdgdz'));
		$this->kcfl->setDbValue($rs->fields('kcfl'));
		$this->kcywjj->setDbValue($rs->fields('kcywjj'));
		$this->sjlrzgh->setDbValue($rs->fields('sjlrzgh'));
		$this->kcjjdz->setDbValue($rs->fields('kcjjdz'));
		$this->yqdm->setDbValue($rs->fields('yqdm'));
		$this->yqmc->setDbValue($rs->fields('yqmc'));
		$this->bsyz->setDbValue($rs->fields('bsyz'));
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;

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
		if ($this->zdkkrs->FormValue == $this->zdkkrs->CurrentValue)
			$this->zdkkrs->CurrentValue = ew_StrToFloat($this->zdkkrs->CurrentValue);

		// Convert decimal values if posted back
		if ($this->llxs->FormValue == $this->llxs->CurrentValue)
			$this->llxs->CurrentValue = ew_StrToFloat($this->llxs->CurrentValue);

		// Convert decimal values if posted back
		if ($this->syxs->FormValue == $this->syxs->CurrentValue)
			$this->syxs->CurrentValue = ew_StrToFloat($this->syxs->CurrentValue);

		// Convert decimal values if posted back
		if ($this->sjxs->FormValue == $this->sjxs->CurrentValue)
			$this->sjxs->CurrentValue = ew_StrToFloat($this->sjxs->CurrentValue);

		// Convert decimal values if posted back
		if ($this->zcfy->FormValue == $this->zcfy->CurrentValue)
			$this->zcfy->CurrentValue = ew_StrToFloat($this->zcfy->CurrentValue);

		// Convert decimal values if posted back
		if ($this->cxfy->FormValue == $this->cxfy->CurrentValue)
			$this->cxfy->CurrentValue = ew_StrToFloat($this->cxfy->CurrentValue);

		// Convert decimal values if posted back
		if ($this->fxfy->FormValue == $this->fxfy->CurrentValue)
			$this->fxfy->CurrentValue = ew_StrToFloat($this->fxfy->CurrentValue);

		// Convert decimal values if posted back
		if ($this->syxmsyq->FormValue == $this->syxmsyq->CurrentValue)
			$this->syxmsyq->CurrentValue = ew_StrToFloat($this->syxmsyq->CurrentValue);

		// Convert decimal values if posted back
		if ($this->ytxs->FormValue == $this->ytxs->CurrentValue)
			$this->ytxs->CurrentValue = ew_StrToFloat($this->ytxs->CurrentValue);

		// Convert decimal values if posted back
		if ($this->scjssjxs->FormValue == $this->scjssjxs->CurrentValue)
			$this->scjssjxs->CurrentValue = ew_StrToFloat($this->scjssjxs->CurrentValue);

		// Convert decimal values if posted back
		if ($this->sxxs->FormValue == $this->sxxs->CurrentValue)
			$this->sxxs->CurrentValue = ew_StrToFloat($this->sxxs->CurrentValue);

		// Convert decimal values if posted back
		if ($this->ksxs->FormValue == $this->ksxs->CurrentValue)
			$this->ksxs->CurrentValue = ew_StrToFloat($this->ksxs->CurrentValue);

		// Convert decimal values if posted back
		if ($this->bsxs->FormValue == $this->bsxs->CurrentValue)
			$this->bsxs->CurrentValue = ew_StrToFloat($this->bsxs->CurrentValue);

		// Convert decimal values if posted back
		if ($this->shdcxs->FormValue == $this->shdcxs->CurrentValue)
			$this->shdcxs->CurrentValue = ew_StrToFloat($this->shdcxs->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// kcdm
		// kczwmc
		// kcywmc
		// xf
		// zxs
		// zs
		// yxyq
		// kcjj
		// jxdg
		// bs1
		// bs2
		// qzxs
		// ksnrjbz
		// sfwyb
		// zdkkrs
		// kclb
		// kkbmdm
		// zhxs
		// yxj
		// pksj
		// pkyq
		// xs
		// kczyzyjmd
		// zycks
		// kthkcdm
		// xlcc
		// gzlxs
		// khfs
		// kcys
		// tkbj
		// llxs
		// syxs
		// sjxs
		// bz
		// kcxz
		// zcfy
		// cxfy
		// fxfy
		// syxmsyq
		// skfsmc
		// axbxrw
		// typk
		// sykkbmdm
		// bsfbj
		// temp1
		// temp2
		// temp3
		// temp4
		// temp5
		// temp6
		// temp7
		// temp8
		// temp9
		// temp10
		// syxfyq
		// kcgs
		// kkxdm
		// xkfl
		// bs11
		// kcsjxs
		// xtkxs
		// knsjxs
		// kwsjxs
		// ytxs
		// scjssjxs
		// sxxs
		// ksxs
		// bsxs
		// shdcxs
		// jys
		// sftykw
		// kcjc
		// kwxs
		// xkdx
		// jsxm
		// bs3
		// xfjs
		// zhxsjs
		// jkxsjs
		// syxsjs
		// sjxsjs
		// sfxssy
		// kcjsztdw
		// bs4
		// syzy
		// lrsj
		// kcmcpy
		// xqdm
		// kcqmc
		// ksxsmc
		// sfbysjkc
		// bs5
		// nj
		// cjlrr
		// sftsbx
		// dxdgdz
		// kcfl
		// kcywjj
		// sjlrzgh
		// kcjjdz
		// yqdm
		// yqmc
		// bsyz

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// kcdm
			$this->kcdm->ViewValue = $this->kcdm->CurrentValue;
			$this->kcdm->ViewCustomAttributes = "";

			// kczwmc
			$this->kczwmc->ViewValue = $this->kczwmc->CurrentValue;
			$this->kczwmc->ViewCustomAttributes = "";

			// kcywmc
			$this->kcywmc->ViewValue = $this->kcywmc->CurrentValue;
			$this->kcywmc->ViewCustomAttributes = "";

			// xf
			$this->xf->ViewValue = $this->xf->CurrentValue;
			$this->xf->ViewCustomAttributes = "";

			// zxs
			$this->zxs->ViewValue = $this->zxs->CurrentValue;
			$this->zxs->ViewCustomAttributes = "";

			// zs
			$this->zs->ViewValue = $this->zs->CurrentValue;
			$this->zs->ViewCustomAttributes = "";

			// yxyq
			$this->yxyq->ViewValue = $this->yxyq->CurrentValue;
			$this->yxyq->ViewCustomAttributes = "";

			// bs1
			$this->bs1->ViewValue = $this->bs1->CurrentValue;
			$this->bs1->ViewCustomAttributes = "";

			// bs2
			$this->bs2->ViewValue = $this->bs2->CurrentValue;
			$this->bs2->ViewCustomAttributes = "";

			// qzxs
			$this->qzxs->ViewValue = $this->qzxs->CurrentValue;
			$this->qzxs->ViewCustomAttributes = "";

			// sfwyb
			$this->sfwyb->ViewValue = $this->sfwyb->CurrentValue;
			$this->sfwyb->ViewCustomAttributes = "";

			// zdkkrs
			$this->zdkkrs->ViewValue = $this->zdkkrs->CurrentValue;
			$this->zdkkrs->ViewCustomAttributes = "";

			// kclb
			$this->kclb->ViewValue = $this->kclb->CurrentValue;
			$this->kclb->ViewCustomAttributes = "";

			// kkbmdm
			$this->kkbmdm->ViewValue = $this->kkbmdm->CurrentValue;
			$this->kkbmdm->ViewCustomAttributes = "";

			// zhxs
			$this->zhxs->ViewValue = $this->zhxs->CurrentValue;
			$this->zhxs->ViewCustomAttributes = "";

			// yxj
			$this->yxj->ViewValue = $this->yxj->CurrentValue;
			$this->yxj->ViewCustomAttributes = "";

			// pksj
			$this->pksj->ViewValue = $this->pksj->CurrentValue;
			$this->pksj->ViewCustomAttributes = "";

			// pkyq
			$this->pkyq->ViewValue = $this->pkyq->CurrentValue;
			$this->pkyq->ViewCustomAttributes = "";

			// xs
			$this->xs->ViewValue = $this->xs->CurrentValue;
			$this->xs->ViewCustomAttributes = "";

			// kthkcdm
			$this->kthkcdm->ViewValue = $this->kthkcdm->CurrentValue;
			$this->kthkcdm->ViewCustomAttributes = "";

			// xlcc
			$this->xlcc->ViewValue = $this->xlcc->CurrentValue;
			$this->xlcc->ViewCustomAttributes = "";

			// gzlxs
			$this->gzlxs->ViewValue = $this->gzlxs->CurrentValue;
			$this->gzlxs->ViewCustomAttributes = "";

			// khfs
			$this->khfs->ViewValue = $this->khfs->CurrentValue;
			$this->khfs->ViewCustomAttributes = "";

			// kcys
			$this->kcys->ViewValue = $this->kcys->CurrentValue;
			$this->kcys->ViewCustomAttributes = "";

			// tkbj
			$this->tkbj->ViewValue = $this->tkbj->CurrentValue;
			$this->tkbj->ViewCustomAttributes = "";

			// llxs
			$this->llxs->ViewValue = $this->llxs->CurrentValue;
			$this->llxs->ViewCustomAttributes = "";

			// syxs
			$this->syxs->ViewValue = $this->syxs->CurrentValue;
			$this->syxs->ViewCustomAttributes = "";

			// sjxs
			$this->sjxs->ViewValue = $this->sjxs->CurrentValue;
			$this->sjxs->ViewCustomAttributes = "";

			// bz
			$this->bz->ViewValue = $this->bz->CurrentValue;
			$this->bz->ViewCustomAttributes = "";

			// kcxz
			$this->kcxz->ViewValue = $this->kcxz->CurrentValue;
			$this->kcxz->ViewCustomAttributes = "";

			// zcfy
			$this->zcfy->ViewValue = $this->zcfy->CurrentValue;
			$this->zcfy->ViewCustomAttributes = "";

			// cxfy
			$this->cxfy->ViewValue = $this->cxfy->CurrentValue;
			$this->cxfy->ViewCustomAttributes = "";

			// fxfy
			$this->fxfy->ViewValue = $this->fxfy->CurrentValue;
			$this->fxfy->ViewCustomAttributes = "";

			// syxmsyq
			$this->syxmsyq->ViewValue = $this->syxmsyq->CurrentValue;
			$this->syxmsyq->ViewCustomAttributes = "";

			// skfsmc
			$this->skfsmc->ViewValue = $this->skfsmc->CurrentValue;
			$this->skfsmc->ViewCustomAttributes = "";

			// axbxrw
			$this->axbxrw->ViewValue = $this->axbxrw->CurrentValue;
			$this->axbxrw->ViewCustomAttributes = "";

			// typk
			$this->typk->ViewValue = $this->typk->CurrentValue;
			$this->typk->ViewCustomAttributes = "";

			// sykkbmdm
			$this->sykkbmdm->ViewValue = $this->sykkbmdm->CurrentValue;
			$this->sykkbmdm->ViewCustomAttributes = "";

			// bsfbj
			$this->bsfbj->ViewValue = $this->bsfbj->CurrentValue;
			$this->bsfbj->ViewCustomAttributes = "";

			// syxfyq
			$this->syxfyq->ViewValue = $this->syxfyq->CurrentValue;
			$this->syxfyq->ViewCustomAttributes = "";

			// kcgs
			$this->kcgs->ViewValue = $this->kcgs->CurrentValue;
			$this->kcgs->ViewCustomAttributes = "";

			// kkxdm
			$this->kkxdm->ViewValue = $this->kkxdm->CurrentValue;
			$this->kkxdm->ViewCustomAttributes = "";

			// xkfl
			$this->xkfl->ViewValue = $this->xkfl->CurrentValue;
			$this->xkfl->ViewCustomAttributes = "";

			// bs11
			$this->bs11->ViewValue = $this->bs11->CurrentValue;
			$this->bs11->ViewCustomAttributes = "";

			// kcsjxs
			$this->kcsjxs->ViewValue = $this->kcsjxs->CurrentValue;
			$this->kcsjxs->ViewCustomAttributes = "";

			// xtkxs
			$this->xtkxs->ViewValue = $this->xtkxs->CurrentValue;
			$this->xtkxs->ViewCustomAttributes = "";

			// knsjxs
			$this->knsjxs->ViewValue = $this->knsjxs->CurrentValue;
			$this->knsjxs->ViewCustomAttributes = "";

			// kwsjxs
			$this->kwsjxs->ViewValue = $this->kwsjxs->CurrentValue;
			$this->kwsjxs->ViewCustomAttributes = "";

			// ytxs
			$this->ytxs->ViewValue = $this->ytxs->CurrentValue;
			$this->ytxs->ViewCustomAttributes = "";

			// scjssjxs
			$this->scjssjxs->ViewValue = $this->scjssjxs->CurrentValue;
			$this->scjssjxs->ViewCustomAttributes = "";

			// sxxs
			$this->sxxs->ViewValue = $this->sxxs->CurrentValue;
			$this->sxxs->ViewCustomAttributes = "";

			// ksxs
			$this->ksxs->ViewValue = $this->ksxs->CurrentValue;
			$this->ksxs->ViewCustomAttributes = "";

			// bsxs
			$this->bsxs->ViewValue = $this->bsxs->CurrentValue;
			$this->bsxs->ViewCustomAttributes = "";

			// shdcxs
			$this->shdcxs->ViewValue = $this->shdcxs->CurrentValue;
			$this->shdcxs->ViewCustomAttributes = "";

			// jys
			$this->jys->ViewValue = $this->jys->CurrentValue;
			$this->jys->ViewCustomAttributes = "";

			// sftykw
			$this->sftykw->ViewValue = $this->sftykw->CurrentValue;
			$this->sftykw->ViewCustomAttributes = "";

			// kcjc
			$this->kcjc->ViewValue = $this->kcjc->CurrentValue;
			$this->kcjc->ViewCustomAttributes = "";

			// kwxs
			$this->kwxs->ViewValue = $this->kwxs->CurrentValue;
			$this->kwxs->ViewCustomAttributes = "";

			// xkdx
			$this->xkdx->ViewValue = $this->xkdx->CurrentValue;
			$this->xkdx->ViewCustomAttributes = "";

			// jsxm
			$this->jsxm->ViewValue = $this->jsxm->CurrentValue;
			$this->jsxm->ViewCustomAttributes = "";

			// bs3
			$this->bs3->ViewValue = $this->bs3->CurrentValue;
			$this->bs3->ViewCustomAttributes = "";

			// xfjs
			$this->xfjs->ViewValue = $this->xfjs->CurrentValue;
			$this->xfjs->ViewCustomAttributes = "";

			// zhxsjs
			$this->zhxsjs->ViewValue = $this->zhxsjs->CurrentValue;
			$this->zhxsjs->ViewCustomAttributes = "";

			// jkxsjs
			$this->jkxsjs->ViewValue = $this->jkxsjs->CurrentValue;
			$this->jkxsjs->ViewCustomAttributes = "";

			// syxsjs
			$this->syxsjs->ViewValue = $this->syxsjs->CurrentValue;
			$this->syxsjs->ViewCustomAttributes = "";

			// sjxsjs
			$this->sjxsjs->ViewValue = $this->sjxsjs->CurrentValue;
			$this->sjxsjs->ViewCustomAttributes = "";

			// sfxssy
			$this->sfxssy->ViewValue = $this->sfxssy->CurrentValue;
			$this->sfxssy->ViewCustomAttributes = "";

			// kcjsztdw
			$this->kcjsztdw->ViewValue = $this->kcjsztdw->CurrentValue;
			$this->kcjsztdw->ViewCustomAttributes = "";

			// bs4
			$this->bs4->ViewValue = $this->bs4->CurrentValue;
			$this->bs4->ViewCustomAttributes = "";

			// syzy
			$this->syzy->ViewValue = $this->syzy->CurrentValue;
			$this->syzy->ViewCustomAttributes = "";

			// lrsj
			$this->lrsj->ViewValue = $this->lrsj->CurrentValue;
			$this->lrsj->ViewCustomAttributes = "";

			// kcmcpy
			$this->kcmcpy->ViewValue = $this->kcmcpy->CurrentValue;
			$this->kcmcpy->ViewCustomAttributes = "";

			// xqdm
			$this->xqdm->ViewValue = $this->xqdm->CurrentValue;
			$this->xqdm->ViewCustomAttributes = "";

			// kcqmc
			$this->kcqmc->ViewValue = $this->kcqmc->CurrentValue;
			$this->kcqmc->ViewCustomAttributes = "";

			// ksxsmc
			$this->ksxsmc->ViewValue = $this->ksxsmc->CurrentValue;
			$this->ksxsmc->ViewCustomAttributes = "";

			// sfbysjkc
			$this->sfbysjkc->ViewValue = $this->sfbysjkc->CurrentValue;
			$this->sfbysjkc->ViewCustomAttributes = "";

			// bs5
			$this->bs5->ViewValue = $this->bs5->CurrentValue;
			$this->bs5->ViewCustomAttributes = "";

			// nj
			$this->nj->ViewValue = $this->nj->CurrentValue;
			$this->nj->ViewCustomAttributes = "";

			// cjlrr
			$this->cjlrr->ViewValue = $this->cjlrr->CurrentValue;
			$this->cjlrr->ViewCustomAttributes = "";

			// sftsbx
			$this->sftsbx->ViewValue = $this->sftsbx->CurrentValue;
			$this->sftsbx->ViewCustomAttributes = "";

			// dxdgdz
			$this->dxdgdz->ViewValue = $this->dxdgdz->CurrentValue;
			$this->dxdgdz->ViewCustomAttributes = "";

			// kcfl
			$this->kcfl->ViewValue = $this->kcfl->CurrentValue;
			$this->kcfl->ViewCustomAttributes = "";

			// sjlrzgh
			$this->sjlrzgh->ViewValue = $this->sjlrzgh->CurrentValue;
			$this->sjlrzgh->ViewCustomAttributes = "";

			// kcjjdz
			$this->kcjjdz->ViewValue = $this->kcjjdz->CurrentValue;
			$this->kcjjdz->ViewCustomAttributes = "";

			// yqdm
			$this->yqdm->ViewValue = $this->yqdm->CurrentValue;
			$this->yqdm->ViewCustomAttributes = "";

			// yqmc
			$this->yqmc->ViewValue = $this->yqmc->CurrentValue;
			$this->yqmc->ViewCustomAttributes = "";

			// bsyz
			$this->bsyz->ViewValue = $this->bsyz->CurrentValue;
			$this->bsyz->ViewCustomAttributes = "";

			// kcdm
			$this->kcdm->LinkCustomAttributes = "";
			$this->kcdm->HrefValue = "";
			$this->kcdm->TooltipValue = "";

			// kczwmc
			$this->kczwmc->LinkCustomAttributes = "";
			$this->kczwmc->HrefValue = "";
			$this->kczwmc->TooltipValue = "";

			// kcywmc
			$this->kcywmc->LinkCustomAttributes = "";
			$this->kcywmc->HrefValue = "";
			$this->kcywmc->TooltipValue = "";

			// xf
			$this->xf->LinkCustomAttributes = "";
			$this->xf->HrefValue = "";
			$this->xf->TooltipValue = "";

			// zxs
			$this->zxs->LinkCustomAttributes = "";
			$this->zxs->HrefValue = "";
			$this->zxs->TooltipValue = "";

			// zs
			$this->zs->LinkCustomAttributes = "";
			$this->zs->HrefValue = "";
			$this->zs->TooltipValue = "";

			// yxyq
			$this->yxyq->LinkCustomAttributes = "";
			$this->yxyq->HrefValue = "";
			$this->yxyq->TooltipValue = "";

			// bs1
			$this->bs1->LinkCustomAttributes = "";
			$this->bs1->HrefValue = "";
			$this->bs1->TooltipValue = "";

			// bs2
			$this->bs2->LinkCustomAttributes = "";
			$this->bs2->HrefValue = "";
			$this->bs2->TooltipValue = "";

			// qzxs
			$this->qzxs->LinkCustomAttributes = "";
			$this->qzxs->HrefValue = "";
			$this->qzxs->TooltipValue = "";

			// sfwyb
			$this->sfwyb->LinkCustomAttributes = "";
			$this->sfwyb->HrefValue = "";
			$this->sfwyb->TooltipValue = "";

			// zdkkrs
			$this->zdkkrs->LinkCustomAttributes = "";
			$this->zdkkrs->HrefValue = "";
			$this->zdkkrs->TooltipValue = "";

			// kclb
			$this->kclb->LinkCustomAttributes = "";
			$this->kclb->HrefValue = "";
			$this->kclb->TooltipValue = "";

			// kkbmdm
			$this->kkbmdm->LinkCustomAttributes = "";
			$this->kkbmdm->HrefValue = "";
			$this->kkbmdm->TooltipValue = "";

			// zhxs
			$this->zhxs->LinkCustomAttributes = "";
			$this->zhxs->HrefValue = "";
			$this->zhxs->TooltipValue = "";

			// yxj
			$this->yxj->LinkCustomAttributes = "";
			$this->yxj->HrefValue = "";
			$this->yxj->TooltipValue = "";

			// pksj
			$this->pksj->LinkCustomAttributes = "";
			$this->pksj->HrefValue = "";
			$this->pksj->TooltipValue = "";

			// pkyq
			$this->pkyq->LinkCustomAttributes = "";
			$this->pkyq->HrefValue = "";
			$this->pkyq->TooltipValue = "";

			// xs
			$this->xs->LinkCustomAttributes = "";
			$this->xs->HrefValue = "";
			$this->xs->TooltipValue = "";

			// kthkcdm
			$this->kthkcdm->LinkCustomAttributes = "";
			$this->kthkcdm->HrefValue = "";
			$this->kthkcdm->TooltipValue = "";

			// xlcc
			$this->xlcc->LinkCustomAttributes = "";
			$this->xlcc->HrefValue = "";
			$this->xlcc->TooltipValue = "";

			// gzlxs
			$this->gzlxs->LinkCustomAttributes = "";
			$this->gzlxs->HrefValue = "";
			$this->gzlxs->TooltipValue = "";

			// khfs
			$this->khfs->LinkCustomAttributes = "";
			$this->khfs->HrefValue = "";
			$this->khfs->TooltipValue = "";

			// kcys
			$this->kcys->LinkCustomAttributes = "";
			$this->kcys->HrefValue = "";
			$this->kcys->TooltipValue = "";

			// tkbj
			$this->tkbj->LinkCustomAttributes = "";
			$this->tkbj->HrefValue = "";
			$this->tkbj->TooltipValue = "";

			// llxs
			$this->llxs->LinkCustomAttributes = "";
			$this->llxs->HrefValue = "";
			$this->llxs->TooltipValue = "";

			// syxs
			$this->syxs->LinkCustomAttributes = "";
			$this->syxs->HrefValue = "";
			$this->syxs->TooltipValue = "";

			// sjxs
			$this->sjxs->LinkCustomAttributes = "";
			$this->sjxs->HrefValue = "";
			$this->sjxs->TooltipValue = "";

			// bz
			$this->bz->LinkCustomAttributes = "";
			$this->bz->HrefValue = "";
			$this->bz->TooltipValue = "";

			// kcxz
			$this->kcxz->LinkCustomAttributes = "";
			$this->kcxz->HrefValue = "";
			$this->kcxz->TooltipValue = "";

			// zcfy
			$this->zcfy->LinkCustomAttributes = "";
			$this->zcfy->HrefValue = "";
			$this->zcfy->TooltipValue = "";

			// cxfy
			$this->cxfy->LinkCustomAttributes = "";
			$this->cxfy->HrefValue = "";
			$this->cxfy->TooltipValue = "";

			// fxfy
			$this->fxfy->LinkCustomAttributes = "";
			$this->fxfy->HrefValue = "";
			$this->fxfy->TooltipValue = "";

			// syxmsyq
			$this->syxmsyq->LinkCustomAttributes = "";
			$this->syxmsyq->HrefValue = "";
			$this->syxmsyq->TooltipValue = "";

			// skfsmc
			$this->skfsmc->LinkCustomAttributes = "";
			$this->skfsmc->HrefValue = "";
			$this->skfsmc->TooltipValue = "";

			// axbxrw
			$this->axbxrw->LinkCustomAttributes = "";
			$this->axbxrw->HrefValue = "";
			$this->axbxrw->TooltipValue = "";

			// typk
			$this->typk->LinkCustomAttributes = "";
			$this->typk->HrefValue = "";
			$this->typk->TooltipValue = "";

			// sykkbmdm
			$this->sykkbmdm->LinkCustomAttributes = "";
			$this->sykkbmdm->HrefValue = "";
			$this->sykkbmdm->TooltipValue = "";

			// bsfbj
			$this->bsfbj->LinkCustomAttributes = "";
			$this->bsfbj->HrefValue = "";
			$this->bsfbj->TooltipValue = "";

			// syxfyq
			$this->syxfyq->LinkCustomAttributes = "";
			$this->syxfyq->HrefValue = "";
			$this->syxfyq->TooltipValue = "";

			// kcgs
			$this->kcgs->LinkCustomAttributes = "";
			$this->kcgs->HrefValue = "";
			$this->kcgs->TooltipValue = "";

			// kkxdm
			$this->kkxdm->LinkCustomAttributes = "";
			$this->kkxdm->HrefValue = "";
			$this->kkxdm->TooltipValue = "";

			// xkfl
			$this->xkfl->LinkCustomAttributes = "";
			$this->xkfl->HrefValue = "";
			$this->xkfl->TooltipValue = "";

			// bs11
			$this->bs11->LinkCustomAttributes = "";
			$this->bs11->HrefValue = "";
			$this->bs11->TooltipValue = "";

			// kcsjxs
			$this->kcsjxs->LinkCustomAttributes = "";
			$this->kcsjxs->HrefValue = "";
			$this->kcsjxs->TooltipValue = "";

			// xtkxs
			$this->xtkxs->LinkCustomAttributes = "";
			$this->xtkxs->HrefValue = "";
			$this->xtkxs->TooltipValue = "";

			// knsjxs
			$this->knsjxs->LinkCustomAttributes = "";
			$this->knsjxs->HrefValue = "";
			$this->knsjxs->TooltipValue = "";

			// kwsjxs
			$this->kwsjxs->LinkCustomAttributes = "";
			$this->kwsjxs->HrefValue = "";
			$this->kwsjxs->TooltipValue = "";

			// ytxs
			$this->ytxs->LinkCustomAttributes = "";
			$this->ytxs->HrefValue = "";
			$this->ytxs->TooltipValue = "";

			// scjssjxs
			$this->scjssjxs->LinkCustomAttributes = "";
			$this->scjssjxs->HrefValue = "";
			$this->scjssjxs->TooltipValue = "";

			// sxxs
			$this->sxxs->LinkCustomAttributes = "";
			$this->sxxs->HrefValue = "";
			$this->sxxs->TooltipValue = "";

			// ksxs
			$this->ksxs->LinkCustomAttributes = "";
			$this->ksxs->HrefValue = "";
			$this->ksxs->TooltipValue = "";

			// bsxs
			$this->bsxs->LinkCustomAttributes = "";
			$this->bsxs->HrefValue = "";
			$this->bsxs->TooltipValue = "";

			// shdcxs
			$this->shdcxs->LinkCustomAttributes = "";
			$this->shdcxs->HrefValue = "";
			$this->shdcxs->TooltipValue = "";

			// jys
			$this->jys->LinkCustomAttributes = "";
			$this->jys->HrefValue = "";
			$this->jys->TooltipValue = "";

			// sftykw
			$this->sftykw->LinkCustomAttributes = "";
			$this->sftykw->HrefValue = "";
			$this->sftykw->TooltipValue = "";

			// kcjc
			$this->kcjc->LinkCustomAttributes = "";
			$this->kcjc->HrefValue = "";
			$this->kcjc->TooltipValue = "";

			// kwxs
			$this->kwxs->LinkCustomAttributes = "";
			$this->kwxs->HrefValue = "";
			$this->kwxs->TooltipValue = "";

			// xkdx
			$this->xkdx->LinkCustomAttributes = "";
			$this->xkdx->HrefValue = "";
			$this->xkdx->TooltipValue = "";

			// jsxm
			$this->jsxm->LinkCustomAttributes = "";
			$this->jsxm->HrefValue = "";
			$this->jsxm->TooltipValue = "";

			// bs3
			$this->bs3->LinkCustomAttributes = "";
			$this->bs3->HrefValue = "";
			$this->bs3->TooltipValue = "";

			// xfjs
			$this->xfjs->LinkCustomAttributes = "";
			$this->xfjs->HrefValue = "";
			$this->xfjs->TooltipValue = "";

			// zhxsjs
			$this->zhxsjs->LinkCustomAttributes = "";
			$this->zhxsjs->HrefValue = "";
			$this->zhxsjs->TooltipValue = "";

			// jkxsjs
			$this->jkxsjs->LinkCustomAttributes = "";
			$this->jkxsjs->HrefValue = "";
			$this->jkxsjs->TooltipValue = "";

			// syxsjs
			$this->syxsjs->LinkCustomAttributes = "";
			$this->syxsjs->HrefValue = "";
			$this->syxsjs->TooltipValue = "";

			// sjxsjs
			$this->sjxsjs->LinkCustomAttributes = "";
			$this->sjxsjs->HrefValue = "";
			$this->sjxsjs->TooltipValue = "";

			// sfxssy
			$this->sfxssy->LinkCustomAttributes = "";
			$this->sfxssy->HrefValue = "";
			$this->sfxssy->TooltipValue = "";

			// kcjsztdw
			$this->kcjsztdw->LinkCustomAttributes = "";
			$this->kcjsztdw->HrefValue = "";
			$this->kcjsztdw->TooltipValue = "";

			// bs4
			$this->bs4->LinkCustomAttributes = "";
			$this->bs4->HrefValue = "";
			$this->bs4->TooltipValue = "";

			// syzy
			$this->syzy->LinkCustomAttributes = "";
			$this->syzy->HrefValue = "";
			$this->syzy->TooltipValue = "";

			// lrsj
			$this->lrsj->LinkCustomAttributes = "";
			$this->lrsj->HrefValue = "";
			$this->lrsj->TooltipValue = "";

			// kcmcpy
			$this->kcmcpy->LinkCustomAttributes = "";
			$this->kcmcpy->HrefValue = "";
			$this->kcmcpy->TooltipValue = "";

			// xqdm
			$this->xqdm->LinkCustomAttributes = "";
			$this->xqdm->HrefValue = "";
			$this->xqdm->TooltipValue = "";

			// kcqmc
			$this->kcqmc->LinkCustomAttributes = "";
			$this->kcqmc->HrefValue = "";
			$this->kcqmc->TooltipValue = "";

			// ksxsmc
			$this->ksxsmc->LinkCustomAttributes = "";
			$this->ksxsmc->HrefValue = "";
			$this->ksxsmc->TooltipValue = "";

			// sfbysjkc
			$this->sfbysjkc->LinkCustomAttributes = "";
			$this->sfbysjkc->HrefValue = "";
			$this->sfbysjkc->TooltipValue = "";

			// bs5
			$this->bs5->LinkCustomAttributes = "";
			$this->bs5->HrefValue = "";
			$this->bs5->TooltipValue = "";

			// nj
			$this->nj->LinkCustomAttributes = "";
			$this->nj->HrefValue = "";
			$this->nj->TooltipValue = "";

			// cjlrr
			$this->cjlrr->LinkCustomAttributes = "";
			$this->cjlrr->HrefValue = "";
			$this->cjlrr->TooltipValue = "";

			// sftsbx
			$this->sftsbx->LinkCustomAttributes = "";
			$this->sftsbx->HrefValue = "";
			$this->sftsbx->TooltipValue = "";

			// dxdgdz
			$this->dxdgdz->LinkCustomAttributes = "";
			$this->dxdgdz->HrefValue = "";
			$this->dxdgdz->TooltipValue = "";

			// kcfl
			$this->kcfl->LinkCustomAttributes = "";
			$this->kcfl->HrefValue = "";
			$this->kcfl->TooltipValue = "";

			// sjlrzgh
			$this->sjlrzgh->LinkCustomAttributes = "";
			$this->sjlrzgh->HrefValue = "";
			$this->sjlrzgh->TooltipValue = "";

			// kcjjdz
			$this->kcjjdz->LinkCustomAttributes = "";
			$this->kcjjdz->HrefValue = "";
			$this->kcjjdz->TooltipValue = "";

			// yqdm
			$this->yqdm->LinkCustomAttributes = "";
			$this->yqdm->HrefValue = "";
			$this->yqdm->TooltipValue = "";

			// yqmc
			$this->yqmc->LinkCustomAttributes = "";
			$this->yqmc->HrefValue = "";
			$this->yqmc->TooltipValue = "";

			// bsyz
			$this->bsyz->LinkCustomAttributes = "";
			$this->bsyz->HrefValue = "";
			$this->bsyz->TooltipValue = "";
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
if (!isset($kcdmb_list)) $kcdmb_list = new ckcdmb_list();

// Page init
$kcdmb_list->Page_Init();

// Page main
$kcdmb_list->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var kcdmb_list = new ew_Page("kcdmb_list");
kcdmb_list.PageID = "list"; // Page ID
var EW_PAGE_ID = kcdmb_list.PageID; // For backward compatibility

// Form object
var fkcdmblist = new ew_Form("fkcdmblist");

// Form_CustomValidate event
fkcdmblist.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fkcdmblist.ValidateRequired = true;
<?php } else { ?>
fkcdmblist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fkcdmblistsrch = new ew_Form("fkcdmblistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$kcdmb_list->TotalRecs = $kcdmb->SelectRecordCount();
	} else {
		if ($kcdmb_list->Recordset = $kcdmb_list->LoadRecordset())
			$kcdmb_list->TotalRecs = $kcdmb_list->Recordset->RecordCount();
	}
	$kcdmb_list->StartRec = 1;
	if ($kcdmb_list->DisplayRecs <= 0 || ($kcdmb->Export <> "" && $kcdmb->ExportAll)) // Display all records
		$kcdmb_list->DisplayRecs = $kcdmb_list->TotalRecs;
	if (!($kcdmb->Export <> "" && $kcdmb->ExportAll))
		$kcdmb_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$kcdmb_list->Recordset = $kcdmb_list->LoadRecordset($kcdmb_list->StartRec-1, $kcdmb_list->DisplayRecs);
?>
<p style="white-space: nowrap;"><span class="ewTitle ewTableTitle"><?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $kcdmb->TableCaption() ?>&nbsp;&nbsp;</span>
<?php $kcdmb_list->ExportOptions->Render("body"); ?>
</p>
<?php if ($kcdmb->Export == "" && $kcdmb->CurrentAction == "") { ?>
<form name="fkcdmblistsrch" id="fkcdmblistsrch" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<a href="javascript:fkcdmblistsrch.ToggleSearchPanel();" style="text-decoration: none;"><img id="fkcdmblistsrch_SearchImage" src="phpimages/collapse.gif" alt="" width="9" height="9" border="0"></a><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("Search") ?></span><br>
<div id="fkcdmblistsrch_SearchPanel">
<input type="hidden" name="t" value="kcdmb">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" size="20" value="<?php echo ew_HtmlEncode($kcdmb->getSessionBasicSearchKeyword()) ?>">
	<input type="submit" name="btnsubmit" id="btnsubmit" value="<?php echo ew_BtnCaption($Language->Phrase("QuickSearchBtn")) ?>">&nbsp;
	<a href="<?php echo $kcdmb_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>&nbsp;
</div>
<div id="xsr_2" class="ewRow">
	<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($kcdmb->getSessionBasicSearchType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($kcdmb->getSessionBasicSearchType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($kcdmb->getSessionBasicSearchType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
</div>
</div>
</div>
</form>
<?php } ?>
<?php $kcdmb_list->ShowPageHeader(); ?>
<?php
$kcdmb_list->ShowMessage();
?>
<br>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fkcdmblist" id="fkcdmblist" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="kcdmb">
<div id="gmp_kcdmb" class="ewGridMiddlePanel">
<?php if ($kcdmb_list->TotalRecs > 0) { ?>
<table cellspacing="0" id="tbl_kcdmblist" class="ewTable ewTableSeparate">
<?php echo $kcdmb->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$kcdmb_list->RenderListOptions();

// Render list options (header, left)
$kcdmb_list->ListOptions->Render("header", "left");
?>
<?php if ($kcdmb->kcdm->Visible) { // kcdm ?>
	<?php if ($kcdmb->SortUrl($kcdmb->kcdm) == "") { ?>
		<td><span id="elh_kcdmb_kcdm" class="kcdmb_kcdm"><?php echo $kcdmb->kcdm->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->kcdm) ?>',1);"><span id="elh_kcdmb_kcdm" class="kcdmb_kcdm">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->kcdm->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->kcdm->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->kcdm->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->kczwmc->Visible) { // kczwmc ?>
	<?php if ($kcdmb->SortUrl($kcdmb->kczwmc) == "") { ?>
		<td><span id="elh_kcdmb_kczwmc" class="kcdmb_kczwmc"><?php echo $kcdmb->kczwmc->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->kczwmc) ?>',1);"><span id="elh_kcdmb_kczwmc" class="kcdmb_kczwmc">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->kczwmc->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->kczwmc->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->kczwmc->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->kcywmc->Visible) { // kcywmc ?>
	<?php if ($kcdmb->SortUrl($kcdmb->kcywmc) == "") { ?>
		<td><span id="elh_kcdmb_kcywmc" class="kcdmb_kcywmc"><?php echo $kcdmb->kcywmc->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->kcywmc) ?>',1);"><span id="elh_kcdmb_kcywmc" class="kcdmb_kcywmc">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->kcywmc->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->kcywmc->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->kcywmc->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->xf->Visible) { // xf ?>
	<?php if ($kcdmb->SortUrl($kcdmb->xf) == "") { ?>
		<td><span id="elh_kcdmb_xf" class="kcdmb_xf"><?php echo $kcdmb->xf->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->xf) ?>',1);"><span id="elh_kcdmb_xf" class="kcdmb_xf">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->xf->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->xf->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->xf->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->zxs->Visible) { // zxs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->zxs) == "") { ?>
		<td><span id="elh_kcdmb_zxs" class="kcdmb_zxs"><?php echo $kcdmb->zxs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->zxs) ?>',1);"><span id="elh_kcdmb_zxs" class="kcdmb_zxs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->zxs->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->zxs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->zxs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->zs->Visible) { // zs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->zs) == "") { ?>
		<td><span id="elh_kcdmb_zs" class="kcdmb_zs"><?php echo $kcdmb->zs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->zs) ?>',1);"><span id="elh_kcdmb_zs" class="kcdmb_zs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->zs->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->zs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->zs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->yxyq->Visible) { // yxyq ?>
	<?php if ($kcdmb->SortUrl($kcdmb->yxyq) == "") { ?>
		<td><span id="elh_kcdmb_yxyq" class="kcdmb_yxyq"><?php echo $kcdmb->yxyq->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->yxyq) ?>',1);"><span id="elh_kcdmb_yxyq" class="kcdmb_yxyq">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->yxyq->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->yxyq->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->yxyq->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->bs1->Visible) { // bs1 ?>
	<?php if ($kcdmb->SortUrl($kcdmb->bs1) == "") { ?>
		<td><span id="elh_kcdmb_bs1" class="kcdmb_bs1"><?php echo $kcdmb->bs1->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->bs1) ?>',1);"><span id="elh_kcdmb_bs1" class="kcdmb_bs1">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->bs1->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->bs1->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->bs1->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->bs2->Visible) { // bs2 ?>
	<?php if ($kcdmb->SortUrl($kcdmb->bs2) == "") { ?>
		<td><span id="elh_kcdmb_bs2" class="kcdmb_bs2"><?php echo $kcdmb->bs2->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->bs2) ?>',1);"><span id="elh_kcdmb_bs2" class="kcdmb_bs2">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->bs2->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->bs2->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->bs2->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->qzxs->Visible) { // qzxs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->qzxs) == "") { ?>
		<td><span id="elh_kcdmb_qzxs" class="kcdmb_qzxs"><?php echo $kcdmb->qzxs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->qzxs) ?>',1);"><span id="elh_kcdmb_qzxs" class="kcdmb_qzxs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->qzxs->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->qzxs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->qzxs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->sfwyb->Visible) { // sfwyb ?>
	<?php if ($kcdmb->SortUrl($kcdmb->sfwyb) == "") { ?>
		<td><span id="elh_kcdmb_sfwyb" class="kcdmb_sfwyb"><?php echo $kcdmb->sfwyb->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->sfwyb) ?>',1);"><span id="elh_kcdmb_sfwyb" class="kcdmb_sfwyb">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->sfwyb->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->sfwyb->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->sfwyb->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->zdkkrs->Visible) { // zdkkrs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->zdkkrs) == "") { ?>
		<td><span id="elh_kcdmb_zdkkrs" class="kcdmb_zdkkrs"><?php echo $kcdmb->zdkkrs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->zdkkrs) ?>',1);"><span id="elh_kcdmb_zdkkrs" class="kcdmb_zdkkrs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->zdkkrs->FldCaption() ?></td><td style="width: 10px;"><?php if ($kcdmb->zdkkrs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->zdkkrs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->kclb->Visible) { // kclb ?>
	<?php if ($kcdmb->SortUrl($kcdmb->kclb) == "") { ?>
		<td><span id="elh_kcdmb_kclb" class="kcdmb_kclb"><?php echo $kcdmb->kclb->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->kclb) ?>',1);"><span id="elh_kcdmb_kclb" class="kcdmb_kclb">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->kclb->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->kclb->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->kclb->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->kkbmdm->Visible) { // kkbmdm ?>
	<?php if ($kcdmb->SortUrl($kcdmb->kkbmdm) == "") { ?>
		<td><span id="elh_kcdmb_kkbmdm" class="kcdmb_kkbmdm"><?php echo $kcdmb->kkbmdm->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->kkbmdm) ?>',1);"><span id="elh_kcdmb_kkbmdm" class="kcdmb_kkbmdm">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->kkbmdm->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->kkbmdm->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->kkbmdm->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->zhxs->Visible) { // zhxs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->zhxs) == "") { ?>
		<td><span id="elh_kcdmb_zhxs" class="kcdmb_zhxs"><?php echo $kcdmb->zhxs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->zhxs) ?>',1);"><span id="elh_kcdmb_zhxs" class="kcdmb_zhxs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->zhxs->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->zhxs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->zhxs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->yxj->Visible) { // yxj ?>
	<?php if ($kcdmb->SortUrl($kcdmb->yxj) == "") { ?>
		<td><span id="elh_kcdmb_yxj" class="kcdmb_yxj"><?php echo $kcdmb->yxj->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->yxj) ?>',1);"><span id="elh_kcdmb_yxj" class="kcdmb_yxj">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->yxj->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->yxj->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->yxj->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->pksj->Visible) { // pksj ?>
	<?php if ($kcdmb->SortUrl($kcdmb->pksj) == "") { ?>
		<td><span id="elh_kcdmb_pksj" class="kcdmb_pksj"><?php echo $kcdmb->pksj->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->pksj) ?>',1);"><span id="elh_kcdmb_pksj" class="kcdmb_pksj">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->pksj->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->pksj->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->pksj->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->pkyq->Visible) { // pkyq ?>
	<?php if ($kcdmb->SortUrl($kcdmb->pkyq) == "") { ?>
		<td><span id="elh_kcdmb_pkyq" class="kcdmb_pkyq"><?php echo $kcdmb->pkyq->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->pkyq) ?>',1);"><span id="elh_kcdmb_pkyq" class="kcdmb_pkyq">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->pkyq->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->pkyq->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->pkyq->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->xs->Visible) { // xs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->xs) == "") { ?>
		<td><span id="elh_kcdmb_xs" class="kcdmb_xs"><?php echo $kcdmb->xs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->xs) ?>',1);"><span id="elh_kcdmb_xs" class="kcdmb_xs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->xs->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->xs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->xs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->kthkcdm->Visible) { // kthkcdm ?>
	<?php if ($kcdmb->SortUrl($kcdmb->kthkcdm) == "") { ?>
		<td><span id="elh_kcdmb_kthkcdm" class="kcdmb_kthkcdm"><?php echo $kcdmb->kthkcdm->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->kthkcdm) ?>',1);"><span id="elh_kcdmb_kthkcdm" class="kcdmb_kthkcdm">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->kthkcdm->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->kthkcdm->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->kthkcdm->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->xlcc->Visible) { // xlcc ?>
	<?php if ($kcdmb->SortUrl($kcdmb->xlcc) == "") { ?>
		<td><span id="elh_kcdmb_xlcc" class="kcdmb_xlcc"><?php echo $kcdmb->xlcc->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->xlcc) ?>',1);"><span id="elh_kcdmb_xlcc" class="kcdmb_xlcc">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->xlcc->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->xlcc->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->xlcc->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->gzlxs->Visible) { // gzlxs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->gzlxs) == "") { ?>
		<td><span id="elh_kcdmb_gzlxs" class="kcdmb_gzlxs"><?php echo $kcdmb->gzlxs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->gzlxs) ?>',1);"><span id="elh_kcdmb_gzlxs" class="kcdmb_gzlxs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->gzlxs->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->gzlxs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->gzlxs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->khfs->Visible) { // khfs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->khfs) == "") { ?>
		<td><span id="elh_kcdmb_khfs" class="kcdmb_khfs"><?php echo $kcdmb->khfs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->khfs) ?>',1);"><span id="elh_kcdmb_khfs" class="kcdmb_khfs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->khfs->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->khfs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->khfs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->kcys->Visible) { // kcys ?>
	<?php if ($kcdmb->SortUrl($kcdmb->kcys) == "") { ?>
		<td><span id="elh_kcdmb_kcys" class="kcdmb_kcys"><?php echo $kcdmb->kcys->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->kcys) ?>',1);"><span id="elh_kcdmb_kcys" class="kcdmb_kcys">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->kcys->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->kcys->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->kcys->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->tkbj->Visible) { // tkbj ?>
	<?php if ($kcdmb->SortUrl($kcdmb->tkbj) == "") { ?>
		<td><span id="elh_kcdmb_tkbj" class="kcdmb_tkbj"><?php echo $kcdmb->tkbj->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->tkbj) ?>',1);"><span id="elh_kcdmb_tkbj" class="kcdmb_tkbj">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->tkbj->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->tkbj->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->tkbj->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->llxs->Visible) { // llxs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->llxs) == "") { ?>
		<td><span id="elh_kcdmb_llxs" class="kcdmb_llxs"><?php echo $kcdmb->llxs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->llxs) ?>',1);"><span id="elh_kcdmb_llxs" class="kcdmb_llxs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->llxs->FldCaption() ?></td><td style="width: 10px;"><?php if ($kcdmb->llxs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->llxs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->syxs->Visible) { // syxs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->syxs) == "") { ?>
		<td><span id="elh_kcdmb_syxs" class="kcdmb_syxs"><?php echo $kcdmb->syxs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->syxs) ?>',1);"><span id="elh_kcdmb_syxs" class="kcdmb_syxs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->syxs->FldCaption() ?></td><td style="width: 10px;"><?php if ($kcdmb->syxs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->syxs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->sjxs->Visible) { // sjxs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->sjxs) == "") { ?>
		<td><span id="elh_kcdmb_sjxs" class="kcdmb_sjxs"><?php echo $kcdmb->sjxs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->sjxs) ?>',1);"><span id="elh_kcdmb_sjxs" class="kcdmb_sjxs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->sjxs->FldCaption() ?></td><td style="width: 10px;"><?php if ($kcdmb->sjxs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->sjxs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->bz->Visible) { // bz ?>
	<?php if ($kcdmb->SortUrl($kcdmb->bz) == "") { ?>
		<td><span id="elh_kcdmb_bz" class="kcdmb_bz"><?php echo $kcdmb->bz->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->bz) ?>',1);"><span id="elh_kcdmb_bz" class="kcdmb_bz">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->bz->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->bz->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->bz->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->kcxz->Visible) { // kcxz ?>
	<?php if ($kcdmb->SortUrl($kcdmb->kcxz) == "") { ?>
		<td><span id="elh_kcdmb_kcxz" class="kcdmb_kcxz"><?php echo $kcdmb->kcxz->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->kcxz) ?>',1);"><span id="elh_kcdmb_kcxz" class="kcdmb_kcxz">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->kcxz->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->kcxz->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->kcxz->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->zcfy->Visible) { // zcfy ?>
	<?php if ($kcdmb->SortUrl($kcdmb->zcfy) == "") { ?>
		<td><span id="elh_kcdmb_zcfy" class="kcdmb_zcfy"><?php echo $kcdmb->zcfy->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->zcfy) ?>',1);"><span id="elh_kcdmb_zcfy" class="kcdmb_zcfy">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->zcfy->FldCaption() ?></td><td style="width: 10px;"><?php if ($kcdmb->zcfy->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->zcfy->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->cxfy->Visible) { // cxfy ?>
	<?php if ($kcdmb->SortUrl($kcdmb->cxfy) == "") { ?>
		<td><span id="elh_kcdmb_cxfy" class="kcdmb_cxfy"><?php echo $kcdmb->cxfy->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->cxfy) ?>',1);"><span id="elh_kcdmb_cxfy" class="kcdmb_cxfy">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->cxfy->FldCaption() ?></td><td style="width: 10px;"><?php if ($kcdmb->cxfy->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->cxfy->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->fxfy->Visible) { // fxfy ?>
	<?php if ($kcdmb->SortUrl($kcdmb->fxfy) == "") { ?>
		<td><span id="elh_kcdmb_fxfy" class="kcdmb_fxfy"><?php echo $kcdmb->fxfy->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->fxfy) ?>',1);"><span id="elh_kcdmb_fxfy" class="kcdmb_fxfy">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->fxfy->FldCaption() ?></td><td style="width: 10px;"><?php if ($kcdmb->fxfy->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->fxfy->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->syxmsyq->Visible) { // syxmsyq ?>
	<?php if ($kcdmb->SortUrl($kcdmb->syxmsyq) == "") { ?>
		<td><span id="elh_kcdmb_syxmsyq" class="kcdmb_syxmsyq"><?php echo $kcdmb->syxmsyq->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->syxmsyq) ?>',1);"><span id="elh_kcdmb_syxmsyq" class="kcdmb_syxmsyq">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->syxmsyq->FldCaption() ?></td><td style="width: 10px;"><?php if ($kcdmb->syxmsyq->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->syxmsyq->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->skfsmc->Visible) { // skfsmc ?>
	<?php if ($kcdmb->SortUrl($kcdmb->skfsmc) == "") { ?>
		<td><span id="elh_kcdmb_skfsmc" class="kcdmb_skfsmc"><?php echo $kcdmb->skfsmc->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->skfsmc) ?>',1);"><span id="elh_kcdmb_skfsmc" class="kcdmb_skfsmc">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->skfsmc->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->skfsmc->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->skfsmc->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->axbxrw->Visible) { // axbxrw ?>
	<?php if ($kcdmb->SortUrl($kcdmb->axbxrw) == "") { ?>
		<td><span id="elh_kcdmb_axbxrw" class="kcdmb_axbxrw"><?php echo $kcdmb->axbxrw->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->axbxrw) ?>',1);"><span id="elh_kcdmb_axbxrw" class="kcdmb_axbxrw">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->axbxrw->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->axbxrw->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->axbxrw->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->typk->Visible) { // typk ?>
	<?php if ($kcdmb->SortUrl($kcdmb->typk) == "") { ?>
		<td><span id="elh_kcdmb_typk" class="kcdmb_typk"><?php echo $kcdmb->typk->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->typk) ?>',1);"><span id="elh_kcdmb_typk" class="kcdmb_typk">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->typk->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->typk->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->typk->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->sykkbmdm->Visible) { // sykkbmdm ?>
	<?php if ($kcdmb->SortUrl($kcdmb->sykkbmdm) == "") { ?>
		<td><span id="elh_kcdmb_sykkbmdm" class="kcdmb_sykkbmdm"><?php echo $kcdmb->sykkbmdm->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->sykkbmdm) ?>',1);"><span id="elh_kcdmb_sykkbmdm" class="kcdmb_sykkbmdm">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->sykkbmdm->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->sykkbmdm->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->sykkbmdm->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->bsfbj->Visible) { // bsfbj ?>
	<?php if ($kcdmb->SortUrl($kcdmb->bsfbj) == "") { ?>
		<td><span id="elh_kcdmb_bsfbj" class="kcdmb_bsfbj"><?php echo $kcdmb->bsfbj->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->bsfbj) ?>',1);"><span id="elh_kcdmb_bsfbj" class="kcdmb_bsfbj">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->bsfbj->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->bsfbj->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->bsfbj->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->syxfyq->Visible) { // syxfyq ?>
	<?php if ($kcdmb->SortUrl($kcdmb->syxfyq) == "") { ?>
		<td><span id="elh_kcdmb_syxfyq" class="kcdmb_syxfyq"><?php echo $kcdmb->syxfyq->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->syxfyq) ?>',1);"><span id="elh_kcdmb_syxfyq" class="kcdmb_syxfyq">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->syxfyq->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->syxfyq->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->syxfyq->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->kcgs->Visible) { // kcgs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->kcgs) == "") { ?>
		<td><span id="elh_kcdmb_kcgs" class="kcdmb_kcgs"><?php echo $kcdmb->kcgs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->kcgs) ?>',1);"><span id="elh_kcdmb_kcgs" class="kcdmb_kcgs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->kcgs->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->kcgs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->kcgs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->kkxdm->Visible) { // kkxdm ?>
	<?php if ($kcdmb->SortUrl($kcdmb->kkxdm) == "") { ?>
		<td><span id="elh_kcdmb_kkxdm" class="kcdmb_kkxdm"><?php echo $kcdmb->kkxdm->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->kkxdm) ?>',1);"><span id="elh_kcdmb_kkxdm" class="kcdmb_kkxdm">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->kkxdm->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->kkxdm->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->kkxdm->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->xkfl->Visible) { // xkfl ?>
	<?php if ($kcdmb->SortUrl($kcdmb->xkfl) == "") { ?>
		<td><span id="elh_kcdmb_xkfl" class="kcdmb_xkfl"><?php echo $kcdmb->xkfl->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->xkfl) ?>',1);"><span id="elh_kcdmb_xkfl" class="kcdmb_xkfl">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->xkfl->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->xkfl->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->xkfl->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->bs11->Visible) { // bs11 ?>
	<?php if ($kcdmb->SortUrl($kcdmb->bs11) == "") { ?>
		<td><span id="elh_kcdmb_bs11" class="kcdmb_bs11"><?php echo $kcdmb->bs11->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->bs11) ?>',1);"><span id="elh_kcdmb_bs11" class="kcdmb_bs11">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->bs11->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->bs11->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->bs11->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->kcsjxs->Visible) { // kcsjxs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->kcsjxs) == "") { ?>
		<td><span id="elh_kcdmb_kcsjxs" class="kcdmb_kcsjxs"><?php echo $kcdmb->kcsjxs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->kcsjxs) ?>',1);"><span id="elh_kcdmb_kcsjxs" class="kcdmb_kcsjxs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->kcsjxs->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->kcsjxs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->kcsjxs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->xtkxs->Visible) { // xtkxs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->xtkxs) == "") { ?>
		<td><span id="elh_kcdmb_xtkxs" class="kcdmb_xtkxs"><?php echo $kcdmb->xtkxs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->xtkxs) ?>',1);"><span id="elh_kcdmb_xtkxs" class="kcdmb_xtkxs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->xtkxs->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->xtkxs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->xtkxs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->knsjxs->Visible) { // knsjxs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->knsjxs) == "") { ?>
		<td><span id="elh_kcdmb_knsjxs" class="kcdmb_knsjxs"><?php echo $kcdmb->knsjxs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->knsjxs) ?>',1);"><span id="elh_kcdmb_knsjxs" class="kcdmb_knsjxs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->knsjxs->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->knsjxs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->knsjxs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->kwsjxs->Visible) { // kwsjxs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->kwsjxs) == "") { ?>
		<td><span id="elh_kcdmb_kwsjxs" class="kcdmb_kwsjxs"><?php echo $kcdmb->kwsjxs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->kwsjxs) ?>',1);"><span id="elh_kcdmb_kwsjxs" class="kcdmb_kwsjxs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->kwsjxs->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->kwsjxs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->kwsjxs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->ytxs->Visible) { // ytxs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->ytxs) == "") { ?>
		<td><span id="elh_kcdmb_ytxs" class="kcdmb_ytxs"><?php echo $kcdmb->ytxs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->ytxs) ?>',1);"><span id="elh_kcdmb_ytxs" class="kcdmb_ytxs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->ytxs->FldCaption() ?></td><td style="width: 10px;"><?php if ($kcdmb->ytxs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->ytxs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->scjssjxs->Visible) { // scjssjxs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->scjssjxs) == "") { ?>
		<td><span id="elh_kcdmb_scjssjxs" class="kcdmb_scjssjxs"><?php echo $kcdmb->scjssjxs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->scjssjxs) ?>',1);"><span id="elh_kcdmb_scjssjxs" class="kcdmb_scjssjxs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->scjssjxs->FldCaption() ?></td><td style="width: 10px;"><?php if ($kcdmb->scjssjxs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->scjssjxs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->sxxs->Visible) { // sxxs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->sxxs) == "") { ?>
		<td><span id="elh_kcdmb_sxxs" class="kcdmb_sxxs"><?php echo $kcdmb->sxxs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->sxxs) ?>',1);"><span id="elh_kcdmb_sxxs" class="kcdmb_sxxs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->sxxs->FldCaption() ?></td><td style="width: 10px;"><?php if ($kcdmb->sxxs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->sxxs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->ksxs->Visible) { // ksxs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->ksxs) == "") { ?>
		<td><span id="elh_kcdmb_ksxs" class="kcdmb_ksxs"><?php echo $kcdmb->ksxs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->ksxs) ?>',1);"><span id="elh_kcdmb_ksxs" class="kcdmb_ksxs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->ksxs->FldCaption() ?></td><td style="width: 10px;"><?php if ($kcdmb->ksxs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->ksxs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->bsxs->Visible) { // bsxs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->bsxs) == "") { ?>
		<td><span id="elh_kcdmb_bsxs" class="kcdmb_bsxs"><?php echo $kcdmb->bsxs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->bsxs) ?>',1);"><span id="elh_kcdmb_bsxs" class="kcdmb_bsxs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->bsxs->FldCaption() ?></td><td style="width: 10px;"><?php if ($kcdmb->bsxs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->bsxs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->shdcxs->Visible) { // shdcxs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->shdcxs) == "") { ?>
		<td><span id="elh_kcdmb_shdcxs" class="kcdmb_shdcxs"><?php echo $kcdmb->shdcxs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->shdcxs) ?>',1);"><span id="elh_kcdmb_shdcxs" class="kcdmb_shdcxs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->shdcxs->FldCaption() ?></td><td style="width: 10px;"><?php if ($kcdmb->shdcxs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->shdcxs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->jys->Visible) { // jys ?>
	<?php if ($kcdmb->SortUrl($kcdmb->jys) == "") { ?>
		<td><span id="elh_kcdmb_jys" class="kcdmb_jys"><?php echo $kcdmb->jys->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->jys) ?>',1);"><span id="elh_kcdmb_jys" class="kcdmb_jys">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->jys->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->jys->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->jys->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->sftykw->Visible) { // sftykw ?>
	<?php if ($kcdmb->SortUrl($kcdmb->sftykw) == "") { ?>
		<td><span id="elh_kcdmb_sftykw" class="kcdmb_sftykw"><?php echo $kcdmb->sftykw->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->sftykw) ?>',1);"><span id="elh_kcdmb_sftykw" class="kcdmb_sftykw">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->sftykw->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->sftykw->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->sftykw->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->kcjc->Visible) { // kcjc ?>
	<?php if ($kcdmb->SortUrl($kcdmb->kcjc) == "") { ?>
		<td><span id="elh_kcdmb_kcjc" class="kcdmb_kcjc"><?php echo $kcdmb->kcjc->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->kcjc) ?>',1);"><span id="elh_kcdmb_kcjc" class="kcdmb_kcjc">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->kcjc->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->kcjc->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->kcjc->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->kwxs->Visible) { // kwxs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->kwxs) == "") { ?>
		<td><span id="elh_kcdmb_kwxs" class="kcdmb_kwxs"><?php echo $kcdmb->kwxs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->kwxs) ?>',1);"><span id="elh_kcdmb_kwxs" class="kcdmb_kwxs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->kwxs->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->kwxs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->kwxs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->xkdx->Visible) { // xkdx ?>
	<?php if ($kcdmb->SortUrl($kcdmb->xkdx) == "") { ?>
		<td><span id="elh_kcdmb_xkdx" class="kcdmb_xkdx"><?php echo $kcdmb->xkdx->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->xkdx) ?>',1);"><span id="elh_kcdmb_xkdx" class="kcdmb_xkdx">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->xkdx->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->xkdx->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->xkdx->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->jsxm->Visible) { // jsxm ?>
	<?php if ($kcdmb->SortUrl($kcdmb->jsxm) == "") { ?>
		<td><span id="elh_kcdmb_jsxm" class="kcdmb_jsxm"><?php echo $kcdmb->jsxm->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->jsxm) ?>',1);"><span id="elh_kcdmb_jsxm" class="kcdmb_jsxm">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->jsxm->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->jsxm->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->jsxm->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->bs3->Visible) { // bs3 ?>
	<?php if ($kcdmb->SortUrl($kcdmb->bs3) == "") { ?>
		<td><span id="elh_kcdmb_bs3" class="kcdmb_bs3"><?php echo $kcdmb->bs3->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->bs3) ?>',1);"><span id="elh_kcdmb_bs3" class="kcdmb_bs3">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->bs3->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->bs3->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->bs3->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->xfjs->Visible) { // xfjs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->xfjs) == "") { ?>
		<td><span id="elh_kcdmb_xfjs" class="kcdmb_xfjs"><?php echo $kcdmb->xfjs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->xfjs) ?>',1);"><span id="elh_kcdmb_xfjs" class="kcdmb_xfjs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->xfjs->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->xfjs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->xfjs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->zhxsjs->Visible) { // zhxsjs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->zhxsjs) == "") { ?>
		<td><span id="elh_kcdmb_zhxsjs" class="kcdmb_zhxsjs"><?php echo $kcdmb->zhxsjs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->zhxsjs) ?>',1);"><span id="elh_kcdmb_zhxsjs" class="kcdmb_zhxsjs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->zhxsjs->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->zhxsjs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->zhxsjs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->jkxsjs->Visible) { // jkxsjs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->jkxsjs) == "") { ?>
		<td><span id="elh_kcdmb_jkxsjs" class="kcdmb_jkxsjs"><?php echo $kcdmb->jkxsjs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->jkxsjs) ?>',1);"><span id="elh_kcdmb_jkxsjs" class="kcdmb_jkxsjs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->jkxsjs->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->jkxsjs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->jkxsjs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->syxsjs->Visible) { // syxsjs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->syxsjs) == "") { ?>
		<td><span id="elh_kcdmb_syxsjs" class="kcdmb_syxsjs"><?php echo $kcdmb->syxsjs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->syxsjs) ?>',1);"><span id="elh_kcdmb_syxsjs" class="kcdmb_syxsjs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->syxsjs->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->syxsjs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->syxsjs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->sjxsjs->Visible) { // sjxsjs ?>
	<?php if ($kcdmb->SortUrl($kcdmb->sjxsjs) == "") { ?>
		<td><span id="elh_kcdmb_sjxsjs" class="kcdmb_sjxsjs"><?php echo $kcdmb->sjxsjs->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->sjxsjs) ?>',1);"><span id="elh_kcdmb_sjxsjs" class="kcdmb_sjxsjs">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->sjxsjs->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->sjxsjs->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->sjxsjs->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->sfxssy->Visible) { // sfxssy ?>
	<?php if ($kcdmb->SortUrl($kcdmb->sfxssy) == "") { ?>
		<td><span id="elh_kcdmb_sfxssy" class="kcdmb_sfxssy"><?php echo $kcdmb->sfxssy->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->sfxssy) ?>',1);"><span id="elh_kcdmb_sfxssy" class="kcdmb_sfxssy">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->sfxssy->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->sfxssy->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->sfxssy->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->kcjsztdw->Visible) { // kcjsztdw ?>
	<?php if ($kcdmb->SortUrl($kcdmb->kcjsztdw) == "") { ?>
		<td><span id="elh_kcdmb_kcjsztdw" class="kcdmb_kcjsztdw"><?php echo $kcdmb->kcjsztdw->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->kcjsztdw) ?>',1);"><span id="elh_kcdmb_kcjsztdw" class="kcdmb_kcjsztdw">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->kcjsztdw->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->kcjsztdw->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->kcjsztdw->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->bs4->Visible) { // bs4 ?>
	<?php if ($kcdmb->SortUrl($kcdmb->bs4) == "") { ?>
		<td><span id="elh_kcdmb_bs4" class="kcdmb_bs4"><?php echo $kcdmb->bs4->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->bs4) ?>',1);"><span id="elh_kcdmb_bs4" class="kcdmb_bs4">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->bs4->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->bs4->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->bs4->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->syzy->Visible) { // syzy ?>
	<?php if ($kcdmb->SortUrl($kcdmb->syzy) == "") { ?>
		<td><span id="elh_kcdmb_syzy" class="kcdmb_syzy"><?php echo $kcdmb->syzy->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->syzy) ?>',1);"><span id="elh_kcdmb_syzy" class="kcdmb_syzy">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->syzy->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->syzy->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->syzy->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->lrsj->Visible) { // lrsj ?>
	<?php if ($kcdmb->SortUrl($kcdmb->lrsj) == "") { ?>
		<td><span id="elh_kcdmb_lrsj" class="kcdmb_lrsj"><?php echo $kcdmb->lrsj->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->lrsj) ?>',1);"><span id="elh_kcdmb_lrsj" class="kcdmb_lrsj">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->lrsj->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->lrsj->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->lrsj->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->kcmcpy->Visible) { // kcmcpy ?>
	<?php if ($kcdmb->SortUrl($kcdmb->kcmcpy) == "") { ?>
		<td><span id="elh_kcdmb_kcmcpy" class="kcdmb_kcmcpy"><?php echo $kcdmb->kcmcpy->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->kcmcpy) ?>',1);"><span id="elh_kcdmb_kcmcpy" class="kcdmb_kcmcpy">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->kcmcpy->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->kcmcpy->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->kcmcpy->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->xqdm->Visible) { // xqdm ?>
	<?php if ($kcdmb->SortUrl($kcdmb->xqdm) == "") { ?>
		<td><span id="elh_kcdmb_xqdm" class="kcdmb_xqdm"><?php echo $kcdmb->xqdm->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->xqdm) ?>',1);"><span id="elh_kcdmb_xqdm" class="kcdmb_xqdm">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->xqdm->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->xqdm->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->xqdm->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->kcqmc->Visible) { // kcqmc ?>
	<?php if ($kcdmb->SortUrl($kcdmb->kcqmc) == "") { ?>
		<td><span id="elh_kcdmb_kcqmc" class="kcdmb_kcqmc"><?php echo $kcdmb->kcqmc->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->kcqmc) ?>',1);"><span id="elh_kcdmb_kcqmc" class="kcdmb_kcqmc">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->kcqmc->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->kcqmc->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->kcqmc->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->ksxsmc->Visible) { // ksxsmc ?>
	<?php if ($kcdmb->SortUrl($kcdmb->ksxsmc) == "") { ?>
		<td><span id="elh_kcdmb_ksxsmc" class="kcdmb_ksxsmc"><?php echo $kcdmb->ksxsmc->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->ksxsmc) ?>',1);"><span id="elh_kcdmb_ksxsmc" class="kcdmb_ksxsmc">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->ksxsmc->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->ksxsmc->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->ksxsmc->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->sfbysjkc->Visible) { // sfbysjkc ?>
	<?php if ($kcdmb->SortUrl($kcdmb->sfbysjkc) == "") { ?>
		<td><span id="elh_kcdmb_sfbysjkc" class="kcdmb_sfbysjkc"><?php echo $kcdmb->sfbysjkc->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->sfbysjkc) ?>',1);"><span id="elh_kcdmb_sfbysjkc" class="kcdmb_sfbysjkc">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->sfbysjkc->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->sfbysjkc->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->sfbysjkc->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->bs5->Visible) { // bs5 ?>
	<?php if ($kcdmb->SortUrl($kcdmb->bs5) == "") { ?>
		<td><span id="elh_kcdmb_bs5" class="kcdmb_bs5"><?php echo $kcdmb->bs5->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->bs5) ?>',1);"><span id="elh_kcdmb_bs5" class="kcdmb_bs5">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->bs5->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->bs5->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->bs5->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->nj->Visible) { // nj ?>
	<?php if ($kcdmb->SortUrl($kcdmb->nj) == "") { ?>
		<td><span id="elh_kcdmb_nj" class="kcdmb_nj"><?php echo $kcdmb->nj->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->nj) ?>',1);"><span id="elh_kcdmb_nj" class="kcdmb_nj">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->nj->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->nj->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->nj->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->cjlrr->Visible) { // cjlrr ?>
	<?php if ($kcdmb->SortUrl($kcdmb->cjlrr) == "") { ?>
		<td><span id="elh_kcdmb_cjlrr" class="kcdmb_cjlrr"><?php echo $kcdmb->cjlrr->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->cjlrr) ?>',1);"><span id="elh_kcdmb_cjlrr" class="kcdmb_cjlrr">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->cjlrr->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->cjlrr->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->cjlrr->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->sftsbx->Visible) { // sftsbx ?>
	<?php if ($kcdmb->SortUrl($kcdmb->sftsbx) == "") { ?>
		<td><span id="elh_kcdmb_sftsbx" class="kcdmb_sftsbx"><?php echo $kcdmb->sftsbx->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->sftsbx) ?>',1);"><span id="elh_kcdmb_sftsbx" class="kcdmb_sftsbx">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->sftsbx->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->sftsbx->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->sftsbx->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->dxdgdz->Visible) { // dxdgdz ?>
	<?php if ($kcdmb->SortUrl($kcdmb->dxdgdz) == "") { ?>
		<td><span id="elh_kcdmb_dxdgdz" class="kcdmb_dxdgdz"><?php echo $kcdmb->dxdgdz->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->dxdgdz) ?>',1);"><span id="elh_kcdmb_dxdgdz" class="kcdmb_dxdgdz">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->dxdgdz->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->dxdgdz->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->dxdgdz->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->kcfl->Visible) { // kcfl ?>
	<?php if ($kcdmb->SortUrl($kcdmb->kcfl) == "") { ?>
		<td><span id="elh_kcdmb_kcfl" class="kcdmb_kcfl"><?php echo $kcdmb->kcfl->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->kcfl) ?>',1);"><span id="elh_kcdmb_kcfl" class="kcdmb_kcfl">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->kcfl->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->kcfl->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->kcfl->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->sjlrzgh->Visible) { // sjlrzgh ?>
	<?php if ($kcdmb->SortUrl($kcdmb->sjlrzgh) == "") { ?>
		<td><span id="elh_kcdmb_sjlrzgh" class="kcdmb_sjlrzgh"><?php echo $kcdmb->sjlrzgh->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->sjlrzgh) ?>',1);"><span id="elh_kcdmb_sjlrzgh" class="kcdmb_sjlrzgh">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->sjlrzgh->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->sjlrzgh->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->sjlrzgh->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->kcjjdz->Visible) { // kcjjdz ?>
	<?php if ($kcdmb->SortUrl($kcdmb->kcjjdz) == "") { ?>
		<td><span id="elh_kcdmb_kcjjdz" class="kcdmb_kcjjdz"><?php echo $kcdmb->kcjjdz->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->kcjjdz) ?>',1);"><span id="elh_kcdmb_kcjjdz" class="kcdmb_kcjjdz">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->kcjjdz->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->kcjjdz->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->kcjjdz->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->yqdm->Visible) { // yqdm ?>
	<?php if ($kcdmb->SortUrl($kcdmb->yqdm) == "") { ?>
		<td><span id="elh_kcdmb_yqdm" class="kcdmb_yqdm"><?php echo $kcdmb->yqdm->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->yqdm) ?>',1);"><span id="elh_kcdmb_yqdm" class="kcdmb_yqdm">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->yqdm->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->yqdm->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->yqdm->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->yqmc->Visible) { // yqmc ?>
	<?php if ($kcdmb->SortUrl($kcdmb->yqmc) == "") { ?>
		<td><span id="elh_kcdmb_yqmc" class="kcdmb_yqmc"><?php echo $kcdmb->yqmc->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->yqmc) ?>',1);"><span id="elh_kcdmb_yqmc" class="kcdmb_yqmc">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->yqmc->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->yqmc->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->yqmc->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($kcdmb->bsyz->Visible) { // bsyz ?>
	<?php if ($kcdmb->SortUrl($kcdmb->bsyz) == "") { ?>
		<td><span id="elh_kcdmb_bsyz" class="kcdmb_bsyz"><?php echo $kcdmb->bsyz->FldCaption() ?></span></td>
	<?php } else { ?>
		<td><div class="ewPointer" onmousedown="ew_Sort(event,'<?php echo $kcdmb->SortUrl($kcdmb->bsyz) ?>',1);"><span id="elh_kcdmb_bsyz" class="kcdmb_bsyz">
			<table cellspacing="0" class="ewTableHeaderBtn"><thead><tr><td><?php echo $kcdmb->bsyz->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td style="width: 10px;"><?php if ($kcdmb->bsyz->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($kcdmb->bsyz->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$kcdmb_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($kcdmb->ExportAll && $kcdmb->Export <> "") {
	$kcdmb_list->StopRec = $kcdmb_list->TotalRecs;
} else {

	// Set the last record to display
	if ($kcdmb_list->TotalRecs > $kcdmb_list->StartRec + $kcdmb_list->DisplayRecs - 1)
		$kcdmb_list->StopRec = $kcdmb_list->StartRec + $kcdmb_list->DisplayRecs - 1;
	else
		$kcdmb_list->StopRec = $kcdmb_list->TotalRecs;
}
$kcdmb_list->RecCnt = $kcdmb_list->StartRec - 1;
if ($kcdmb_list->Recordset && !$kcdmb_list->Recordset->EOF) {
	$kcdmb_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $kcdmb_list->StartRec > 1)
		$kcdmb_list->Recordset->Move($kcdmb_list->StartRec - 1);
} elseif (!$kcdmb->AllowAddDeleteRow && $kcdmb_list->StopRec == 0) {
	$kcdmb_list->StopRec = $kcdmb->GridAddRowCount;
}

// Initialize aggregate
$kcdmb->RowType = EW_ROWTYPE_AGGREGATEINIT;
$kcdmb->ResetAttrs();
$kcdmb_list->RenderRow();
while ($kcdmb_list->RecCnt < $kcdmb_list->StopRec) {
	$kcdmb_list->RecCnt++;
	if (intval($kcdmb_list->RecCnt) >= intval($kcdmb_list->StartRec)) {
		$kcdmb_list->RowCnt++;

		// Set up key count
		$kcdmb_list->KeyCount = $kcdmb_list->RowIndex;

		// Init row class and style
		$kcdmb->ResetAttrs();
		$kcdmb->CssClass = "";
		if ($kcdmb->CurrentAction == "gridadd") {
		} else {
			$kcdmb_list->LoadRowValues($kcdmb_list->Recordset); // Load row values
		}
		$kcdmb->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$kcdmb->RowAttrs = array_merge($kcdmb->RowAttrs, array('data-rowindex'=>$kcdmb_list->RowCnt, 'id'=>'r' . $kcdmb_list->RowCnt . '_kcdmb', 'data-rowtype'=>$kcdmb->RowType));

		// Render row
		$kcdmb_list->RenderRow();

			// Render list options
			$kcdmb_list->RenderListOptions();
?>
	<tr<?php echo $kcdmb->RowAttributes() ?>>
<?php

// Render list options (body, left)
$kcdmb_list->ListOptions->Render("body", "left", $kcdmb_list->RowCnt);
?>
	<?php if ($kcdmb->kcdm->Visible) { // kcdm ?>
		<td<?php echo $kcdmb->kcdm->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_kcdm" class="kcdmb_kcdm">
<span<?php echo $kcdmb->kcdm->ViewAttributes() ?>>
<?php echo $kcdmb->kcdm->ListViewValue() ?></span>
<a name="<?php echo $kcdmb_list->PageObjName . "_row_" . $kcdmb_list->RowCnt ?>" id="<?php echo $kcdmb_list->PageObjName . "_row_" . $kcdmb_list->RowCnt ?>"></a></span></td>
	<?php } ?>
	<?php if ($kcdmb->kczwmc->Visible) { // kczwmc ?>
		<td<?php echo $kcdmb->kczwmc->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_kczwmc" class="kcdmb_kczwmc">
<span<?php echo $kcdmb->kczwmc->ViewAttributes() ?>>
<?php echo $kcdmb->kczwmc->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->kcywmc->Visible) { // kcywmc ?>
		<td<?php echo $kcdmb->kcywmc->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_kcywmc" class="kcdmb_kcywmc">
<span<?php echo $kcdmb->kcywmc->ViewAttributes() ?>>
<?php echo $kcdmb->kcywmc->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->xf->Visible) { // xf ?>
		<td<?php echo $kcdmb->xf->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_xf" class="kcdmb_xf">
<span<?php echo $kcdmb->xf->ViewAttributes() ?>>
<?php echo $kcdmb->xf->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->zxs->Visible) { // zxs ?>
		<td<?php echo $kcdmb->zxs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_zxs" class="kcdmb_zxs">
<span<?php echo $kcdmb->zxs->ViewAttributes() ?>>
<?php echo $kcdmb->zxs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->zs->Visible) { // zs ?>
		<td<?php echo $kcdmb->zs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_zs" class="kcdmb_zs">
<span<?php echo $kcdmb->zs->ViewAttributes() ?>>
<?php echo $kcdmb->zs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->yxyq->Visible) { // yxyq ?>
		<td<?php echo $kcdmb->yxyq->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_yxyq" class="kcdmb_yxyq">
<span<?php echo $kcdmb->yxyq->ViewAttributes() ?>>
<?php echo $kcdmb->yxyq->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->bs1->Visible) { // bs1 ?>
		<td<?php echo $kcdmb->bs1->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_bs1" class="kcdmb_bs1">
<span<?php echo $kcdmb->bs1->ViewAttributes() ?>>
<?php echo $kcdmb->bs1->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->bs2->Visible) { // bs2 ?>
		<td<?php echo $kcdmb->bs2->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_bs2" class="kcdmb_bs2">
<span<?php echo $kcdmb->bs2->ViewAttributes() ?>>
<?php echo $kcdmb->bs2->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->qzxs->Visible) { // qzxs ?>
		<td<?php echo $kcdmb->qzxs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_qzxs" class="kcdmb_qzxs">
<span<?php echo $kcdmb->qzxs->ViewAttributes() ?>>
<?php echo $kcdmb->qzxs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->sfwyb->Visible) { // sfwyb ?>
		<td<?php echo $kcdmb->sfwyb->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_sfwyb" class="kcdmb_sfwyb">
<span<?php echo $kcdmb->sfwyb->ViewAttributes() ?>>
<?php echo $kcdmb->sfwyb->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->zdkkrs->Visible) { // zdkkrs ?>
		<td<?php echo $kcdmb->zdkkrs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_zdkkrs" class="kcdmb_zdkkrs">
<span<?php echo $kcdmb->zdkkrs->ViewAttributes() ?>>
<?php echo $kcdmb->zdkkrs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->kclb->Visible) { // kclb ?>
		<td<?php echo $kcdmb->kclb->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_kclb" class="kcdmb_kclb">
<span<?php echo $kcdmb->kclb->ViewAttributes() ?>>
<?php echo $kcdmb->kclb->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->kkbmdm->Visible) { // kkbmdm ?>
		<td<?php echo $kcdmb->kkbmdm->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_kkbmdm" class="kcdmb_kkbmdm">
<span<?php echo $kcdmb->kkbmdm->ViewAttributes() ?>>
<?php echo $kcdmb->kkbmdm->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->zhxs->Visible) { // zhxs ?>
		<td<?php echo $kcdmb->zhxs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_zhxs" class="kcdmb_zhxs">
<span<?php echo $kcdmb->zhxs->ViewAttributes() ?>>
<?php echo $kcdmb->zhxs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->yxj->Visible) { // yxj ?>
		<td<?php echo $kcdmb->yxj->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_yxj" class="kcdmb_yxj">
<span<?php echo $kcdmb->yxj->ViewAttributes() ?>>
<?php echo $kcdmb->yxj->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->pksj->Visible) { // pksj ?>
		<td<?php echo $kcdmb->pksj->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_pksj" class="kcdmb_pksj">
<span<?php echo $kcdmb->pksj->ViewAttributes() ?>>
<?php echo $kcdmb->pksj->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->pkyq->Visible) { // pkyq ?>
		<td<?php echo $kcdmb->pkyq->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_pkyq" class="kcdmb_pkyq">
<span<?php echo $kcdmb->pkyq->ViewAttributes() ?>>
<?php echo $kcdmb->pkyq->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->xs->Visible) { // xs ?>
		<td<?php echo $kcdmb->xs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_xs" class="kcdmb_xs">
<span<?php echo $kcdmb->xs->ViewAttributes() ?>>
<?php echo $kcdmb->xs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->kthkcdm->Visible) { // kthkcdm ?>
		<td<?php echo $kcdmb->kthkcdm->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_kthkcdm" class="kcdmb_kthkcdm">
<span<?php echo $kcdmb->kthkcdm->ViewAttributes() ?>>
<?php echo $kcdmb->kthkcdm->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->xlcc->Visible) { // xlcc ?>
		<td<?php echo $kcdmb->xlcc->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_xlcc" class="kcdmb_xlcc">
<span<?php echo $kcdmb->xlcc->ViewAttributes() ?>>
<?php echo $kcdmb->xlcc->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->gzlxs->Visible) { // gzlxs ?>
		<td<?php echo $kcdmb->gzlxs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_gzlxs" class="kcdmb_gzlxs">
<span<?php echo $kcdmb->gzlxs->ViewAttributes() ?>>
<?php echo $kcdmb->gzlxs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->khfs->Visible) { // khfs ?>
		<td<?php echo $kcdmb->khfs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_khfs" class="kcdmb_khfs">
<span<?php echo $kcdmb->khfs->ViewAttributes() ?>>
<?php echo $kcdmb->khfs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->kcys->Visible) { // kcys ?>
		<td<?php echo $kcdmb->kcys->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_kcys" class="kcdmb_kcys">
<span<?php echo $kcdmb->kcys->ViewAttributes() ?>>
<?php echo $kcdmb->kcys->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->tkbj->Visible) { // tkbj ?>
		<td<?php echo $kcdmb->tkbj->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_tkbj" class="kcdmb_tkbj">
<span<?php echo $kcdmb->tkbj->ViewAttributes() ?>>
<?php echo $kcdmb->tkbj->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->llxs->Visible) { // llxs ?>
		<td<?php echo $kcdmb->llxs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_llxs" class="kcdmb_llxs">
<span<?php echo $kcdmb->llxs->ViewAttributes() ?>>
<?php echo $kcdmb->llxs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->syxs->Visible) { // syxs ?>
		<td<?php echo $kcdmb->syxs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_syxs" class="kcdmb_syxs">
<span<?php echo $kcdmb->syxs->ViewAttributes() ?>>
<?php echo $kcdmb->syxs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->sjxs->Visible) { // sjxs ?>
		<td<?php echo $kcdmb->sjxs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_sjxs" class="kcdmb_sjxs">
<span<?php echo $kcdmb->sjxs->ViewAttributes() ?>>
<?php echo $kcdmb->sjxs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->bz->Visible) { // bz ?>
		<td<?php echo $kcdmb->bz->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_bz" class="kcdmb_bz">
<span<?php echo $kcdmb->bz->ViewAttributes() ?>>
<?php echo $kcdmb->bz->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->kcxz->Visible) { // kcxz ?>
		<td<?php echo $kcdmb->kcxz->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_kcxz" class="kcdmb_kcxz">
<span<?php echo $kcdmb->kcxz->ViewAttributes() ?>>
<?php echo $kcdmb->kcxz->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->zcfy->Visible) { // zcfy ?>
		<td<?php echo $kcdmb->zcfy->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_zcfy" class="kcdmb_zcfy">
<span<?php echo $kcdmb->zcfy->ViewAttributes() ?>>
<?php echo $kcdmb->zcfy->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->cxfy->Visible) { // cxfy ?>
		<td<?php echo $kcdmb->cxfy->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_cxfy" class="kcdmb_cxfy">
<span<?php echo $kcdmb->cxfy->ViewAttributes() ?>>
<?php echo $kcdmb->cxfy->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->fxfy->Visible) { // fxfy ?>
		<td<?php echo $kcdmb->fxfy->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_fxfy" class="kcdmb_fxfy">
<span<?php echo $kcdmb->fxfy->ViewAttributes() ?>>
<?php echo $kcdmb->fxfy->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->syxmsyq->Visible) { // syxmsyq ?>
		<td<?php echo $kcdmb->syxmsyq->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_syxmsyq" class="kcdmb_syxmsyq">
<span<?php echo $kcdmb->syxmsyq->ViewAttributes() ?>>
<?php echo $kcdmb->syxmsyq->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->skfsmc->Visible) { // skfsmc ?>
		<td<?php echo $kcdmb->skfsmc->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_skfsmc" class="kcdmb_skfsmc">
<span<?php echo $kcdmb->skfsmc->ViewAttributes() ?>>
<?php echo $kcdmb->skfsmc->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->axbxrw->Visible) { // axbxrw ?>
		<td<?php echo $kcdmb->axbxrw->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_axbxrw" class="kcdmb_axbxrw">
<span<?php echo $kcdmb->axbxrw->ViewAttributes() ?>>
<?php echo $kcdmb->axbxrw->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->typk->Visible) { // typk ?>
		<td<?php echo $kcdmb->typk->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_typk" class="kcdmb_typk">
<span<?php echo $kcdmb->typk->ViewAttributes() ?>>
<?php echo $kcdmb->typk->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->sykkbmdm->Visible) { // sykkbmdm ?>
		<td<?php echo $kcdmb->sykkbmdm->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_sykkbmdm" class="kcdmb_sykkbmdm">
<span<?php echo $kcdmb->sykkbmdm->ViewAttributes() ?>>
<?php echo $kcdmb->sykkbmdm->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->bsfbj->Visible) { // bsfbj ?>
		<td<?php echo $kcdmb->bsfbj->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_bsfbj" class="kcdmb_bsfbj">
<span<?php echo $kcdmb->bsfbj->ViewAttributes() ?>>
<?php echo $kcdmb->bsfbj->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->syxfyq->Visible) { // syxfyq ?>
		<td<?php echo $kcdmb->syxfyq->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_syxfyq" class="kcdmb_syxfyq">
<span<?php echo $kcdmb->syxfyq->ViewAttributes() ?>>
<?php echo $kcdmb->syxfyq->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->kcgs->Visible) { // kcgs ?>
		<td<?php echo $kcdmb->kcgs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_kcgs" class="kcdmb_kcgs">
<span<?php echo $kcdmb->kcgs->ViewAttributes() ?>>
<?php echo $kcdmb->kcgs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->kkxdm->Visible) { // kkxdm ?>
		<td<?php echo $kcdmb->kkxdm->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_kkxdm" class="kcdmb_kkxdm">
<span<?php echo $kcdmb->kkxdm->ViewAttributes() ?>>
<?php echo $kcdmb->kkxdm->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->xkfl->Visible) { // xkfl ?>
		<td<?php echo $kcdmb->xkfl->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_xkfl" class="kcdmb_xkfl">
<span<?php echo $kcdmb->xkfl->ViewAttributes() ?>>
<?php echo $kcdmb->xkfl->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->bs11->Visible) { // bs11 ?>
		<td<?php echo $kcdmb->bs11->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_bs11" class="kcdmb_bs11">
<span<?php echo $kcdmb->bs11->ViewAttributes() ?>>
<?php echo $kcdmb->bs11->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->kcsjxs->Visible) { // kcsjxs ?>
		<td<?php echo $kcdmb->kcsjxs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_kcsjxs" class="kcdmb_kcsjxs">
<span<?php echo $kcdmb->kcsjxs->ViewAttributes() ?>>
<?php echo $kcdmb->kcsjxs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->xtkxs->Visible) { // xtkxs ?>
		<td<?php echo $kcdmb->xtkxs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_xtkxs" class="kcdmb_xtkxs">
<span<?php echo $kcdmb->xtkxs->ViewAttributes() ?>>
<?php echo $kcdmb->xtkxs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->knsjxs->Visible) { // knsjxs ?>
		<td<?php echo $kcdmb->knsjxs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_knsjxs" class="kcdmb_knsjxs">
<span<?php echo $kcdmb->knsjxs->ViewAttributes() ?>>
<?php echo $kcdmb->knsjxs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->kwsjxs->Visible) { // kwsjxs ?>
		<td<?php echo $kcdmb->kwsjxs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_kwsjxs" class="kcdmb_kwsjxs">
<span<?php echo $kcdmb->kwsjxs->ViewAttributes() ?>>
<?php echo $kcdmb->kwsjxs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->ytxs->Visible) { // ytxs ?>
		<td<?php echo $kcdmb->ytxs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_ytxs" class="kcdmb_ytxs">
<span<?php echo $kcdmb->ytxs->ViewAttributes() ?>>
<?php echo $kcdmb->ytxs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->scjssjxs->Visible) { // scjssjxs ?>
		<td<?php echo $kcdmb->scjssjxs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_scjssjxs" class="kcdmb_scjssjxs">
<span<?php echo $kcdmb->scjssjxs->ViewAttributes() ?>>
<?php echo $kcdmb->scjssjxs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->sxxs->Visible) { // sxxs ?>
		<td<?php echo $kcdmb->sxxs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_sxxs" class="kcdmb_sxxs">
<span<?php echo $kcdmb->sxxs->ViewAttributes() ?>>
<?php echo $kcdmb->sxxs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->ksxs->Visible) { // ksxs ?>
		<td<?php echo $kcdmb->ksxs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_ksxs" class="kcdmb_ksxs">
<span<?php echo $kcdmb->ksxs->ViewAttributes() ?>>
<?php echo $kcdmb->ksxs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->bsxs->Visible) { // bsxs ?>
		<td<?php echo $kcdmb->bsxs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_bsxs" class="kcdmb_bsxs">
<span<?php echo $kcdmb->bsxs->ViewAttributes() ?>>
<?php echo $kcdmb->bsxs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->shdcxs->Visible) { // shdcxs ?>
		<td<?php echo $kcdmb->shdcxs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_shdcxs" class="kcdmb_shdcxs">
<span<?php echo $kcdmb->shdcxs->ViewAttributes() ?>>
<?php echo $kcdmb->shdcxs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->jys->Visible) { // jys ?>
		<td<?php echo $kcdmb->jys->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_jys" class="kcdmb_jys">
<span<?php echo $kcdmb->jys->ViewAttributes() ?>>
<?php echo $kcdmb->jys->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->sftykw->Visible) { // sftykw ?>
		<td<?php echo $kcdmb->sftykw->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_sftykw" class="kcdmb_sftykw">
<span<?php echo $kcdmb->sftykw->ViewAttributes() ?>>
<?php echo $kcdmb->sftykw->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->kcjc->Visible) { // kcjc ?>
		<td<?php echo $kcdmb->kcjc->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_kcjc" class="kcdmb_kcjc">
<span<?php echo $kcdmb->kcjc->ViewAttributes() ?>>
<?php echo $kcdmb->kcjc->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->kwxs->Visible) { // kwxs ?>
		<td<?php echo $kcdmb->kwxs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_kwxs" class="kcdmb_kwxs">
<span<?php echo $kcdmb->kwxs->ViewAttributes() ?>>
<?php echo $kcdmb->kwxs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->xkdx->Visible) { // xkdx ?>
		<td<?php echo $kcdmb->xkdx->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_xkdx" class="kcdmb_xkdx">
<span<?php echo $kcdmb->xkdx->ViewAttributes() ?>>
<?php echo $kcdmb->xkdx->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->jsxm->Visible) { // jsxm ?>
		<td<?php echo $kcdmb->jsxm->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_jsxm" class="kcdmb_jsxm">
<span<?php echo $kcdmb->jsxm->ViewAttributes() ?>>
<?php echo $kcdmb->jsxm->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->bs3->Visible) { // bs3 ?>
		<td<?php echo $kcdmb->bs3->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_bs3" class="kcdmb_bs3">
<span<?php echo $kcdmb->bs3->ViewAttributes() ?>>
<?php echo $kcdmb->bs3->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->xfjs->Visible) { // xfjs ?>
		<td<?php echo $kcdmb->xfjs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_xfjs" class="kcdmb_xfjs">
<span<?php echo $kcdmb->xfjs->ViewAttributes() ?>>
<?php echo $kcdmb->xfjs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->zhxsjs->Visible) { // zhxsjs ?>
		<td<?php echo $kcdmb->zhxsjs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_zhxsjs" class="kcdmb_zhxsjs">
<span<?php echo $kcdmb->zhxsjs->ViewAttributes() ?>>
<?php echo $kcdmb->zhxsjs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->jkxsjs->Visible) { // jkxsjs ?>
		<td<?php echo $kcdmb->jkxsjs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_jkxsjs" class="kcdmb_jkxsjs">
<span<?php echo $kcdmb->jkxsjs->ViewAttributes() ?>>
<?php echo $kcdmb->jkxsjs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->syxsjs->Visible) { // syxsjs ?>
		<td<?php echo $kcdmb->syxsjs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_syxsjs" class="kcdmb_syxsjs">
<span<?php echo $kcdmb->syxsjs->ViewAttributes() ?>>
<?php echo $kcdmb->syxsjs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->sjxsjs->Visible) { // sjxsjs ?>
		<td<?php echo $kcdmb->sjxsjs->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_sjxsjs" class="kcdmb_sjxsjs">
<span<?php echo $kcdmb->sjxsjs->ViewAttributes() ?>>
<?php echo $kcdmb->sjxsjs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->sfxssy->Visible) { // sfxssy ?>
		<td<?php echo $kcdmb->sfxssy->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_sfxssy" class="kcdmb_sfxssy">
<span<?php echo $kcdmb->sfxssy->ViewAttributes() ?>>
<?php echo $kcdmb->sfxssy->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->kcjsztdw->Visible) { // kcjsztdw ?>
		<td<?php echo $kcdmb->kcjsztdw->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_kcjsztdw" class="kcdmb_kcjsztdw">
<span<?php echo $kcdmb->kcjsztdw->ViewAttributes() ?>>
<?php echo $kcdmb->kcjsztdw->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->bs4->Visible) { // bs4 ?>
		<td<?php echo $kcdmb->bs4->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_bs4" class="kcdmb_bs4">
<span<?php echo $kcdmb->bs4->ViewAttributes() ?>>
<?php echo $kcdmb->bs4->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->syzy->Visible) { // syzy ?>
		<td<?php echo $kcdmb->syzy->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_syzy" class="kcdmb_syzy">
<span<?php echo $kcdmb->syzy->ViewAttributes() ?>>
<?php echo $kcdmb->syzy->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->lrsj->Visible) { // lrsj ?>
		<td<?php echo $kcdmb->lrsj->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_lrsj" class="kcdmb_lrsj">
<span<?php echo $kcdmb->lrsj->ViewAttributes() ?>>
<?php echo $kcdmb->lrsj->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->kcmcpy->Visible) { // kcmcpy ?>
		<td<?php echo $kcdmb->kcmcpy->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_kcmcpy" class="kcdmb_kcmcpy">
<span<?php echo $kcdmb->kcmcpy->ViewAttributes() ?>>
<?php echo $kcdmb->kcmcpy->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->xqdm->Visible) { // xqdm ?>
		<td<?php echo $kcdmb->xqdm->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_xqdm" class="kcdmb_xqdm">
<span<?php echo $kcdmb->xqdm->ViewAttributes() ?>>
<?php echo $kcdmb->xqdm->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->kcqmc->Visible) { // kcqmc ?>
		<td<?php echo $kcdmb->kcqmc->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_kcqmc" class="kcdmb_kcqmc">
<span<?php echo $kcdmb->kcqmc->ViewAttributes() ?>>
<?php echo $kcdmb->kcqmc->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->ksxsmc->Visible) { // ksxsmc ?>
		<td<?php echo $kcdmb->ksxsmc->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_ksxsmc" class="kcdmb_ksxsmc">
<span<?php echo $kcdmb->ksxsmc->ViewAttributes() ?>>
<?php echo $kcdmb->ksxsmc->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->sfbysjkc->Visible) { // sfbysjkc ?>
		<td<?php echo $kcdmb->sfbysjkc->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_sfbysjkc" class="kcdmb_sfbysjkc">
<span<?php echo $kcdmb->sfbysjkc->ViewAttributes() ?>>
<?php echo $kcdmb->sfbysjkc->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->bs5->Visible) { // bs5 ?>
		<td<?php echo $kcdmb->bs5->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_bs5" class="kcdmb_bs5">
<span<?php echo $kcdmb->bs5->ViewAttributes() ?>>
<?php echo $kcdmb->bs5->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->nj->Visible) { // nj ?>
		<td<?php echo $kcdmb->nj->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_nj" class="kcdmb_nj">
<span<?php echo $kcdmb->nj->ViewAttributes() ?>>
<?php echo $kcdmb->nj->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->cjlrr->Visible) { // cjlrr ?>
		<td<?php echo $kcdmb->cjlrr->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_cjlrr" class="kcdmb_cjlrr">
<span<?php echo $kcdmb->cjlrr->ViewAttributes() ?>>
<?php echo $kcdmb->cjlrr->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->sftsbx->Visible) { // sftsbx ?>
		<td<?php echo $kcdmb->sftsbx->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_sftsbx" class="kcdmb_sftsbx">
<span<?php echo $kcdmb->sftsbx->ViewAttributes() ?>>
<?php echo $kcdmb->sftsbx->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->dxdgdz->Visible) { // dxdgdz ?>
		<td<?php echo $kcdmb->dxdgdz->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_dxdgdz" class="kcdmb_dxdgdz">
<span<?php echo $kcdmb->dxdgdz->ViewAttributes() ?>>
<?php echo $kcdmb->dxdgdz->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->kcfl->Visible) { // kcfl ?>
		<td<?php echo $kcdmb->kcfl->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_kcfl" class="kcdmb_kcfl">
<span<?php echo $kcdmb->kcfl->ViewAttributes() ?>>
<?php echo $kcdmb->kcfl->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->sjlrzgh->Visible) { // sjlrzgh ?>
		<td<?php echo $kcdmb->sjlrzgh->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_sjlrzgh" class="kcdmb_sjlrzgh">
<span<?php echo $kcdmb->sjlrzgh->ViewAttributes() ?>>
<?php echo $kcdmb->sjlrzgh->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->kcjjdz->Visible) { // kcjjdz ?>
		<td<?php echo $kcdmb->kcjjdz->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_kcjjdz" class="kcdmb_kcjjdz">
<span<?php echo $kcdmb->kcjjdz->ViewAttributes() ?>>
<?php echo $kcdmb->kcjjdz->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->yqdm->Visible) { // yqdm ?>
		<td<?php echo $kcdmb->yqdm->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_yqdm" class="kcdmb_yqdm">
<span<?php echo $kcdmb->yqdm->ViewAttributes() ?>>
<?php echo $kcdmb->yqdm->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->yqmc->Visible) { // yqmc ?>
		<td<?php echo $kcdmb->yqmc->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_yqmc" class="kcdmb_yqmc">
<span<?php echo $kcdmb->yqmc->ViewAttributes() ?>>
<?php echo $kcdmb->yqmc->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($kcdmb->bsyz->Visible) { // bsyz ?>
		<td<?php echo $kcdmb->bsyz->CellAttributes() ?>><span id="el<?php echo $kcdmb_list->RowCnt ?>_kcdmb_bsyz" class="kcdmb_bsyz">
<span<?php echo $kcdmb->bsyz->ViewAttributes() ?>>
<?php echo $kcdmb->bsyz->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$kcdmb_list->ListOptions->Render("body", "right", $kcdmb_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($kcdmb->CurrentAction <> "gridadd")
		$kcdmb_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($kcdmb->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($kcdmb_list->Recordset)
	$kcdmb_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($kcdmb->CurrentAction <> "gridadd" && $kcdmb->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table border="0" cellspacing="0" cellpadding="0" class="ewPager">
	<tr>
		<td nowrap>
<?php if (!isset($kcdmb_list->Pager)) $kcdmb_list->Pager = new cPrevNextPager($kcdmb_list->StartRec, $kcdmb_list->DisplayRecs, $kcdmb_list->TotalRecs) ?>
<?php if ($kcdmb_list->Pager->RecordCount > 0) { ?>
	<table border="0" cellspacing="0" cellpadding="0"><tr><td><span class="phpmaker"><?php echo $Language->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($kcdmb_list->Pager->FirstButton->Enabled) { ?>
	<td><a href="<?php echo $kcdmb_list->PageUrl() ?>start=<?php echo $kcdmb_list->Pager->FirstButton->Start ?>"><img src="phpimages/first.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/firstdisab.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($kcdmb_list->Pager->PrevButton->Enabled) { ?>
	<td><a href="<?php echo $kcdmb_list->PageUrl() ?>start=<?php echo $kcdmb_list->Pager->PrevButton->Start ?>"><img src="phpimages/prev.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/prevdisab.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" id="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $kcdmb_list->Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($kcdmb_list->Pager->NextButton->Enabled) { ?>
	<td><a href="<?php echo $kcdmb_list->PageUrl() ?>start=<?php echo $kcdmb_list->Pager->NextButton->Start ?>"><img src="phpimages/next.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/nextdisab.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--last page button-->
	<?php if ($kcdmb_list->Pager->LastButton->Enabled) { ?>
	<td><a href="<?php echo $kcdmb_list->PageUrl() ?>start=<?php echo $kcdmb_list->Pager->LastButton->Start ?>"><img src="phpimages/last.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/lastdisab.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
	<td><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $kcdmb_list->Pager->PageCount ?></span></td>
	</tr></table>
	</td>	
	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td>
	<span class="phpmaker"><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $kcdmb_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $kcdmb_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $kcdmb_list->Pager->RecordCount ?></span>
<?php } else { ?>
	<?php if ($kcdmb_list->SearchWhere == "0=101") { ?>
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
</span>
</div>
</td></tr></table>
<script type="text/javascript">
fkcdmblistsrch.Init();
fkcdmblist.Init();
</script>
<?php
$kcdmb_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$kcdmb_list->Page_Terminate();
?>
