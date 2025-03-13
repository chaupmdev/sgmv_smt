<?php
/**
 * BCMイベント輸送サービスのコンビニ先払専用のお申し込み送信バッチ。
 * @package    /lib/service
 * @subpackage BCM
 * @author     Juj-Yamagami(SP)
 * @copyright  2018 Sp Media-Tec CO,.LTD. All rights reserved.
 */

/**
 * #@+
 * include files
 */
require_once dirname ( __FILE__ ) . '/../Lib.php';
Sgmov_Lib::useAllComponents ( FALSE );
/**
 * #@-
 */
class Sgmov_Service_BcmData {
    /**
     * 宇奈月温泉の識別
     */
    const UNAZUKI_SHIKIBETU = "una";
    
    /**
     * ミルクランの識別
     */
    const MIRUKURAN_SHIKIBETU = "mlk";

    /**
     * ミルクランの空港
     */
    const MIRUKURAN_AIRPORT = "1";
    
    /**
     * コミケ詳細の往復区分：復路
     */
    const COMIKET_DETAIL_TYPE_FUKURO = "2";
    
	/**
	 * DB値から送信用csvファイル作成
	 *
	 * @param object $comiket
	 * @return string
	 */
	public function makeIFcsv(&$comiket) {
		Sgmov_Component_Log::debug ( $comiket );
		$csv = "";
		$csv .= "\"HEADER\"";
		$csv .= "\r\n";
		$csv .= $this->setComiket ( $comiket );

        // detailを取得
		$detailList = $this->selectDetail( $comiket ['id'],  @$comiket['id_sub'], @$comiket['event_key']);

        // detail,boxのcsvを生成する
		for($i = 0; $i < $detailList->size (); $i ++) {

			$detail = $detailList->get ( $i );
			Sgmov_Component_Log::debug ($detail);

            // detailのcsv生成：コストコは下記box取得後に生成
            if ($detail ['service'] != 7) {
                $csv .= $this->setComiketDetail($detail, $comiket);
            }

            // serviceごとにboxの設定
            // 宅配(イベントはほとんどこの処理を通る)
			if ($detail ['service'] == 1) {
				$boxList = $this->selectBox ( $detail ['comiket_id'], $detail ['type'], @$detail['comiket_id_sub'] );
				for($j = 0; $j < $boxList->size (); $j ++) {
					$box = $boxList->get ( $j );
					Sgmov_Component_Log::debug ( $box );
					$csv .= $this->setComiketBox ( $box );
				}
			}
            // カーゴ
			if ($detail ['service'] == 2) {
				$cargoList = $this->selectCargo ( $detail ['comiket_id'], $detail ['type'], @$detail['comiket_id_sub'] );
				for($j = 0; $j < $cargoList->size (); $j ++) {
					$cargo = $cargoList->get ( $j );
					Sgmov_Component_Log::debug ( $cargo );
					$csv .= $this->setComiketCargo ( $cargo );
				}
			}
            // ミルクラン
			if ($detail ['service'] == 4) {
				$boxList = $this->selectBox ( $detail ['comiket_id'], $detail ['type'], @$detail['comiket_id_sub']  );
				for($j = 0; $j < $boxList->size (); $j ++) {
					$box = $boxList->get ( $j );
					Sgmov_Component_Log::debug ( $box );
					$csv .= $this->setComiketBox ( $box );
				}
			}
            // 物販
			if ($detail ['service'] == 6) {
				$boxList = $this->selectShohin ( $detail ['comiket_id'], $detail ['type'], @$detail['comiket_id_sub']  );
				for($j = 0; $j < $boxList->size (); $j ++) {
					$box = $boxList->get ( $j );
					Sgmov_Component_Log::debug ( $box );
					$csv .= $this->setShohin ( $box );
				}
			}
            // コストコ
            if ($detail ['service'] == 7) {
                // box用CSVを別途取得する
                $boxCsv="";
                $boxList = $this->selectComiketBoxForCostco ( $detail ['comiket_id'] );
                for($j=0;$j<$boxList->size();$j++) {
                    $box = $boxList->get($j);
                    $kijiranBox[]=$box;
                    Sgmov_Component_Log::debug( $box );
                    $boxCsv .= $this->setBoxForCostco( $box );
                }
                // detailのcsv生成
                $csv .= $this->setComiketDetail($detail, $comiket, $kijiranBox);
                // 先に取得したboxCsvを結合する
                $csv .= $boxCsv;
            }
            // アルペン
            if ($detail ['service'] == 8) { 
                // alpen_app_boxを取得
                $boxList = $this->selectAlpenBox($detail['comiket_id']);
                for($j=0; $j<$boxList->size(); $j++){
                    $box = $boxList->get($j);
                    Sgmov_Component_Log::debug($box);
                    $csv .= $this->setAlpenBox($box);
                }
			}
		}

		$csv .= "\"TRAILER\"";

		return $csv;
	}

	/**
	 * コミケ申込明細データを取得
	 *
	 * @param int $comiket_id
	 * @return Sgmov_Component_DBResult
	 */
	private function selectDetail($comiket_id, $comiket_id_sub = '', $event_key){
		$db = Sgmov_Component_DB::getAdmin();

        $list = array();
        if(@empty($comiket_id_sub)){
            $sql = "SELECT * FROM comiket_detail WHERE comiket_id = $1 ORDER BY type ASC ";

            $list = $db->executeQuery($sql, array($comiket_id));
        }
        else{

            // アルペン以外
            if($event_key != 'alp'){
                $sql = "SELECT * FROM comiket_detail WHERE comiket_id = $1 AND comiket_id_sub = $2 ORDER BY type ASC ";
                $list = $db->executeQuery( $sql, array($comiket_id,$comiket_id_sub));
            }
            // アルペン
            else{
                $sql = "SELECT app_id AS comiket_id ,* FROM alpen_app_detail WHERE app_id = $1 AND app_id_sub = $2 ORDER BY type ASC ";
                $list = $db->executeQuery($sql, array($comiket_id, $comiket_id_sub));
            }
        }

		Sgmov_Component_Log::debug ( 'size=' . $list->size () );

		return $list;
	}

	/**
	 * コミケ申込宅配データを取得
	 *
	 * @param int $comiket_id
	 * @param int $type
	 * @return Sgmov_Component_DBResult
	 */
	private function selectShohin($comiket_id, $type, $comiket_id_sub = '') {
		$db = Sgmov_Component_DB::getAdmin ();
        $list = array();
        if (@empty($comiket_id_sub)) {
            $sql = "select * from comiket_box left join shohin on comiket_box.box_id = shohin.id where comiket_id = $1 and comiket_box.type = $2 order by box_id asc ";

            $list = $db->executeQuery ( $sql, array (
                    $comiket_id,
                    $type
            ) );
        } else {
            $sql = "select * from comiket_box left join shohin on comiket_box.box_id = shohin.id where comiket_id = $1 and comiket_box.type = $2 and comiket_id_sub = $3  order by box_id asc ";

            $list = $db->executeQuery ( $sql, array (
                    $comiket_id,
                    $type,
                    $comiket_id_sub
            ) );
        }

		Sgmov_Component_Log::debug ( 'size=' . $list->size () );

		return $list;
	}

	/**
	 * コストコ商品マスタを取得
	 *
	 * @param int $shohinCd
	 * @return result
	 */
	private function selectShohinForCostco($shohinCd) {
		$db = Sgmov_Component_DB::getAdmin ();
        $list = array();
		$result = array();

		$sql = " select * from costco_shohin where shohin_cd = $1 and start_date::date <= now()::date and end_date::date >= now()::date ";
		$list = $db->executeQuery($sql, array(
			$shohinCd
		));
		//2023/01/10 GiapLN imp ticket #SMT6-352
        $resSize = $list->size();
        if(empty($resSize)) {
            return array();
        }
		Sgmov_Component_Log::debug ( 'size=' . $list->size () );
		$result = $list->get(0);

		return $result;
	}
    
	/**
	 * コストコ商品マスタを取得
	 *
	 * @param int $comiketId
	 * @return result
	 */
	private function selectShohinForCostcoByComiketId($comiketId) {
		$db = Sgmov_Component_DB::getAdmin ();
        $list = array();
		$result = array();

		$sql = " select * from costco_shohin where shohin_cd = (select shohin_cd from comiket_box where comiket_id = $1 and shohin_cd is not null limit 1) and start_date::date <= now()::date and end_date::date >= now()::date ";
		$list = $db->executeQuery ( $sql, array (
			$comiketId
		) );
			
		Sgmov_Component_Log::debug ( 'size=' . $list->size () );
        if ($list->size () <= 0 ) {
            return $result;
        }
        
		$result = $list->get(0);

		return $result;
	}

	/**
	 * アルペン商品マスタを取得
	 *
	 * @param int $shohinCd
	 * @return result
	 */
	private function selectShohinForAlpen($shohinCd) {
		$db = Sgmov_Component_DB::getAdmin ();
        $list = array();
		$result = array();

		$sql = " select * from alpen_shohin where shohin_cd = $1 ";
		$list = $db->executeQuery ( $sql, array (
			$shohinCd
		) );
			
		Sgmov_Component_Log::debug ( 'size=' . $list->size () );
		$result = $list->get(0);

		return $result;
	}

	/**
	 * コストコオプション料金マスタを取得
	 * 未使用
	 *
	 * @param int $id
	 * @return result
	 */
	private function selectOptionForCostco($id) {
		$db = Sgmov_Component_DB::getAdmin ();
        $list = array();
		$result = array();

		$sql = " select * from costco_option where id = $1  and start_date::date <= now()::date and end_date::date >= now()::date ";
		$list = $db->executeQuery ( $sql, array (
			$id
		) );
        //2023/01/09 GiapLN imp ticket #SMT6-352
        $resSize = $list->size();
        if(empty($resSize)) {
            return array();
        }
		Sgmov_Component_Log::debug ( 'size=' . $list->size () );
		$result = $list->get(0);

		return $result;
	}

	/**
	 * コストコオプション料金マスタをオプション種別取得
	 * 未使用
	 *
	 * @param int $eventsubId
	 * @param int $type
	 * @return result
	 */
	private function selectOptionForCostcoByType($eventsubId, $type) {
		$db = Sgmov_Component_DB::getAdmin ();
        $list = array();
		$result = array();

		$sql = " select * from costco_option where eventsub_id = $1 and type = $2 and start_date::date <= now()::date and end_date::date >= now()::date ";
		$list = $db->executeQuery ( $sql, array (
			$eventsubId,
			$type,
		) );
        //2023/01/09 GiapLN imp ticket #SMT6-352
        $resSize = $list->size();
        if(empty($resSize)) {
            return array();
        }
        
		Sgmov_Component_Log::debug ( 'size=' . $list->size () );
		$result = $list->get(0);

		return $result;
	}



	/**
	 * コストコboxデータの取得
	 *
	 * @param [type] $comiket_id
	 * @return Sgmov_Component_DBResult
	 */
	private function selectComiketBoxForCostco($comiket_id) {
		$db = Sgmov_Component_DB::getAdmin ();
        $list = array();

		$sql = " select * from comiket_box where comiket_id = $1 order by box_id asc ";

		$list = $db->executeQuery ( $sql, array (
				$comiket_id
		) );
        
		Sgmov_Component_Log::debug ( 'size=' . $list->size () );

		return $list;
	}


	/**
	 * アルペンboxデータの取得
	 *
	 * @param [type] $comiket_id
	 * @return Sgmov_Component_DBResult
	 */
	private function selectAlpenBox($comiket_id) {
		$db = Sgmov_Component_DB::getAdmin();
        $list = array();

		$sql = "SELECT      * 
		        FROM        alpen_app_box A
		        LEFT JOIN   alpen_box     B
		        ON          A.box_id = B.id 
		        WHERE       A.app_id = $1 
		        ORDER BY    A.box_id ASC ";

		$list = $db->executeQuery($sql, array($comiket_id));
		Sgmov_Component_Log::debug ( 'size=' . $list->size () );

		return $list;
	}


	/**
	 * コミケ申込宅配データを取得
	 *
	 * @param int $comiket_id
	 * @param int $type
	 * @return Sgmov_Component_DBResult
	 */
	private function selectBox($comiket_id, $type, $comiket_id_sub = '') {
		$db = Sgmov_Component_DB::getAdmin ();
        $list = array();
        if (@empty($comiket_id_sub)) {
            $sql = "select * from comiket_box left join box on comiket_box.box_id = box.id where comiket_id = $1 and type = $2 order by box_id asc ";

            $list = $db->executeQuery ( $sql, array (
                    $comiket_id,
                    $type
            ) );
        } else {
            $sql = "select * from comiket_box left join box on comiket_box.box_id = box.id where comiket_id = $1 and type = $2 and comiket_id_sub = $3  order by box_id asc ";

            $list = $db->executeQuery ( $sql, array (
                    $comiket_id,
                    $type,
                    $comiket_id_sub
            ) );
        }

		Sgmov_Component_Log::debug ( 'size=' . $list->size () );

		return $list;
	}

	/**
	 * コミケ申込カーゴデータを取得
	 *
	 * @param int $comiket_id
	 * @param int $type
	 * @return Sgmov_Component_DBResult
	 */
	private function selectCargo($comiket_id, $type, $comiket_id_sub = '') {
		$db = Sgmov_Component_DB::getAdmin ();
        $list = array();
        if (@empty($comiket_id_sub)) {
            $sql = "select * from comiket_cargo where comiket_id = $1 and type = $2 order by num asc ";

            $list = $db->executeQuery ( $sql, array (
                    $comiket_id,
                    $type
            ) );
        } else {
            $sql = "select * from comiket_cargo where comiket_id = $1 and type = $2 and comiket_id = $3 order by num asc ";

            $list = $db->executeQuery ( $sql, array (
                    $comiket_id,
                    $type,
                    $comiket_id_sub
            ) );
        }

		Sgmov_Component_Log::debug ( 'size=' . $list->size () );

		return $list;
	}

	/**
	 * Comiketをセット
	 *
	 * @param object $comiket
	 * @return string
	 *
	 */
	private function setComiket(&$comiket) {
        
//        $db = Sgmov_Component_DB::getAdmin ();
//        
//        $sql = "select * from comiket_kanren where id = $1";
//		$list = $db->executeQuery ($sql, array($comiket['id']));
//        $comiketKanrenInfo = $list->get(0);
//        if ($list->size () == 0 || @empty($comiketKanrenInfo['oya_id'])) {
//            $oyaId = $comiket['id'];
//        } else {
//            $oyaId = $comiketKanrenInfo['oya_id'];
//        }
        Sgmov_Component_Log::debug ( $comiket );
		$d = @array (
				$comiket ['id'],
//                $comiket ['id_sub'],
				$comiket ['merchant_result'],
				$comiket ['merchant_datetime'] == null ? "" : date ( 'YmdHis', strtotime ( $comiket ['merchant_datetime'] ) ),
				$comiket ['receipted'] == null ? "" : date ( 'YmdHis', strtotime ( $comiket ['receipted'] ) ),
				$comiket ['payment_method_cd'],
				$comiket ['convenience_store_cd'],
				$comiket ['receipt_cd'],
				$comiket ['authorization_cd'],
				$comiket ['div'],
				$comiket ['event_id'],
				$comiket ['eventsub_id'],
				$comiket ['customer_cd'],
				$comiket ['office_name'],
				$comiket ['personal_name_sei'],
				$comiket ['personal_name_mei'],
				$comiket ['zip'],
				sprintf('%02d', $comiket ['pref_id']),
				$comiket ['address'],
				$comiket ['building'],
				$comiket ['tel'],
				$comiket ['mail'],
				$comiket ['booth_name'],
				$comiket ['building_name'],
				$comiket ['booth_position'],
				$comiket ['booth_num'],
				$comiket ['staff_sei'],
				$comiket ['staff_mei'],
				$comiket ['staff_sei_furi'],
				$comiket ['staff_mei_furi'],
				$comiket ['staff_tel'],
				$comiket ['choice'],
				$comiket ['amount'],
				$comiket ['amount_tax'],
				$comiket ['create_ip'],
				date ( 'YmdHis', strtotime ( $comiket ['created'] ) ),
				$comiket ['modify_ip'],
				date ( 'YmdHis', strtotime ( $comiket ['modified'] ) ),
				$comiket ['payment_order_id'],
				$comiket ['transaction_id'],
				$comiket ['bpn_type'],
//                $oyaId,
//                $comiket ['toiawase_no'],

				$comiket ['amount_kokyaku'],
				$comiket ['amount_tax_kokyaku'],
		);
        Sgmov_Component_Log::debug ( '############################## BCM 05-01' );
		// ダブルクォーテーションで囲んでつなげる
		$ret = '"H"';
		foreach ( $d as $item ) {
			$ret .= ',' . $this->escapeIFcsv ( $item );
		}
		$ret .= "\r\n";
        Sgmov_Component_Log::debug ( '############################## BCM 05-02' );
		return $ret;
	}

	/**
	 * ComiketDetailをセット
	 *
	 * @param array $detail
	 * @param array $comiket
	 * @param array $box
	 * @return string
	 */
	private function setComiketDetail(&$detail, $comiket, $box=array()) {

        // サービス選択を登録用に変換
        $setService=$detail['service'];
        // アルペンの場合
        if($detail['service']==8){
            $setService=1;
        }
        
        $timeFormat = "H";
        if ($detail['service'] == 4) {//ミルクラン
            $timeFormat = "Hi";
        }
        
        $eventKey = $comiket['event_key'];
        
		$d = array (
				$detail ['comiket_id'],
//                $detail ['comiket_id_sub'],
				$detail ['type'],
				$detail ['cd'],
				$detail ['name'],
				$detail ['hatsu_jis5code'],
				$detail ['hatsu_shop_check_code'],
				$detail ['hatsu_shop_check_code_eda'],
				$detail ['hatsu_shop_code'],
				$detail ['hatsu_shop_local_code'],
				$detail ['chaku_jis5code'],
				$detail ['chaku_shop_check_code'],
				$detail ['chaku_shop_check_code_eda'],
				$detail ['chaku_shop_code'],
				$detail ['chaku_shop_local_code'],
				$detail ['zip'],
				sprintf('%02d', $detail ['pref_id']),
				$detail ['address'],
				$detail ['building'],
				$detail ['tel'],
				$detail ['collect_date']     == null ? "" : date ( 'Ymd', strtotime ( $detail ['collect_date'] ) ),
				$detail ['collect_st_time']  == null ? "" : date ( 'H'  , strtotime ( $detail ['collect_st_time'] ) ),
				$detail ['collect_ed_time']  == null ? "" : date ( 'H'  , strtotime ( $detail ['collect_ed_time'] ) ),
				$detail ['delivery_date']    == null ? "" : date ( 'Ymd', strtotime ( $detail ['delivery_date'] ) ),
				$detail ['delivery_st_time'] == null ? "" : date ( "{$timeFormat}"  , strtotime ( $detail ['delivery_st_time'] ) ),
				$detail ['delivery_ed_time'] == null ? "" : date ( 'H'  , strtotime ( $detail ['delivery_ed_time'] ) ),
				$detail ['delivery_timezone_cd'],
				$detail ['delivery_timezone_name'],
				$setService,                        // 変換したサービス選択をセット
				$detail ['note'],
				$detail ['fare'],
				$detail ['fare_tax'],
				$detail ['cost'],
				$detail ['cost_tax'],
                $detail ['binshu_kbn'],
                $detail ['toiawase_no'],
                $detail ['toiawase_no_niugoki'],
//                $detail ['azukari_kaisu_type'],
//                $detail ['azukari_toriatsukai_type'],
				$detail ['kokyaku_futan_flg'], // 顧客負担フラグ => 1:登録者全額負担、2:顧客全額負担(D24) // TODO: 未対応
				$detail ['fare_kokyaku'],
				$detail ['fare_tax_kokyaku'],
				$detail ['sagyo_jikan'], // TODO: 未対応
		);

        // サービス選択で記事欄の項目を取得する
        // 記事欄追加：記事欄1～6
        switch($detail['service']){
            // コストコ
            case '7':
                // boxから連携する情報を抽出
                $shohinCd  ='';
                $shohinName='';
                $sagyoName ='';
                $kaidanName='';

                foreach($box as $k=>$v){
                    // 商品情報を取得：6：通常商品、7：顧客請求商品(D24)の場合
                    if($v['type']==6 || $v['type']==7){
                        $shohinCd  = $v['shohin_cd'];
                        $shohinName= $v['note1'];
                    }
                    if($v['type']==8){
                        // detail.作業時間合計+付帯作業名を取得：8：オプションで商品CDがカラの場合
                        if(empty($v['shohin_cd'])){
                            $sagyoName = $detail['sagyo_jikan'].'分　'.$v['note1'];
                        }
                        // 階段上げ作業名+作業費を取得：8：オプションで商品CDがA,Bの場合
                        if(!empty($v['shohin_cd'])){
                            //コストコ明細データの記事６の作業費金額（税込）にしてください
                            //$zeinuki    = $v['cost_amount_tax']/1.10;
                            //$kaidanName = $v['note1'].''.$zeinuki.'円';
                            $costAmoutTaxFormat = isset($v['cost_amount_tax']) ? number_format($v['cost_amount_tax']) : $v['cost_amount_tax'];
                            $kaidanName = $v['note1'].''. $costAmoutTaxFormat.'円';
                        }
                    }
                }
                 // comiket.個人お名前姓＋　＋個人お名前名
                $d[]=$this->cropStr($comiket['personal_name_sei'].'　'.$comiket['personal_name_mei']);
                 // comiket.電話番号⇒comiket_detail.配送先電話番号
                //$d[]=$comiket['tel'];
                $d[]= $detail ['tel'];
                // box.商品コード
                $d[]=$this->cropStr($shohinCd);
                // box.商品名
                $d[]=$this->cropStr($shohinName);
                // box.作業時間(分)+作業名
                $d[]=$this->cropStr($sagyoName);
                // box.作業名(階段上げ)+作業金額(税抜)
                $d[]=$this->cropStr($kaidanName);
                
                //記事欄7～//記事欄10
                $d[]='';
                $d[]='';
                $d[]='';
                $d[]='';
            break;
            // アルペン
            case '8':
                // detail.記事欄1～4
                $d[]=$this->cropStr($detail['kijiran1']);
                $d[]=$this->cropStr($detail['kijiran2']);
                $d[]=$this->cropStr($detail['kijiran3']);
                $d[]=$this->cropStr($detail['kijiran4']);
                $d[]='';
                $d[]='';
                //記事欄7～//記事欄10
                $d[]='';
                $d[]='';
                $d[]='';
                $d[]='';
            break;
            // 上記以外
            default:
                if (strtolower($eventKey) == self::UNAZUKI_SHIKIBETU) { //宇奈月温泉の場合、記事欄１～記事欄２                   
                   if ($detail['type'] == self::COMIKET_DETAIL_TYPE_FUKURO) {
                        //記事欄１
                        $d[]='';
                       //記事欄2：宿泊先名（往路と復路が同じ）
                       $d[]=mb_convert_kana($this->cropStr($comiket['building_name']), 'A');
                       //記事欄3：復路の場合、復路集荷日
                       $d[]=mb_convert_kana($this->cropStr($detail['collect_date']), 'A');
                   } else {
                        //記事欄１
                       $customerCd = mb_substr($comiket['customer_cd'], 0 , 8) . "―" . mb_substr($comiket['customer_cd'], 8 , 3);
                        $d[]=mb_convert_kana('請求先：' . $this->cropStr($customerCd), 'A');
                       //記事欄2：宿泊先名（往路と復路が同じ）
                       $d[]=mb_convert_kana($this->cropStr($comiket['building_name']), 'A');
                       //記事欄3：往路の場合、宿泊日（引渡希望日）
                       $d[]=mb_convert_kana($this->cropStr($detail['delivery_date']), 'A');
                   }
                   //その以外は空白
                   $d[]='';
                   $d[]='';
                   $d[]='';
                   //記事欄7～//記事欄10
                   $d[]='';
                   $d[]='';
                   $d[]='';
                   $d[]='';
                } elseif (strtolower($eventKey) == self::MIRUKURAN_SHIKIBETU){ //ミルクランの場合
                    //発着情報を取得する
                    if ($detail['type'] == self::COMIKET_DETAIL_TYPE_FUKURO) {
                        $chakuCd = $detail['mlk_hachaku_shikibetu_cd'];
                        $chakuInfo = $this->selectHachakuMst($chakuCd);
                    } else {
                        $detailList = $this->selectDetail($detail ['comiket_id'],  @$comiket['id_sub'], @$comiket['event_key']);
                        $fukuro = $detailList->get(1);
                        $chakuCd = $fukuro['mlk_hachaku_shikibetu_cd'];
                        $chakuInfo = $this->selectHachakuMst($chakuCd);                        
                    }
                    $hastuCd = mb_substr($detail['cd'], 0, 8);
                    $hastuInfo = $this->selectHachakuMst($hastuCd);
                    
                    if ($detail['mlk_hachaku_type_cd'] == self::MIRUKURAN_AIRPORT && $detail['type'] == self::COMIKET_DETAIL_TYPE_FUKURO) {
                        $deliveryDateTime =  date ( 'Y/m/d H:i', strtotime ($detail ['delivery_date'] . $detail ['delivery_st_time']));
                        //記事欄1:detail.お届け日 + お届け開始時刻
                        $d[]=$this->cropStr($deliveryDateTime);//飛行機出発日時
                        //記事欄2:detail.備考
                        $d[]=$this->cropStr($detail['mlk_bin_nm']); 
                        //記事欄3:comiket.担当者姓+　+担当者名
                        $d[]=$this->cropStr($comiket['staff_sei'].'　'.$comiket['staff_mei']);
                        //記事欄4:comiket.担当者電話番号
                        $d[]=$comiket['staff_tel'];
                        //記事欄5
                        $d[]=$this->cropStr($detail['note']);
                        //記事欄6
                        $d[]= mb_substr($comiket['mail'], 0, 32);
                    } else {
                        if ($fukuro['mlk_hachaku_type_cd'] == self::MIRUKURAN_AIRPORT) {
                            $deliveryDateTime =  date ( 'Y/m/d H:i', strtotime ($fukuro ['delivery_date'] . $fukuro ['delivery_st_time']));
                            //記事欄1:detail.お届け日 + お届け開始時刻
                            $d[]=$this->cropStr($deliveryDateTime);//飛行機出発日時
                            //記事欄2:detail.備考
                            $d[]=$this->cropStr($fukuro['mlk_bin_nm']); 
                        } else {
                            //記事欄1
                            $d[]= '';
                            //記事欄2
                            $d[]= '';
                        }
                        //記事欄3:comiket.担当者姓+　+担当者名
                        $d[]=$this->cropStr($comiket['staff_sei'].'　'.$comiket['staff_mei']);
                        //記事欄4:comiket.担当者電話番号
                        $d[]=$comiket['staff_tel'];
                        //記事欄5:detail.備考
                        $d[]=$this->cropStr($detail['note']);
                        //記事欄6
                        $d[]= mb_substr($comiket['mail'], 0, 32);
                    }
                    //記事欄7:発記号
                    $d[]= $hastuCd;
                    //記事欄8:発名称（日本語）
                    $d[]= isset($hastuInfo) ? $hastuInfo[0]['name_jp'] : "";
                    //記事欄9:着記号
                    $d[]= $chakuCd;
                    //記事欄10:着名称（日本語）
                    $d[]= isset($chakuInfo) ? $chakuInfo[0]['name_jp'] : "";
                } else {
                    //comiket.ブース名
                    $d[]=$this->cropStr($comiket['building_name']);
                    //comiket.ブース位置+ブース番号
                    $d[]=$this->cropStr($comiket['booth_position'].$comiket['booth_num']);
                    //comiket.担当者姓+　+担当者名
                    $d[]=$this->cropStr($comiket['staff_sei'].'　'.$comiket['staff_mei']);
                    //comiket.担当者電話番号
                    $d[]=$comiket['staff_tel'];
                    //detail.備考
                    $d[]=$this->cropStr($detail['note']);
                    //detail.便種区分：1:▲飛脚クール便（冷蔵）/ 2:●飛脚クール便（冷凍）
                    $binshuName='';
                    if($detail['note']==1){
                        $binshuName='▲飛脚クール便（冷蔵）';
                    }
                    else if($detail['note']==2){
                        $binshuName='●飛脚クール便（冷凍）';
                    }
                    $d[]=$binshuName;

                   //記事欄7～//記事欄10
                   $d[]='';
                   $d[]='';
                   $d[]='';
                   $d[]='';
                }
            break;
        }
        
        //ミルクラン_発着選択
        $d[] = $detail ['mlk_hachaku_type_cd'];
        //ミルクラン_発着地識別番号
        $d[] = $detail ['mlk_hachaku_shikibetu_cd'];

		// ダブルクォーテーションで囲んでつなげる
		$ret = '"M"';
		foreach ( $d as $item ) {
			$ret .= ',' . $this->escapeIFcsv ( $item );
		}
		$ret .= "\r\n";

		return $ret;
	}

	/**
	 * ComiketBoxをセット
	 *
	 * @param object $box
	 * @return string
	 */
	private function setComiketBox(&$box) {
        // 家具・家電区分 空文字で設定
        $kaguKadenKbn='';
        $optionKbn = '';
		$d = array (
				$box ['comiket_id'],
//                $box ['comiket_id_sub'],
				$box ['type'],
				empty ( $box ['name'] ) ? '0' : @strip_tags($box ['name']),
				empty ( $box ['size'] ) ? '0' : $box ['size'],
				$box ['num'],
				$box ['fare_price'],
				$box ['fare_amount'],
				$box ['fare_price_tax'],
				$box ['fare_amount_tax'],
				$box ['cost_price'],
				$box ['cost_amount'],
				$box ['cost_price_tax'],
				$box ['cost_amount_tax'],
				$box ['ziko_shohin_cd'],
				///////////////////////////////////////////////
				@$box ['fare_price_kokyaku'],
				@$box ['fare_amount_kokyaku'],
				@$box ['fare_price_tax_kokyaku'],
				@$box ['fare_amount_tax_kokyaku'],
				@$box ['sagyo_jikan'],
				@$box ['shohin_cd'],
				$kaguKadenKbn,
                $optionKbn,
		);

		// ダブルクォーテーションで囲んでつなげる
		$ret = '"T"';
		foreach ( $d as $item ) {
			$ret .= ',' . $this->escapeIFcsv ( $item );
		}
		$ret .= "\r\n";

		return $ret;
	}


	/**
	 * ComiketBoxをセット
	 *
	 * @param object $box
	 * @return string
	 */
	private function setShohin(&$box) {
        // 家具・家電区分 空文字で設定
        $kaguKadenKbn='';
        $optionKbn = '';
		$d = array (
				$box ['comiket_id'],
//                $box ['comiket_id_sub'],
				$box ['type'],
				empty ( $box ['name'] ) ? '0' : @strip_tags($box ['name']),
				empty ( $box ['size'] ) ? '0' : $box ['size'],
				$box ['num'],
				$box ['fare_price'],
				$box ['fare_amount'],
				$box ['fare_price_tax'],
				$box ['fare_amount_tax'],
				$box ['cost_price'],
				$box ['cost_amount'],
				$box ['cost_price_tax'],
				$box ['cost_amount_tax'],
				$box ['ziko_shohin_cd'],

				///////////////////////////////////////////////
				@$box ['fare_price_kokyaku'],
				@$box ['fare_amount_kokyaku'],
				@$box ['fare_price_tax_kokyaku'],
				@$box ['fare_amount_tax_kokyaku'],
				@$box ['sagyo_jikan'],
				@$box ['shyohin_cd'],
				$kaguKadenKbn,
                $optionKbn,
		);

		// ダブルクォーテーションで囲んでつなげる
		$ret = '"T"';
		foreach ( $d as $item ) {
			$ret .= ',' . $this->escapeIFcsv ( $item );
		}
		$ret .= "\r\n";

		return $ret;
	}

	/**
	 * コストコboxデータを生成
	 *
	 * @param object $box
	 * @return string
	 */
	private function setBoxForCostco(&$box) {
        Sgmov_Component_Log::debug('##################### setBoxForCostco 0');
        // 家具・家電区分
        $kaguKadenKbn='';
        $optionKbn = '';
		if ($box['type'] == '6' || $box['type'] == '7') { // 6:通常商品、7:D24商品
            // 商品情報取得
			$shohinInfo = $this->selectShohinForCostco($box['shohin_cd']);
            //2023/01/10 GiapLN imp ticket #SMT6-352
            if (empty($shohinInfo)) {
                throw new Exception('table costco_shohin has empty data with shohin_cd = ['.$box['shohin_cd'].']');
            }
			$box['name'] = $box['note1'];
			$box['size'] = $shohinInfo['size'];

            // 家具・家電区分：商品のoption_idから決定
            switch($shohinInfo['option_id']){
                // 家具設置、8888は未使用：家具便種(553)扱い
                case 8:
                case 8888:
                    $kaguKadenKbn='0';
                    break;
                // それ以外：家電便種扱い(554)
                default:
                    $kaguKadenKbn='1';
                    break;
            }
		}

        Sgmov_Component_Log::debug('##################### setBoxForCostco 0-1');
		if ($box['type'] == '8' && $box['shohin_cd'] != 'A' && $box['shohin_cd'] != 'B') { // 8:オプション(通常)
			$box['name'] = $box['note1'];
			$box['size'] = '0';
            
            //2022/09/14 冷蔵庫のD24対応
            $shohinInfo = $this->selectShohinForCostcoByComiketId($box['comiket_id']);
            //画面ではオプション無しを選ぶ場合、設定しない。
            if (isset($shohinInfo['option_id']) && $box['sagyo_jikan'] > 0) {
                $optionKbn = $shohinInfo['option_id'];
            }
		}
        Sgmov_Component_Log::debug('##################### setBoxForCostco 0-2');
		if ($box['type'] == '8' && ($box['shohin_cd'] == 'A' || $box['shohin_cd'] == 'B')) { // 8:オプション(階段)
			$box['name'] = $box['note1'];
			$box['size'] = '0';
		}
        Sgmov_Component_Log::debug('##################### setBoxForCostco 0-3');
		if ($box['type'] == '9') { // 9:リサイクル
			$box['name'] = $box['note1'];
			$box['size'] = '0';
		}
        Sgmov_Component_Log::debug('##################### setBoxForCostco 1');

        Sgmov_Component_Log::debug($box);
		$d = @array (
			$box ['comiket_id'],
			$box ['type'],
			//empty ( $box ['name'] ) ? '0' : @strip_tags($box ['name']),
            empty ( $box ['name'] ) ? '0' : Sgmov_Lib::subbyte(@strip_tags($box ['name']),100),//100バイ対応
			empty ( $box ['size'] ) ? '0' : @ceil($box ['size']),
			$box ['num'],
			$box ['fare_price'],
			$box ['fare_amount'],
			$box ['fare_price_tax'],
			$box ['fare_amount_tax'],
			$box ['cost_price'],
			$box ['cost_amount'],
			$box ['cost_price_tax'],
			$box ['cost_amount_tax'],
			$box ['ziko_shohin_cd'] == null ? "" : $box ['ziko_shohin_cd'],

			$box ['fare_price_kokyaku'],
			$box ['fare_amount_kokyaku'],
			$box ['fare_price_tax_kokyaku'],
			$box ['fare_amount_tax_kokyaku'],
			$box ['sagyo_jikan'] == null ? "" : $box ['sagyo_jikan'],
			$box ['shohin_cd'],
			$kaguKadenKbn,
            $optionKbn,
		);
        Sgmov_Component_Log::debug('##################### setBoxForCostco 2 csv array');
        Sgmov_Component_Log::debug($d);
		// ダブルクォーテーションで囲んでつなげる
		$ret = '"T"';
		foreach ( $d as $item ) {
			$ret .= ',' . $this->escapeIFcsv ( $item );
		}
		$ret .= "\r\n";
        Sgmov_Component_Log::debug('##################### setBoxForCostco 3');
		return $ret;
	}

	/**
	 * アルペンboxデータを生成
	 * 2022/04/19現在コストコと同じ形式
	 *
	 * @param object $box
	 * @return string
	 */
	private function setAlpenBox(&$box) {
        Sgmov_Component_Log::debug('##################### setBoxForAlpen 0');
        // 家具・家電区分
        $kaguKadenKbn='';
        $optionKbn = '';
		if ($box['type'] == '6' || $box['type'] == '7') { // 6:通常商品、7:D24商品
			// 商品情報取得
			$shohinInfo = $this->selectShohinForAlpen($box['shohin_cd']);
			$box['name'] = $box['note1'];
			$box['size'] = $shohinInfo['size'];

            // 家具・家電区分：商品のoption_idから決定
            switch($shohinInfo['option_id']){
                // 家具設置、8888は未使用：家具便種(553)扱い
                case 8:
                case 8888:
                    $kaguKadenKbn='0';
                    break;
                // それ以外：家電便種扱い(554)
                default:
                    $kaguKadenKbn='1';
                    break;
            }
		}
		if ($box['type'] == '8' && $box['shohin_cd'] != 'A' && $box['shohin_cd'] != 'B') { // 8:オプション(通常)
			$box['name'] = $box['note1'];
			$box['size'] = '0';
		}
		if ($box['type'] == '8' && ($box['shohin_cd'] == 'A' || $box['shohin_cd'] == 'B')) { // 8:オプション(階段)
			$box['name'] = $box['note1'];
			$box['size'] = '0';
		}
		if ($box['type'] == '9') { // 9:リサイクル
			$box['name'] = $box['note1'];
			$box['size'] = '0';
		}

		Sgmov_Component_Log::debug($box);
		$d = @array (
			$box ['app_id'],
			$box ['type'],
			empty ( $box ['name'] ) ? '0' : @strip_tags($box ['name']),
			empty ( $box ['size'] ) ? '0' : @ceil($box ['size']),
			$box ['num'],
			$box ['fare_price'],
			$box ['fare_amount'],
			$box ['fare_price_tax'],
			$box ['fare_amount_tax'],
			$box ['cost_price'],
			$box ['cost_amount'],
			$box ['cost_price_tax'],
			$box ['cost_amount_tax'],
			$box ['ziko_shohin_cd'] == null ? "" : $box ['ziko_shohin_cd'],

			$box ['fare_price_kokyaku'],
			$box ['fare_amount_kokyaku'],
			$box ['fare_price_tax_kokyaku'],
			$box ['fare_amount_tax_kokyaku'],
			$box ['sagyo_jikan'] == null ? "" : $box ['sagyo_jikan'],
			$box ['shohin_cd'],
			$kaguKadenKbn,
            $optionKbn,
		);
        Sgmov_Component_Log::debug('##################### setBoxForCostco 2');
		// ダブルクォーテーションで囲んでつなげる
		$ret = '"T"';
		foreach ( $d as $item ) {
			$ret .= ',' . $this->escapeIFcsv ( $item );
		}
		$ret .= "\r\n";
        Sgmov_Component_Log::debug('##################### setBoxForCostco 3');
		return $ret;
	}

	/**
	 * ComiketCargoをセット
	 *
	 * @param object $cargo
	 * @return string
	 */
	private function setComiketCargo(&$cargo) {
		$d = array (
				$cargo ['comiket_id'],
//                $cargo ['comiket_id_sub'],
				$cargo ['type'],
				500,
				$cargo ['num'],
				$cargo ['fare_amount']
		);

		// ダブルクォーテーションで囲んでつなげる
		$ret = '"C"';
		foreach ( $d as $item ) {
			$ret .= ',' . $this->escapeIFcsv ( $item );
		}
		$ret .= "\r\n";

		return $ret;
	}

	/**
	 * 値に対して、IFcsv用のエスケープ処理を行う
	 *
	 * @param string $s
	 * @return string $s
	 */
	private function escapeIFcsv($s) {
		$s = str_replace ( "\r\n", "\n", $s ); // 改行コードを統一
		$s = str_replace ( "\r", "\n", $s ); // 改行コードを統一
		$s = str_replace ( "\n", '\r\n', $s ); // 改行コードを統一
		$s = str_replace ( '\\', '\\\\', $s ); // \→\\に置換
		$s = str_replace ( ",", "\\,", $s ); // ,→\,に置換
		$s = str_replace ( '"', '\"', $s ); // "→\"に置換
		$s = '"' . $s . '"';
		$s = mb_convert_encoding ( $s, 'SJIS-win', 'UTF-8' );

		return $s;
	}

	/**
	 * DB値からキャンセル用csvファイル作成
	 *
	 * @param object $comiket
	 * @return string
	 */
	public function makeIFcancel(&$comiket) {
		Sgmov_Component_Log::debug ( $comiket );
		$csv = "";
		$csv .= "\"HEADER\"";
		$csv .= "\r\n";
		$csv .= '"H"';
		$csv .= ',';
		$csv .= $this->escapeIFcsv ($comiket ['id']);
		$csv .= "\r\n";
		$csv .= "\"TRAILER\"";
		return $csv;
	}

	/**
	 * 文字列を指定バイト数以内に切り出す
	 *
	 * @param str $str
	 * @param int $byteLen
	 * @return string
	 */
	public function cropStr($str, $byteLen=32){
        // 半角1byte、全角2byteで切り出します
        $byteNum=0;
        $cropStr='';
        foreach(preg_split('/(?<!^)(?!$)/u',$str) as $v){
            // 2byte以上は全角扱い
            if(strlen($v)>1){
                $byteNum+=2;
            }
            // 半角
            else{
                $byteNum+=1;
            }
            $cropStr.=$v;
            if($byteNum==32 || $byteNum==31){
                break;
            }
        }
        return $cropStr;
	}

	/**
	 * 発着地マスタデータを取得
	 *
	 * @param string $hachakuten_shikibetu_cd
	 * @return Sgmov_Component_DBResult
	 */
	private function selectHachakuMst($hachakuten_shikibetu_cd) {
		$db = Sgmov_Component_DB::getAdmin ();
        $sql = "SELECT * FROM mlk_hachakuten_mst WHERE hachakuten_shikibetu_cd = $1";
        $result = $db->executeQuery($sql, array($hachakuten_shikibetu_cd));
        $resSize = $result->size();
        if(empty($resSize)) {
            return array();
        }

        for($i=0;$i<$resSize;$i++){
            $dataInfo[]=$result->get($i);
        }
        return $dataInfo;
	}
}