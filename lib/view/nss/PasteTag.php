<?php
/**
 * にじそうさくの貼付票PDFを出力します。
 * @package    /lib/view/nss
 * @author     Juj-Yamagami(SP)
 * @copyright  2024 Sp Media-Tec CO,.LTD. All rights reserved.
 */

/**
 * #@+
 * include files
 */
require_once dirname ( __FILE__ ) . '/../../Lib.php';
Sgmov_Lib::useAllComponents ( FALSE );

Sgmov_Lib::useComponents('Redirect');
// イベント識別子(URLのpath)はマジックナンバーで記述せずこの変数で記述してください
$dirDiv=Sgmov_Lib::setDirDiv(dirname(__FILE__));
Sgmov_Lib::useView($dirDiv.'/Common');

/**
 * #@-
 */
class Sgmov_View_Nss_PasteTag {

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
				c.booth_name,
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
			$this->addSheet ( $pdf, $comiket, $page );
		}
        try {
            $detect = new MobileDetect();
            $isSmartPhone = $detect->isMobile();
            if ($isSmartPhone) {
                $pdf->Output('haritsuke_' . $comiket_id . '.pdf', 'D');
            } else {
                $pdf->Output('event.pdf', 'I');
            }
        } catch (Exception $e) {
            Sgmov_Component_Log::debug($e->getMessage());
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
        list ( $x, $y) = array( 0, 0 );

		if ($page % 2 != 0) {
			// 奇数
			$pdf->AddPage (); // ページが奇数の時だけページ追加
		} else {
			// 偶数
			$y += 149;
		}
		
		$whichDay = $comiket ['which_day'];
		
		// A4縦の大きさは横210mm×縦297mm
		$pdf->Image(dirname(__FILE__) . "/nss.png", $x, $y, 210.0, 145.0, "PNG", false);
		
		Sgmov_Component_Log::debug ("PATH");
		Sgmov_Component_Log::debug (dirname(__FILE__));
		
		$chiku = '東';
		$hole = $comiket ['building_name'];
		$block = $comiket ['booth_position'];
		$space = $comiket ['booth_num'];
		
		$space1 = '';
		$space2 = '';
        if(strlen($space) == 4){
            // 0099
		    $space1 = substr($space, -2, 1);
		    $space2 = substr($space, -1, 1);
		}
		
		$circle = $comiket ['booth_name'];

		define('__BLANK__', '');
		define('__BORDER__', 0); // BORDERを1にすると枠線が表示されます。レイアウト確認に役立ちます。
		
		// 個口数
		if ($comiket ['service'] != 3) {
		    $this->setColor ( $pdf, $whichDay, 1 );
		    $pdf->SetFont ( 'DejaVu', '', 16 );
		    $pdf->SetXY ( 146, $y+25 );
		    $pdf->Cell ( 10, 7, $comiket['sum_num'], __BORDER__, 0, 'R' );
		    $pdf->SetXY ( 173, $y+25 );
		    $pdf->Cell ( 10, 7, strval($page), __BORDER__, 0, 'R' );
		}

		// 地区
		$this->setColor ( $pdf, $whichDay, 1 );
		$pdf->SetXY ( 42, $y+63 );
		$pdf->SetFont ( 'DejaVu', '', 60 );
		$pdf->Cell ( 23, 22, $chiku, __BORDER__, 0, 'C', 1 );
		
		// ホール
		$pdf->SetXY ( 75, $y+63 );
		$pdf->SetFont ( 'DejaVu', '', 64 );
		$pdf->Cell ( 18, 22, $hole, __BORDER__, 0, 'C', 1 );
		
		// ブロック
		$pdf->SetXY ( 105, $y+63 );
		$pdf->SetFont ( 'DejaVu', '', 64 );
		$pdf->Cell ( 22, 22, $block, __BORDER__, 0, 'C', 1 );
		
		// スペースナンバー1
		$pdf->SetXY ( 138, $y+63 );
		$pdf->SetFont ( 'DejaVu', '', 64 );
		$pdf->Cell ( 18, 22, $space1, __BORDER__, 0, 'C', 1 );
		
		// スペースナンバー2
		$pdf->SetXY ( 158, $y+63 );
		$pdf->SetFont ( 'DejaVu', '', 64 );
		$pdf->Cell ( 18, 22, $space2, __BORDER__, 0, 'C', 1 );
		
		// サークル名
		$pdf->SetXY ( 44, $y+107 );
		$pdf->SetFont ( 'DejaVu', '', 25 );
		$pdf->Cell ( 150, 20, $circle, __BORDER__, 0, 'L', 1 );
		
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
	    
	    /* 1日目も2日目も文字色を黒、背景を白とする */
	    
// 	    switch ($whichDay) {
// 	        case 1 :
// 	            if ($pattern == 1) {
// 	                $pdf->SetFillColor ( 255, 255, 255 );
// 	                $pdf->SetTextColor (   0, 0,  0 );
// 	            } else if ($pattern == 2) {
// 	                $pdf->SetFillColor (   0, 0,  0 );
// 	                $pdf->SetTextColor ( 255, 255, 255 );
// 	            }
// 	            break;
// 	        case 2 :
// 	            if ($pattern == 1) {
// 	                $pdf->SetFillColor ( 255, 255, 255 );
// 	                $pdf->SetTextColor ( 0, 0, 0 );
// 	            } else if ($pattern == 2) {
// 	                $pdf->SetFillColor ( 0, 0, 0 );
// 	                $pdf->SetTextColor ( 255, 255, 255 );
// 	            }
// 	            break;
// 	        default :
	            if ($pattern == 1) {
	                $pdf->SetFillColor ( 255, 255, 255 );
	                $pdf->SetTextColor ( 64, 64, 64 );
	            } else if ($pattern == 2) {
	                $pdf->SetFillColor ( 128, 128, 128 );
	                $pdf->SetTextColor ( 255, 255, 255 );
	            }
// 	    }
	    return;
	}
}