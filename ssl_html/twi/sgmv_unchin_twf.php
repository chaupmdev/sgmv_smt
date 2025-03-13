<?php
// 料金
$src='		43	40	36	33	26	16	22	20	13	04	02	01	#N/A	47
		45	41	37	34	25	17	23	15	14	06	05	#N/A	#N/A	#N/A
		46	44	39	35	29	18	21	#N/A	12	07	03	#N/A	#N/A	#N/A
		#N/A	42	38	31	27	#N/A	24	#N/A	11	#N/A	#N/A	#N/A	#N/A	#N/A
		#N/A	#N/A	#N/A	32	28	#N/A	#N/A	#N/A	08	#N/A	#N/A	#N/A	#N/A	#N/A
		#N/A	#N/A	#N/A	#N/A	30	#N/A	#N/A	#N/A	10	#N/A	#N/A	#N/A	#N/A	#N/A
		#N/A	#N/A	#N/A	#N/A	#N/A	#N/A	#N/A	#N/A	09	#N/A	#N/A	#N/A	#N/A	#N/A
		#N/A	#N/A	#N/A	#N/A	#N/A	#N/A	#N/A	#N/A	19	#N/A	#N/A	#N/A	#N/A	#N/A
13	60	1210	1210	1100	990	880	770	770	770	770	770	880	1210		1914
13	80	1485	1485	1375	1265	1155	1045	1045	1045	1045	1045	1155	1485		3520
13	100	1826	1826	1716	1606	1496	1386	1386	1386	1386	1386	1496	1826		4686
13	140	2288	2288	2178	2068	1958	1848	1848	1848	1848	1848	1958	2288		7579
13	160	2508	2508	2398	2288	2178	2068	2068	2068	2068	2068	2178	2508		10560
13	170	3960	3630	3410	3410	3135	3135	3135	3135	2420	3190	3410	3960		15400
13	180	4400	4015	3740	3740	3410	3410	3410	3410	2695	3520	3740	4455		17820
13	200	5500	5005	4565	4565	4180	4180	4180	4180	3245	4290	4565	5500		22660
13	220	6545	5940	5445	5445	4895	4895	4895	4895	3795	5060	5390	6985		27500
13	240	8690	7810	7095	7095	6380	6380	6380	6380	4895	6600	7095	8745		37180
13	260	10835	9680	8800	8800	7865	7865	7865	7865	5995	8140	8800	10945		46860
13	スーツケース	2563	2563	2453	2343	2233	2123	2123	2123	2123	2123	2233	2563		7854

';
//SEQUENCE開始
$seq=112837;

// イベントサブID
$eventsub_id=2201;

// box_cd
$box_cd=array
(
 '60' =>'2'
,'80' =>'3'
,'100'=>'4'
,'140'=>'5'
,'160'=>'6'
,'170'=>'7'
,'180'=>'8'
,'200'=>'9'
,'220'=>'10'
,'240'=>'11'
,'260'=>'12'
,'スーツケース'=>'1'
);




// 配列にバラす
$tmpArr1=explode("\n",$src);
foreach(explode("\n",$src) as $v){
    $tmpArr[]=explode("\t",$v);
}

// $tmpArr[0][0]～[7][15]は都道府県。数字以外は無視
// $tmpArr[8][0],[8][1]～$tmpArr[19][0],[19][1]はサイズと料金
// 都道府県を取得する
for($i=0;$i<=7;$i++){
    foreach($tmpArr[$i] as $k=>$v){
        if($v>0 && $i<=47){
            $pref[$v]= $k;
        }
    }
}


$t="\t";

// テーブルデータコピペ版 /////////////////////////////////////////////
// 往路データ：ボックスサイズ+料金+都道府県で配列生成
for($i=8;$i<=19;$i++){
    foreach($pref as $k=>$v){
        $box[]= $seq.$t                     // シーケンス
                .str_replace("\r","",$k).$t // 発 各都道府県CD
                .$tmpArr[$i][0].$t          // 着 (東京)都道府県CD
                .$box_cd[$tmpArr[$i][1]].$t // boxサイズ→box_cd
                .str_replace("\r","",$tmpArr[$i][$v],$k).$t         // 料金
                .date('Y/m/d').$t           // 登録日
                .date('Y/m/d').$t           // 更新日
                .$eventsub_id               // eventsub_id
;
        $seq++;
    }
}

// 復路データ：ボックスサイズ+料金+都道府県で配列生成
for($i=8;$i<=19;$i++){
    foreach($pref as $k=>$v){
        $box[]= $seq.$t                     // シーケンス
                .$tmpArr[$i][0].$t          // 発 (東京)都道府県CD
                .str_replace("\r","",$k).$t // 着 各都道府県CD
                .$box_cd[$tmpArr[$i][1]].$t // boxサイズ→box_cd
                .str_replace("\r","",$tmpArr[$i][$v],$k).$t         // 料金
                .date('Y/m/d').$t           // 登録日
                .date('Y/m/d').$t           // 更新日
                .$eventsub_id               // eventsub_id
;
        $seq++;
    }
}

foreach($box as $v){
//    echo("<pre>".$v."<pre>");
}
unset($box);
///////////////////////////////////////////////
// クエリ生成版 /////////////////////////////////////////////
// 往路データ：ボックスサイズ+料金+都道府県で配列生成
for($i=8;$i<=19;$i++){
    foreach($pref as $k=>$v){
        $box[]= '' //$seq.$t                     // シーケンス
                .str_replace("\r","",$k).$t // 発 各都道府県CD
                .$tmpArr[$i][0].$t          // 着 (東京)都道府県CD
                .$box_cd[$tmpArr[$i][1]].$t // boxサイズ→box_cd
                .str_replace("\r","",$tmpArr[$i][$v],$k).$t         // 料金
                .date('Y/m/d').$t           // 登録日
                .date('Y/m/d').$t           // 更新日
                .$eventsub_id               // eventsub_id
;
        $seq++;
    }
}

// 復路データ：ボックスサイズ+料金+都道府県で配列生成
for($i=8;$i<=19;$i++){
    foreach($pref as $k=>$v){
        $box[]= '' //$seq.$t                     // シーケンス
                .$tmpArr[$i][0].$t          // 発 (東京)都道府県CD
                .str_replace("\r","",$k).$t // 着 各都道府県CD
                .$box_cd[$tmpArr[$i][1]].$t // boxサイズ→box_cd
                .str_replace("\r","",$tmpArr[$i][$v],$k).$t         // 料金
                .date('Y/m/d').$t           // 登録日
                .date('Y/m/d').$t           // 更新日
                .$eventsub_id               // eventsub_id
;
        $seq++;
    }
}
// 出力：
//select * from box_id_seq

$sql="SELECT setval('box_fare_id_seq', (SELECT MAX(id) FROM box_fare));<br>";
$sql.='INSERT INTO box_fare(hatsu_jis2  , chaku_jis2  , box_id  , fare  , created  , modified  , eventsub_id) VALUES
<br>';

foreach($box as $v){
//    echo("<pre>".$v."<pre>");
    $sql.=str_replace("\t","','", "('".$v."'),<br>");
}

///////////////////////////////////////////////


echo(substr($sql,0,-5).';');
// <script>alert("Excelでコピペする場合は\n1ケタ都道府県コードが0埋めになっていることを\n確認すること")</script>
?>

