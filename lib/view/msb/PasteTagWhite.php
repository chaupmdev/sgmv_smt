<?php
/**
 * イベント輸送サービスの貼付票PDFを出力します。
 * @package    /lib/view/dsn
 * @author     Juj-Yamagami(SP)
 * @copyright  2020 Sp Media-Tec CO,.LTD. All rights reserved.
 */

/**
 * #@+
 * include files
 */
require_once dirname ( __FILE__ ) . '/../../Lib.php';
Sgmov_Lib::useAllComponents ( FALSE );

/**
 * #@-
 */
class Sgmov_View_Dsn_PasteTag_White {

	/**
	 * 処理
	 */
	public function execute() {
		$p = filter_input ( INPUT_GET, "param" );

		if(!$this->check($p)){
			return;
		}
		$comiket_id = intval(substr($p, 0, 10));

		Sgmov_Component_Log::debug ( $comiket_id );

		$comiket = $this->selectComiket ( $comiket_id );

		// 白紙化
		$comiket ['service'] = 3; // 白紙の場合は貸切と同様。台数に関わらず2枚を出力し、好きなだけ印刷してもらう
		$comiket ['booth_position'] = null;
		$comiket ['booth_num'] = null;
		$comiket ['publisher'] = null;
		$comiket ['staff_name'] = null;

		Sgmov_Component_Log::debug ( $comiket );

		if ($comiket != null) {
			$this->publish ( $comiket );
		}

		return;
	}

	/**
	 * チェックディジット
	 * @param string $p
	 */
	private function check($p){

		if(strlen($p) != 11){
			Sgmov_Component_Log::debug ( '11桁ではない' );
			return false;
		}

		if(!is_numeric($p)){
			Sgmov_Component_Log::debug ( '数値ではない' );
			return false;
		}

		$id = substr($p, 0, 10);
		$cd = substr($p, 10, 1);

		Sgmov_Component_Log::debug ( 'id:'.$id );
		Sgmov_Component_Log::debug ( 'cd:'.$cd );

		$sp = intval($id) % 7;

		Sgmov_Component_Log::debug ( 'sp:'.$sp );

		if($sp !== intval($cd)){
			Sgmov_Component_Log::debug ( 'CD不一致' );
			return false;
		}

		return true;
	}

	/**
	 * コミケ申込データを取得
	 *
	 * @param int $comiket_id
	 * @return array
	 */
	private function selectComiket($comiket_id) {
		$db = Sgmov_Component_DB::getAdmin ();

		$sql = "select
				e.id as event_id,
				es.id as eventsub_id,
				(d.delivery_date - es.term_fr + 1) as which_day,
				e.name as event_name,
				es.name as eventsub_name,
				es.building_display as building_display,
				to_char(d.delivery_date, 'yyyy/mm/dd') as delivery_date,
				d.service,
				case d.service when 1 then box.sum_num when 2 then cargo.sum_num when 3 then charter.sum_num else 0 end as sum_num,
				c.building_name,
				c.booth_position,
				c.booth_num,
				case c.div when 1 then c.personal_name_sei || ' ' || c.personal_name_mei when 2 then c.office_name else '' end as publisher,
				staff_sei || ' ' || staff_mei as staff_name
				from comiket c
				inner join comiket_detail d on c.id = d.comiket_id and d.type = 1
				inner join event e on c.event_id  = e.id
				inner join eventsub es on c.eventsub_id = es.id
				left join (select comiket_id, type, sum(num) sum_num from comiket_box group by comiket_id, type ) box on d.comiket_id = box.comiket_id and d.type = box.type
				left join (select comiket_id, type, sum(num) sum_num from comiket_cargo group by comiket_id, type ) cargo on d.comiket_id = cargo.comiket_id and d.type = cargo.type
				left join (select comiket_id, type, sum(num) sum_num from comiket_charter group by comiket_id, type ) charter on d.comiket_id = charter.comiket_id and d.type = charter.type
				where c.id = $1 ";

		$list = $db->executeQuery ( $sql, array (
				$comiket_id
		) );

		Sgmov_Component_Log::debug ( 'size='.$list->size () );

		if ($list->size () == 0) {
			return null;
		}

		return $list->get ( 0 );
	}

	/**
	 * 発行
	 *
	 * @param array $comiket
	 */
	private function publish($comiket) {
		Sgmov_Component_Log::debug ( 'publish start' );

		$pdf = new tFPDF ();

		$pdf->AddFont ( 'DejaVu', '', 'ipaexg.ttf', true );
		$pdf->SetFont ( 'DejaVu', '', 14 );
		$pdf->Open ();

		if ($comiket ['service'] == 3) {
			$sum_num = 2; // 貸切の場合は台数に関わらず2枚を出力し、好きなだけ印刷してもらう
		} else {
			$sum_num = $comiket ['sum_num'];
		}

		for($page = 1; $page <= $sum_num; $page ++) {
			$this->addSheet ( $pdf, $comiket, $page );
		}

		try {

			$pdf->Output ( 'event.pdf', 'I' );

			// $pdf->Output ( mb_convert_encoding ( 'C:\pdf-out\貼付票' . date ( 'YmdHis' ) . '.pdf', 'sjis', 'utf-8' ), 'F' );
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
	 * @param array $comiket
	 * @param int $page
	 */
	private function addSheet(&$pdf, &$comiket, $page) {
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

		$whichDay = $comiket ['which_day'];

		// 色四角
		$this->setColor ( $pdf, $whichDay, 2 );
		$pdf->Rect ( $x, $y, 171, 125, 'F' );

		// 白四角
		$pdf->SetFillColor ( 255, 255, 255 );
		$pdf->Rect ( $x += 4, $y += 4, 171 - 8, 125 - 8, 'F' );

		$this->setColor ( $pdf, $whichDay, 1 );

		// N日目・本体
		$pdf->SetXY ( $x += 4, $y += 4 );
		$pdf->SetFont ( 'DejaVu', '', 48 );
		if($whichDay > 0){
			$pdf->Cell ( 14, 26, $whichDay, 0, 0, 'C' );
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
		$pdf->Cell ( 90, 7, $comiket ['delivery_date'], 0, 0, 'R' );

		// 個口数・タイトル
		$pdf->SetXY ( $x += 90, $y -= 12 );
		$pdf->Cell ( 39, 6, '送った御荷物、', 0, 0, 'C' );

		// 個口数・本体
		$pdf->SetXY ( $x, $y += 6 );
		if ($comiket ['service'] == 3) {
			$pdf->Cell ( 39, 7, "　個中の　個", 0, 0, 'C' );
		} else {
			$pdf->Cell ( 39, 7, "{$comiket['sum_num']}個中の{$page}個", 0, 0, 'C' );
		}

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
			$this->setColor ( $pdf, $whichDay, 2 );

			$pdf->SetFont ( 'DejaVu', '', 14 );
			$pdf->Cell ( 26, 26, '配置場所', 0, 0, 'C', 1 );

			// 配置場所・パネル
			$pdf->Rect ( $x += (26 + 1), $y, 128, 26, 'F' );

			// 館名、ブース位置が「その他」、ブース番号が「0000」にそれぞれなっているならば空白で出力する
			$buildingName = $comiket ['building_name'];
			if ($buildingName === 'その他') {
				$buildingName = '';
			}

			$boothPosition = $comiket ['booth_position'];
			if ($boothPosition === 'その他') {
				$boothPosition = '';
			}

			$boothNum = $comiket ['booth_num'];
			if ($boothNum === '0000') {
				$boothNum = '';
			}

			if ($comiket ['eventsub_id'] == '23' || $comiket ['eventsub_id'] == '25') {
				$pdf->SetXY ( $x += 4, $y += 4 );
				$pdf->SetXY ( $x += 26, $y += 13 );
			} else {
				// 青海
				$pdf->SetXY ( $x += 2, $y += 17 );
				$pdf->SetFont ( 'DejaVu', '', 10 );
				$pdf->Cell ( 10, 5, '青海', 0, 0, 'C', 1 );

				// ホール
				$this->setColor ( $pdf, $whichDay, 1 );
				$pdf->SetXY ( $x += 10, $y -= 12 );
				$pdf->SetFont ( 'DejaVu', '', 24 );
				$pdf->Cell ( 20, 18, $comiket ['building_name'], 0, 0, 'C', 1);

				// ホール・単位
				$this->setColor ( $pdf, $whichDay, 2 );
				$pdf->SetXY ( $x += 21, $y += 13 );
				$pdf->SetFont ( 'DejaVu', '', 10 );
				$pdf->Cell ( 12, 5, 'ホール', 0, 0, 'C', 1 );
			}

			// ブロック
			$this->setColor ( $pdf, $whichDay, 1 );
			$pdf->SetXY ( $x += 13, $y -= 13 );
			$pdf->SetFont ( 'DejaVu', '', 24 );
			$pdf->Cell ( 14, 18, $boothPosition, 0, 0, 'C', 1 );

			// ブロック・単位
			$this->setColor ( $pdf, $whichDay, 2 );
			$pdf->SetXY ( $x += 15, $y += 13 );
			$pdf->SetFont ( 'DejaVu', '', 10 );
			if ($comiket ['eventsub_id'] == '26') {
				$pdf->Cell ( 16, 5, 'エリア', 0, 0, 'C', 1 );
			} else {
				$pdf->Cell ( 16, 5, 'ブロック', 0, 0, 'C', 1 );
			}

			// スペース
			$this->setColor ( $pdf, $whichDay, 1 );
			$pdf->SetXY ( $x += 17, $y -= 13 );
			$pdf->SetFont ( 'DejaVu', '', 24 );
			$pdf->Cell ( 33, 18, $boothNum, 0, 0, 'C', 1 );

			// スペース・単位
			$this->setColor ( $pdf, $whichDay, 2 );
			$pdf->SetXY ( $x += 34, $y += 13 );
			$pdf->SetFont ( 'DejaVu', '', 10 );
			
			if ($comiket ['eventsub_id'] == '26') {
				$pdf->Cell ( 14, -5, 'ブース', 0, 0, 'C', 1 );	
				$pdf->SetXY ( $x += 0, $y += 1 );
				$pdf->Cell ( 12, 5, '番号', 0, 0, 'C', 1 );	
			} else {
				$pdf->Cell ( 15, 5, 'スペース', 0, 0, 'C', 1 );
			}

			// リセット
			$x += -3;
			$y += -2;

			// 出展者名の書き出し位置へ
			$pdf->SetXY ( $x -= (1 + 33 + 1 + 16 + 1 + 14 + 1 + 12 + 1 + 25 + 4 + 1 + 26), $y += (5 + 4 + 1) );
		}

		// 出展者名・タイトル
		$this->setColor ( $pdf, $whichDay, 2 );
		$pdf->SetFont ( 'DejaVu', '', 14 );
		$pdf->Cell ( 26, 26, '出展者名', 0, 0, 'C', 1 );

		// 出展者名・パネル
		$pdf->Rect ( $x += (26 + 1), $y, 128, 26, 'F' );

		// 出展者名・本体
		$this->setColor ( $pdf, $whichDay, 1 );
		$pdf->SetXY ( $x += 4, $y += 4 );
		$pdf->SetFont ( 'DejaVu', '', 20 );
		$pdf->Cell ( 119, 18, $comiket ['publisher'], 0, 0, 'L', 1 );

		// 担当者名・タイトル
		$this->setColor ( $pdf, $whichDay, 2 );
		$pdf->SetXY ( $x -= (4 + 1 + 26), $y += (18 + 4 + 1) );
		$pdf->SetFont ( 'DejaVu', '', 14 );
		$pdf->Cell ( 26, 26, '担当者名', 0, 0, 'C', 1 );

		// 担当者名・パネル
		$pdf->Rect ( $x += (26 + 1), $y, 128, 26, 'F' );

		// 担当者名・本体
		$this->setColor ( $pdf, $whichDay, 1 );
		$pdf->SetXY ( $x += 4, $y += 4 );
		$pdf->SetFont ( 'DejaVu', '', 20 );
		$pdf->Cell ( 119, 18, $comiket ['staff_name'], 0, 0, 'L', 1 );

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
	private function setColor(&$pdf, $whichDay, $pattern) {
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
		return;
	}
}