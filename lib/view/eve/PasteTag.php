<?php
/**
 * コミックマーケット104 法人向貼付票PDFを出力します。
 * @package    /lib/view/eve
 * @author     Juj-Yamagami(SP)
 * @copyright  2021 Sp Media-Tec CO,.LTD. All rights reserved.
 */

/**
 * #@+
 * include files
 */
require_once dirname ( __FILE__ ) . '/../../Lib.php';
Sgmov_Lib::useAllComponents ( FALSE );

// 2022-03-25 ToanDD3 implement STM6-84
Sgmov_Lib::useComponents('Redirect');
/**
 * #@-
 */
Sgmov_Lib::useView('eve/Common');

/**
 * #@-
 */
class Sgmov_View_Eve_PasteTag extends Sgmov_View_Eve_Common {

	/**
	 * 処理
	 */
	// 2022-03-25 ToanDD3 implement SMT6-84
	// public function execute() {
	public function executeInner() {
        $isSecurity = $this->isSecurityTaio();
		if ($isSecurity && !isset($_SESSION['EVE_LOGIN']['user_type'])) {
			// Redirect to 404 page if not loged in
			Sgmov_Component_Redirect::redirectPublicSsl("/404.html");
        }

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
			// 2022-03-30 ToanDD3 implement download file on Mobile
			// $this->publish ( $comiket );
			$this->publish ( $comiket, $comiket_id );
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
				c.building_name,
				c.booth_position,
				c.booth_num,
				c.booth_name,
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
	// 2022-03-30 ToanDD3 implement download file on Mobile
	// private function publish($comiket) {
	private function publish($comiket, $comiket_id) {
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
			//if($comiket['id'] == '6'){
			//	$this->addSheetMufg ( $pdf, $comiket, $page );
			//} else {
				$this->addSheet ( $pdf, $comiket, $page );
			//}
		}

		try {
			// 2022-03-30 ToanDD3 implement download file on Mobile
			// $pdf->Output ( 'event.pdf', 'I' );
			$detect = new MobileDetect();
			$isSmartPhone = $detect->isMobile();
			if ($isSmartPhone) {
				$pdf->Output ( 'haritsuke_'.$comiket_id.'.pdf', 'D' );
			} else {
				$pdf->Output ( 'event.pdf', 'I' );
			}
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
			$y += 126;
		}

		$whichDay = $comiket ['which_day'];

		// A4縦の大きさは横210mm×縦297mm
		$pdf->Image(dirname(__FILE__) . "/com104.company.png", 18, 18+$y, 171.0, 125.0, "PNG", false);
		
		$border = 0; // BORDERを1にすると枠線が表示されます。レイアウト確認に役立ちます。

		$event_name = $comiket ['event_name'];
		$delivery_date = $comiket ['delivery_date'];

		$num = '';
		if ($comiket ['service'] == 3) {
			$num = "　個中の　個";
		} else {
			$num = "{$comiket['sum_num']}個中の{$page}個";
		}

		$building_name = $comiket ['building_name'];
		$booth_position = $comiket ['booth_position'];
		
		$booth_num = $comiket ['booth_num'];
		$publisher = $comiket ['booth_name'];
		$staff_name = $comiket ['staff_name'];

		// イベント名
		$this->setColor ( $pdf, $whichDay, 1 );
		$pdf->SetFont ( 'DejaVu', '', 20 );
		$pdf->SetXY ( 48, $y+28 );
		$pdf->Cell ( 90, 16, $event_name, $border, 0, 'L', 1 );

		// 配送日
		$this->setColor ( $pdf, $whichDay, 1 );
		$pdf->SetFont ( 'DejaVu', '', 16 );
		$pdf->SetXY ( 110, $y+47 );
		$pdf->Cell ( 40, 6, $delivery_date, $border, 0, 'L', 1 );

		// 送った御荷物、○○個中の●●個
		$this->setColor ( $pdf, $whichDay, 1 );
		$pdf->SetFont ( 'DejaVu', '', 16 );
		$pdf->SetXY ( 141, $y+32 );
		$pdf->Cell ( 42, 6, '送った御荷物、', $border, 0, 'R', 1 );
		$pdf->SetXY ( 141, $y+39 );
		$pdf->Cell ( 42, 6, $num, $border, 0, 'R', 1 );

		// 配置場所 ホール
		$this->setColor ( $pdf, $whichDay, 1 );
		$pdf->SetFont ( 'DejaVu', '', 20 );
		$pdf->SetXY ( 58, $y+61 );
		$pdf->Cell ( 27, 16, $booth_position, $border, 0, 'C', 1 );
		
		// 配置場所 ブース
		$this->setColor ( $pdf, $whichDay, 1 );
		$pdf->SetFont ( 'DejaVu', '', 20 );
		$pdf->SetXY ( 111, $y+61 );
		$pdf->Cell ( 31, 16, $booth_num, $border, 0, 'C', 1 );

		// 出展者名
		$this->setColor ( $pdf, $whichDay, 1 );
		$pdf->SetFont ( 'DejaVu', '', 20 );
		$pdf->SetXY ( 58, $y+88 );
		$pdf->Cell ( 117, 16, $publisher, $border, 0, 'L', 1 );

		// 担当者名
		$this->setColor ( $pdf, $whichDay, 1 );
		$pdf->SetFont ( 'DejaVu', '', 20 );
		$pdf->SetXY ( 58, $y+115 );
		$pdf->Cell ( 117, 16, $staff_name, $border, 0, 'L', 1 );

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

		if ($pattern == 1) {
			$pdf->SetFillColor ( 255, 255, 255 );
			$pdf->SetTextColor ( 128, 128, 128 );
		} else if ($pattern == 2) {
			$pdf->SetFillColor ( 128, 128, 128 );
			$pdf->SetTextColor ( 255, 255, 255 );
		}

		return;
	}
}