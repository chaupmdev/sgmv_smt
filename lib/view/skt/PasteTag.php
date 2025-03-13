<?php
/**
 * コミックマーケット99 個人向の貼付票PDFを出力します。
 * @package    /lib/view/evp
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
class Sgmov_View_Evp_PasteTag {

	/**
	 * 処理
	 */
	public function execute() {

		$p = filter_input ( INPUT_GET, "param" );

		Sgmov_Component_Log::debug ( $p );

		if(!$this->check($p)){
			return;
		}
		$comiket_id = intval(substr($p, 0, 10));

		Sgmov_Component_Log::debug ( $comiket_id );

		$comiket = $this->selectComiket ( $comiket_id );

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
				e.id as id,
				es.id as sid,
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
				staff_sei || ' ' || staff_mei as staff_name,
				personal_name_sei || ' ' || personal_name_mei as personal_name
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
		$pdf->SetMargins ( 0.0, 0.0, 0.0 );
		$pdf->SetAutoPageBreak ( true, 0.0 );
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

		$pdf = null;

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
		list ( $x, $y ) = array ( 0, 0 );

		if ($page % 2 != 0) {
			// 奇数
			$pdf->AddPage (); // ページが奇数の時だけページ追加
		} else {
			// 偶数
			$y += 149;
		}

		$whichDay = $comiket ['which_day'];

		// A4縦の大きさは横210mm×縦297mm
		if($whichDay == 1){
			$pdf->Image(dirname(__FILE__) . "/com99.20211230.png", $x, $y, 210.0, 148.0, "PNG", false);
		} else if($whichDay == 2){
			$pdf->Image(dirname(__FILE__) . "/com99.20211231.png", $x, $y, 210.0, 148.0, "PNG", false);
		}

/*
		$building_name = $comiket ['building_name'];
		$building_name_array = explode(',', $building_name, 2 );
		if(count($building_name_array) < 2){
			throw new Exception('[building_name]が不正です。');
		}
*/
		$booth_name = $comiket ['booth_name'];
		if($booth_name == ''){
			throw new Exception('[booth_name]が不正です。');
		}

		$chiku = $booth_name;
		$hole  = $booth_name;
		$block = $comiket ['booth_position'];
		$space = $comiket ['booth_num'];
		$space1 = substr($space, -2, 1);
		$space2 = substr($space, -1, 1);
		$circle = $comiket ['booth_name'];

		$border = 0; // $borderを1にすると枠線が表示されます。レイアウト確認に役立ちます。

		// 地区
		$this->setColor ( $pdf, $whichDay, 1 );
		$pdf->SetXY ( 46.5, $y+75 );
		$pdf->SetFont ( 'DejaVu', '', 60 );
		$pdf->Cell ( 20, 20, $chiku, $border, 0, 'C', 1 );

		// ホール
		$pdf->SetXY ( 82, $y+75 );
		$pdf->SetFont ( 'DejaVu', '', 64 );
		$pdf->Cell ( 20, 20, $hole, $border, 0, 'C', 1 );

		// ブロック
		$pdf->SetXY ( 118, $y+75 );
		$pdf->SetFont ( 'DejaVu', '', 64 );
		$pdf->Cell ( 20, 20, $block, $border, 0, 'C', 1 );

// 		// スペースナンバー1
// 		$pdf->SetXY ( 155, $y+75 );
// 		$pdf->SetFont ( 'DejaVu', '', 64 );
// 		$pdf->Cell ( 18, 20, $space1, $border, 0, 'C', 1 );

// 		// スペースナンバー2
// 		$pdf->SetXY ( 176, $y+75 );
// 		$pdf->SetFont ( 'DejaVu', '', 64 );
// 		$pdf->Cell ( 18, 20, $space2, $border, 0, 'C', 1 );

		// サークル名
		$pdf->SetXY ( 47, $y+112 );
		$pdf->SetFont ( 'DejaVu', '', 25 );
		$pdf->Cell ( 146, 20, $circle, $border, 0, 'L', 1 );

		// スペースナンバー
		$pdf->SetXY ( 155, $y+75 );
		$color = 0; // スペースナンバーが4桁となり、枠よりハミ出る表出となりますが、背景色と文字色が同色だと視認性が悪い為、（スペースナンバーのみ）文字色を変更しています。
		if($whichDay==1){$color=2;}
		if($whichDay==2){$color=1;}
		$this->setColor ( $pdf, $color, 1 );
		$pdf->SetFont ( 'DejaVu', '', 64 );
		$pdf->Cell ( 40, 20, $space, $border, 0, 'C', 1 );

		Sgmov_Component_Log::debug ( 'addSheet end' );

		return;
	}

	/**
	 * N日目で色を変える
	 *
	 * @param tFPDF $pdf
	 * @param int $whichDay
	 * @param int $pattern 1:白背景に色文字、 2:色背景に白文字
	 */
	private function setColor(&$pdf, $whichDay, $pattern) {
		switch ($whichDay) {
			case 1 :
				if ($pattern == 1) {
					$pdf->SetFillColor ( 255, 255, 255 );
					$pdf->SetTextColor (   0, 0,  0 );
				} else if ($pattern == 2) {
					$pdf->SetFillColor (   0, 0,  0 );
					$pdf->SetTextColor ( 255, 255, 255 );
				}
				break;
			case 2 :
				if ($pattern == 1) {
					$pdf->SetFillColor ( 255, 255, 255 );
					$pdf->SetTextColor ( 0, 0, 0 );
				} else if ($pattern == 2) {
					$pdf->SetFillColor ( 0, 0, 0 );
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