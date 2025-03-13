<?php
/**
 * 声の教育者 貼付票PDFを出力します。
 * @package    /lib/view/jns
 * @author     Juj-Yamagami(SP)
 * @copyright  2022 Sp Media-Tec CO,.LTD. All rights reserved.
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
class Sgmov_View_Jns_PasteTag_White {

	/**
	 * 処理
	 */
	public function execute() {

		// 白紙化
		$comiket = array();
		$comiket ['service'] = 3; // 白紙の場合は貸切と同様。台数に関わらず2枚を出力し、好きなだけ印刷してもらう

		$comiket ['event_name'] = '第41回　中・高入試';
		$comiket ['which_day'] = '1';
		$comiket ['eventsub_name'] = '受験なんでも相談会';
		$comiket ['delivery_date'] = '2022/06/25';

		$comiket ['building_name'] = null;
		$comiket ['booth_position'] = null;
		$comiket ['publisher'] = null;
		$comiket ['staff_name'] = null;

		Sgmov_Component_Log::debug ( $comiket );

		if ($comiket != null) {
			$this->publish ( $comiket );
		}

		return;
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
			//if($comiket['id'] == '6'){
			//	$this->addSheetMufg ( $pdf, $comiket, $page );
			//} else {
				$this->addSheet ( $pdf, $comiket, $page );
			//}
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
			$y += 126;
		}

		$whichDay = $comiket ['which_day'];

		// A4縦の大きさは横210mm×縦297mm
		$pdf->Image(dirname(__FILE__) . "/event.1200.".$whichDay.".png", 18, 18+$y, 171.0, 125.0, "PNG", false);

		$event_name = $comiket ['event_name'] . $comiket ['eventsub_name'];
		$delivery_date = $comiket ['delivery_date'];

		$num = '';
		if ($comiket ['service'] == 3) {
			$num = "　個中の　個";
		} else {
			$num = "{$comiket['sum_num']}個中の{$page}個";
		}

		$building_name = $comiket ['building_name'];
		$booth_position = $comiket ['booth_position'];
		$publisher = $comiket ['publisher'];
		$staff_name = $comiket ['staff_name'];

		$border = 0; // $borderを1にすると枠線が表示されます。レイアウト確認に役立ちます。

		// イベント名
		$this->setColor ( $pdf, $whichDay, 1 );
		$pdf->SetFont ( 'DejaVu', '', 20 );
		$pdf->SetXY ( 30, $y+25 );
		$pdf->Cell ( 150, 16, $event_name, $border, 0, 'L', 1 );
		
		// 配送日
		$this->setColor ( $pdf, $whichDay, 1 );
		$pdf->SetFont ( 'DejaVu', '', 16 );
		$pdf->SetXY ( 130, $y+47 );
		$pdf->Cell ( 40, 6, $delivery_date, $border, 0, 'L', 1 );
		
		// 送った御荷物、○○個中の●●個
		$this->setColor ( $pdf, $whichDay, 1 );
		$pdf->SetFont ( 'DejaVu', '', 16 );
		$pdf->SetXY ( 100, $y+39 );
		$pdf->Cell ( 80, 6, '送った御荷物、'.$num, $border, 0, 'L', 1 );
		
		// 配置場所1
		$this->setColor ( $pdf, $whichDay, 1 );
		$pdf->SetFont ( 'DejaVu', '', 20 );
		$pdf->SetXY ( 58, $y+61 );
		$pdf->Cell ( 31, 16, $building_name, $border, 0, 'C', 1 );
		
		// 配置場所2
		//$this->setColor ( $pdf, $whichDay, 1 );
		//$pdf->SetFont ( 'DejaVu', '', 20 );
		//$pdf->SetXY ( 112, $y+61 );
		//$pdf->Cell ( 31, 16, $booth_position, $border, 0, 'C', 1 );
		
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
 			case 4 :
 				if ($pattern == 1) {
 					$pdf->SetFillColor ( 255, 255, 255 );
 					$pdf->SetTextColor (   0, 121, 107 );
 				} else if ($pattern == 2) {
 					$pdf->SetFillColor (   0, 121, 107 );
 					$pdf->SetTextColor ( 255, 255, 255 );
 				}
 				break;
 			case 5 :
 				if ($pattern == 1) {
 					$pdf->SetFillColor ( 255, 255, 255 );
 					$pdf->SetTextColor (  34, 150, 243 );
 				} else if ($pattern == 2) {
 					$pdf->SetFillColor (  34, 150, 243 );
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