<?php

// Global variable for table object
$kcdmb = NULL;

//
// Table class for kcdmb
//
class ckcdmb extends cTable {
	var $kcdm;
	var $kczwmc;
	var $kcywmc;
	var $xf;
	var $zxs;
	var $zs;
	var $yxyq;
	var $kcjj;
	var $jxdg;
	var $bs1;
	var $bs2;
	var $qzxs;
	var $ksnrjbz;
	var $sfwyb;
	var $zdkkrs;
	var $kclb;
	var $kkbmdm;
	var $zhxs;
	var $yxj;
	var $pksj;
	var $pkyq;
	var $xs;
	var $kczyzyjmd;
	var $zycks;
	var $kthkcdm;
	var $xlcc;
	var $gzlxs;
	var $khfs;
	var $kcys;
	var $tkbj;
	var $llxs;
	var $syxs;
	var $sjxs;
	var $bz;
	var $kcxz;
	var $zcfy;
	var $cxfy;
	var $fxfy;
	var $syxmsyq;
	var $skfsmc;
	var $axbxrw;
	var $typk;
	var $sykkbmdm;
	var $bsfbj;
	var $temp1;
	var $temp2;
	var $temp3;
	var $temp4;
	var $temp5;
	var $temp6;
	var $temp7;
	var $temp8;
	var $temp9;
	var $temp10;
	var $syxfyq;
	var $kcgs;
	var $kkxdm;
	var $xkfl;
	var $bs11;
	var $kcsjxs;
	var $xtkxs;
	var $knsjxs;
	var $kwsjxs;
	var $ytxs;
	var $scjssjxs;
	var $sxxs;
	var $ksxs;
	var $bsxs;
	var $shdcxs;
	var $jys;
	var $sftykw;
	var $kcjc;
	var $kwxs;
	var $xkdx;
	var $jsxm;
	var $bs3;
	var $xfjs;
	var $zhxsjs;
	var $jkxsjs;
	var $syxsjs;
	var $sjxsjs;
	var $sfxssy;
	var $kcjsztdw;
	var $bs4;
	var $syzy;
	var $lrsj;
	var $kcmcpy;
	var $xqdm;
	var $kcqmc;
	var $ksxsmc;
	var $sfbysjkc;
	var $bs5;
	var $nj;
	var $cjlrr;
	var $sftsbx;
	var $dxdgdz;
	var $kcfl;
	var $kcywjj;
	var $sjlrzgh;
	var $kcjjdz;
	var $yqdm;
	var $yqmc;
	var $bsyz;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;
		$this->TableVar = 'kcdmb';
		$this->TableName = 'kcdmb';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row

		// kcdm
		$this->kcdm = new cField('kcdmb', 'kcdmb', 'x_kcdm', 'kcdm', '`kcdm`', '`kcdm`', 200, -1, FALSE, '`kcdm`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kcdm'] = &$this->kcdm;

		// kczwmc
		$this->kczwmc = new cField('kcdmb', 'kcdmb', 'x_kczwmc', 'kczwmc', '`kczwmc`', '`kczwmc`', 200, -1, FALSE, '`kczwmc`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kczwmc'] = &$this->kczwmc;

		// kcywmc
		$this->kcywmc = new cField('kcdmb', 'kcdmb', 'x_kcywmc', 'kcywmc', '`kcywmc`', '`kcywmc`', 200, -1, FALSE, '`kcywmc`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kcywmc'] = &$this->kcywmc;

		// xf
		$this->xf = new cField('kcdmb', 'kcdmb', 'x_xf', 'xf', '`xf`', '`xf`', 200, -1, FALSE, '`xf`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['xf'] = &$this->xf;

		// zxs
		$this->zxs = new cField('kcdmb', 'kcdmb', 'x_zxs', 'zxs', '`zxs`', '`zxs`', 200, -1, FALSE, '`zxs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['zxs'] = &$this->zxs;

		// zs
		$this->zs = new cField('kcdmb', 'kcdmb', 'x_zs', 'zs', '`zs`', '`zs`', 200, -1, FALSE, '`zs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['zs'] = &$this->zs;

		// yxyq
		$this->yxyq = new cField('kcdmb', 'kcdmb', 'x_yxyq', 'yxyq', '`yxyq`', '`yxyq`', 200, -1, FALSE, '`yxyq`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['yxyq'] = &$this->yxyq;

		// kcjj
		$this->kcjj = new cField('kcdmb', 'kcdmb', 'x_kcjj', 'kcjj', '`kcjj`', '`kcjj`', 201, -1, FALSE, '`kcjj`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kcjj'] = &$this->kcjj;

		// jxdg
		$this->jxdg = new cField('kcdmb', 'kcdmb', 'x_jxdg', 'jxdg', '`jxdg`', '`jxdg`', 201, -1, FALSE, '`jxdg`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['jxdg'] = &$this->jxdg;

		// bs1
		$this->bs1 = new cField('kcdmb', 'kcdmb', 'x_bs1', 'bs1', '`bs1`', '`bs1`', 200, -1, FALSE, '`bs1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['bs1'] = &$this->bs1;

		// bs2
		$this->bs2 = new cField('kcdmb', 'kcdmb', 'x_bs2', 'bs2', '`bs2`', '`bs2`', 200, -1, FALSE, '`bs2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['bs2'] = &$this->bs2;

		// qzxs
		$this->qzxs = new cField('kcdmb', 'kcdmb', 'x_qzxs', 'qzxs', '`qzxs`', '`qzxs`', 200, -1, FALSE, '`qzxs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['qzxs'] = &$this->qzxs;

		// ksnrjbz
		$this->ksnrjbz = new cField('kcdmb', 'kcdmb', 'x_ksnrjbz', 'ksnrjbz', '`ksnrjbz`', '`ksnrjbz`', 201, -1, FALSE, '`ksnrjbz`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ksnrjbz'] = &$this->ksnrjbz;

		// sfwyb
		$this->sfwyb = new cField('kcdmb', 'kcdmb', 'x_sfwyb', 'sfwyb', '`sfwyb`', '`sfwyb`', 200, -1, FALSE, '`sfwyb`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['sfwyb'] = &$this->sfwyb;

		// zdkkrs
		$this->zdkkrs = new cField('kcdmb', 'kcdmb', 'x_zdkkrs', 'zdkkrs', '`zdkkrs`', '`zdkkrs`', 131, -1, FALSE, '`zdkkrs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->zdkkrs->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['zdkkrs'] = &$this->zdkkrs;

		// kclb
		$this->kclb = new cField('kcdmb', 'kcdmb', 'x_kclb', 'kclb', '`kclb`', '`kclb`', 200, -1, FALSE, '`kclb`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kclb'] = &$this->kclb;

		// kkbmdm
		$this->kkbmdm = new cField('kcdmb', 'kcdmb', 'x_kkbmdm', 'kkbmdm', '`kkbmdm`', '`kkbmdm`', 200, -1, FALSE, '`kkbmdm`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kkbmdm'] = &$this->kkbmdm;

		// zhxs
		$this->zhxs = new cField('kcdmb', 'kcdmb', 'x_zhxs', 'zhxs', '`zhxs`', '`zhxs`', 200, -1, FALSE, '`zhxs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['zhxs'] = &$this->zhxs;

		// yxj
		$this->yxj = new cField('kcdmb', 'kcdmb', 'x_yxj', 'yxj', '`yxj`', '`yxj`', 200, -1, FALSE, '`yxj`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['yxj'] = &$this->yxj;

		// pksj
		$this->pksj = new cField('kcdmb', 'kcdmb', 'x_pksj', 'pksj', '`pksj`', '`pksj`', 200, -1, FALSE, '`pksj`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['pksj'] = &$this->pksj;

		// pkyq
		$this->pkyq = new cField('kcdmb', 'kcdmb', 'x_pkyq', 'pkyq', '`pkyq`', '`pkyq`', 200, -1, FALSE, '`pkyq`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['pkyq'] = &$this->pkyq;

		// xs
		$this->xs = new cField('kcdmb', 'kcdmb', 'x_xs', 'xs', '`xs`', '`xs`', 200, -1, FALSE, '`xs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['xs'] = &$this->xs;

		// kczyzyjmd
		$this->kczyzyjmd = new cField('kcdmb', 'kcdmb', 'x_kczyzyjmd', 'kczyzyjmd', '`kczyzyjmd`', '`kczyzyjmd`', 201, -1, FALSE, '`kczyzyjmd`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kczyzyjmd'] = &$this->kczyzyjmd;

		// zycks
		$this->zycks = new cField('kcdmb', 'kcdmb', 'x_zycks', 'zycks', '`zycks`', '`zycks`', 201, -1, FALSE, '`zycks`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['zycks'] = &$this->zycks;

		// kthkcdm
		$this->kthkcdm = new cField('kcdmb', 'kcdmb', 'x_kthkcdm', 'kthkcdm', '`kthkcdm`', '`kthkcdm`', 200, -1, FALSE, '`kthkcdm`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kthkcdm'] = &$this->kthkcdm;

		// xlcc
		$this->xlcc = new cField('kcdmb', 'kcdmb', 'x_xlcc', 'xlcc', '`xlcc`', '`xlcc`', 200, -1, FALSE, '`xlcc`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['xlcc'] = &$this->xlcc;

		// gzlxs
		$this->gzlxs = new cField('kcdmb', 'kcdmb', 'x_gzlxs', 'gzlxs', '`gzlxs`', '`gzlxs`', 200, -1, FALSE, '`gzlxs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['gzlxs'] = &$this->gzlxs;

		// khfs
		$this->khfs = new cField('kcdmb', 'kcdmb', 'x_khfs', 'khfs', '`khfs`', '`khfs`', 200, -1, FALSE, '`khfs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['khfs'] = &$this->khfs;

		// kcys
		$this->kcys = new cField('kcdmb', 'kcdmb', 'x_kcys', 'kcys', '`kcys`', '`kcys`', 200, -1, FALSE, '`kcys`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kcys'] = &$this->kcys;

		// tkbj
		$this->tkbj = new cField('kcdmb', 'kcdmb', 'x_tkbj', 'tkbj', '`tkbj`', '`tkbj`', 200, -1, FALSE, '`tkbj`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['tkbj'] = &$this->tkbj;

		// llxs
		$this->llxs = new cField('kcdmb', 'kcdmb', 'x_llxs', 'llxs', '`llxs`', '`llxs`', 131, -1, FALSE, '`llxs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->llxs->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['llxs'] = &$this->llxs;

		// syxs
		$this->syxs = new cField('kcdmb', 'kcdmb', 'x_syxs', 'syxs', '`syxs`', '`syxs`', 131, -1, FALSE, '`syxs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->syxs->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['syxs'] = &$this->syxs;

		// sjxs
		$this->sjxs = new cField('kcdmb', 'kcdmb', 'x_sjxs', 'sjxs', '`sjxs`', '`sjxs`', 131, -1, FALSE, '`sjxs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->sjxs->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['sjxs'] = &$this->sjxs;

		// bz
		$this->bz = new cField('kcdmb', 'kcdmb', 'x_bz', 'bz', '`bz`', '`bz`', 200, -1, FALSE, '`bz`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['bz'] = &$this->bz;

		// kcxz
		$this->kcxz = new cField('kcdmb', 'kcdmb', 'x_kcxz', 'kcxz', '`kcxz`', '`kcxz`', 200, -1, FALSE, '`kcxz`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kcxz'] = &$this->kcxz;

		// zcfy
		$this->zcfy = new cField('kcdmb', 'kcdmb', 'x_zcfy', 'zcfy', '`zcfy`', '`zcfy`', 131, -1, FALSE, '`zcfy`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->zcfy->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['zcfy'] = &$this->zcfy;

		// cxfy
		$this->cxfy = new cField('kcdmb', 'kcdmb', 'x_cxfy', 'cxfy', '`cxfy`', '`cxfy`', 131, -1, FALSE, '`cxfy`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->cxfy->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['cxfy'] = &$this->cxfy;

		// fxfy
		$this->fxfy = new cField('kcdmb', 'kcdmb', 'x_fxfy', 'fxfy', '`fxfy`', '`fxfy`', 131, -1, FALSE, '`fxfy`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fxfy->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['fxfy'] = &$this->fxfy;

		// syxmsyq
		$this->syxmsyq = new cField('kcdmb', 'kcdmb', 'x_syxmsyq', 'syxmsyq', '`syxmsyq`', '`syxmsyq`', 131, -1, FALSE, '`syxmsyq`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->syxmsyq->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['syxmsyq'] = &$this->syxmsyq;

		// skfsmc
		$this->skfsmc = new cField('kcdmb', 'kcdmb', 'x_skfsmc', 'skfsmc', '`skfsmc`', '`skfsmc`', 200, -1, FALSE, '`skfsmc`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['skfsmc'] = &$this->skfsmc;

		// axbxrw
		$this->axbxrw = new cField('kcdmb', 'kcdmb', 'x_axbxrw', 'axbxrw', '`axbxrw`', '`axbxrw`', 200, -1, FALSE, '`axbxrw`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['axbxrw'] = &$this->axbxrw;

		// typk
		$this->typk = new cField('kcdmb', 'kcdmb', 'x_typk', 'typk', '`typk`', '`typk`', 200, -1, FALSE, '`typk`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['typk'] = &$this->typk;

		// sykkbmdm
		$this->sykkbmdm = new cField('kcdmb', 'kcdmb', 'x_sykkbmdm', 'sykkbmdm', '`sykkbmdm`', '`sykkbmdm`', 200, -1, FALSE, '`sykkbmdm`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['sykkbmdm'] = &$this->sykkbmdm;

		// bsfbj
		$this->bsfbj = new cField('kcdmb', 'kcdmb', 'x_bsfbj', 'bsfbj', '`bsfbj`', '`bsfbj`', 200, -1, FALSE, '`bsfbj`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['bsfbj'] = &$this->bsfbj;

		// temp1
		$this->temp1 = new cField('kcdmb', 'kcdmb', 'x_temp1', 'temp1', '`temp1`', '`temp1`', 201, -1, FALSE, '`temp1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['temp1'] = &$this->temp1;

		// temp2
		$this->temp2 = new cField('kcdmb', 'kcdmb', 'x_temp2', 'temp2', '`temp2`', '`temp2`', 201, -1, FALSE, '`temp2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['temp2'] = &$this->temp2;

		// temp3
		$this->temp3 = new cField('kcdmb', 'kcdmb', 'x_temp3', 'temp3', '`temp3`', '`temp3`', 201, -1, FALSE, '`temp3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['temp3'] = &$this->temp3;

		// temp4
		$this->temp4 = new cField('kcdmb', 'kcdmb', 'x_temp4', 'temp4', '`temp4`', '`temp4`', 201, -1, FALSE, '`temp4`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['temp4'] = &$this->temp4;

		// temp5
		$this->temp5 = new cField('kcdmb', 'kcdmb', 'x_temp5', 'temp5', '`temp5`', '`temp5`', 201, -1, FALSE, '`temp5`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['temp5'] = &$this->temp5;

		// temp6
		$this->temp6 = new cField('kcdmb', 'kcdmb', 'x_temp6', 'temp6', '`temp6`', '`temp6`', 201, -1, FALSE, '`temp6`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['temp6'] = &$this->temp6;

		// temp7
		$this->temp7 = new cField('kcdmb', 'kcdmb', 'x_temp7', 'temp7', '`temp7`', '`temp7`', 201, -1, FALSE, '`temp7`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['temp7'] = &$this->temp7;

		// temp8
		$this->temp8 = new cField('kcdmb', 'kcdmb', 'x_temp8', 'temp8', '`temp8`', '`temp8`', 201, -1, FALSE, '`temp8`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['temp8'] = &$this->temp8;

		// temp9
		$this->temp9 = new cField('kcdmb', 'kcdmb', 'x_temp9', 'temp9', '`temp9`', '`temp9`', 201, -1, FALSE, '`temp9`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['temp9'] = &$this->temp9;

		// temp10
		$this->temp10 = new cField('kcdmb', 'kcdmb', 'x_temp10', 'temp10', '`temp10`', '`temp10`', 201, -1, FALSE, '`temp10`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['temp10'] = &$this->temp10;

		// syxfyq
		$this->syxfyq = new cField('kcdmb', 'kcdmb', 'x_syxfyq', 'syxfyq', '`syxfyq`', '`syxfyq`', 200, -1, FALSE, '`syxfyq`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['syxfyq'] = &$this->syxfyq;

		// kcgs
		$this->kcgs = new cField('kcdmb', 'kcdmb', 'x_kcgs', 'kcgs', '`kcgs`', '`kcgs`', 200, -1, FALSE, '`kcgs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kcgs'] = &$this->kcgs;

		// kkxdm
		$this->kkxdm = new cField('kcdmb', 'kcdmb', 'x_kkxdm', 'kkxdm', '`kkxdm`', '`kkxdm`', 200, -1, FALSE, '`kkxdm`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kkxdm'] = &$this->kkxdm;

		// xkfl
		$this->xkfl = new cField('kcdmb', 'kcdmb', 'x_xkfl', 'xkfl', '`xkfl`', '`xkfl`', 200, -1, FALSE, '`xkfl`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['xkfl'] = &$this->xkfl;

		// bs11
		$this->bs11 = new cField('kcdmb', 'kcdmb', 'x_bs11', 'bs11', '`bs11`', '`bs11`', 200, -1, FALSE, '`bs11`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['bs11'] = &$this->bs11;

		// kcsjxs
		$this->kcsjxs = new cField('kcdmb', 'kcdmb', 'x_kcsjxs', 'kcsjxs', '`kcsjxs`', '`kcsjxs`', 200, -1, FALSE, '`kcsjxs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kcsjxs'] = &$this->kcsjxs;

		// xtkxs
		$this->xtkxs = new cField('kcdmb', 'kcdmb', 'x_xtkxs', 'xtkxs', '`xtkxs`', '`xtkxs`', 200, -1, FALSE, '`xtkxs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['xtkxs'] = &$this->xtkxs;

		// knsjxs
		$this->knsjxs = new cField('kcdmb', 'kcdmb', 'x_knsjxs', 'knsjxs', '`knsjxs`', '`knsjxs`', 200, -1, FALSE, '`knsjxs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['knsjxs'] = &$this->knsjxs;

		// kwsjxs
		$this->kwsjxs = new cField('kcdmb', 'kcdmb', 'x_kwsjxs', 'kwsjxs', '`kwsjxs`', '`kwsjxs`', 200, -1, FALSE, '`kwsjxs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kwsjxs'] = &$this->kwsjxs;

		// ytxs
		$this->ytxs = new cField('kcdmb', 'kcdmb', 'x_ytxs', 'ytxs', '`ytxs`', '`ytxs`', 131, -1, FALSE, '`ytxs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ytxs->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['ytxs'] = &$this->ytxs;

		// scjssjxs
		$this->scjssjxs = new cField('kcdmb', 'kcdmb', 'x_scjssjxs', 'scjssjxs', '`scjssjxs`', '`scjssjxs`', 131, -1, FALSE, '`scjssjxs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->scjssjxs->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['scjssjxs'] = &$this->scjssjxs;

		// sxxs
		$this->sxxs = new cField('kcdmb', 'kcdmb', 'x_sxxs', 'sxxs', '`sxxs`', '`sxxs`', 131, -1, FALSE, '`sxxs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->sxxs->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['sxxs'] = &$this->sxxs;

		// ksxs
		$this->ksxs = new cField('kcdmb', 'kcdmb', 'x_ksxs', 'ksxs', '`ksxs`', '`ksxs`', 131, -1, FALSE, '`ksxs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ksxs->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['ksxs'] = &$this->ksxs;

		// bsxs
		$this->bsxs = new cField('kcdmb', 'kcdmb', 'x_bsxs', 'bsxs', '`bsxs`', '`bsxs`', 131, -1, FALSE, '`bsxs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->bsxs->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['bsxs'] = &$this->bsxs;

		// shdcxs
		$this->shdcxs = new cField('kcdmb', 'kcdmb', 'x_shdcxs', 'shdcxs', '`shdcxs`', '`shdcxs`', 131, -1, FALSE, '`shdcxs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->shdcxs->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['shdcxs'] = &$this->shdcxs;

		// jys
		$this->jys = new cField('kcdmb', 'kcdmb', 'x_jys', 'jys', '`jys`', '`jys`', 200, -1, FALSE, '`jys`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['jys'] = &$this->jys;

		// sftykw
		$this->sftykw = new cField('kcdmb', 'kcdmb', 'x_sftykw', 'sftykw', '`sftykw`', '`sftykw`', 200, -1, FALSE, '`sftykw`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['sftykw'] = &$this->sftykw;

		// kcjc
		$this->kcjc = new cField('kcdmb', 'kcdmb', 'x_kcjc', 'kcjc', '`kcjc`', '`kcjc`', 200, -1, FALSE, '`kcjc`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kcjc'] = &$this->kcjc;

		// kwxs
		$this->kwxs = new cField('kcdmb', 'kcdmb', 'x_kwxs', 'kwxs', '`kwxs`', '`kwxs`', 200, -1, FALSE, '`kwxs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kwxs'] = &$this->kwxs;

		// xkdx
		$this->xkdx = new cField('kcdmb', 'kcdmb', 'x_xkdx', 'xkdx', '`xkdx`', '`xkdx`', 200, -1, FALSE, '`xkdx`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['xkdx'] = &$this->xkdx;

		// jsxm
		$this->jsxm = new cField('kcdmb', 'kcdmb', 'x_jsxm', 'jsxm', '`jsxm`', '`jsxm`', 200, -1, FALSE, '`jsxm`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['jsxm'] = &$this->jsxm;

		// bs3
		$this->bs3 = new cField('kcdmb', 'kcdmb', 'x_bs3', 'bs3', '`bs3`', '`bs3`', 200, -1, FALSE, '`bs3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['bs3'] = &$this->bs3;

		// xfjs
		$this->xfjs = new cField('kcdmb', 'kcdmb', 'x_xfjs', 'xfjs', '`xfjs`', '`xfjs`', 200, -1, FALSE, '`xfjs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['xfjs'] = &$this->xfjs;

		// zhxsjs
		$this->zhxsjs = new cField('kcdmb', 'kcdmb', 'x_zhxsjs', 'zhxsjs', '`zhxsjs`', '`zhxsjs`', 200, -1, FALSE, '`zhxsjs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['zhxsjs'] = &$this->zhxsjs;

		// jkxsjs
		$this->jkxsjs = new cField('kcdmb', 'kcdmb', 'x_jkxsjs', 'jkxsjs', '`jkxsjs`', '`jkxsjs`', 200, -1, FALSE, '`jkxsjs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['jkxsjs'] = &$this->jkxsjs;

		// syxsjs
		$this->syxsjs = new cField('kcdmb', 'kcdmb', 'x_syxsjs', 'syxsjs', '`syxsjs`', '`syxsjs`', 200, -1, FALSE, '`syxsjs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['syxsjs'] = &$this->syxsjs;

		// sjxsjs
		$this->sjxsjs = new cField('kcdmb', 'kcdmb', 'x_sjxsjs', 'sjxsjs', '`sjxsjs`', '`sjxsjs`', 200, -1, FALSE, '`sjxsjs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['sjxsjs'] = &$this->sjxsjs;

		// sfxssy
		$this->sfxssy = new cField('kcdmb', 'kcdmb', 'x_sfxssy', 'sfxssy', '`sfxssy`', '`sfxssy`', 200, -1, FALSE, '`sfxssy`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['sfxssy'] = &$this->sfxssy;

		// kcjsztdw
		$this->kcjsztdw = new cField('kcdmb', 'kcdmb', 'x_kcjsztdw', 'kcjsztdw', '`kcjsztdw`', '`kcjsztdw`', 200, -1, FALSE, '`kcjsztdw`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kcjsztdw'] = &$this->kcjsztdw;

		// bs4
		$this->bs4 = new cField('kcdmb', 'kcdmb', 'x_bs4', 'bs4', '`bs4`', '`bs4`', 200, -1, FALSE, '`bs4`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['bs4'] = &$this->bs4;

		// syzy
		$this->syzy = new cField('kcdmb', 'kcdmb', 'x_syzy', 'syzy', '`syzy`', '`syzy`', 200, -1, FALSE, '`syzy`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['syzy'] = &$this->syzy;

		// lrsj
		$this->lrsj = new cField('kcdmb', 'kcdmb', 'x_lrsj', 'lrsj', '`lrsj`', '`lrsj`', 200, -1, FALSE, '`lrsj`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['lrsj'] = &$this->lrsj;

		// kcmcpy
		$this->kcmcpy = new cField('kcdmb', 'kcdmb', 'x_kcmcpy', 'kcmcpy', '`kcmcpy`', '`kcmcpy`', 200, -1, FALSE, '`kcmcpy`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kcmcpy'] = &$this->kcmcpy;

		// xqdm
		$this->xqdm = new cField('kcdmb', 'kcdmb', 'x_xqdm', 'xqdm', '`xqdm`', '`xqdm`', 200, -1, FALSE, '`xqdm`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['xqdm'] = &$this->xqdm;

		// kcqmc
		$this->kcqmc = new cField('kcdmb', 'kcdmb', 'x_kcqmc', 'kcqmc', '`kcqmc`', '`kcqmc`', 200, -1, FALSE, '`kcqmc`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kcqmc'] = &$this->kcqmc;

		// ksxsmc
		$this->ksxsmc = new cField('kcdmb', 'kcdmb', 'x_ksxsmc', 'ksxsmc', '`ksxsmc`', '`ksxsmc`', 200, -1, FALSE, '`ksxsmc`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ksxsmc'] = &$this->ksxsmc;

		// sfbysjkc
		$this->sfbysjkc = new cField('kcdmb', 'kcdmb', 'x_sfbysjkc', 'sfbysjkc', '`sfbysjkc`', '`sfbysjkc`', 200, -1, FALSE, '`sfbysjkc`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['sfbysjkc'] = &$this->sfbysjkc;

		// bs5
		$this->bs5 = new cField('kcdmb', 'kcdmb', 'x_bs5', 'bs5', '`bs5`', '`bs5`', 200, -1, FALSE, '`bs5`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['bs5'] = &$this->bs5;

		// nj
		$this->nj = new cField('kcdmb', 'kcdmb', 'x_nj', 'nj', '`nj`', '`nj`', 200, -1, FALSE, '`nj`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nj'] = &$this->nj;

		// cjlrr
		$this->cjlrr = new cField('kcdmb', 'kcdmb', 'x_cjlrr', 'cjlrr', '`cjlrr`', '`cjlrr`', 200, -1, FALSE, '`cjlrr`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['cjlrr'] = &$this->cjlrr;

		// sftsbx
		$this->sftsbx = new cField('kcdmb', 'kcdmb', 'x_sftsbx', 'sftsbx', '`sftsbx`', '`sftsbx`', 200, -1, FALSE, '`sftsbx`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['sftsbx'] = &$this->sftsbx;

		// dxdgdz
		$this->dxdgdz = new cField('kcdmb', 'kcdmb', 'x_dxdgdz', 'dxdgdz', '`dxdgdz`', '`dxdgdz`', 200, -1, FALSE, '`dxdgdz`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['dxdgdz'] = &$this->dxdgdz;

		// kcfl
		$this->kcfl = new cField('kcdmb', 'kcdmb', 'x_kcfl', 'kcfl', '`kcfl`', '`kcfl`', 200, -1, FALSE, '`kcfl`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kcfl'] = &$this->kcfl;

		// kcywjj
		$this->kcywjj = new cField('kcdmb', 'kcdmb', 'x_kcywjj', 'kcywjj', '`kcywjj`', '`kcywjj`', 201, -1, FALSE, '`kcywjj`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kcywjj'] = &$this->kcywjj;

		// sjlrzgh
		$this->sjlrzgh = new cField('kcdmb', 'kcdmb', 'x_sjlrzgh', 'sjlrzgh', '`sjlrzgh`', '`sjlrzgh`', 200, -1, FALSE, '`sjlrzgh`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['sjlrzgh'] = &$this->sjlrzgh;

		// kcjjdz
		$this->kcjjdz = new cField('kcdmb', 'kcdmb', 'x_kcjjdz', 'kcjjdz', '`kcjjdz`', '`kcjjdz`', 200, -1, FALSE, '`kcjjdz`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['kcjjdz'] = &$this->kcjjdz;

		// yqdm
		$this->yqdm = new cField('kcdmb', 'kcdmb', 'x_yqdm', 'yqdm', '`yqdm`', '`yqdm`', 200, -1, FALSE, '`yqdm`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['yqdm'] = &$this->yqdm;

		// yqmc
		$this->yqmc = new cField('kcdmb', 'kcdmb', 'x_yqmc', 'yqmc', '`yqmc`', '`yqmc`', 200, -1, FALSE, '`yqmc`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['yqmc'] = &$this->yqmc;

		// bsyz
		$this->bsyz = new cField('kcdmb', 'kcdmb', 'x_bsyz', 'bsyz', '`bsyz`', '`bsyz`', 200, -1, FALSE, '`bsyz`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['bsyz'] = &$this->bsyz;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`kcdmb`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlWhere() { // Where
		$sWhere = "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "";
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		return TRUE;
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(),
			$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->SqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . substr($sSql, 13);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$origFilter = $this->CurrentFilter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`kcdmb`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, strlen($names)-1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, strlen($values)-1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// UPDATE statement
	function UpdateSQL(&$rs) {
		global $conn;
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, strlen($sql)-1);
		if ($this->CurrentFilter <> "")	$sql .= " WHERE " . $this->CurrentFilter;
		return $sql;
	}

	// DELETE statement
	function DeleteSQL(&$rs) {
		$SQL = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (substr($SQL, -5) == " AND ") $SQL = substr($SQL, 0, strlen($SQL)-5);
		if ($this->CurrentFilter <> "")	$SQL .= " AND " . $this->CurrentFilter;
		return $SQL;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "kcdmblist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "kcdmblist.php";
	}

	// View URL
	function GetViewUrl() {
		return $this->KeyUrl("kcdmbview.php", $this->UrlParm());
	}

	// Add URL
	function GetAddUrl() {
		return "kcdmbadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("kcdmbedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("kcdmbadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("kcdmbdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {

			//return $arKeys; // do not return yet, so the values will also be checked by the following code
		}

		// check keys
		$ar = array();
		foreach ($arKeys as $key) {
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
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

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// kcjj
		$this->kcjj->ViewValue = $this->kcjj->CurrentValue;
		$this->kcjj->ViewCustomAttributes = "";

		// jxdg
		$this->jxdg->ViewValue = $this->jxdg->CurrentValue;
		$this->jxdg->ViewCustomAttributes = "";

		// bs1
		$this->bs1->ViewValue = $this->bs1->CurrentValue;
		$this->bs1->ViewCustomAttributes = "";

		// bs2
		$this->bs2->ViewValue = $this->bs2->CurrentValue;
		$this->bs2->ViewCustomAttributes = "";

		// qzxs
		$this->qzxs->ViewValue = $this->qzxs->CurrentValue;
		$this->qzxs->ViewCustomAttributes = "";

		// ksnrjbz
		$this->ksnrjbz->ViewValue = $this->ksnrjbz->CurrentValue;
		$this->ksnrjbz->ViewCustomAttributes = "";

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

		// kczyzyjmd
		$this->kczyzyjmd->ViewValue = $this->kczyzyjmd->CurrentValue;
		$this->kczyzyjmd->ViewCustomAttributes = "";

		// zycks
		$this->zycks->ViewValue = $this->zycks->CurrentValue;
		$this->zycks->ViewCustomAttributes = "";

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

		// temp1
		$this->temp1->ViewValue = $this->temp1->CurrentValue;
		$this->temp1->ViewCustomAttributes = "";

		// temp2
		$this->temp2->ViewValue = $this->temp2->CurrentValue;
		$this->temp2->ViewCustomAttributes = "";

		// temp3
		$this->temp3->ViewValue = $this->temp3->CurrentValue;
		$this->temp3->ViewCustomAttributes = "";

		// temp4
		$this->temp4->ViewValue = $this->temp4->CurrentValue;
		$this->temp4->ViewCustomAttributes = "";

		// temp5
		$this->temp5->ViewValue = $this->temp5->CurrentValue;
		$this->temp5->ViewCustomAttributes = "";

		// temp6
		$this->temp6->ViewValue = $this->temp6->CurrentValue;
		$this->temp6->ViewCustomAttributes = "";

		// temp7
		$this->temp7->ViewValue = $this->temp7->CurrentValue;
		$this->temp7->ViewCustomAttributes = "";

		// temp8
		$this->temp8->ViewValue = $this->temp8->CurrentValue;
		$this->temp8->ViewCustomAttributes = "";

		// temp9
		$this->temp9->ViewValue = $this->temp9->CurrentValue;
		$this->temp9->ViewCustomAttributes = "";

		// temp10
		$this->temp10->ViewValue = $this->temp10->CurrentValue;
		$this->temp10->ViewCustomAttributes = "";

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

		// kcywjj
		$this->kcywjj->ViewValue = $this->kcywjj->CurrentValue;
		$this->kcywjj->ViewCustomAttributes = "";

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

		// kcjj
		$this->kcjj->LinkCustomAttributes = "";
		$this->kcjj->HrefValue = "";
		$this->kcjj->TooltipValue = "";

		// jxdg
		$this->jxdg->LinkCustomAttributes = "";
		$this->jxdg->HrefValue = "";
		$this->jxdg->TooltipValue = "";

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

		// ksnrjbz
		$this->ksnrjbz->LinkCustomAttributes = "";
		$this->ksnrjbz->HrefValue = "";
		$this->ksnrjbz->TooltipValue = "";

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

		// kczyzyjmd
		$this->kczyzyjmd->LinkCustomAttributes = "";
		$this->kczyzyjmd->HrefValue = "";
		$this->kczyzyjmd->TooltipValue = "";

		// zycks
		$this->zycks->LinkCustomAttributes = "";
		$this->zycks->HrefValue = "";
		$this->zycks->TooltipValue = "";

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

		// temp1
		$this->temp1->LinkCustomAttributes = "";
		$this->temp1->HrefValue = "";
		$this->temp1->TooltipValue = "";

		// temp2
		$this->temp2->LinkCustomAttributes = "";
		$this->temp2->HrefValue = "";
		$this->temp2->TooltipValue = "";

		// temp3
		$this->temp3->LinkCustomAttributes = "";
		$this->temp3->HrefValue = "";
		$this->temp3->TooltipValue = "";

		// temp4
		$this->temp4->LinkCustomAttributes = "";
		$this->temp4->HrefValue = "";
		$this->temp4->TooltipValue = "";

		// temp5
		$this->temp5->LinkCustomAttributes = "";
		$this->temp5->HrefValue = "";
		$this->temp5->TooltipValue = "";

		// temp6
		$this->temp6->LinkCustomAttributes = "";
		$this->temp6->HrefValue = "";
		$this->temp6->TooltipValue = "";

		// temp7
		$this->temp7->LinkCustomAttributes = "";
		$this->temp7->HrefValue = "";
		$this->temp7->TooltipValue = "";

		// temp8
		$this->temp8->LinkCustomAttributes = "";
		$this->temp8->HrefValue = "";
		$this->temp8->TooltipValue = "";

		// temp9
		$this->temp9->LinkCustomAttributes = "";
		$this->temp9->HrefValue = "";
		$this->temp9->TooltipValue = "";

		// temp10
		$this->temp10->LinkCustomAttributes = "";
		$this->temp10->HrefValue = "";
		$this->temp10->TooltipValue = "";

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

		// kcywjj
		$this->kcywjj->LinkCustomAttributes = "";
		$this->kcywjj->HrefValue = "";
		$this->kcywjj->TooltipValue = "";

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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
	}

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;

		// Write header
		$Doc->ExportTableHeader();
		if ($Doc->Horizontal) { // Horizontal format, write header
			$Doc->BeginExportRow();
			if ($ExportPageType == "view") {
				$Doc->ExportCaption($this->kcdm);
				$Doc->ExportCaption($this->kczwmc);
				$Doc->ExportCaption($this->kcywmc);
				$Doc->ExportCaption($this->xf);
				$Doc->ExportCaption($this->zxs);
				$Doc->ExportCaption($this->zs);
				$Doc->ExportCaption($this->yxyq);
				$Doc->ExportCaption($this->kcjj);
				$Doc->ExportCaption($this->jxdg);
				$Doc->ExportCaption($this->bs1);
				$Doc->ExportCaption($this->bs2);
				$Doc->ExportCaption($this->qzxs);
				$Doc->ExportCaption($this->ksnrjbz);
				$Doc->ExportCaption($this->sfwyb);
				$Doc->ExportCaption($this->zdkkrs);
				$Doc->ExportCaption($this->kclb);
				$Doc->ExportCaption($this->kkbmdm);
				$Doc->ExportCaption($this->zhxs);
				$Doc->ExportCaption($this->yxj);
				$Doc->ExportCaption($this->pksj);
				$Doc->ExportCaption($this->pkyq);
				$Doc->ExportCaption($this->xs);
				$Doc->ExportCaption($this->kczyzyjmd);
				$Doc->ExportCaption($this->zycks);
				$Doc->ExportCaption($this->kthkcdm);
				$Doc->ExportCaption($this->xlcc);
				$Doc->ExportCaption($this->gzlxs);
				$Doc->ExportCaption($this->khfs);
				$Doc->ExportCaption($this->kcys);
				$Doc->ExportCaption($this->tkbj);
				$Doc->ExportCaption($this->llxs);
				$Doc->ExportCaption($this->syxs);
				$Doc->ExportCaption($this->sjxs);
				$Doc->ExportCaption($this->bz);
				$Doc->ExportCaption($this->kcxz);
				$Doc->ExportCaption($this->zcfy);
				$Doc->ExportCaption($this->cxfy);
				$Doc->ExportCaption($this->fxfy);
				$Doc->ExportCaption($this->syxmsyq);
				$Doc->ExportCaption($this->skfsmc);
				$Doc->ExportCaption($this->axbxrw);
				$Doc->ExportCaption($this->typk);
				$Doc->ExportCaption($this->sykkbmdm);
				$Doc->ExportCaption($this->bsfbj);
				$Doc->ExportCaption($this->temp1);
				$Doc->ExportCaption($this->temp2);
				$Doc->ExportCaption($this->temp3);
				$Doc->ExportCaption($this->temp4);
				$Doc->ExportCaption($this->temp5);
				$Doc->ExportCaption($this->temp6);
				$Doc->ExportCaption($this->temp7);
				$Doc->ExportCaption($this->temp8);
				$Doc->ExportCaption($this->temp9);
				$Doc->ExportCaption($this->temp10);
				$Doc->ExportCaption($this->syxfyq);
				$Doc->ExportCaption($this->kcgs);
				$Doc->ExportCaption($this->kkxdm);
				$Doc->ExportCaption($this->xkfl);
				$Doc->ExportCaption($this->bs11);
				$Doc->ExportCaption($this->kcsjxs);
				$Doc->ExportCaption($this->xtkxs);
				$Doc->ExportCaption($this->knsjxs);
				$Doc->ExportCaption($this->kwsjxs);
				$Doc->ExportCaption($this->ytxs);
				$Doc->ExportCaption($this->scjssjxs);
				$Doc->ExportCaption($this->sxxs);
				$Doc->ExportCaption($this->ksxs);
				$Doc->ExportCaption($this->bsxs);
				$Doc->ExportCaption($this->shdcxs);
				$Doc->ExportCaption($this->jys);
				$Doc->ExportCaption($this->sftykw);
				$Doc->ExportCaption($this->kcjc);
				$Doc->ExportCaption($this->kwxs);
				$Doc->ExportCaption($this->xkdx);
				$Doc->ExportCaption($this->jsxm);
				$Doc->ExportCaption($this->bs3);
				$Doc->ExportCaption($this->xfjs);
				$Doc->ExportCaption($this->zhxsjs);
				$Doc->ExportCaption($this->jkxsjs);
				$Doc->ExportCaption($this->syxsjs);
				$Doc->ExportCaption($this->sjxsjs);
				$Doc->ExportCaption($this->sfxssy);
				$Doc->ExportCaption($this->kcjsztdw);
				$Doc->ExportCaption($this->bs4);
				$Doc->ExportCaption($this->syzy);
				$Doc->ExportCaption($this->lrsj);
				$Doc->ExportCaption($this->kcmcpy);
				$Doc->ExportCaption($this->xqdm);
				$Doc->ExportCaption($this->kcqmc);
				$Doc->ExportCaption($this->ksxsmc);
				$Doc->ExportCaption($this->sfbysjkc);
				$Doc->ExportCaption($this->bs5);
				$Doc->ExportCaption($this->nj);
				$Doc->ExportCaption($this->cjlrr);
				$Doc->ExportCaption($this->sftsbx);
				$Doc->ExportCaption($this->dxdgdz);
				$Doc->ExportCaption($this->kcfl);
				$Doc->ExportCaption($this->kcywjj);
				$Doc->ExportCaption($this->sjlrzgh);
				$Doc->ExportCaption($this->kcjjdz);
				$Doc->ExportCaption($this->yqdm);
				$Doc->ExportCaption($this->yqmc);
				$Doc->ExportCaption($this->bsyz);
			} else {
				$Doc->ExportCaption($this->kcdm);
				$Doc->ExportCaption($this->kczwmc);
				$Doc->ExportCaption($this->kcywmc);
				$Doc->ExportCaption($this->xf);
				$Doc->ExportCaption($this->zxs);
				$Doc->ExportCaption($this->zs);
				$Doc->ExportCaption($this->yxyq);
				$Doc->ExportCaption($this->bs1);
				$Doc->ExportCaption($this->bs2);
				$Doc->ExportCaption($this->qzxs);
				$Doc->ExportCaption($this->sfwyb);
				$Doc->ExportCaption($this->zdkkrs);
				$Doc->ExportCaption($this->kclb);
				$Doc->ExportCaption($this->kkbmdm);
				$Doc->ExportCaption($this->zhxs);
				$Doc->ExportCaption($this->yxj);
				$Doc->ExportCaption($this->pksj);
				$Doc->ExportCaption($this->pkyq);
				$Doc->ExportCaption($this->xs);
				$Doc->ExportCaption($this->kthkcdm);
				$Doc->ExportCaption($this->xlcc);
				$Doc->ExportCaption($this->gzlxs);
				$Doc->ExportCaption($this->khfs);
				$Doc->ExportCaption($this->kcys);
				$Doc->ExportCaption($this->tkbj);
				$Doc->ExportCaption($this->llxs);
				$Doc->ExportCaption($this->syxs);
				$Doc->ExportCaption($this->sjxs);
				$Doc->ExportCaption($this->bz);
				$Doc->ExportCaption($this->kcxz);
				$Doc->ExportCaption($this->zcfy);
				$Doc->ExportCaption($this->cxfy);
				$Doc->ExportCaption($this->fxfy);
				$Doc->ExportCaption($this->syxmsyq);
				$Doc->ExportCaption($this->skfsmc);
				$Doc->ExportCaption($this->axbxrw);
				$Doc->ExportCaption($this->typk);
				$Doc->ExportCaption($this->sykkbmdm);
				$Doc->ExportCaption($this->bsfbj);
				$Doc->ExportCaption($this->syxfyq);
				$Doc->ExportCaption($this->kcgs);
				$Doc->ExportCaption($this->kkxdm);
				$Doc->ExportCaption($this->xkfl);
				$Doc->ExportCaption($this->bs11);
				$Doc->ExportCaption($this->kcsjxs);
				$Doc->ExportCaption($this->xtkxs);
				$Doc->ExportCaption($this->knsjxs);
				$Doc->ExportCaption($this->kwsjxs);
				$Doc->ExportCaption($this->ytxs);
				$Doc->ExportCaption($this->scjssjxs);
				$Doc->ExportCaption($this->sxxs);
				$Doc->ExportCaption($this->ksxs);
				$Doc->ExportCaption($this->bsxs);
				$Doc->ExportCaption($this->shdcxs);
				$Doc->ExportCaption($this->jys);
				$Doc->ExportCaption($this->sftykw);
				$Doc->ExportCaption($this->kcjc);
				$Doc->ExportCaption($this->kwxs);
				$Doc->ExportCaption($this->xkdx);
				$Doc->ExportCaption($this->jsxm);
				$Doc->ExportCaption($this->bs3);
				$Doc->ExportCaption($this->xfjs);
				$Doc->ExportCaption($this->zhxsjs);
				$Doc->ExportCaption($this->jkxsjs);
				$Doc->ExportCaption($this->syxsjs);
				$Doc->ExportCaption($this->sjxsjs);
				$Doc->ExportCaption($this->sfxssy);
				$Doc->ExportCaption($this->kcjsztdw);
				$Doc->ExportCaption($this->bs4);
				$Doc->ExportCaption($this->syzy);
				$Doc->ExportCaption($this->lrsj);
				$Doc->ExportCaption($this->kcmcpy);
				$Doc->ExportCaption($this->xqdm);
				$Doc->ExportCaption($this->kcqmc);
				$Doc->ExportCaption($this->ksxsmc);
				$Doc->ExportCaption($this->sfbysjkc);
				$Doc->ExportCaption($this->bs5);
				$Doc->ExportCaption($this->nj);
				$Doc->ExportCaption($this->cjlrr);
				$Doc->ExportCaption($this->sftsbx);
				$Doc->ExportCaption($this->dxdgdz);
				$Doc->ExportCaption($this->kcfl);
				$Doc->ExportCaption($this->sjlrzgh);
				$Doc->ExportCaption($this->kcjjdz);
				$Doc->ExportCaption($this->yqdm);
				$Doc->ExportCaption($this->yqmc);
				$Doc->ExportCaption($this->bsyz);
			}
			$Doc->EndExportRow();
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
				if ($ExportPageType == "view") {
					$Doc->ExportField($this->kcdm);
					$Doc->ExportField($this->kczwmc);
					$Doc->ExportField($this->kcywmc);
					$Doc->ExportField($this->xf);
					$Doc->ExportField($this->zxs);
					$Doc->ExportField($this->zs);
					$Doc->ExportField($this->yxyq);
					$Doc->ExportField($this->kcjj);
					$Doc->ExportField($this->jxdg);
					$Doc->ExportField($this->bs1);
					$Doc->ExportField($this->bs2);
					$Doc->ExportField($this->qzxs);
					$Doc->ExportField($this->ksnrjbz);
					$Doc->ExportField($this->sfwyb);
					$Doc->ExportField($this->zdkkrs);
					$Doc->ExportField($this->kclb);
					$Doc->ExportField($this->kkbmdm);
					$Doc->ExportField($this->zhxs);
					$Doc->ExportField($this->yxj);
					$Doc->ExportField($this->pksj);
					$Doc->ExportField($this->pkyq);
					$Doc->ExportField($this->xs);
					$Doc->ExportField($this->kczyzyjmd);
					$Doc->ExportField($this->zycks);
					$Doc->ExportField($this->kthkcdm);
					$Doc->ExportField($this->xlcc);
					$Doc->ExportField($this->gzlxs);
					$Doc->ExportField($this->khfs);
					$Doc->ExportField($this->kcys);
					$Doc->ExportField($this->tkbj);
					$Doc->ExportField($this->llxs);
					$Doc->ExportField($this->syxs);
					$Doc->ExportField($this->sjxs);
					$Doc->ExportField($this->bz);
					$Doc->ExportField($this->kcxz);
					$Doc->ExportField($this->zcfy);
					$Doc->ExportField($this->cxfy);
					$Doc->ExportField($this->fxfy);
					$Doc->ExportField($this->syxmsyq);
					$Doc->ExportField($this->skfsmc);
					$Doc->ExportField($this->axbxrw);
					$Doc->ExportField($this->typk);
					$Doc->ExportField($this->sykkbmdm);
					$Doc->ExportField($this->bsfbj);
					$Doc->ExportField($this->temp1);
					$Doc->ExportField($this->temp2);
					$Doc->ExportField($this->temp3);
					$Doc->ExportField($this->temp4);
					$Doc->ExportField($this->temp5);
					$Doc->ExportField($this->temp6);
					$Doc->ExportField($this->temp7);
					$Doc->ExportField($this->temp8);
					$Doc->ExportField($this->temp9);
					$Doc->ExportField($this->temp10);
					$Doc->ExportField($this->syxfyq);
					$Doc->ExportField($this->kcgs);
					$Doc->ExportField($this->kkxdm);
					$Doc->ExportField($this->xkfl);
					$Doc->ExportField($this->bs11);
					$Doc->ExportField($this->kcsjxs);
					$Doc->ExportField($this->xtkxs);
					$Doc->ExportField($this->knsjxs);
					$Doc->ExportField($this->kwsjxs);
					$Doc->ExportField($this->ytxs);
					$Doc->ExportField($this->scjssjxs);
					$Doc->ExportField($this->sxxs);
					$Doc->ExportField($this->ksxs);
					$Doc->ExportField($this->bsxs);
					$Doc->ExportField($this->shdcxs);
					$Doc->ExportField($this->jys);
					$Doc->ExportField($this->sftykw);
					$Doc->ExportField($this->kcjc);
					$Doc->ExportField($this->kwxs);
					$Doc->ExportField($this->xkdx);
					$Doc->ExportField($this->jsxm);
					$Doc->ExportField($this->bs3);
					$Doc->ExportField($this->xfjs);
					$Doc->ExportField($this->zhxsjs);
					$Doc->ExportField($this->jkxsjs);
					$Doc->ExportField($this->syxsjs);
					$Doc->ExportField($this->sjxsjs);
					$Doc->ExportField($this->sfxssy);
					$Doc->ExportField($this->kcjsztdw);
					$Doc->ExportField($this->bs4);
					$Doc->ExportField($this->syzy);
					$Doc->ExportField($this->lrsj);
					$Doc->ExportField($this->kcmcpy);
					$Doc->ExportField($this->xqdm);
					$Doc->ExportField($this->kcqmc);
					$Doc->ExportField($this->ksxsmc);
					$Doc->ExportField($this->sfbysjkc);
					$Doc->ExportField($this->bs5);
					$Doc->ExportField($this->nj);
					$Doc->ExportField($this->cjlrr);
					$Doc->ExportField($this->sftsbx);
					$Doc->ExportField($this->dxdgdz);
					$Doc->ExportField($this->kcfl);
					$Doc->ExportField($this->kcywjj);
					$Doc->ExportField($this->sjlrzgh);
					$Doc->ExportField($this->kcjjdz);
					$Doc->ExportField($this->yqdm);
					$Doc->ExportField($this->yqmc);
					$Doc->ExportField($this->bsyz);
				} else {
					$Doc->ExportField($this->kcdm);
					$Doc->ExportField($this->kczwmc);
					$Doc->ExportField($this->kcywmc);
					$Doc->ExportField($this->xf);
					$Doc->ExportField($this->zxs);
					$Doc->ExportField($this->zs);
					$Doc->ExportField($this->yxyq);
					$Doc->ExportField($this->bs1);
					$Doc->ExportField($this->bs2);
					$Doc->ExportField($this->qzxs);
					$Doc->ExportField($this->sfwyb);
					$Doc->ExportField($this->zdkkrs);
					$Doc->ExportField($this->kclb);
					$Doc->ExportField($this->kkbmdm);
					$Doc->ExportField($this->zhxs);
					$Doc->ExportField($this->yxj);
					$Doc->ExportField($this->pksj);
					$Doc->ExportField($this->pkyq);
					$Doc->ExportField($this->xs);
					$Doc->ExportField($this->kthkcdm);
					$Doc->ExportField($this->xlcc);
					$Doc->ExportField($this->gzlxs);
					$Doc->ExportField($this->khfs);
					$Doc->ExportField($this->kcys);
					$Doc->ExportField($this->tkbj);
					$Doc->ExportField($this->llxs);
					$Doc->ExportField($this->syxs);
					$Doc->ExportField($this->sjxs);
					$Doc->ExportField($this->bz);
					$Doc->ExportField($this->kcxz);
					$Doc->ExportField($this->zcfy);
					$Doc->ExportField($this->cxfy);
					$Doc->ExportField($this->fxfy);
					$Doc->ExportField($this->syxmsyq);
					$Doc->ExportField($this->skfsmc);
					$Doc->ExportField($this->axbxrw);
					$Doc->ExportField($this->typk);
					$Doc->ExportField($this->sykkbmdm);
					$Doc->ExportField($this->bsfbj);
					$Doc->ExportField($this->syxfyq);
					$Doc->ExportField($this->kcgs);
					$Doc->ExportField($this->kkxdm);
					$Doc->ExportField($this->xkfl);
					$Doc->ExportField($this->bs11);
					$Doc->ExportField($this->kcsjxs);
					$Doc->ExportField($this->xtkxs);
					$Doc->ExportField($this->knsjxs);
					$Doc->ExportField($this->kwsjxs);
					$Doc->ExportField($this->ytxs);
					$Doc->ExportField($this->scjssjxs);
					$Doc->ExportField($this->sxxs);
					$Doc->ExportField($this->ksxs);
					$Doc->ExportField($this->bsxs);
					$Doc->ExportField($this->shdcxs);
					$Doc->ExportField($this->jys);
					$Doc->ExportField($this->sftykw);
					$Doc->ExportField($this->kcjc);
					$Doc->ExportField($this->kwxs);
					$Doc->ExportField($this->xkdx);
					$Doc->ExportField($this->jsxm);
					$Doc->ExportField($this->bs3);
					$Doc->ExportField($this->xfjs);
					$Doc->ExportField($this->zhxsjs);
					$Doc->ExportField($this->jkxsjs);
					$Doc->ExportField($this->syxsjs);
					$Doc->ExportField($this->sjxsjs);
					$Doc->ExportField($this->sfxssy);
					$Doc->ExportField($this->kcjsztdw);
					$Doc->ExportField($this->bs4);
					$Doc->ExportField($this->syzy);
					$Doc->ExportField($this->lrsj);
					$Doc->ExportField($this->kcmcpy);
					$Doc->ExportField($this->xqdm);
					$Doc->ExportField($this->kcqmc);
					$Doc->ExportField($this->ksxsmc);
					$Doc->ExportField($this->sfbysjkc);
					$Doc->ExportField($this->bs5);
					$Doc->ExportField($this->nj);
					$Doc->ExportField($this->cjlrr);
					$Doc->ExportField($this->sftsbx);
					$Doc->ExportField($this->dxdgdz);
					$Doc->ExportField($this->kcfl);
					$Doc->ExportField($this->sjlrzgh);
					$Doc->ExportField($this->kcjjdz);
					$Doc->ExportField($this->yqdm);
					$Doc->ExportField($this->yqmc);
					$Doc->ExportField($this->bsyz);
				}
				$Doc->EndExportRow();
			}
			$Recordset->MoveNext();
		}
		$Doc->ExportTableFooter();
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
