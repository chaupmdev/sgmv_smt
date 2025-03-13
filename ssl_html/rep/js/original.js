$(function(){

	$(".pagetop").hide();
     // ↑ページトップボタンを非表示にする

    $(window).on("scroll", function() {

        if ($(this).scrollTop() > 100) {
            // ↑ スクロール位置が100よりも小さい場合に以下の処理をする
            $('.pagetop').fadeIn();
            // ↑ (100より小さい時は)ページトップボタンをスライドダウン
        } else {
            $('.pagetop').fadeOut();
            // ↑ それ以外の場合の場合はスライドアップする。
        }

    // フッター固定する

        scrollHeight = $(document).height();
        // ドキュメントの高さ
        scrollPosition = $(window).height() + $(window).scrollTop();
        //　ウィンドウの高さ+スクロールした高さ→　現在のトップからの位置
        footHeight = $("footer").innerHeight();
        // フッターの高さ

        if ( scrollHeight - scrollPosition  <= footHeight ) {
        // 現在の下から位置が、フッターの高さの位置にはいったら
        //  ".gotop"のpositionをabsoluteに変更し、フッターの高さの位置にする
            $(".pagetop").css({
                "position":"absolute",
                "bottom": "50px"
            });
        } else {
        // それ以外の場合は元のcssスタイルを指定
            $(".pagetop").css({
                "position":"fixed",
                "bottom": "10px"
            });
        }
    });

    // トップへスムーススクロール
    $('.pagetop a').click(function () {
        $('body,html').animate({
        scrollTop: 0
        }, 500);
        // ページのトップへ 500 のスピードでスクロールする
        return false;
     });



});
