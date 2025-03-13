<?php
/**
 * イベント輸送サービスの白紙の貼付票PDFを出力します。
 * @package    /lib/view/eve
 * @author     Juj-Yamagami(SP)
 * @copyright  2018 Sp Media-Tec CO,.LTD. All rights reserved.
 */

/**
 * #@+
 * include files
 */
require_once dirname ( __FILE__ ) . '/../Lib.php';
//Sgmov_Lib::useAllComponents ( FALSE );

/**
 * #@-
 */
class Sgmov_View_Eve_WhitePasteTag {

	/**
	 * 処理
	 */
	public function execute($eventsub_id) {

		$comiket = $this->selectComiket ( $eventsub_id );

		Sgmov_Component_Log::debug ( $comiket );

		if ($comiket != null) {
			$this->publish ( $comiket );
		}

		return;
	}

	/**
	 * イベントマスタを取得
	 *
	 * @param int $comiket_id
	 * @return object
	 */
	private function selectComiket($eventsub_id) {
		$db = Sgmov_Component_DB::getAdmin ();

		$sql = "select
				e.id as event_id,
				es.id as eventsub_id,
				es.term_fr as term_fr,
				es.term_to as term_to,
				e.name as event_name,
				es.name as eventsub_name,
				es.building_display as building_display,
				'A' as building_name,
				'' as booth_position,
				'' as booth_num,
				'' as publisher,
				'' as staff_name
				from event e
				inner join eventsub es on e.id = es.event_id
				where es.id = $1 ";

		$list = $db->executeQuery ( $sql, array (
				$eventsub_id
		) );

		Sgmov_Component_Log::debug ( 'size=' . $list->size () );

		if ($list->size () == 0) {
			return null;
		}

		return $list->get ( 0 );
	}

	/**
	 * 発行
	 *
	 * @param object $comiket
	 */
	private function publish($comiket) {
		Sgmov_Component_Log::debug ( 'publish start' );

		$pdf = new tFPDF ();

		$pdf->AddFont ( 'DejaVu', '', 'ipaexg.ttf', true );
		$pdf->SetFont ( 'DejaVu', '', 14 );
		$pdf->Open ();
		if ($comiket ['event_id'] == 2) { //if ($comiket ['eventsub_id'] == 12 || $comiket ['eventsub_id'] == 11 || $comiket ['eventsub_id'] == 4) {

			/**
			 * 2018夏の「コミックマーケット９４」は開催開始日前日の8/9固定になります。「コミックマーケット９５」も同様。
			 * 将来のコミケでもこの通りである確信はないので、イベントIDではなくイベントサブIDで判定する。
			 * 紙が勿体ないので2シート（A4が1枚）ずつ印刷。
			 *
			 * 2019/07/19 コミケは常に開催開始日前日で良い事になりました。
			 */

			$this->addSheet ( $pdf, $comiket, 1, 0 );
			$this->addSheet ( $pdf, $comiket, 2, 0 );

		} else {
			$fr = strtotime ( $comiket ['term_fr'] );
			$to = strtotime ( $comiket ['term_to'] );

			// 何秒間離れているかを計算
			$diffSec = abs ( $to - $fr );

			// 何日間離れているかを計算
			$diffDay = $diffSec / (60 * 60 * 24);

			// 足掛け日数を計算
			$days = $diffDay + 1;

			// 1日あたり2シート（A4が1枚）ずつ印刷
			$sheets = $days * 2;

			// ループで必要なシート数分を出力
			for($sno = 1; $sno <= $sheets; $sno ++) {
				$whichDay = ceil ( $sno / 2 );
				$this->addSheet ( $pdf, $comiket, $sno, $whichDay );
			}
		}

		try {
			//$pdf->Output ( 'paste_tag_'.filter_input ( INPUT_GET, "param" ).'.pdf', 'I' );
			$path = '../../ssl_html/eve/pdf/paste_tag/';
			$name = 'paste_tag_'.$comiket['eventsub_id'].'.pdf';
			Sgmov_Component_Log::debug ( 'Output '.$path.$name );
			$pdf->Output (  $path.$name, 'F' );
		} catch ( Exception $e ) {
			Sgmov_Component_Log::debug ( $e->getMessage () );
		}

		Sgmov_Component_Log::debug ( 'publish end' );

		return;
	}

	/**
	 * シートを記述する
	 *
	 * @param tFPDF $pdf
	 * @param object $comiket
	 * @param int $page
	 * @param int $whichDay
	 */
	private function addSheet(&$pdf, &$comiket, $page, $whichDay) {
		Sgmov_Component_Log::debug ( 'addSheet start' );

		// 始点
		list ( $x, $y ) = array (
				0,
				0
		);

		if ($page % 2 != 0) {
			// 奇数
			$pdf->AddPage (); // ページが奇数の時だけページ追加
			$x += 18;
			$y += 18;
		} else {
			// 偶数
			$x += 18;
			$y += 144;
		}

		// 色四角
		$this->setColor ( $pdf, $comiket['eventsub_id'], $whichDay, 2 );
		$pdf->Rect ( $x, $y, 171, 125, 'F' );

		// 白四角
		$pdf->SetFillColor ( 255, 255, 255 );
		$pdf->Rect ( $x += 4, $y += 4, 171 - 8, 125 - 8, 'F' );

		$this->setColor ( $pdf, $comiket['eventsub_id'], $whichDay, 1 );

		// N日目・本体
		$pdf->SetXY ( $x += 4, $y += 4 );
		$pdf->SetFont ( 'DejaVu', '', 48 );
		if($whichDay > 0){
			$pdf->Cell ( 14, 26, "$whichDay", 0, 0, 'C' );
		}

		// N日目・単位
		$pdf->SetXY ( $x += 14, $y += 5 );
		$pdf->SetFont ( 'DejaVu', '', 14 );
		if($whichDay > 0){
			$pdf->Cell ( 13, 26 - 5, '日目', 0, 0, 'C' );
		}

		// イベント名
		$pdf->SetXY ( $x += 13, $y -= 5 );
		$pdf->SetFont ( 'DejaVu', '', 18 );
		$pdf->Cell ( 90, 19, $comiket ['event_name'] . $comiket ['eventsub_name'], 0, 0, 'L' );

		// 配送日
		$pdf->SetXY ( $x, $y += 19 );
		$pdf->SetFont ( 'DejaVu', '', 14 );
		$addDays = $whichDay - 1;
		$addDays = "$addDays";
		$deliveryDate = strtotime("+$addDays day", strtotime($comiket ['term_fr']));
		$pdf->Cell ( 90, 7, date ( 'Y/m/d', $deliveryDate), 0, 0, 'R' );

		// 個口数・タイトル
		$pdf->SetXY ( $x += 90, $y -= 12 );
		$pdf->Cell ( 39, 6, '送った御荷物、', 0, 0, 'C' );

		// 個口数・本体
		$pdf->SetXY ( $x, $y += 6 );
		$pdf->Cell ( 39, 7, "　個中の　個", 0, 0, 'C' );

		// 配置場所の書き出し位置へ
		$pdf->SetXY ( $x -= (90 + 27), $y += (13 + 4) );

		if( $comiket['building_display'] == '0' ){

			/**
			 * イベントサブマスタの館名表示有無が0の場合、配置場所エリアごと非表示とする
			 */

			// 出展者名の書き出し位置へ
			$pdf->SetXY ( $x, $y += (26 + 1) );

		} else {

			// 配置場所・タイトル
			$this->setColor ( $pdf, $comiket['eventsub_id'], $whichDay, 2 );

			$pdf->SetFont ( 'DejaVu', '', 14 );
			$pdf->Cell ( 26, 26, '配置場所', 0, 0, 'C', 1 );

			// 配置場所・パネル
			$pdf->Rect ( $x += (26 + 1), $y, 128, 26, 'F' );

			if($comiket['event_id'] == '2'){ //

				/**
				 * コミケのみ特殊フォーマット。
				 * ブース番号（booth_num）のみを「ブース」として印字する。
				 * コミケは将来的にもこのフォーマットになると思われるので、イベントサブIDではなくイベントIDで判定する。
				 */

				// ブース
				$this->setColor ( $pdf, $comiket['eventsub_id'], $whichDay, 1 );
				$pdf->SetXY ( $x += 4, $y += 4 );
				$pdf->SetFont ( 'DejaVu', '', 24 );
				$pdf->Cell ( 33, 18, $comiket ['booth_num'], 0, 0, 'C', 1 );

				// ブース・単位
				$this->setColor ( $pdf, $comiket['eventsub_id'], $whichDay, 2 );
				$pdf->SetXY ( $x += 34, $y += 13 );
				$pdf->SetFont ( 'DejaVu', '', 10 );
				$pdf->Cell ( 15, 5, 'ブース', 0, 0, 'C', 1 );

				$pdf->SetXY ( $x += 26, $y += 13 );
				$pdf->SetXY ( $x += 13, $y -= 13 );
				$pdf->SetXY ( $x += 15, $y += 13 );
				$pdf->SetXY ( $x += 17, $y -= 13 );

			} else {

				if($comiket['eventsub_id'] == 300){ // ゲームマーケットの場合はホールを狭くしてブロックを広くする

					// ホール
					$this->setColor ( $pdf, $comiket['eventsub_id'], $whichDay, 1 );
					$pdf->SetXY ( $x += 4, $y += 4 );
					$pdf->SetFont ( 'DejaVu', '', 24 );
					$pdf->Cell ( 14, 18, $comiket ['building_name'], 0, 0, 'C', 1 );

					// ホール・単位
					$this->setColor ( $pdf, $comiket['eventsub_id'], $whichDay, 2 );
					$pdf->SetXY ( $x += 15, $y += 13 );
					$pdf->SetFont ( 'DejaVu', '', 10 );
					$pdf->Cell ( 12, 5, 'ホール', 0, 0, 'C', 1 );

					// ブロック
					$this->setColor ( $pdf, $comiket['eventsub_id'], $whichDay, 1 );
					$pdf->SetXY ( $x += 13, $y -= 13 );
					$pdf->SetFont ( 'DejaVu', '', 24 );
					$pdf->Cell ( 25, 18, $comiket ['booth_position'], 0, 0, 'C', 1 );

					// ブロック・単位
					$this->setColor ( $pdf, $comiket['eventsub_id'], $whichDay, 2 );
					$pdf->SetXY ( $x += 26, $y += 13 );
					$pdf->SetFont ( 'DejaVu', '', 10 );
					$pdf->Cell ( 16, 5, 'ブロック', 0, 0, 'C', 1 );

				} else {
					if($comiket['event_id'] == '7' && $comiket['eventsub_id'] == '23'){
						$pdf->SetXY ( $x += 4, $y += 4 );
						$pdf->SetXY ( $x += 26, $y += 13 );
					} else {
						// ホール
						$this->setColor ( $pdf, $comiket['eventsub_id'], $whichDay, 1 );
						$pdf->SetXY ( $x += 4, $y += 4 );
						$pdf->SetFont ( 'DejaVu', '', 24 );
						$pdf->Cell ( 25, 18, $comiket ['building_name'], 0, 0, 'C', 1 );

						// ホール・単位
						$this->setColor ( $pdf, $comiket['eventsub_id'], $whichDay, 2 );
						$pdf->SetXY ( $x += 26, $y += 13 );
						$pdf->SetFont ( 'DejaVu', '', 10 );
						$pdf->Cell ( 12, 5, 'ホール', 0, 0, 'C', 1 );
					}

					// ブロック
					$this->setColor ( $pdf, $comiket['eventsub_id'], $whichDay, 1 );
					$pdf->SetXY ( $x += 13, $y -= 13 );
					$pdf->SetFont ( 'DejaVu', '', 24 );
					$pdf->Cell ( 14, 18, $comiket ['booth_position'], 0, 0, 'C', 1 );

					// ブロック・単位
					$this->setColor ( $pdf, $comiket['eventsub_id'], $whichDay, 2 );
					$pdf->SetXY ( $x += 15, $y += 13 );
					$pdf->SetFont ( 'DejaVu', '', 10 );
					$pdf->Cell ( 16, 5, 'ブロック', 0, 0, 'C', 1 );
				}

				// スペース
				$this->setColor ( $pdf, $comiket['eventsub_id'], $whichDay, 1 );
				$pdf->SetXY ( $x += 17, $y -= 13 );
				$pdf->SetFont ( 'DejaVu', '', 24 );
				$pdf->Cell ( 33, 18, $comiket ['booth_num'], 0, 0, 'C', 1 );

				// スペース・単位
				$this->setColor ( $pdf, $comiket['eventsub_id'], $whichDay, 2 );
				$pdf->SetXY ( $x += 34, $y += 13 );
				$pdf->SetFont ( 'DejaVu', '', 10 );
				$pdf->Cell ( 15, 5, 'スペース', 0, 0, 'C', 1 );
			}

			// 出展者名の書き出し位置へ
			$pdf->SetXY ( $x -= (1 + 33 + 1 + 16 + 1 + 14 + 1 + 12 + 1 + 25 + 4 + 1 + 26), $y += (5 + 4 + 1) );
		}

		// 出展者名・タイトル
		$this->setColor ( $pdf, $comiket['eventsub_id'], $whichDay, 2 );
		$pdf->SetFont ( 'DejaVu', '', 14 );
		$pdf->Cell ( 26, 26, '出展者名', 0, 0, 'C', 1 );

		// 出展者名・パネル
		$pdf->Rect ( $x += (26 + 1), $y, 128, 26, 'F' );

		// 出展者名・本体
		$this->setColor ( $pdf, $comiket['eventsub_id'], $whichDay, 1 );
		$pdf->SetXY ( $x += 4, $y += 4 );
		$pdf->SetFont ( 'DejaVu', '', 20 );
		$pdf->Cell ( 119, 18, $comiket ['publisher'], 0, 0, 'L', 1 );

		// 担当者名・タイトル
		$this->setColor ( $pdf, $comiket['eventsub_id'], $whichDay, 2 );
		$pdf->SetXY ( $x -= (4 + 1 + 26), $y += (18 + 4 + 1) );
		$pdf->SetFont ( 'DejaVu', '', 14 );
		$pdf->Cell ( 26, 26, '担当者名', 0, 0, 'C', 1 );

		// 担当者名・パネル
		$pdf->Rect ( $x += (26 + 1), $y, 128, 26, 'F' );

		// 担当者名・本体
		$this->setColor ( $pdf, $comiket['eventsub_id'], $whichDay, 1 );
		$pdf->SetXY ( $x += 4, $y += 4 );
		$pdf->SetFont ( 'DejaVu', '', 20 );
		$pdf->Cell ( 119, 18, $comiket ['staff_name'], 0, 0, 'L', 1 );

		// ウォーターマーク
		/*$pdf->SetXY ( $x+=30 , $y -= 20 );
		$pdf->SetFont('DejaVu','',110);
		$pdf->SetTextColor(80,80,80);
		$angle = 30;
		$angle *= M_PI/180;
		$c=cos($angle);
		$s=sin($angle);
		$cx=$x*$pdf->k;
		$cy=($pdf->h-$y)*$pdf->k;
		$pdf->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
		$pdf->Cell ( 39, 6, 'SAMPLE', 0, 0, 'C' );
		$pdf->_out('Q');
		$pdf->SetXY ( 0, 0 );*/

		Sgmov_Component_Log::debug ( 'addSheet end' );

		return;
	}

	/**
	 * N日目で色を変える
	 *
	 * @param tFPDF $pdf
	 * @param int $whichDay
	 * @param int $pattern
	 */
	private function setColor(&$pdf, $eventsub_id, $whichDay, $pattern) {
		if($eventsub_id == 300){ // ゲームマーケット
			// A
			switch ($whichDay) {
				case 1 :
					if ($pattern == 1) {
						$pdf->SetFillColor ( 255, 255, 255 );
						$pdf->SetTextColor ( 0,102,102 );//#006666 rgb(0,102,102)
					} else if ($pattern == 2) {
						$pdf->SetFillColor ( 0,102,102 );
						$pdf->SetTextColor ( 255, 255, 255 );
					}
					break;
				case 2 :
					if ($pattern == 1) {
						$pdf->SetFillColor ( 255, 255, 255 );
						$pdf->SetTextColor ( 0,0,255 ); //#0000FF rgb(0,0,255)
					} else if ($pattern == 2) {
						$pdf->SetFillColor ( 0,0,255 );
						$pdf->SetTextColor ( 255, 255, 255 );
					}
					break;
				default :
					if ($pattern == 1) {
						$pdf->SetFillColor ( 255, 255, 255 );
						$pdf->SetTextColor ( 128, 128, 128 );
					} else if ($pattern == 2) {
						$pdf->SetFillColor ( 128, 128, 128 );
						$pdf->SetTextColor ( 255, 255, 255 );
					}
			}

			//B
			/*
			switch ($whichDay) {
				case 1 :
					if ($pattern == 1) {
						$pdf->SetFillColor ( 255, 255, 255 );
						$pdf->SetTextColor ( 204,   0, 153 );//#CC0099 rgb(204,0,153)
					} else if ($pattern == 2) {
						$pdf->SetFillColor ( 204,   0, 153 );
						$pdf->SetTextColor ( 255, 255, 255 );
					}
					break;
				case 2 :
					if ($pattern == 1) {
						$pdf->SetFillColor ( 255, 255, 255 );
						$pdf->SetTextColor ( 255,51,0 );//#FF3300 rgb(255,51,0)
					} else if ($pattern == 2) {
						$pdf->SetFillColor ( 255,51,0 );
						$pdf->SetTextColor ( 255, 255, 255 );
					}
					break;
				default :
					if ($pattern == 1) {
						$pdf->SetFillColor ( 255, 255, 255 );
						$pdf->SetTextColor ( 128, 128, 128 );
					} else if ($pattern == 2) {
						$pdf->SetFillColor ( 128, 128, 128 );
						$pdf->SetTextColor ( 255, 255, 255 );
					}
			}*/
		} else {
			switch ($whichDay) {
				case 1 :
					if ($pattern == 1) {
						$pdf->SetFillColor ( 255, 255, 255 );
						$pdf->SetTextColor ( 255, 153, 255 );
					} else if ($pattern == 2) {
						$pdf->SetFillColor ( 255, 153, 255 );
						$pdf->SetTextColor ( 255, 255, 255 );
					}
					break;
				case 2 :
					if ($pattern == 1) {
						$pdf->SetFillColor ( 255, 255, 255 );
						$pdf->SetTextColor ( 0, 0, 0 );
					} else if ($pattern == 2) {
						$pdf->SetFillColor ( 255, 255, 0 );
						$pdf->SetTextColor ( 0, 0, 0 );
					}
					break;
				case 3 :
					if ($pattern == 1) {
						$pdf->SetFillColor ( 255, 255, 255 );
						$pdf->SetTextColor ( 153, 51, 255 );
					} else if ($pattern == 2) {
						$pdf->SetFillColor ( 153, 51, 255 );
						$pdf->SetTextColor ( 255, 255, 255 );
					}
					break;
				default :
					if ($pattern == 1) {
						$pdf->SetFillColor ( 255, 255, 255 );
						$pdf->SetTextColor ( 128, 128, 128 );
					} else if ($pattern == 2) {
						$pdf->SetFillColor ( 128, 128, 128 );
						$pdf->SetTextColor ( 255, 255, 255 );
					}
			}
		}

		return;
	}
}