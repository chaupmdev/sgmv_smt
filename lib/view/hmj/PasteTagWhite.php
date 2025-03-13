<?php
/**
 * ハンドメイドジャパン 貼付票PDFを出力します。
 * @package    /lib/view/hmj
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
Sgmov_Lib::useView('hmj/Common');

/**
 * #@-
 */
class Sgmov_View_Hmj_PasteTag_White {

	/**
	 * 処理
	 */
	public function execute() {

    	$this->publish (null);

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

		$sum_num = 2;

		for($page = 1; $page <= $sum_num; $page ++) {
			$this->addSheet ( $pdf, $comiket, $page );
		}

		try {

			$pdf->Output ( 'event.pdf', 'I' );
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

		$whichDay = "2";

		// A4縦の大きさは横210mm×縦297mm
		$pdf->Image(dirname(__FILE__) . "/event.1800.".$whichDay.".png", 18, 18+$y, 171.0, 125.0, "PNG", false);
		
		Sgmov_Component_Log::debug ("PATH");
		Sgmov_Component_Log::debug (dirname(__FILE__));
		
		define('__BLANK__', '');
		define('__BORDER__', 0); // BORDERを1にすると枠線が表示されます。レイアウト確認に役立ちます。

		$event_name = 'ハンドメイドインジャパンフェス2024夏';
		$delivery_date = '2024/07/21';
		$num = "　個中の　個";

		// N日目・本体
		$this->setColor ( $pdf, $whichDay, 1 , 1);
		$pdf->SetFont ( 'DejaVu', '', 54 );
		$pdf->SetXY ( 24, $y+27 );
		if($whichDay > 0){
			$pdf->Cell ( 14, 26, $whichDay, 0, 0, 'C' );
		}

		// N日目・単位
		$this->setColor ( $pdf, $whichDay, 1 , 1);
		$pdf->SetFont ( 'DejaVu', '', 14 );
		$pdf->SetXY ( 39, $y+33 );
		if($whichDay > 0){
			$pdf->Cell ( 13, 26 - 5, '日目', 0, 0, 'C' );
		}

		// イベント名
		$this->setColor ( $pdf, $whichDay, 1 , 2 );
		$pdf->SetFont ( 'DejaVu', '', 16 );
		$pdf->SetXY ( 52, $y+22 );
		$pdf->Cell ( 130, 16, $event_name, __BORDER__, 0, 'L', 1 );

		// 配送日
		$this->setColor ( $pdf, $whichDay, 1 , 1);
		$pdf->SetFont ( 'DejaVu', '', 16 );
		$pdf->SetXY ( 110, $y+47 );
		$pdf->Cell ( 40, 6, $delivery_date, __BORDER__, 0, 'L', 1 );

		// 送った御荷物、○○個中の●●個
		$this->setColor ( $pdf, $whichDay, 1 , 1);
		$pdf->SetFont ( 'DejaVu', '', 16 );
		$pdf->SetXY ( 142, $y+32 );
		$pdf->Cell ( 42, 6, '送った御荷物、', __BORDER__, 0, 'R', 1 );
		$pdf->SetXY ( 124, $y+39 );
		$pdf->Cell ( 60, 6, $num, __BORDER__, 0, 'R', 1 );

// 		// 配置場所1
// 		$this->setColor ( $pdf, $whichDay, 1 , 1);
// 		$pdf->SetFont ( 'DejaVu', '', 20 );
// 		$pdf->SetXY ( 57, $y+61 );
// 		$pdf->Cell ( 24, 16, __BLANK__, __BORDER__, 0, 'C', 1 );

// 		// 配置場所2
// 		$this->setColor ( $pdf, $whichDay, 1 , 1);
// 		$pdf->SetFont ( 'DejaVu', '', 20 );
// 		$pdf->SetXY ( 95, $y+61 );
// 		$pdf->Cell ( 14, 16, __BLANK__, __BORDER__, 0, 'C', 1 );
		
// 		// 配置場所3
// 		$this->setColor ( $pdf, $whichDay, 1 , 1);
// 		$pdf->SetFont ( 'DejaVu', '', 20 );
// 		$pdf->SetXY ( 128, $y+61 );
// 		$pdf->Cell ( 32, 16, __BLANK__, __BORDER__, 0, 'C', 1 );

// 		// 出展者名
// 		$this->setColor ( $pdf, $whichDay, 1 , 2);
// 		$pdf->SetFont ( 'DejaVu', '', 20 );
// 		$pdf->SetXY ( 58, $y+88 );
// 		$pdf->Cell ( 117, 16, __BLANK__, __BORDER__, 0, 'L', 1 );

// 		// 担当者名
// 		$this->setColor ( $pdf, $whichDay, 1 , 2);
// 		$pdf->SetFont ( 'DejaVu', '', 20 );
// 		$pdf->SetXY ( 58, $y+115 );
// 		$pdf->Cell ( 117, 16, __BLANK__, __BORDER__, 0, 'L', 1 );

		Sgmov_Component_Log::debug ( 'addSheet end' );

		return;
	}

	/**
	 * N日目で色を変える
	 *
	 * @param tFPDF $pdf
	 * @param int $whichDay
	 * @param int $pattern 1:白背景に色文字、 2:色背景に白文字
	 * @param int $color 1:ぴんく、2：水色
	 */
	private function setColor(&$pdf, $whichDay, $pattern, $color) {

 		switch ($whichDay) {
 			case 1 :
 			
 				/*
 				if ($pattern == 1) {
 					$pdf->SetFillColor ( 255, 255, 255 );
 					$pdf->SetTextColor ( 176, 229, 254 );    //176 229 254
 				} else if ($pattern == 2) {
 					$pdf->SetFillColor ( 255, 153, 255 );
 					$pdf->SetTextColor ( 255, 255, 255 );
 				}*/

 				if ($pattern == 1) {
 					$pdf->SetFillColor ( 255, 255, 255 );
 					if($color == 1){
 						$pdf->SetTextColor ( 255, 153, 255 );//1：ぴんく
 					} else if($color == 2) {
 						$pdf->SetTextColor ( 176, 229, 254 );//2：水色
 					}
 					
 				} else if ($pattern == 2) {
 					$pdf->SetFillColor ( 128, 128, 128 );
 					$pdf->SetTextColor ( 255, 255, 255 );
 				}

 				break;
 			case 2 :
 				/*
 				if ($pattern == 1) {
 					$pdf->SetFillColor ( 255, 255, 255 );
 					$pdf->SetTextColor ( 0, 0, 0 );
 				} else if ($pattern == 2) {
 					$pdf->SetFillColor ( 255, 255, 0 );
 					$pdf->SetTextColor ( 0, 0, 0 );
 				}*/

 				if ($pattern == 1) {
 					$pdf->SetFillColor ( 255, 255, 255 );
 					if($color == 1){
 						$pdf->SetTextColor ( 0, 0, 0 );//1：黒
 					} else if($color == 2) {
 						$pdf->SetTextColor ( 176, 229, 254 );//2：水色
 					}
 					
 				} else if ($pattern == 2) {
 					$pdf->SetFillColor ( 128, 128, 128 );
 					$pdf->SetTextColor ( 255, 255, 255 );
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