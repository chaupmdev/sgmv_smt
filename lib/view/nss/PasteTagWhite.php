<?php
/**
 * にじそうさくの白紙貼付票PDFを出力します。
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

/**
 * #@-
 */
class Sgmov_View_Nss_PasteTag_White {

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
		$pdf->Open ();

		$sum_num = 2; // 白紙の場合は台数に関わらず2枚を出力し、好きなだけ印刷してもらう

		for($page = 1; $page <= $sum_num; $page ++) {
		    $this->addSheet ( $pdf, $comiket, $page );
		}

		try {
			$pdf->Output ( 'event.pdf', 'I' );
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
	    list ( $x, $y) = array( 0, 0 );
	    
	    if ($page % 2 != 0) {
	        // 奇数
	        $pdf->AddPage (); // ページが奇数の時だけページ追加
	    } else {
	        // 偶数
	        $y += 149;
	    }
	    
	    $whichDay = null;
	    
	    // A4縦の大きさは横210mm×縦297mm
	    $pdf->Image(dirname(__FILE__) . "/nss.png", $x, $y, 210.0, 145.0, "PNG", false);
	    
	    Sgmov_Component_Log::debug ("PATH");
	    Sgmov_Component_Log::debug (dirname(__FILE__));
	    
	    define('__BLANK__', '');
	    define('__BORDER__', 0); // BORDERを1にすると枠線が表示されます。レイアウト確認に役立ちます。
	    
	    $chiku = '東';
	    
	    // 個口数
        $this->setColor ( $pdf, $whichDay, 1 );
        $pdf->SetFont ( 'DejaVu', '', 16 );
        $pdf->SetXY ( 146, $y+25 );
        $pdf->Cell ( 10, 7, __BLANK__, __BORDER__, 0, 'R' );
        $pdf->SetXY ( 173, $y+25 );
        $pdf->Cell ( 10, 7, __BLANK__, __BORDER__, 0, 'R' );
    
	    // 地区
	    $this->setColor ( $pdf, $whichDay, 1 );
	    $pdf->SetXY ( 42, $y+63 );
	    $pdf->SetFont ( 'DejaVu', '', 60 );
	    $pdf->Cell ( 23, 22, $chiku, __BORDER__, 0, 'C', 1 );
	    
	    // ホール
	    $pdf->SetXY ( 75, $y+63 );
	    $pdf->SetFont ( 'DejaVu', '', 64 );
	    $pdf->Cell ( 18, 22, __BLANK__, __BORDER__, 0, 'C', 1 );
	    
	    // ブロック
	    $pdf->SetXY ( 105, $y+63 );
	    $pdf->SetFont ( 'DejaVu', '', 64 );
	    $pdf->Cell ( 22, 22, __BLANK__, __BORDER__, 0, 'C', 1 );
	    
	    // スペースナンバー1
	    $pdf->SetXY ( 138, $y+63 );
	    $pdf->SetFont ( 'DejaVu', '', 36 );
	    $pdf->Cell ( 18, 22, __BLANK__, __BORDER__, 0, 'C', 1 );
	    
	    // スペースナンバー2
	    $pdf->SetXY ( 158, $y+63 );
	    $pdf->SetFont ( 'DejaVu', '', 36 );
	    $pdf->Cell ( 18, 22, __BLANK__, __BORDER__, 0, 'C', 1 );
	    
	    // サークル名
	    $pdf->SetXY ( 42, $y+107 );
	    $pdf->SetFont ( 'DejaVu', '', 25 );
	    $pdf->Cell ( 150, 20, __BLANK__, __BORDER__, 0, 'L', 1 );
	    
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