<?php
if(preg_replace('/\?.+$/', '', $_SERVER['REQUEST_URI']) === '/'){
    $headerClass = 'header01 ';
}
 else {
    $headerClass = '';
}
?>
<div id="gHeader" class="<?php echo $headerClass; ?>on">

<header id="gHeaderItems">
		<div class="hBox">
			<div class="logo">
					<span class="logo-blue">
						<a href="https://www.sg-hldgs.co.jp/" target="_blank"><img src="/img/common/logo-sgh.png" alt="SGホールディングス" class=""></a>
						<span class="logo-line"></span>
						<a href="/"><img src="/img/common/logo-sgmv.png" alt="SGmoving" class=""></a>
					</span>

					<span class="logo-white">
						<a href="https://www.sg-hldgs.co.jp/" target="_blank"><img src="/img/common/logo-sgh-white.png" alt="SGホールディングス" class=""></a>
						<span class="logo-line"></span>
						<a href="/"><img src="/img/common/logo-sgmv-white.png" alt="SGmoving" class=""></a>					
					</span>
			</div>
			<div class="rBox">
				<nav id="gNavi">
					<ul class="hLinkList">
						<li><a href="/corporate/"><span>私たちについて</span></a>
							<div class="bgBox bgBox01">
								<div class="area">
									<div class="close"><img src="/img/common/close.png" alt="close"></div>
									<div class="pho"><img src="/img/common/photo02.jpg" alt=""></div>
									<div class="txtBox">
										<ul class="bgLinkList">
											<li><a href="/corporate/">私たちについて<span>ABOUT US</span></a>
												<ul class="bgLinkList01">
													<li><a href="/corporate/message/">社長ご挨拶</a></li>	
													<li><a href="/corporate/profile/">会社概要</a></li>
													<li><a href="/corporate/office/">事業所一覧</a></li>
													<li><a href="/corporate/vision/">ビジョン</a></li>
													<li><a href="/corporate/quality/">品質と信頼</a></li>
													<li><a href="/sustainability/">サステナビリティ</a></li>
													<li><a href="https://www.sg-hldgs.co.jp/company/philosophy/" target="_blank">企業理念・行動憲章</a></li>
													<li><a href="/corporate/pdf/Basic-Internal-Control-Policy.pdf">内部統制基本方針</a></li>
												</ul>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</li>
						<li><a href="/service/"><span>事業案内</span></a>
							<div class="bgBox">
								<div class="area">
									<div class="close"><img src="/img/common/close.png" alt="close"></div>
									<div class="pho"><img src="/img/common/photo01.jpg" alt=""></div>
									<div class="txtBox">
										<ul class="bgLinkList">
											<li><a href="/service/">事業案内<span>OUR SERVICE</span></a>
												<ul class="bgLinkUl">
													<li><a href="/service/#moving">移転・引越</a>
														<ul class="bgLinkList01">
															<li><a href="/service/moving/transfer/">オフィスや施設などの移転</a></li>
															<li><a href="/service/moving/relocate/">転勤サポート</a></li>
															<li><a href="/service/moving/opening/">開業前搬入</a></li>
															<li><a href="/service/moving/mansion/">マンション一斉入居</a></li>
															<li><a href="/service/moving/individual/">個人引越</a></li>
															<li><a href="/service/moving/furniture/">内装工事</a></li>
															<li><a href="/service/moving/support/">暮らしのサポート関連</a></li>
														</ul>
													</li>
													<li><a href="/service/#install">設置輸送</a>
														<ul class="bgLinkList01">
															<li><a href="/service/install/setting/">家具・家電設置サービス、その他設置</a></li>
															<li><a href="/service/install/conditioner/">エアコン取付工事</a></li>
															<li><a href="/service/install/ev/">EV充電設備設置工事</a></li>
															<li><a href="/service/install/led-light/">LED照明交換工事</a></li>
															<li><a href="/service/install/warranty/">延長保証支援サービス</a></li>
														</ul>
													</li>
													<li><a href="/service/#reverse-logistics">静脈物流</a>
														<ul class="bgLinkList01">
															<li><a href="/service/reverse-logistics/sg-ecope/">廃棄物マネジメントサービス</a></li>
															<!--<li><a href="/service/reverse-logistics/sg-ark/">家電リサイクル収集運搬</a></li>-->
															<li><a href="/service/reverse-logistics/sg-ark/">家電リサイクル収集運搬</a></li>
															<li><a href="/service/reverse-logistics/battery/">蓄電池回収</a></li>
															<li><a href="/service/reverse-logistics/document/">機密文書回収サービス</a></li>
														</ul>
													</li>
													<li><a href="/service/#delivery">輸送・配送</a>
														<ul class="bgLinkList01">
															<li><a href="/service/delivery/technical/">精密機器・重量物輸送</a></li>
															<li><a href="/service/delivery/charter/">チャーター輸送</a></li>
															<li><a href="/service/delivery/art/">美術品輸送</a></li>
															<li><a href="/service/delivery/event/">イベント・トータルサポート</a></li>
															<li><a href="/service/delivery/travel/">旅客手荷物輸送</a></li>
														</ul>
													</li>
												</ul>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</li>
						<li><a href="/case/"><span>事例紹介</span></a>
							<div class="bgBox bgBox02">
								<div class="area">
									<div class="close"><img src="/img/common/close.png" alt="close"></div>
									<div class="pho"><img src="/img/common/photo03.jpg" alt=""></div>
									<div class="txtBox">
										<ul class="bgLinkList">
											<li><a href="/case/">事例紹介<span>SOLUTION</span></a>
												<ul class="bgLinkUl">
													<li>
														<ul class="bgLinkList01">
															<li><a href="/case/moving01/">官公庁の移転</a></li>
															<li><a href="/case/moving02/">特殊性の高い施設の移転</a></li>
															<li><a href="/case/moving03/">事務所や物流倉庫の移転</a></li>
															<li><a href="/case/moving04/">商業施設の開業前搬入</a></li>
															<li><a href="/case/moving05/">転勤に伴うお引越</a></li>
														</ul>
													</li>
													<li>
														<ul class="bgLinkList01">
															<li><a href="/case/setting01/">株式会社ジャパネットたかた 様</a></li>
															<li><a href="/case/setting02/">イケア・ジャパン株式会社 様</a></li>
															<li><a href="/case/setting03/">株式会社 DINOS CORPORATION 様</a></li>
															<li><a href="/case/reverselogistics01/">賃貸管理会社様へのご提案</a></li>
															<li><a href="/case/reverselogistics02/">自治体向けへの家電４品目回収</a></li>
														</ul>
													</li>
													<li>
														<ul class="bgLinkList01">
															<li><a href="/case/reverselogistics03/">SG-ECOPE 運用事例</a></li>
															<li><a href="/case/solution01/">美術品輸送</a></li>
															<li><a href="/case/solution02/">SG-WONDERを活用したイベント輸送</a></li>
															<li><a href="/case/solution03/">災害備蓄品保管・輸送</a></li>
															<li><a href="/case/solution04/">動物輸送</a></li>
															<li><a href="/case/solution05/">ルート輸送</a></li>
															<li><a href="/case/solution06/">テクニカル輸送</a></li>
														</ul>
													</li>
												</ul>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</li>
						<li><a href="/careers/"><span>採用情報</span></a></li>
						<li><a href="/news/"><span>ニュース</span></a></li>
					</ul>
				</nav>
				<ul class="lanList">
					<li class=""><a data-stt-changelang="ja" href="?stt_lang=ja" data-stt-active data-stt-ignore>JP</a></li>
					<li class=""><a data-stt-changelang="en" href="?stt_lang=en" data-stt-ignore>EN</a></li>
					<li class=""><a data-stt-changelang="zh-CN" href="?stt_lang=zh-CN" data-stt-ignore>CN</a></li>
				</ul>
				<div class="hBtn"><a href="/contact/"><span>お問い合わせ</span></a></div>
			</div>
		</div>
	</header>
	<div class="cover"></div>
	<div class="menu on"><span class="txt"><span class="line"></span><span class="line"></span><span class="line"></span><small class="txt01">MENU</small><small class="txt02">CLOSE</small></span></div>
	<div class="menuBox">
		<div class="inner">
			<ul class="mLinkList">
				<li>
					<ul class="linkList">
						<li class="down"><a href="/service/">事業案内<span>OUR SERVICE</span></a>
							<ul class="linkUl linkUl02">
								<li class="list01"><a href="/service" class="link sp">事業案内トップ</a><a href="/service/#moving">移転・引越</a>
									<ul class="linkList01">
										<li><a href="/service/moving/transfer/">オフィスや施設などの移転</a></li>
										<li><a href="/service/moving/relocate/">転勤サポート</a></li>
										<li><a href="/service/moving/opening/">開業前搬入</a></li>
										<li><a href="/service/moving/mansion/">マンション一斉入居</a></li>
										<li><a href="/service/moving/individual/">個人引越</a></li>
										<li><a href="/service/moving/furniture/">内装工事</a></li>
										<li><a href="/service/moving/support/">暮らしのサポート関連</a></li>
										<li><a href="/service/moving/ev/">EV充電設備設置工事</a></li>
										<li><a href="/service/moving/led-light/">LED照明交換工事</a></li>
									</ul>
								</li>
								<li class="list02"><a href="/service/#install">設置輸送</a>
									<ul class="linkList01">
										<li><a href="/service/install/setting/">家具・家電設置サービス、その他設置</a></li>
										<li><a href="/service/install/conditioner/">エアコン取付工事</a></li>
										<li><a href="/service/install/warranty/">延長保証支援サービス</a></li>
									</ul>
								</li>
								<li class="list04"><a href="/service/#reverse-logistics">静脈物流</a>
									<ul class="linkList01">
										<li><a href="/service/reverse-logistics/sg-ecope/">廃棄物マネジメントサービス</a></li>
										<li><a href="/service/reverse-logistics/sg-ark/">家電リサイクル収集運搬</a></li>
										<li><a href="/service/reverse-logistics/battery/">蓄電池回収</a></li>
										<li><a href="/service/reverse-logistics/document/">機密文書回収サービス</a></li>
									</ul>
								</li>
								<li class="list03"><a href="/service/#delivery">輸送・配送</a>
									<ul class="linkList01">
										<li><a href="/service/delivery/technical/">精密機器・重量物輸送</a></li>
										<li><a href="/service/delivery/charter/">チャーター輸送</a></li>
										<li><a href="/service/delivery/art/">美術品輸送</a></li>
										<li><a href="/service/delivery/event/">イベント・トータルサポート</a></li>
										<li><a href="/service/delivery/travel/">旅客手荷物輸送</a></li>
										<li><a href="/service/delivery/transportation/">その他の付加価値</a></li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
				</li>
			<li>
					<ul class="linkList">
						<li class="down"><a href="/corporate/">私たちについて<span>ABOUT US</span></a>
							<ul class="linkUl">
								<li><a href="/corporate/" class="link sp">私たちについてトップ</a>
									<ul class="linkList01">
										<li><a href="/corporate/message/">社長ご挨拶</a></li>	
										<li><a href="/corporate/profile/">会社概要</a></li>
										<li><a href="/corporate/office/">事業所一覧</a></li>
										<li><a href="/corporate/vision/">ビジョン</a></li>
										<li><a href="/corporate/quality/">品質と信頼</a></li>
										<li><a href="/sustainability/">サステナビリティ</a></li>
										<li><a href="https://www.sg-hldgs.co.jp/company/philosophy/" target="_blank">企業理念・行動憲章</a></li>
										<li><a href="/corporate/pdf/Basic-Internal-Control-Policy.pdf" target="_blank">内部統制基本方針</a></li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
				</li>
				<li>
					<ul class="linkList">
						<li class="down"><a href="/case/">事例紹介<span>SOLUTION</span></a>
							<ul class="linkUl">
								<li><a href="/case/" class="link sp">事例紹介トップ</a>
									<ul class="linkList01">
										<li><a href="/case/moving03/">事務所や物流倉庫の移転</a></li>
										<li><a href="/case/moving01/">官公庁の移転</a></li>
										<li><a href="/case/moving04/">複合施設や商業施設の一斉搬入出管理・作業</a></li>
										<li><a href="/case/moving02/">教育施設や医療機関の移転</a></li>
										<li><a href="/case/moving05/">人事異動などに伴うお引越し</a></li>
										<li><a href="/case/setting02/">イケア・ジャパン株式会社 様</a></li>
										<li><a href="/case/setting01/">株式会社ジャパネットたかた 様</a></li>
										<li><a href="/case/setting03/">株式会社 DINOS CORPORATION 様</a></li>
										<li><a href="/case/reverselogistics01/">賃貸管理会社様へのご提案</a></li>
										<li><a href="/case/reverselogistics02/">自治体向けへの家電４品目回収</a></li>
										<li><a href="/case/reverselogistics03/">SG-ECOPE 運用事例</a></li>
										<li><a href="/case/solution01/">美術品輸送</a></li>
										<li><a href="/case/solution02/">SG-WONDERを活用したイベント輸送</a></li>
										<li><a href="/case/solution03/">災害備蓄品保管・輸送</a></li>
										<li><a href="/case/solution04/">動物輸送</a></li>
										<li><a href="/case/solution05/">ルート輸送</a></li>
										<li><a href="/case/solution06/">テクニカル輸送</a></li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
				</li>
				<li>
					<ul class="linkList">
						<li><a href="/careers/">採用情報<span>RECRUIT</span></a></li>
						<li><a href="/partner/">パートナー企業募集<span>PARTNER</span></a></li>
						<li><a href="/news/">ニュース<span>Sgmoving NEWS＆TOPICS</span></a></li>
						<li><a href="/contact/">お問い合わせ<span>CONTACT</span></a></li>
						<li>
							<ul class="linkUl01">
								<li><a href="/agreement/">各種約款</a></li>
								<li><a href="/privacy-policy/">個人情報保護方針</a></li>
								<li><a href="/term/">サイトポリシー</a></li>
							</ul>
						</li>
					</ul>
				</li>
			</ul>
			<div class="btmBox">
				<p class="title">PickUp Contents</p>
				<ul class="bannerList">
					<li><a href="/service/reverse-logistics/sg-ecope/"><img src="/img/common/banner_sg-ecope.png" alt="SG-ECOPE"></a></li>
					<li><a href="/service/reverse-logistics/sg-ark/"><img src="/img/common/banner_sg-ark.png" alt="SG-ARK"></a></li>
					<li><a href="/service/install/ev/"><img src="/img/common/banner_ev.png" alt="EV・PHV充電設備設置"></a></li>
					<li><a href="/service/install/led-light/"><img src="/img/common/banner_led.png" alt="LED照明工事"></a></li>
				</ul>
				<p class="text">&copy; SG Moving Co.,Ltd. All Rights Reserved.</p>
			</div>
		</div>
	</div>

</div>