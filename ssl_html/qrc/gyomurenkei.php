<?php

// $url = 'https://sagawa-mov-test04.media-tec.jp/qrc/kessai_kanryo';
$url = 'https://sgmoving/qrc/kessai_kanryo';
$param = array(
    'uketsuke_no' => '0123456789',
    'toiawase_no' => '111122223333',
    'kessai_meisai_id' => '012345678',
    'veritrans_kessai_id' => 'event_20220322160920_RjhLieIbZWFSPfaBpGoMvYnQdOcgCNsJAUXHVuTmDkEqtlrK_561959529110'
);
$contents_array = post_request($url, $param);

/**
 * POST送信実行
 */
function post_request($url, $param)
{
    //リクエスト時のオプション指定
    $options = array(
        'http' => array(
            'method' => 'POST', //ここでPOSTを指定
            'header' => array(
                'Content-type: application/x-www-form-urlencoded',
                'User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:13.0) Gecko/20100101 Firefox/13.0.1'
            ),
            'content' => http_build_query($param),
            'ignore_errors' => true,
            'protocol_version' => '1.1'
        ),
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false
        )
    );

    //リクエスト実行
    $contents = @file_get_contents($url, false, stream_context_create($options));

    //ステータスコード抜粋
    preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $http_response_header[0], $matches);
    $statusCode = (int)$matches[1];

    //配列で返すためにjsonのエンコード
    $contents_array = array();
    if ($statusCode === 200) {
        $contents_array = json_decode($contents);
    }
    return $contents_array;
}
