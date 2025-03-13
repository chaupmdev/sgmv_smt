/*global $*/
function showhide(zanhyojicnt) {
    'use strict';
    var i;
    // 表示されていないコースを表示します。
    for (i = 0; i < zanhyojicnt; ++i) {
        $('zanCource' + i).show();
    }
    // 「 全てのコースを表示する」リンクを非表示にします。
    $('allcourceLink').hide();
    // 「 全てのコースを表示フラグ」を1にします。
    $('allbtnClickFlg').val('1');
}