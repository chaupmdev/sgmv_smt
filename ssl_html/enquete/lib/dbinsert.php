<?php

    include 'DB.php';
    include '../lib/checkdb.php';
    
    $q1 = isset($_POST['q1'])  ? $_POST['q1']  : "";
    $q2 = isset($_POST['q2'])  ? $_POST['q2']  : "";
    $q3 = isset($_POST['q3'])  ? $_POST['q3']  : "";
    $q4 = isset($_POST['q4'])  ? $_POST['q4']  : "";
    $q5 = isset($_POST['q5'])  ? $_POST['q5']  : "";
    
    $q6 = isset($_POST['q6'])  ? $_POST['q6']  : "";
    $q7 = isset($_POST['q7'])  ? $_POST['q7']  : "";
    $q8 = isset($_POST['q8'])  ? $_POST['q8']  : "";
    $q9 = isset($_POST['q9'])  ? $_POST['q9']  : "";
    $q10 = isset($_POST['q10'])  ? $_POST['q10']  : "";
    
    $q11 = isset($_POST['q11'])  ? $_POST['q11']  : "";
    
    $q12 = isset($_POST['q12'])  ? $_POST['q12']  : "";
    $q12a = isset($_POST['q12a'])  ? $_POST['q12a']  : "";
    $q12b = isset($_POST['q12b'])  ? $_POST['q12b']  : "";
    $q12c = isset($_POST['q12c'])  ? $_POST['q12c']  : "";
    $q12d = isset($_POST['q12d'])  ? $_POST['q12d']  : "";
    
    $ptp = isset($_POST['ptp'])  ? $_POST['ptp']  : "";
    $kbn = isset($_POST['kbn'])  ? $_POST['kbn']  : "";
    $param = isset($_POST['param']) ? $_POST['param'] : "";

    $param = substr($param, 0, -1);//チェックデジットを外す
    
    $array_setti    = array('031', '032', '033', '034', '035' , '036', '037', '038', '039', '040' ,'041','042');
    $array_hikkoshi = array('043', '044', '045', '046', '047' , '048', '049', '050', '051', '052' ,'053','054');
    

    
    if($ptp == 0){//スマホ
        $arrayQ[] = $q1;
        $arrayQ[] = $q2;
        $arrayQ[] = $q3;
        $arrayQ[] = $q4;
        $arrayQ[] = $q5;
        $arrayQ[] = $q6;
        $arrayQ[] = $q7;
        $arrayQ[] = $q8;
        $arrayQ[] = $q9;
        $arrayQ[] = $q10;
        $arrayQ[] = $q11;
        
        //if($kbn == 0){//設置
            //$arrayQ[] = $q12;
        //}
        
        if($q12a == 1){
            //半角/→全角／
            $q12b = str_replace('/', '／', $q12b);
            $q12c = str_replace('/', '／', $q12c);
            $q12d = str_replace('/', '／', $q12d);
    
           $arrayQ[] = $q12a . '/' . $q12b . '/' .$q12c . '/' . $q12d; 
        }
        
    }
    elseif($ptp == 1){//ガラケー
        $arrayQ[] = mb_convert_encoding($q1,"UTF-8","sjis-win");
        $arrayQ[] = mb_convert_encoding($q2,"UTF-8","sjis-win");
        $arrayQ[] = mb_convert_encoding($q3,"UTF-8","sjis-win");
        $arrayQ[] = mb_convert_encoding($q4,"UTF-8","sjis-win");
        $arrayQ[] = mb_convert_encoding($q5,"UTF-8","sjis-win");
        $arrayQ[] = mb_convert_encoding($q6,"UTF-8","sjis-win");
        $arrayQ[] = mb_convert_encoding($q7,"UTF-8","sjis-win");
        $arrayQ[] = mb_convert_encoding($q8,"UTF-8","sjis-win");
        $arrayQ[] = mb_convert_encoding($q9,"UTF-8","sjis-win");
        $arrayQ[] = mb_convert_encoding($q10,"UTF-8","sjis-win");
        $arrayQ[] = mb_convert_encoding($q11,"UTF-8","sjis-win");
        
        //if($kbn == 0){//設置
            //$arrayQ[] = mb_convert_encoding($q12,"UTF-8","sjis-win");
        //}

        if($q12a == 1){
           $arrayQ[] = mb_convert_encoding($q12a,"UTF-8","sjis-win") 
                   . '/' . str_replace('/', '／', mb_convert_encoding($q12b,"UTF-8","sjis-win")) 
                   . '/' . str_replace('/', '／', mb_convert_encoding($q12c,"UTF-8","sjis-win")) 
                   . '/' . str_replace('/', '／', mb_convert_encoding($q12d,"UTF-8","sjis-win")); 
        }
    }
    
    $sql  = ' INSERT INTO enquete ';
    $sql .= ' ( ';
    $sql .= '  enq_type ';      //アンケート種類
    $sql .= ' ,phone_type ';    //携帯タイプ
    if($kbn == 0){//設置
    $sql .= ' ,sagyoirai_no ';  //作業依頼番号
    }elseif($kbn == 1){//引越
    $sql .= ' ,uketsuke_no ';   //受付番号
    }
    $sql .= ' ,created ';       //登録日時
    $sql .= ' ,modified ';      //更新日時
    $sql .= ' ) VALUES ( ';
    $sql .= '  :enq_type ';
    $sql .= ' ,:phone_type ';
    if($kbn == 0){//設置
    $sql .= ' ,:sagyoirai_no ';
    }elseif($kbn == 1){//引越
    $sql .= ' ,:uketsuke_no ';
    }
    $sql .= ' ,NOW() ';
    $sql .= ' ,NOW() ';
    $sql .= ' ) ';
 
    $stmt = $con->prepare($sql);
 
    $stmt->bindValue(':enq_type', $kbn);
    $stmt->bindValue(':phone_type', $ptp);
    if($kbn == 0){//設置
    $stmt->bindValue(':sagyoirai_no', $param);
    }elseif($kbn == 1){//引越
    $stmt->bindValue(':uketsuke_no', $param);
    }
 
    try {
      $stmt->execute();
    } catch (PDOException $e) {
      die('insert failed: '.$e->getMessage());
    }
    

    
    if($kbn == 0){//設置
    $stmt = $con->prepare('SELECT id FROM enquete WHERE sagyoirai_no = :sno limit 1');
    }elseif($kbn == 1){//引越
    $stmt = $con->prepare('SELECT id FROM enquete WHERE uketsuke_no = :sno limit 1');
    }
    $stmt->bindValue(':sno', $param);
    $flag = $stmt->execute();
    
    if (!$flag) {
      $info = $stmt->errorInfo();
      exit($info[2]);
    }

    //トランザクションid
    $trzid = -1;
    
    while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $trzid = $data['id'];
    }
    
    //アンケート明細データインサート
    $sql  = ' INSERT INTO enquete_meisai ';
    $sql .= ' ( ';
    $sql .= '  id ';        //トランザクションID
    $sql .= ' ,enq_id ';    //アンケートID
    $sql .= ' ,answer ';    //回答値
    $sql .= ' ,created ';   //登録日時
    $sql .= ' ,modified ';  //更新日時
    $sql .= ' ) VALUES ( ';
    $sql .= '  :id ';
    $sql .= ' ,:enq_id ';
    $sql .= ' ,:answer ';
    $sql .= ' ,NOW() ';
    $sql .= ' ,NOW() ';
    $sql .= ' ) ';
    
    
    for($i = 0; $i < count($arrayQ);$i++){
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':id', $trzid ,PDO::PARAM_INT);
        if($kbn == 0){//設置
            $stmt->bindValue(':enq_id', $array_setti[$i] , PDO::PARAM_STR);
        }elseif($kbn == 1){//引越
            $stmt->bindValue(':enq_id', $array_hikkoshi[$i] , PDO::PARAM_STR);
        }
        $stmt->bindValue(':answer', strval($arrayQ[$i]) , PDO::PARAM_STR);
        try {
          $stmt->execute();
        } catch (PDOException $e) {
          die('insert failed: '.$e->getMessage());
        }
    }
    

    if($ptp == 0){
    header('Location: ../s/completion.php');
    }elseif($ptp == 1){
    header('Location: ../i/completion.php');
    }