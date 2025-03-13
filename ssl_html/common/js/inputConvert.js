(function() {
	$(document).ready(function() {

		/**
		 * ------------------------------------------------------------
		 * クラスにバインド
		 */

		$(document).on('change click blur', '.nospace', function() {
			$(this).val(del_space($(this).val()));
		});
		$(document).on('change click blur', '.trim', function() {
			$(this).val(trim($(this).val()));
		});
		$(document).on('change click blur', '.hankaku', function() {
			$(this).val(hankaku($(this).val()));
		});
		$(document).on('change click blur', '.zenkaku', function() {
			$(this).val(zenkaku($(this).val()));
		});
		$(document).on('change click blur', '.hankaku-num-only', function() {
			$(this).val(hankaku_num_only($(this).val()));
		});
		$(document).on('change click blur', '.zenkaku-num-only', function() {
			$(this).val(zenkaku_num_only($(this).val()));
		});
		$(document).on('change click blur', '.num-and-hyphen', function() {
			$(this).val(numberAndHyphen($(this).val()));
		});
		$(document).on('change click blur', '.zenkaku-only', function() {
			$(this).val(del_hankaku(zenkaku($(this).val())));
		});
		$(document).on('change click blur', '.hankaku-only', function() {
			$(this).val(del_zenkaku(hankaku($(this).val())));
		});
		$(document).on('change click blur', '.hiragana', function() {
			$(this).val(conv_hiragana($(this).val()));
		});
		$(document).on('change click blur', '.katakana', function() {
			$(this).val(conv_katakana($(this).val()));
		});
		$(document).on('change click blur', '.hiragana-only', function() {
			$(this).val(only_hiragana($(this).val()));
		});
		$(document).on('change click blur', '.katakana-only', function() {
			$(this).val(only_katakana($(this).val()));
		});

		$(document).on('input', '.preventsymbol', function() {
  			$(this).val($(this).val().replace(/[0-9`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, ''));
		});

		/**
		 * ------------------------------------------------------------
		 * 変換関数
		 */

		/**
		 * できるだけ半角にする
		 */
		function hankaku(t) {
			return (t + '').replace(/[！-～]/g, function(s) {
				return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
			});
		}
		/**
		 * できるだけ全角にする
		 */
		function zenkaku(t) {
			return (t + '').replace(/[!-~]/g, function(s) {
				return String.fromCharCode(s.charCodeAt(0) + 0xFEE0);
			});
		}
		/**
		 * 半角数字だけにする
		 */
		function hankaku_num_only(t) {
			return (hankaku(t)).replace(/[^0-9]/g, '');
		}
		/**
		 * 全角数字だけにする
		 */
		function zenkaku_num_only(t) {
			return (zenkaku(t)).replace(/[^０-９]/g, '');
		}
		/**
		 * 半角に変換後、数字とハイフンだけにする
		 */
		function numberAndHyphen(t) {
			return (hankaku(t)).replace(/[ー－―～＿]+/g, '-').replace(/[^0-9\-]/g, '');
		}
		/**
		 * 半角を削除
		 */
		function del_hankaku(t) {
			return (t + '').replace(/[!-~\s]/g, '');
		}
		/**
		 * 全角を削除
		 */
		function del_zenkaku(t) {
			return (t + '').replace(/[^!-~\s]/g, '');
		}
		/**
		 * 先頭と末尾の空白と改行を削除
		 */
		function trim(t) {
			return (t + '').replace(/^[\s]*/, '').replace(/[\s]*$/, '');
		}
		/**
		 * 空白・改行を削除
		 */
		function del_space(t) {
			return (t + '').replace(/[\s　]+/g, '');
		}
		/**
		 * 平仮名だけにする
		 */
		function only_hiragana(t) {
			t = conv_hiragana(t);
			return (t + '').replace(/[^ぁ-ん０-９ー\s]+/g, '');
		}
		/**
		 * ひらがなに変換する
		 */
		function conv_hiragana(t) {
			t = zenkaku(t);
			return (t + '').replace(/[ァ-ン]/g, function(s) {
				return String.fromCharCode(s.charCodeAt(0) - 0x60);
			});
		}
		/**
		 * カタカナだけにする
		 */
		function only_katakana(t) {
			t = conv_katakana(t);
			return (t + '').replace(/[^ァ-ン０-９ー\s]+/g, '');
		}
		/**
		 * カタカナに変換する
		 */
		function conv_katakana(t) {
			t = zenkaku(t);
			return (t + '').replace(/[ぁ-ん]/g, function(s) {
				return String.fromCharCode(s.charCodeAt(0) + 0x60);
			});
		}

	});
})($);