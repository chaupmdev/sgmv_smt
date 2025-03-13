<?php
/**
 * コミックマーケット104 個人向貼付票PDFを出力します。
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
class Sgmov_View_Evp_PasteTag_White {

	/**
	 * 処理
	 */
	public function execute() {
		$comiket ['service'] = 3; // 白紙の場合は貸切と同様。台数に関わらず2枚を出力し、好きなだけ印刷してもらう
		$comiket ['which_day'] = '2';
		$this->publish ( $comiket );
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
			$pdf->Image(dirname(__FILE__) . "/com104.20240811.png", $x, $y, 210.0, 148.0, "PNG", false);
		} else if($whichDay == 2){
			$pdf->Image(dirname(__FILE__) . "/com104.20240812.png", $x, $y, 210.0, 148.0, "PNG", false);
		}

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