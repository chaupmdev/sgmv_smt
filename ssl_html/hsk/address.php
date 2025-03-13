<?php
/**
 * 品質選手権住所入力画面を表示します。
 * @package	ssl_html
 * @subpackage HSK
 * @author	 J.Yamagami
 * @copyright  2019-2019 SP-MediaTec CO,.LTD. All rights reserved.
 */
/*
 * #@+
 * include files
 */
require_once dirname ( __FILE__ ) . '/../../lib/Lib.php';

Sgmov_Lib::useView ( 'hsk/Address' );

/* * #@- */
// 処理を実行
$view = new Sgmov_View_Hsk_Address ();

$result = $view->execute ();
$outInfo = $result ['outInfo'];
$ticket = $result['ticket'];

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<?php require_once './parts/html_head.php'; ?>
<script type="text/javascript">
$(function() {

	$('button#soushin').click(function(e){
		//$("#warn-msg-area2").hide();
		//$('#scrnLock').fadeIn(300);
		e.stopPropagation();
		var $form = $('form').first();
		var data = $form.serializeArray();
		return $.ajax({
			async: true,
			cache: false,
			data: data,
			dataType: 'json',
			timeout: 60000,
			type: 'post',
			url: '/hsk/address_check_input.php'
		}).done(function (data, textStatus, jqXHR) {

			// 画面上部エラーメッセージ全て
			$('.input-error-all').hide();
			if (data["errMsgAll"] && data["errMsgAll"] != '') {
				$('.input-error-all').slideDown(250);
				$('.input-error-all .alert-message').html(data["errMsgAll"]);
			}

			// 各項目にマークする仕様はあきらめました

			if (data['isErr']) {
				setTimeout(function() { $('#page_top').trigger('click'); }, 600); // TODO　ここが何をしているのか不明です
			} else {
				$('#form1').submit();
			}

		}).fail(function (jqXHR, textStatus, errorThrown) {
// 	        // consoleの存在チェック
// 	        if (!window.console) {
// 	            return;
// 	        }
// 	        // consoleが存在する場合、エラー内容を出力
// 	        window.console.log(jqXHR);
// 	        window.console.log(textStatus);
// 	        window.console.log(errorThrown);
// 	        $('#scrnLock').fadeOut(500);
			location.href = '/hsk/error';
		});
	});

	$('input[name="adrs_search_btn"]').on('click', (function () {
		var $form = $('form').first();
		//alert($('#form1').data('featureId'));alert($('#form1').data('id'));console.log(AjaxZip2.zip2addr);
		AjaxZip2.zip2addr(
				'input_forms',
				'address_zip1',
				'address_ken',
				'address_shi',
				'address_zip2',
				'',
				'',
				$form.data('featureId'),
				$form.data('id'),
				'<?php echo $ticket ?>'
		);

		$('input').filter('[name="address_zip1"],[name="address_zip2"]').trigger('focusout');
	}));

});
</script>
<style>
.form-group {margin; 10px 0px;

}

#personal_name {
	width: 200px;
}

#address_zip1 {
	width: 50px;
}

#address_zip2 {
	width: 70px;
}

#address_ken {
	width: 200px;
}

#address_shi {
	width: 99%;
}

#address_ban {
	width: 99%;
}

#denwa {
	width: 200px;
}

#kibobi {
	width: 200px;
}

#kiboji {
	width: 200px;
}
</style>
</head>
<body>
<?php require_once './parts/page_head.php'; ?>
	<form action="/hsk/address_end" name="form1" id="form1" data-feature-id="EVE" data-id="EVE001" method="POST">
		<input name="ticket" type="hidden" value="<?php echo $ticket ?>" />
		<div class="main main-raised">
			<div class="container">
				<input type="hidden" name="id" value="<?php echo @$_GET["param"]; ?>" />
				<div id="error-msg-area" class="input-error-all" style="display:none;">
					<div class="title" style="margin-bottom: 0px;"></div>
					<div class="row" style="padding-top: 10px;">
						<div class="col-md-12">
							<!-- 入力エラーアラート Start -->
							<div class="alert alert-danger alert-dismissible fade show input-error-all" style="display:none;">
								<i class="material-icons">error_outline</i>
								<span class="alert-message"></span>
							</div>
							<!-- 入力エラーアラート End -->
						</div>
					</div>
				</div>
				<div id="section-address">
					<div class="title border-bottom" style="padding-top: 10px;">
						<h3>
							<i class="material-icons" style="font-size: 1.2em;">list_alt</i>&nbsp;
							<span style="font-weight: 600;"> 景品送り先登録 </span>
						</h3>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card discription">
								<div class="card-body">
									<i class="material-icons"
										style="font-size: 22px; color: #0468b4;">info</i> &nbsp;<span
										style="color: #588ab1;">景品の送り先を登録してください。</span>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card okyakusama-info">
								<div class="card-header card-header-text card-header-info">
									<div class="card-text">
										<h4 class="card-title">お名前</h4>
									</div>
								</div>
								<div class="card-body">
									<!-- 入力エラーアラート Start -->
									<div id="alert-okyakusama" class="alert alert-danger alert-dismissible fade show input-error" style="display:none;">
										<span class="alert-message"></span>
									</div>
									<!-- 入力エラーアラート End -->
									<div class="form-group personal_name">
										<div>
											<label>お名前</label>
										</div>
										<div>
											<input name="personal_name" id="personal_name" maxlength="100" type="text" value="" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card address-info">
								<div class="card-header card-header-text card-header-info">
									<div class="card-text">
										<h4 class="card-title">住所</h4>
									</div>
								</div>
								<div class="card-body">
									<!-- 入力エラーアラート Start -->
									<div id="alert-address" class="alert alert-danger alert-dismissible fade show input-error" style="display:none;">
										<span class="alert-message"></span>
									</div>
									<!-- 入力エラーアラート End -->
									<div class="form-group address_zip">
										<div>
											<label>郵便番号</label>
										</div>
										<div>
											<span>〒</span>
											<input maxlength="3" name="address_zip1" id="address_zip1" type="text" value="" /> <span>-</span> <input maxlength="4" name="address_zip2" id="address_zip2" type="text" value="" /> <input class="m110" name="adrs_search_btn" type="button" value="住所検索" />
											<span style="font-size: 12px;" class="forget-address-discription"> &#12288;※郵便番号が不明な方は<a style="text-decoration: underline" target="_blank" href="http://www.post.japanpost.jp/zipcode/">こちら...</a></span>
										</div>
									</div>
									<div class="form-group address_ken">
										<div>
											<label>都道府県</label>
										</div>
										<div>
											<select name="address_ken" id="address_ken">
												<option value="">選択してください</option>
											<?php foreach ($outInfo['kenList'] as $key => $val) : ?>
												<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
											<?php endforeach; ?>
												</select>
										</div>
									</div>
									<div class="form-group address_shi">
										<div>
											<label>市区町村</label>
										</div>
										<div>
											<input name="address_shi" id="address_shi" maxlength="14" type="text" value="" />
										</div>
									</div>
									<div class="form-group address_ban">
										<div>
											<label>番地・建物名</label>
										</div>
										<div>
											<input name="address_ban" id="address_ban" maxlength="30" type="text" value="" />
										</div>
									</div>
									<div class="form-group denwa">
										<div>
											<label>電話番号</label>
										</div>
										<div>
											<input name="denwa" id="denwa" maxlength="15" type="text" value="" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card kibo-info">
								<div class="card-header card-header-text card-header-info">
									<div class="card-text">
										<h4 class="card-title">配達希望日時</h4>
									</div>
								</div>
								<div class="card-body">
									<!-- 入力エラーアラート Start -->
									<div id="alert-kibo" class="alert alert-danger alert-dismissible fade show input-error" style="display:none;">
										<span class="alert-message"></span>
									</div>
									<!-- 入力エラーアラート End -->
									<div class="form-group kibobi">
										<div>
											<label>配達希望日</label>
										</div>
										<div>
											<select name="kibobi" id="kibobi">
											<option value="">選択してください</option>
										<?php foreach ($outInfo['kibobiList'] as $key => $val) : ?>
											<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
										<?php endforeach; ?>
											</select>
										</div>
									</div>
									<div class="form-group kiboji">
										<div>
											<label>配達時間</label>
										</div>
										<div>
											<select name="kiboji" id="kiboji">
											<option value="">選択してください</option>
										<?php foreach ($outInfo['kibojiList'] as $key => $val) : ?>
											<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
										<?php endforeach; ?>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12" style="text-align: center;">
							<button type="button" class="btn btn-primary lg btn-conf" id="soushin">送信</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">&nbsp;</div>
					</div>
				</div>
				<!-- section-enq -->
			</div>
			<!-- container -->
		</div>
		<!-- main -->
	</form>
<?php require_once './parts/page_footer.php'; ?>
	</body>
</html>