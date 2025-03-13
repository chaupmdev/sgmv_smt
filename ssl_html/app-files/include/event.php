<?php
    // 現在日時
    $nowDate = new DateTime('now');
    // 1桁数字は0埋めすること 例) 2022-01-02 03:04:05
    
    //1.NEW ENERGY
    // 表示開始日時
    $stDate = new DateTime('2022-09-07 00:00:00');
    // 表示終了日時
    $edDate = new DateTime('2022-09-11 20:00:00');

    // 識別子1
    $shikibetushi='nen';
    // バナー画像：280*112程度のサイズ
    $bannerFile='btn_nen.png';
    
    //2.eve開催イベント
    // 表示開始日時
    $stDate_eve2 = new DateTime('2022-07-29 00:00:00');
    // 表示終了日時
    $edDate_eve2 = new DateTime('2022-08-14 22:00:00');

    // 識別子2
    $shikibetushi_eve2='eve';
    // バナー画像：280*112程度のサイズ
    $bannerFile_eve2='btn_eve_2.png';
    
    //3.evp開催イベント
    // 表示開始日時
    $stDate_evp = new DateTime('2022-07-29 00:00:00');
    // 表示終了日時
    $edDate_evp = new DateTime('2022-08-14 22:00:00');

    // 識別子2
    $shikibetushi_evp='evp';
    // バナー画像：280*112程度のサイズ
    $bannerFile_evp='btn_evp.png';

?>
<?php if ($stDate <= $nowDate && $nowDate <= $edDate) : ?>
  <!-- ここから イベントバナー1 -->
  <!-- ***************************************************** -->
    <div class="home-service-banner__item">
      <a href="/<?=$shikibetushi?>/input/" class="home-service-banner__link" target="_blank" rel="noopener" style="border: ridge 1px #CCC;">
        <img src="/app-files/include/event_img/<?=$bannerFile?>" alt="">
      </a>
  </div>
  <!-- ***************************************************** -->
  <!-- ここまで イベントバナー1 -->
<?php endif; ?>

<!-- ここから イベントバナー2 -->
<?php if ($stDate_eve2 <= $nowDate && $nowDate <= $edDate_eve2) : ?>
    <div class="home-service-banner__item">
      <a href="/<?=$shikibetushi_eve2?>/input/" class="home-service-banner__link" target="_blank" rel="noopener" style="border: ridge 1px #CCC;">
        <img src="/app-files/include/event_img/<?=$bannerFile_eve2?>" alt="">
      </a>
  </div>
<?php endif; ?>
<!-- ここまで イベントバナー2 -->
  
<!-- ここから イベントバナー3 -->
<?php if ($stDate_evp <= $nowDate && $nowDate <= $edDate_evp) : ?>
    <div class="home-service-banner__item">
      <a href="/<?=$shikibetushi_evp?>/input/" class="home-service-banner__link" target="_blank" rel="noopener" style="border: ridge 1px #CCC;">
        <img src="/app-files/include/event_img/<?=$bannerFile_evp?>" alt="">
      </a>
  </div>
<?php endif; ?>
<!-- ここまで イベントバナー3 -->

<style>
@media print, screen and (min-width: 480px) {
  body .home-service-banner .home-service-banner__item {
      width: calc((100% - 40px)/3);
      max-width: 280px;
      margin-top: 0px;
      margin-left: 20px;
  }
}

@media screen and (min-width: 768px) and (max-width: 1240px) {
  body .home-service-banner .home-service-banner__item:nth-child(3n+1) {
    margin-left: 20;
    margin-top: 0px;
  }
}
</style>
  <!-- ここまで イベントバナー -->
