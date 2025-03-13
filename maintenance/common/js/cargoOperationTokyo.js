/*--------------------------------------------------------------------------*
 *  
 *  キャンペーン設定・管理画面のコース・発着エリア設定のチェックボックス
 *  の活性・非活性を操作します。
 *  
 *  H.Tsuji
 *  
 *  2010 H.Tsuji
 *  
 *--------------------------------------------------------------------------*/

/***************************************************************************
 * オンロード時
 *
 ***************************************************************************/
function inputOnload(courceplan) {

	if (courceplan == "1") {
		// 単身カーゴが含まれる場合
		clickTanshinCargoPlan("1_1");
	} else if (courceplan == "2") {
		// 単身エアカーゴのみ
		clickTanshinCargoPlan("1_2");
	} else {
		clickTanshinCargoPlan("")
	}

	// 地域コード選択状態の非活性処理
	clickArea("");

}

/***************************************************************************
 * 全コース選択ボタンがクリックされた場合
 *
 ***************************************************************************/
function allCourcePlanBtn(methodFlg, beforeCheck1_1, beforeCheck1_2) {
	if (methodFlg) {
		// 全選択
		if (beforeCheck1_1) {
			document.getElementById("1_1").checked = true;
		} else {
			document.getElementById("1_1").checked = false;
		}
		if (beforeCheck1_2) {
			document.getElementById("1_2").checked = true;
		} else {
			document.getElementById("1_2").checked = false;
		}
	} else {
		// 全選択解除
		document.getElementById("1_1").checked = beforeCheck1_1;
		document.getElementById("1_2").checked = beforeCheck1_2;
	}
}

/***************************************************************************
 * カーゴコースがクリックされた場合
 *
 ***************************************************************************/
function clickTanshinCargoPlan(clickId) {

	if (clickId == "1_1") {
		// 単身カーゴプラン
		if (document.getElementById("1_1").checked) {
			// 到着エリアの沖縄を非活性、チェックを外す
			document.getElementById("to_center_12").disabled = true;
			document.getElementById("to_center_12").checked = false;
			document.getElementById("area_to_center_12_0").disabled = true;
			document.getElementById("area_to_center_12_0").checked = false;
		} else {
			// 到着エリアの沖縄を活性
			document.getElementById("to_center_12").disabled = false;
			document.getElementById("area_to_center_12_0").disabled = false;
		}
		
		// 地域コード選択状態の非活性処理
		clickArea("");

	} else if (clickId == "1_2") {
		// 単身エアカーゴプラン

		// コース全要素ID取得
		allCource = document.forms[0].cource_ids.value;
		allCources = allCource.split(":");

		// 出発地域全要素ID取得
		allFromArea = document.forms[0].fromarea_ids.value;
		allFromAreas = allFromArea.split(":");

		// 到着地域全要素ID取得
		allToArea = document.forms[0].toarea_ids.value;
		allToAreas = allToArea.split(":");
		
		if (document.getElementById("1_2").checked) {
			// コース非活性化処理
			for (i = 0; i < (allCources.length - 1); i++) {
				if (allCources[i] != "1_2") {
					document.getElementById(allCources[i]).disabled = true;
					document.getElementById(allCources[i]).checked = false;
				}
			}
			//東京23区以外の出発エリア・到着エリア非活性化処理
			for (i = 0; i < (allFromAreas.length - 1); i++) {
				if (allFromAreas[i] != "area_from_center_0_0") {
					document.getElementById(allFromAreas[i]).disabled = true;
					document.getElementById(allFromAreas[i]).checked = false;
				}
			}
			for (i = 0; i < (allToAreas.length - 1); i++) {
				if (allToAreas[i] != "area_to_center_0_0" && allToAreas[i] != "area_to_center_2_0" &&
						allToAreas[i] != "area_to_center_9_0" && allToAreas[i] != "area_to_center_11_3") {
					document.getElementById(allToAreas[i]).disabled = true;
					document.getElementById(allToAreas[i]).checked = false;
				}
			}
		} else {
			// 他の全コース/プランを活性
			// コース非活性化処理
			for (i = 0; i < (allCources.length - 1); i++) {
				document.getElementById(allCources[i]).disabled = false;
			}
			//東京23区/東京23区/大阪府/福岡県以外の出発エリア・到着エリア非活性化処理
			for (i = 0; i < (allFromAreas.length - 1); i++) {
				document.getElementById(allFromAreas[i]).disabled = false;
			}
			for (i = 0; i < (allToAreas.length - 1); i++) {
				document.getElementById(allToAreas[i]).disabled = false;
			}
		}

		// 地域コード選択状態の非活性処理
		clickArea("");

	} else {

		// 単身カーゴプランのチェック有無
		cargo = document.getElementById("1_1").checked;
		// 単身エアカーゴプランのチェック有無
		aircargo = document.getElementById("1_2").checked;

		if (aircargo) {
			clickTanshinCargoPlan("1_2");
		} else if (cargo) {
			clickTanshinCargoPlan("1_1");
		}
		
	}

}


/***************************************************************************
 * （※onAreaClickedの中の処理の１つとして実行されます）
 * 単身カーゴプランまたは単身エアカーゴがonで、かつ、
 * centerId（1:東京23区、2:東京23区、3:福岡）がクリックされた場合
 *
 ***************************************************************************/
function clickArea(centerId) {

	// 東京23区のチェック有無
	sapporo = document.getElementById("area_from_center_0_0").checked;
	// 単身カーゴプランのチェック有無
	cargo = document.getElementById("1_1").checked;
	// 単身エアカーゴのチェック有無
	aircargo = document.getElementById("1_2").checked;

	// 到着地域全要素ID取得
	allToArea = document.forms[0].toarea_ids.value;
	allToAreas = allToArea.split(":");

	if (aircargo) {
		// 単身カーゴプランまたは単身エアカーゴにチェックが入っている場合
		if (centerId == "area_from_center_0_0") {
			if (document.getElementById("area_from_center_0_0").checked) {
				// 東京23区にチェックを入れた場合
				// 北海道（札幌市）、福岡県を非活性、チェックを外す
				for (i = 0; i < (allToAreas.length - 1); i++) {
					if (allToAreas[i] != "area_to_center_0_0" && allToAreas[i] != "area_to_center_11_3") {
						document.getElementById(allToAreas[i]).disabled = true;
						document.getElementById(allToAreas[i]).checked = false;
					}
				}
				// 北海道（札幌市）、福岡県を活性化
				document.getElementById("area_to_center_0_0").disabled = false;
				document.getElementById("area_to_center_11_3").disabled = false;
			} else {
				// 東京23区のチェックを外した場合
				if (aircargo) {
					// 東京23区・大阪府を活性化
					document.getElementById("area_to_center_2_0").disabled = false;
					document.getElementById("area_to_center_9_0").disabled = false;
				}
			}
		} else {
			// 直接クリック以外の呼び出し（エリアごと選択した場合など）
			if (sapporo) {
				// 札幌on
				clickArea("area_from_center_0_0");
			}
		}
	} else {
		// 単身カーゴ、単身エアカーゴどちらにもチェックが入っていない場合、到着エリアは全活性化
		for (i = 0; i < (allToAreas.length - 1); i++) {
			document.getElementById(allToAreas[i]).disabled = false;
		}
		if (cargo) {
			document.getElementById("to_center_12").disabled = true;
			document.getElementById("to_center_12").checked = false;
			document.getElementById("area_to_center_12_0").disabled = true;
			document.getElementById("area_to_center_12_0").checked = false;
		}
	}


}

