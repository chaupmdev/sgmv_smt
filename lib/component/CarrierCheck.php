<?php
 /**
 * キャリアチェック関数
 *
 * @package Component
 * @author
 */
class Sgmov_Component_CarrierCheck
{

    /**
     * ユーザーエージェントからキャリアの判定を行います。
     *
     * スマートフォンならtrueを、それ以外ならfalseを返します。
     *
     * @return true/false
     */
    public static function checkUA()
    {

        // agent取得（デバイス判別）
        $agent = $_SERVER["HTTP_USER_AGENT"];

        if ( (strpos($agent,'iPhone')!==false) ||
             (strpos($agent,'iPad')!==false) ||
             (strpos($agent,'iPod')!==false) ||
             (strpos($agent,'Android')!==false) ||
             (strpos($agent,'BlackBerry')!==false) ||
             (strpos($agent,'Windows.Phone')!==false)) {

            $device = 'smart';

        } else {
            $device = 'PC';
        }

        if ( $device == 'smart' ) {
            return true;
        } elseif ( $device == 'PC' ) {
            return false;
        }
    }
}