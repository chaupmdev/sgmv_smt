/*--------------------------------------------------------------------------*
 *  
 *  コースプランと地域の表示制御
 *  
 *  H.Tsuji
 *  
 *  2010 H.Tsuji
 *  
 *--------------------------------------------------------------------------*/

/***************************************************************************
 * input.phpの読み込み時に実行され、checkCoucePlanを実行します
 *
 ***************************************************************************/
function inputOnload(mode) {

	checkCoucePlan(mode);
	getFromAreaCd();

}

/***************************************************************************
 * input.phpの読み込み時に実行され、checkCoucePlanを実行します
 *
 ***************************************************************************/
function getFromAreaCd() {

	// 出発地域の選択値を取得
	fromArea1 = document.getElementById("fromarea1").value;
	fromArea2 = document.getElementById("fromarea2").value;
	fromArea3 = document.getElementById("fromarea3").value;
	
	if (fromArea1 != "") {
		document.forms[0].formareacd.value = fromArea1;
	} else if (fromArea2 != "") {
		document.forms[0].formareacd.value = fromArea2;
	} else {
		document.forms[0].formareacd.value = fromArea3;
	}

}

/***************************************************************************
 * onChange時に実行され、コースプランと地域の表示制御をします
 *
 ***************************************************************************/
function checkCoucePlan(mode) {

	// コースプランの選択値を取得
	courcePlan = document.forms[0].course_plan_cd_sel.selectedIndex;

	if (courcePlan == "1") {
		// 単身カーゴプラン
		// 沖縄なしプルダウンに切り替える
		document.getElementById("fromarea1").style.display = "none";
		document.getElementById("fromarea2").style.display = "";
		document.getElementById("fromarea3").style.display = "none";
		document.getElementById("fromarea1").selectedIndex = "";
		document.getElementById("fromarea3").selectedIndex = "";
		document.forms[0].formareacd.value = "";
  	} else if (courcePlan == "2") {
		// 単身エアカーゴ
		// 北海道/東京23区/大阪府/福岡県プルダウンに切り替える
		document.getElementById("fromarea1").style.display = "none";
		document.getElementById("fromarea2").style.display = "none";
		document.getElementById("fromarea3").style.display = "";
		document.getElementById("fromarea1").selectedIndex = "";
		document.getElementById("fromarea2").selectedIndex = "";
		document.forms[0].formareacd.value = "";
	} else {
		// 通常
		document.getElementById("fromarea1").style.display = "";
		document.getElementById("fromarea2").style.display = "none";
		document.getElementById("fromarea3").style.display = "none";
		document.getElementById("fromarea2").selectedIndex = "";
		document.getElementById("fromarea3").selectedIndex = "";
	}

}