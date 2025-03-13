<?php
/**
 * イベント輸送サービスの貼付票PDFを出力します。
 * @package    /lib/view/twf
 * @author     Juj-Yamagami(SP)
 * @copyright  2021 Sp Media-Tec CO,.LTD. All rights reserved.
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
class Sgmov_View_Twf_PasteTag_White {

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
		$comiket ['booth_name'] = null;
		$comiket ['building_name'] = null;
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
				c.booth_name,
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

		if ($page % 2 != 0) {
			// 奇数
			$pdf->AddPage (); // ページが奇数の時だけページ追加
			$btm = 0;
		} else {
			// 偶数
			$btm = 126;
		}

		$whichDay = $comiket ['which_day'];

		// 色四角
		$this->setColor ( $pdf, $whichDay, 2 );
		$pdf->Rect ( $x=18, $y=18 + $btm, 171, 125, 'F' );

		// 白四角
		$pdf->SetFillColor ( 255, 255, 255 );
		$pdf->Rect ( $x=22, $y=22 + $btm, 171 - 8, 125 - 8, 'F' );

		$this->setColor ( $pdf, $whichDay, 1 );
		
		// 会場名（固定文言）
		$pdf->SetXY ( $x=26, $y=21 + $btm );
		$pdf->SetFont ( 'DejaVu', '', 18 );
		$pdf->Cell ( 90, 19, '東京ドームシティ プリズムホール', 0, 0, 'L' );

		// イベント名（変数）
		$pdf->SetXY ( $x=26, $y=35 + $btm );
		$pdf->SetFont ( 'DejaVu', '', 20 );
		$pdf->Cell ( 90, 19, $comiket ['event_name'], 0, 0, 'L' );

		// 個口数・タイトル（固定文言）
		$pdf->SetXY ( $x=138, $y=32 + $btm );
		$pdf->SetFont ( 'DejaVu', '', 14 );
		$pdf->Cell ( 39, 6, '送った御荷物、', 0, 0, 'C' );

		// 個口数・本体（変数）
		$pdf->SetXY ( $x=138, $y=40 + $btm );
		$pdf->SetFont ( 'DejaVu', '', 18 );
		if ($comiket ['service'] == 3) {
			$pdf->Cell ( 39, 7, "　個中の　個", 0, 0, 'C' );
		} else {
			$pdf->Cell ( 39, 7, "{$comiket['sum_num']}個中の{$page}個", 0, 0, 'C' );
		}

		// コーナー名（固定文言）
		$pdf->SetXY ( $x=26, $y=56 + $btm );
			$this->setColor ( $pdf, $whichDay, 2 );
			$pdf->SetFont ( 'DejaVu', '', 14 );
			$pdf->Cell ( 26, 26, 'コーナー名', 0, 0, 'C', 1 );

		// コーナー名と産地名  入力枠の下地（色ON）
		$pdf->Rect ( $x=53, $y=56 + $btm, 128, 26, 'F' );
/*
		// コーナー名（変数）
		$pdf->SetXY ( $x=57, $y=60 + $btm );
			$this->setColor ( $pdf, $whichDay, 1 );
			$pdf->SetFont ( 'DejaVu', '', 12 );
			$pdf->Cell ( 69, 18, $comiket ['building_name'], 0, 0, 'L', 1 );

		// 産地名（固定文言）
		$pdf->SetXY ( $x=127, $y=67 + $btm );
			$this->setColor ( $pdf, $whichDay, 2 );
			$pdf->SetFont ( 'DejaVu', '', 14 );
			$pdf->Cell ( 16, 5, '産地名', 0, 0, 'C', 1 );

		// 産地名（変数）
		$pdf->SetXY ( $x=143, $y=60 + $btm );
			$this->setColor ( $pdf, $whichDay, 1 );	
			$pdf->SetFont ( 'DejaVu', '', 10 );
			$pdf->Cell ( 33, 18, $comiket ['booth_position'], 0, 0, 'L', 1 );
*/


		// コーナー名（変数）
		$pdf->SetXY ( $x=57, $y=60 + $btm );
			$this->setColor ( $pdf, $whichDay, 1 );
			$pdf->SetFont ( 'DejaVu', '', 12 );
			$pdf->Cell ( 55, 18, $comiket ['building_name'], 0, 0, 'L', 1 );

		// 産地名（固定文言）
		$pdf->SetXY ( $x=113, $y=67 + $btm );
			$this->setColor ( $pdf, $whichDay, 2 );
			$pdf->SetFont ( 'DejaVu', '', 14 );
			$pdf->Cell ( 16, 5, '産地名', 0, 0, 'C', 1 );

		// 産地名（変数）
		$pdf->SetXY ( $x=130, $y=60 + $btm );
			$this->setColor ( $pdf, $whichDay, 1 );	
			$pdf->SetFont ( 'DejaVu', '', 10 );
			$pdf->Cell ( 46, 18, $comiket ['booth_position'], 0, 0, 'L', 1 );




		// 会社名（固定文言）
		$pdf->SetXY ( $x=26, $y=83 + $btm );
			$this->setColor ( $pdf, $whichDay, 2 );
			$pdf->SetFont ( 'DejaVu', '', 14 );
			$pdf->Cell ( 26, 26, '会社名', 0, 0, 'C', 1 );

		// 会社名 入力枠の下地（色ON）
		$pdf->Rect ( $x=53, $y=83 + $btm, 128, 26, 'F' );

		// 会社名（変数）
		$pdf->SetXY ( $x=57, $y=87 + $btm );
			$this->setColor ( $pdf, $whichDay, 1 );
			$pdf->SetFont ( 'DejaVu', '', 20 );
			$pdf->Cell ( 119, 18, $comiket ['booth_name'], 0, 0, 'L', 1 );

		// 担当者名（固定文言）
		$pdf->SetXY ( $x=26, $y=110 + $btm );
			$this->setColor ( $pdf, $whichDay, 2 );
			$pdf->SetFont ( 'DejaVu', '', 14 );
			$pdf->Cell ( 26, 26, '担当者名', 0, 0, 'C', 1 );

		// 担当者名  入力枠の下地（色ON）
		$pdf->Rect ( $x=53, $y=110 + $btm, 128, 26, 'F' );

		// 担当者名（変数）
		$pdf->SetXY ( $x=57, $y=114 + $btm );
			$this->setColor ( $pdf, $whichDay, 1 );
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
	 * https://note.cman.jp/color/base_color.cgi
	 */
	private function setColor(&$pdf, $whichDay, $pattern) {
		/*
		switch ($whichDay) {
			case 1 :
				if ($pattern == 1) {
					$pdf->SetFillColor ( 255, 255, 255 ); // 白
					$pdf->SetTextColor ( 255, 153, 255 ); // ピンク
				} else if ($pattern == 2) {
					$pdf->SetFillColor ( 255, 153, 255 ); // ピンク
					$pdf->SetTextColor ( 255, 255, 255 ); // 白
				}
				break;
			case 2 :
				if ($pattern == 1) {
					$pdf->SetFillColor ( 255, 255, 255 ); // 白
					$pdf->SetTextColor ( 0, 0, 0 ); // 黒
				} else if ($pattern == 2) {
					$pdf->SetFillColor ( 255, 255, 0 ); // 黄色
					$pdf->SetTextColor ( 0, 0, 0 ); // 黒
				}
				break;
			case 3 :*/
				if ($pattern == 1) {
					$pdf->SetFillColor ( 255, 255, 255 ); // 白
					$pdf->SetTextColor ( 153, 51, 255 ); // 紫
				} else if ($pattern == 2) {
					$pdf->SetFillColor ( 153, 51, 255 ); // 紫
					$pdf->SetTextColor ( 255, 255, 255 ); // 白
				}
				/*
				break;
			default :
				if ($pattern == 1) {
					$pdf->SetFillColor ( 255, 255, 255 ); // 白
					$pdf->SetTextColor ( 128, 128, 128 ); // グレー
				} else if ($pattern == 2) {
					$pdf->SetFillColor ( 128, 128, 128 ); // グレー
					$pdf->SetTextColor ( 255, 255, 255 ); // 白
				}
		}*/
		return;
	}
}