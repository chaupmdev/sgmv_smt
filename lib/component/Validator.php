<?php
/**
 * @package    ClassDefFile
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */

/**#@+
 * include files
 */
require_once dirname(__FILE__) . '/../Lib.php';
Sgmov_Lib::useComponents(array('Log', 'Exception', 'ErrorCode', 'String'));
Sgmov_Lib::useServices(array('Yubin', 'UncollectableZipcode', 'OtherCampaign', 'SocketZipCodeDll'));
/**#@-*/

/**
 * 妥当性検査機能を提供します。
 *
 * メソッドチェーンを使用して検査を実行できるように
 * 全ての検査メソッドはクラスのインスタンスを返します。
 *
 * 既に検査エラーが設定されている場合、検査メソッドは何もせずに
 * 処理を終了します。そのため、最初に発生した検査エラーがチェーンの
 * 結果として返されます。
 *
 * [注意事項(共通)]
 *
 * エラーハンドリングでエラーが例外に変換されることを
 * 前提として設計されています。
 *
 * テストのため全て public で宣言します。
 * 名前がアンダーバーで始まるものは使用しないでください。
 *
 * テストでモックを使用するものや、実装を含めると複雑になるものは
 * 実装が分離されています。
 *
 * @package Component
 * @author     M.Shiiba(TPE)
 * @copyright  2009-2010 SAGAWA COMPUTERSYSTEM CO,.LTD. All rights reserved.
 */
class Sgmov_Component_Validator {

    /**
     * 検査に合格
     */
    const VALID = 0;

    /**
     * 検査に合格の場合の標準メッセージ
     */
    const VALID_MESSAGE = '';

    /**
     * 検査に不合格：入力値が空
     */
    const INVALID_EMPTY = 1;

    /**
     * 入力値が空の場合の標準メッセージ
     */
    const INVALID_EMPTY_MESSAGE = '入力してください。';

    /**
     * 入力値が空の場合の標準メッセージ(トップ用)
     */
    const INVALID_EMPTY_MESSAGE_TOP = 'を入力してください。';

    /**
     * 検査に不合格：入力値が未選択
     */
    const INVALID_NOT_SELECTED = 2;

    /**
     * 入力値が未選択の場合の標準メッセージ
     */
    const INVALID_NOT_SELECTED_MESSAGE = '選択してください。';

    /**
     * 入力値が未選択の場合の標準メッセージ(トップ用)
     */
    const INVALID_NOT_SELECTED_MESSAGE_TOP = 'を選択してください。';

    /**
     * 検査に不合格：入力値が指定された文字数を超える
     */
    const INVALID_OVER_LENGTH = 100;

    /**
     * 入力値が指定された文字数を超える場合の標準メッセージ
     */
    const INVALID_OVER_LENGTH_MESSAGE = '%s文字以内で入力してください。';

    /**
     * 入力値が指定された文字数を超える場合の標準メッセージ(トップ用)
     */
    const INVALID_OVER_LENGTH_MESSAGE_TOP = 'は%s文字以内で入力してください。';

    /**
     * 検査に不合格：入力値に全角文字以外の文字が含まれる
     */
    const INVALID_NOT_ONLY_ZENKAKU = 101;

    /**
     * 入力値に全角文字以外の文字が含まれる場合の標準メッセージ
     */
    const INVALID_NOT_ONLY_ZENKAKU_MESSAGE = '半角文字が含まれています。';

    /**
     * 入力値に全角文字以外の文字が含まれる場合の標準メッセージ(トップ用)
     */
    const INVALID_NOT_ONLY_ZENKAKU_MESSAGE_TOP = 'に半角文字が含まれています。';

    /**
     * 検査に不合格：WEBシステムで使用不可の文字が含まれる
     */
    const INVALID_NOT_WEBSYSTEM = 102;

    /**
     * 入力値にWEBシステムで使用不可の文字が含まれる場合の標準メッセージ
     */
    const INVALID_NOT_WEBSYSTEM_MESSAGE = 'お使いいただけない文字が含まれています。';

    /**
     * 入力値にWEBシステムで使用不可の文字が含まれる場合の標準メッセージ(トップ用)
     */
    const INVALID_NOT_WEBSYSTEM_MESSAGE_TOP = 'にお使いいただけない文字が含まれています。';

    /**
     * 検査に不合格：入力値が指定された文字数未満
     */
    const INVALID_UNDER_LENGTH = 103;

    /**
     * 入力値が指定された文字数未満の場合の標準メッセージ
     */
    const INVALID_UNDER_LENGTH_MESSAGE = '%s文字以上で入力してください。';

    /**
     * 入力値が指定された文字数未満の場合の標準メッセージ(トップ用)
     */
    const INVALID_UNDER_LENGTH_MESSAGE_TOP = 'は%s文字以上で入力してください。';

    /**
     * 検査に不合格：二つの入力値が一致しない
     */
    const INVALID_STRING_COMPARISON = 104;

    /**
     * 二つの入力値が一致しない場合の標準メッセージ
     */
    const INVALID_STRING_COMPARISON_MESSAGE = '入力内容をお確かめください。';

    /**
     * 二つの入力値が一致しない標準メッセージ(トップ用)
     */
    const INVALID_STRING_COMPARISON_MESSAGE_TOP = 'の入力内容をお確かめください。';

    /**
     * 検査に不合格：入力値に半角英数字以外の文字が含まれる
     */
    const INVALID_NOT_HALF_WIDTH_ALPHA_NUMERIC_CHARACTERS = 105;

    /**
     * 入力値に半角英数字以外の文字が含まれる場合の標準メッセージ
     */
    const INVALID_NOT_HALF_WIDTH_ALPHA_NUMERIC_CHARACTERS_MESSAGE = '半角英数字以外が含まれています。';

    /**
     * 入力値に半角英数字以外の文字が含まれる場合の標準メッセージ(トップ用)
     */
    const INVALID_NOT_HALF_WIDTH_ALPHA_NUMERIC_CHARACTERS_MESSAGE_TOP = 'に半角英数字以外が含まれています。';

    /**
     * 検査に不合格：入力値に全角カタカナでも半角英字でもない文字が含まれる
     */
    //const INVALID_NOT_FULL_WIDTH_SQUARE_JAPANESE_SYLLABARY_CHARACTERS_OR_HALF_WIDTH_ALPHA_CHARACTERS = 106;
    const INVALID_NOT_ALPHA_CHARACTERS_OR_FULL_WIDTH_SQUARE_JAPANESE_SYLLABARY_CHARACTERS = 106;

    /**
     * 入力値に全角カタカナでも半角英字でもない文字が含まれる場合の標準メッセージ
     */
    //const INVALID_NOT_FULL_WIDTH_SQUARE_JAPANESE_SYLLABARY_CHARACTERS_OR_HALF_WIDTH_ALPHA_CHARACTERS_MESSAGE = '全角カタカナでも半角英字でもない文字が含まれています。';
    const INVALID_NOT_ALPHA_CHARACTERS_OR_FULL_WIDTH_SQUARE_JAPANESE_SYLLABARY_CHARACTERS_MESSAGE = 'カタカナまたは英字で入力してください。';

    /**
     * 入力値に全角カタカナでも半角英字でもない文字が含まれる場合の標準メッセージ(トップ用)
     */
    //const INVALID_NOT_FULL_WIDTH_SQUARE_JAPANESE_SYLLABARY_CHARACTERS_OR_HALF_WIDTH_ALPHA_CHARACTERS_MESSAGE_TOP = 'に全角カタカナでも半角英字でもない文字が含まれています。';
    const INVALID_NOT_ALPHA_CHARACTERS_OR_FULL_WIDTH_SQUARE_JAPANESE_SYLLABARY_CHARACTERS_MESSAGE_TOP = 'はカタカナまたは英字で入力してください。';

    /**
     * 検査に不合格：半角カナが含まれる
     */
    const INVALID_NOT_HALF_WIDTH_KANA = 107;

    /**
     * 入力値に半角カナが含まれる場合の標準メッセージ
     */
    const INVALID_NOT_HALF_WIDTH_KANA_MESSAGE = '半角カナが含まれています。';

    /**
     * 入力値に半角カナが含まれる場合の標準メッセージ(トップ用)
     */
    const INVALID_NOT_HALF_WIDTH_KANA_MESSAGE_TOP = 'に半角カナが含まれています。';

    /**
     * 検査に不合格：入力値に半角数字以外の文字が含まれる
     */
    const INVALID_NOT_INTEGER = 200;

    /**
     * 入力値に半角数字以外の文字が含まれる場合の標準メッセージ
     */
    const INVALID_NOT_INTEGER_MESSAGE = '数値を入力してください。';

    /**
     * 入力値に半角数字以外の文字が含まれる場合の標準メッセージ(トップ用)
     */
    const INVALID_NOT_INTEGER_MESSAGE_TOP = 'には数値を入力してください。';

    /**
     * 検査に不合格：数値が最大値よりも大きい
     */
    const INVALID_INTEGER_TOO_BIG = 201;

    /**
     * 数値が最大値よりも大きい場合の標準メッセージ
     */
    const INVALID_INTEGER_TOO_BIG_MESSAGE = '%s以下の数値を入力してください。';

    /**
     * 数値が最大値よりも大きい場合の標準メッセージ(トップ用)
     */
    const INVALID_INTEGER_TOO_BIG_MESSAGE_TOP = 'には%s以下の数値を入力してください。';

    /**
     * 検査に不合格：数値が最小値よりも小さい
     */
    const INVALID_INTEGER_TOO_SMALL = 202;

    /**
     * 数値が最小値よりも小さい場合の標準メッセージ
     */
    const INVALID_INTEGER_TOO_SMALL_MESSAGE = '%s以上の数値を入力してください。';

    /**
     * 数値が最小値よりも小さい場合の標準メッセージ(トップ用)
     */
    const INVALID_INTEGER_TOO_SMALL_MESSAGE_TOP = 'には%s以上の数値を入力してください。';

    /**
     * 検査に不合格：日付文字列に空文字列が含まれる
     */
    const INVALID_DATE_CONTAINS_EMPTY = 300;

    /**
     * 日付文字列に空文字列が含まれる場合の標準メッセージ
     */
    const INVALID_DATE_CONTAINS_EMPTY_MESSAGE = '入力内容をお確かめください。';

    /**
     * 日付文字列に空文字列が含まれる場合の標準メッセージ(トップ用)
     */
    const INVALID_DATE_CONTAINS_EMPTY_MESSAGE_TOP = 'の入力内容をお確かめください。';

    /**
     * 検査に不合格：日付が(YYYY,MM,DD)の形式ではない
     */
    const INVALID_DATE_FORM = 301;

    /**
     * 日付が(YYYY,MM,DD)の形式ではない場合の標準メッセージ
     */
    const INVALID_DATE_FORM_MESSAGE = '入力内容をお確かめください。';

    /**
     * 日付が(YYYY,MM,DD)の形式ではない場合の標準メッセージ(トップ用)
     */
    const INVALID_DATE_FORM_MESSAGE_TOP = 'の入力内容をお確かめください。';

    /**
     * 検査に不合格：日付が存在しない
     */
    const INVALID_DATE_NOT_EXIST = 302;

    /**
     * 日付が存在しない場合の標準メッセージ
     */
    const INVALID_DATE_NOT_EXIST_MESSAGE = '日付が存在しません。';

    /**
     * 日付が存在しない場合の標準メッセージ(トップ用)
     */
    const INVALID_DATE_NOT_EXIST_MESSAGE_TOP = 'の日付が存在しません。';

    /**
     * 検査に不合格：日付が最小値よりも小さい
     */
    const INVALID_DATE_TOO_SMALL = 303;

    /**
     * 日付が最小値よりも小さい場合の標準メッセージ
     */
    const INVALID_DATE_TOO_SMALL_MESSAGE = '%s以降の日付を入力してください。';

    /**
     * 日付が最小値よりも小さい場合の標準メッセージ(トップ用)
     */
    const INVALID_DATE_TOO_SMALL_MESSAGE_TOP = 'には%s以降の日付を入力してください。';

    /**
     * 検査に不合格：日付が最大値よりも大きい
     */
    const INVALID_DATE_TOO_BIG = 304;

    /**
     * 日付が最大値よりも大きい場合の標準メッセージ
     */
    const INVALID_DATE_TOO_BIG_MESSAGE = '%sまでの日付を入力してください。';

    /**
     * 検査に不合格：時刻文字列に空文字列が含まれる
     */
    const INVALID_TIME_CONTAINS_EMPTY = 350;

    /**
     * 時刻文字列に空文字列が含まれる場合の標準メッセージ
     */
    const INVALID_TIME_CONTAINS_EMPTY_MESSAGE = '入力内容をお確かめください。';

    /**
     * 時刻文字列に空文字列が含まれる場合の標準メッセージ(トップ用)
     */
    const INVALID_TIME_CONTAINS_EMPTY_MESSAGE_TOP = 'の入力内容をお確かめください。';

    /**
     * 検査に不合格：時刻が(HH,MI,SS)の形式ではない
     */
    const INVALID_TIME_FORM = 351;

    /**
     * 時刻が(YYYY,MM,DD)の形式ではない場合の標準メッセージ
     */
    const INVALID_TIME_FORM_MESSAGE = '入力内容をお確かめください。';

    /**
     * 時刻が(YYYY,MM,DD)の形式ではない場合の標準メッセージ(トップ用)
     */
    const INVALID_TIME_FORM_MESSAGE_TOP = 'の入力内容をお確かめください。';

    /**
     * 検査に不合格：時刻が存在しない
     */
    const INVALID_TIME_NOT_EXIST = 352;

    /**
     * 時刻が存在しない場合の標準メッセージ
     */
    const INVALID_TIME_NOT_EXIST_MESSAGE = '時刻が存在しません。';

    /**
     * 時刻が存在しない場合の標準メッセージ(トップ用)
     */
    const INVALID_TIME_NOT_EXIST_MESSAGE_TOP = 'の時刻が存在しません。';

    /**
     * 日付が最大値よりも大きい場合の標準メッセージ(トップ用)
     */
    const INVALID_DATE_TOO_BIG_MESSAGE_TOP = 'には%sまでの日付を入力してください。';

    /**
     * 検査に不合格：入力値が指定された値の中に含まれない
     */
    const INVALID_NOT_IN = 400;

    /**
     * 入力値が指定された値の中に含まれない場合の標準メッセージ
     */
    const INVALID_NOT_IN_MESSAGE = '入力内容をお確かめください。';

    /**
     * 入力値が指定された値の中に含まれない場合の標準メッセージ(トップ用)
     */
    const INVALID_NOT_IN_MESSAGE_TOP = 'の入力内容をお確かめください。';

    /**
     * 検査に不合格：入力値が電話番号の形式ではない(数値文字列ではない)
     */
    const INVALID_PHONE_NOT_NUMERIC = 500;

    /**
     * 入力値が電話番号の形式ではない(数値文字列ではない)場合の標準メッセージ
     */
    const INVALID_PHONE_NOT_NUMERIC_MESSAGE = '入力内容をお確かめください。';

    /**
     * 入力値が電話番号の形式ではない(数値文字列ではない)場合の標準メッセージ(トップ用)
     */
    const INVALID_PHONE_NOT_NUMERIC_MESSAGE_TOP = 'の入力内容をお確かめください。';

    /**
     * 検査に不合格：入力値が電話番号の形式ではない(長さオーバー)
     */
    const INVALID_PHONE_OVER_LENGTH = 501;

    /**
     * 入力値が電話番号の形式ではない(長さオーバー)場合の標準メッセージ
     */
    const INVALID_PHONE_OVER_LENGTH_MESSAGE = '入力内容をお確かめください。';

    /**
     * 入力値が電話番号の形式ではない(長さオーバー)場合の標準メッセージ(トップ用)
     */
    const INVALID_PHONE_OVER_LENGTH_MESSAGE_TOP = 'の入力内容をお確かめください。';

    /**
     * 検査に不合格：入力値が電話番号の形式ではない(空の項目がある)
     */
    const INVALID_PHONE_CONTAINS_EMPTY = 502;

    /**
     * 入力値が電話番号の形式ではない(空の項目がある)場合の標準メッセージ
     */
    const INVALID_PHONE_CONTAINS_EMPTY_MESSAGE = '入力内容をお確かめください。';

    /**
     * 入力値が電話番号の形式ではない(空の項目がある)場合の標準メッセージ(トップ用)
     */
    const INVALID_PHONE_CONTAINS_EMPTY_MESSAGE_TOP = 'の入力内容をお確かめください。';

    /**
     * 検査に不合格：入力値が「該当する住所がありません」
     */
    const INVALID_ADDRESS_EMPTY = 600;

    /**
     * 入力値が「該当する住所がありません」の場合の標準メッセージ
     */
    const INVALID_ADDRESS_EMPTY_MESSAGE = '入力内容をお確かめください。';

    /**
     * 入力値が「該当する住所がありません」の場合の標準メッセージ(トップ用)
     */
    const INVALID_ADDRESS_EMPTY_MESSAGE_TOP = 'の入力内容をお確かめください。';

    /**
     * 検査に不合格：入力値が郵便番号の形式ではない(3桁数値と4桁数値でない)
     */
    const INVALID_ZIPCODE_FORM = 700;

    /**
     * 入力値が郵便番号の形式ではない(3桁数値と4桁数値でない)場合の標準メッセージ
     */
    const INVALID_ZIPCODE_FORM_MESSAGE = '入力内容をお確かめください。';

    /**
     * 入力値が郵便番号の形式ではない(3桁数値と4桁数値でない)場合の標準メッセージ(トップ用)
     */
    const INVALID_ZIPCODE_FORM_MESSAGE_TOP = 'の入力内容をお確かめください。';

    /**
     * 検査に不合格：入力値が郵便番号の形式ではない(空の項目がある)
     */
    const INVALID_ZIPCODE_CONTAINS_EMPTY = 701;

    /**
     * 入力値が郵便番号の形式ではない(空の項目がある)場合の標準メッセージ
     */
    const INVALID_ZIPCODE_CONTAINS_EMPTY_MESSAGE = '入力内容をお確かめください。';

    /**
     * 入力値が郵便番号の形式ではない(空の項目がある)場合の標準メッセージ(トップ用)
     */
    const INVALID_ZIPCODE_CONTAINS_EMPTY_MESSAGE_TOP = 'の入力内容をお確かめください。';

    /**
     * 検査に不合格：入力された郵便番号が存在しない
     */
    const INVALID_ZIPCODE_NOT_EXIST = 702;

    /**
     * 入力された郵便番号が存在しない場合の標準メッセージ
     */
    const INVALID_ZIPCODE_NOT_EXIST_MESSAGE = '入力内容をお確かめください。';

    /**
     * 入力された郵便番号が存在しない場合の標準メッセージ(トップ用)
     */
    const INVALID_ZIPCODE_NOT_EXIST_MESSAGE_TOP = 'の入力内容をお確かめください。';

    /**
     * 検査に不合格：入力された郵便番号が集荷不可地区に存在する
     */
    const INVALID_UNCOLLECTABLE_ZIPCODE = 703;

    /**
     * 入力された郵便番号が集荷不可地区に存在する場合の標準メッセージ
     */
    const INVALID_UNCOLLECTABLE_ZIPCODE_MESSAGE = '集荷・配達できない地域の恐れがあります。';

    /**
     * 入力された郵便番号が集荷不可地区に存在する場合の標準メッセージ(トップ用)
     */
    const INVALID_UNCOLLECTABLE_ZIPCODE_MESSAGE_TOP = 'は集荷・配達できない地域の恐れがあります。';

    /**
     * 検査に不合格：ソケット通信先の郵便番号DLLに入力された郵便番号が存在しない
     */
    const INVALID_ZIPCODE_NOT_EXIST_SOCKET = 704;

    /**
     * ソケット通信先の郵便番号DLLに入力された郵便番号が存在しない場合の標準メッセージ
     */
    const INVALID_ZIPCODE_NOT_EXIST_SOCKET_MESSAGE = '入力内容をお確かめください。';

    /**
     * ソケット通信先の郵便番号DLLに入力された郵便番号が存在しない場合の標準メッセージ(トップ用)
     */
    const INVALID_ZIPCODE_NOT_EXIST_SOCKET_MESSAGE_TOP = 'の入力内容をお確かめください。';

    /**
     * 検査に不合格：入力値がメールアドレスの形式ではない
     */
    const INVALID_MAIL = 800;

    /**
     * 入力値がメールアドレスの形式ではない場合の標準メッセージ
     */
    const INVALID_MAIL_MESSAGE = '入力内容をお確かめください。';

    /**
     * 入力値がメールアドレスの形式ではない場合の標準メッセージ(トップ用)
     */
    const INVALID_MAIL_MESSAGE_TOP = 'の入力内容をお確かめください。';

    /**
     * 検査に不合格：入力値がFLG形式ではない
     */
    const INVALID_FLG = 900;

    /**
     * 入力値が半角英数字ではない場合の標準メッセージ
     */
    const INVALID_FLG_MESSAGE = '半角英数字を入力してください。';

    /**
     * 入力値が半角英数字ではない場合の標準メッセージ(トップ用)
     */
    const INVALID_FLG_MESSAGE_TOP = 'は半角英数字を入力してください。';

    /**
     * 検査に不合格：入力値がFLG形式ではない
     */
    const FLG_REPEAT = 901;

    /**
     * 入力値が重複の場合の標準メッセージ
     */
    const FLG_REPEAT_MESSAGE = '重複しています。';

    /**
     * 入力値が重複の場合の標準メッセージ(トップ用)
     */
    const FLG_REPEAT_MESSAGE_TOP = 'が別のキャンペーンと重複しています。';

    const PHONE_LEN_NOT_DASH = 11;
    
    const PHONE_LEN_DASH = 13;
    /**
     * 単一値のバリデータを生成します。
     * @param string $value チェックする文字列
     * @return Sgmov_Component_Validator バリデータのインスタンス
     */
    public static function createSingleValueValidator($value) {

        if (isset($value) && !is_string($value)) {Sgmov_Component_Log::debug(var_export($value, true));Sgmov_Component_Log::debug(var_export(isset($value), true));Sgmov_Component_Log::debug(var_export(!is_string($value), true));
            throw new Sgmov_Component_Exception('$value には文字列を入力してください。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
        }

        return new Sgmov_Component_Validator(strval($value));
    }

    /**
     * 複数値のバリデータを生成します。
     * @param array $values チェックする文字列の配列
     * @return Sgmov_Component_Validator バリデータのインスタンス
     */
    public static function createMultipleValueValidator($values) {
        if (!is_array($values)) {
            throw new Sgmov_Component_Exception('$values には文字列の配列を入力してください。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
        }
        return new Sgmov_Component_Validator($values);
    }

    /**
     * 日付のバリデータを生成します。
     * @param string $year 年
     * @param string $month 月(1から12)
     * @param string $day 日にち
     * @return Sgmov_Component_Validator バリデータのインスタンス
     */
    public static function createDateValidator($year, $month, $day) {
        return new Sgmov_Component_Validator(array($year, $month, $day));
    }

    /**
     * 時刻のバリデータを生成します。
     * @param string $hours   時間
     * @param string $minutes 分
     * @param string $seconds 秒
     * @return Sgmov_Component_Validator バリデータのインスタンス
     */
    public static function createTimeValidator($hours, $minutes, $seconds) {
        return new Sgmov_Component_Validator(array($hours, $minutes, $seconds));
    }

    /**
     * 電話番号のバリデータを生成します。
     * @param string $tel1 電話番号1
     * @param string $tel2 電話番号2
     * @param string $tel3 電話番号3
     * @return Sgmov_Component_Validator バリデータのインスタンス
     */
    public static function createPhoneValidator($tel1, $tel2, $tel3) {
        return new Sgmov_Component_Validator(array($tel1, $tel2, $tel3));
    }

    /**
     * 郵便番号のバリデータを生成します。
     * @param string $zip1 郵便番号1
     * @param string $zip2 郵便番号2
     * @return Sgmov_Component_Validator バリデータのインスタンス
     */
    public static function createZipValidator($zip1, $zip2) {
        return new Sgmov_Component_Validator(array($zip1, $zip2));
    }

    /**
     * 検査する値
     * @var array
     */
    public $_values;

    /**
     * 検査結果
     * @var integer
     */
    public $_result = self::VALID;

    /**
     * 検査結果の内容を示す標準的な文字列
     * @var string
     */
    public $_resultMessage = self::VALID_MESSAGE;

    /**
     * 検査結果の内容を示す標準的な文字列(TOP用)
     * @var string
     */
    public $_resultMessageTop = self::VALID_MESSAGE;

    /**
     * 通常はコンストラクターを直接呼び出さずに、{@link createValidator()} 、
     * {@link createTelephoneNumberValidator()} 、{@link createZipCodeValidator()}
     * を使用してインスタンスを作成してください。
     *
     * 複数の入力値を指定した場合はいずれかの値が不合格の場合に不合格となります。
     * @param mixed $values 入力値(文字列、または文字列の配列)
     */
    public function __construct($values) {
        // 文字列の配列が渡されたことを確認
        if (!isset($values)) {
            throw new Sgmov_Component_Exception('$values には文字列の配列を入力してください。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
        } else if (!is_array($values)) {
            if (!is_string($values)) {
                throw new Sgmov_Component_Exception('$values には文字列の配列を入力してください。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            $this->_values = array($values);
        } else {
            if (count($values) == 0) {
                throw new Sgmov_Component_Exception('$values には文字列の配列を入力してください。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
            }
            foreach ($values as $value) {
                if (!is_string($value)) {
                    throw new Sgmov_Component_Exception('$values には文字列の配列を入力してください。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
                }
            }
            $this->_values = $values;
        }
    }

    /**
     * 検査結果に合格しているかどうかを返します。
     * @return boolean 合格の場合TRUE、不合格の場合FALSE。
     */
    public function isValid() {
        return ($this->_result === self::VALID);
    }

    /**
     * 検査結果を返します。
     * @return integer 検査結果
     */
    public function getResult() {
        return $this->_result;
    }

    /**
     * 検査結果の内容を示す標準的な文字列を返します。
     * @return string 検査結果の内容を示す標準的な文字列
     */
    public function getResultMessage() {
        return $this->_resultMessage;
    }

    /**
     * 検査結果の内容を示す標準的な文字列を返します(TOP用)。
     * @return string 検査結果の内容を示す標準的な文字列
     */
    public function getResultMessageTop() {
        return $this->_resultMessageTop;
    }

    /**
     * 入力値が空でないことを確認します。
     * 入力値が空の場合に不合格となります。
     * @return Sgmov_Component_Validator 検査後の自インスタンス
     */
    public function isNotEmpty() {
        if ($this->isValid()) {
            foreach ($this->_values as $value) {
                if (Sgmov_Component_String::getCount($value) == 0) {
                    Sgmov_Component_Log::debug('空です。');
                    $this->_result           = self::INVALID_EMPTY;
                    $this->_resultMessage    = self::INVALID_EMPTY_MESSAGE;
                    $this->_resultMessageTop = self::INVALID_EMPTY_MESSAGE_TOP;
                    break;
                }
            }
        }
        return $this;
    }

    /**
     * 入力値が選択されていること(空でないこと)を確認します。
     * 入力値が空の場合に不合格となります。
     *
     * isNotEmptyとの違いはエラーメッセージのみです。
     *
     * @return Sgmov_Component_Validator 検査後の自インスタンス
     */
    public function isSelected() {
        if ($this->isValid()) {
            foreach ($this->_values as $value) {
                if (Sgmov_Component_String::getCount($value) == 0) {
                    Sgmov_Component_Log::debug('空です。');
                    $this->_result           = self::INVALID_NOT_SELECTED;
                    $this->_resultMessage    = self::INVALID_NOT_SELECTED_MESSAGE;
                    $this->_resultMessageTop = self::INVALID_NOT_SELECTED_MESSAGE_TOP;
                    break;
                }
            }
        }
        return $this;
    }

    /**
     * 入力値が指定された文字数以内であることを確認します。
     * 入力値が指定された文字数を超える場合に不合格となります。
     *
     * @param integer $maxCount 最大文字数
     * @return Sgmov_Component_Validator 検査後の自インスタンス
     */
    public function isLengthLessThanOrEqualTo($maxCount) {
        if (!is_integer($maxCount)) {
            throw new Sgmov_Component_Exception('$maxCount には整数を入力してください。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
        }

        if ($this->isValid()) {
            foreach ($this->_values as $value) {
                $len = Sgmov_Component_String::getCount($value);
                if ($len > $maxCount) {
                    Sgmov_Component_Log::debug("最大長を超えています。最大長:{$maxCount}  値:{$value}  文字列長:{$len}");
                    $this->_result           = self::INVALID_OVER_LENGTH;
                    $this->_resultMessage    = sprintf(self::INVALID_OVER_LENGTH_MESSAGE, $maxCount);
                    $this->_resultMessageTop = sprintf(self::INVALID_OVER_LENGTH_MESSAGE_TOP, $maxCount);
                    break;
                }
            }
        }
        return $this;
    }

    /**
     * 入力値が指定された文字数以上であることを確認します。
     * 入力値が指定された文字数未満の場合に不合格となります。
     *
     * @param integer $minCount 最小文字数
     * @return Sgmov_Component_Validator 検査後の自インスタンス
     */
    public function isLengthMoreThanOrEqualTo($minCount) {
        if (!is_integer($minCount)) {
            throw new Sgmov_Component_Exception('$minCount には整数を入力してください。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
        }

        if ($this->isValid()) {
            foreach ($this->_values as $value) {
                $len = Sgmov_Component_String::getCount($value);
                if ($len < $minCount) {
                    Sgmov_Component_Log::debug("最小長未満です。最小長:{$minCount}  値:{$value}  文字列長:{$len}");
                    $this->_result           = self::INVALID_UNDER_LENGTH;
                    $this->_resultMessage    = sprintf(self::INVALID_UNDER_LENGTH_MESSAGE, $minCount);
                    $this->_resultMessageTop = sprintf(self::INVALID_UNDER_LENGTH_MESSAGE_TOP, $minCount);
                    break;
                }
            }
        }
        return $this;
    }

    /**
     * 入力値が一致することを確認します。
     * 入力値の1番目と2番目が一致する場合、合格とします。
     * 入力値が両方とも空文字列の場合は合格となります。
     * 入力値のどちらかが空文字列の場合は不合格となります。
     * @return Sgmov_Component_Validator 検査後の自インスタンス。
     */
    public function isStringComparison() {
        if (count($this->_values) != 2) {
            throw new Sgmov_Component_Exception('$this->_values の項目数が不正です。' . Sgmov_Component_String::toDebugString($this->_values),
                 Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
        }

        if ($this->isValid()) {
            $value1 = strval($this->_values[0]);
            $value2 = strval($this->_values[1]);

            $len1 = Sgmov_Component_String::getCount($value1);
            $len2 = Sgmov_Component_String::getCount($value2);
            if ($len1 === 0 && $len2 === 0) {
                // どちらも未入力の場合は合格
            } else if ($len1 > 0 && $len2 > 0) {
                // どちらも入力
                if ($value1 !== $value2) {
                    if (Sgmov_Component_Log::isDebug()) {
                        $checkValueString = Sgmov_Component_String::toDebugString($this->_values);
                        Sgmov_Component_Log::debug("入力値が一致しません。値:{$checkValueString}");
                    }
                    $this->_result           = self::INVALID_STRING_COMPARISON;
                    $this->_resultMessage    = self::INVALID_STRING_COMPARISON_MESSAGE;
                    $this->_resultMessageTop = self::INVALID_STRING_COMPARISON_MESSAGE_TOP;
                }
            } else {
                // どちらかが未入力
                $this->_result           = self::INVALID_STRING_COMPARISON;
                $this->_resultMessage    = self::INVALID_STRING_COMPARISON_MESSAGE;
                $this->_resultMessageTop = self::INVALID_STRING_COMPARISON_MESSAGE_TOP;
            }
        }
        return $this;
    }

    /**
     * 入力値が全角文字だけを含むことを確認します。
     * 入力値に全角文字以外の文字が含まれる場合に不合格となります。
     * 入力値が空文字列の場合は合格となります。
     * @return Sgmov_Component_Validator 検査後の自インスタンス。
     */
    public function isZenkakuOnly() {
        if ($this->isValid()) {
            foreach ($this->_values as $value) {
                if (preg_match('/[ -~｡-ﾟ]/u', $value)) {
                    Sgmov_Component_Log::debug("半角文字を含んでいます。値:{$value}");
                    $this->_result           = self::INVALID_NOT_ONLY_ZENKAKU;
                    $this->_resultMessage    = self::INVALID_NOT_ONLY_ZENKAKU_MESSAGE;
                    $this->_resultMessageTop = self::INVALID_NOT_ONLY_ZENKAKU_MESSAGE_TOP;
                    break;
                }
            }
        }
        return $this;
    }

    /**
     * 入力値が半角英数字だけを含むことを確認します。
     * 入力値に半角英数字以外の文字が含まれる場合に不合格となります。
     * 入力値が空文字列の場合は合格となります。
     * @return Sgmov_Component_Validator 検査後の自インスタンス。
     */
    public function isHalfWidthAlphaNumericCharacters() {
        if ($this->isValid()) {
            foreach ($this->_values as $value) {
                $len = Sgmov_Component_String::getCount($value);
                if ($len > 0) {
                    if (!ctype_alnum($value)) {
                        Sgmov_Component_Log::debug("半角英数字以外を含んでいます。値:{$value}");
                        $this->_result           = self::INVALID_NOT_HALF_WIDTH_ALPHA_NUMERIC_CHARACTERS;
                        $this->_resultMessage    = self::INVALID_NOT_HALF_WIDTH_ALPHA_NUMERIC_CHARACTERS_MESSAGE;
                        $this->_resultMessageTop = self::INVALID_NOT_HALF_WIDTH_ALPHA_NUMERIC_CHARACTERS_MESSAGE_TOP;
                        break;
                    }
                }
            }
        }
        return $this;
    }

    /**
     * 入力値が全角カタカナと英字だけを含むことを確認します。
     * 入力値に全角カタカナでも英字でもない文字が含まれる場合に不合格となります。
     * 入力値が空文字列の場合は合格となります。
     * @return Sgmov_Component_Validator 検査後の自インスタンス。
     */
    //public function isFullWidthSquareJapaneseSyllabaryCharactersOrHalfWidthAlphaCharacters() {
    public function isAlphaCharactersOrFullWidthSquareJapaneseSyllabaryCharacters() {
        if ($this->isValid()) {
            foreach ($this->_values as $value) {
                $len = Sgmov_Component_String::getCount($value);
                if ($len > 0) {
                    //if (!preg_match('/^[A-Za-zァ-ヶー]+$/u', $value)) {
                    if (!preg_match('/^[A-Za-zＡ-Ｚａ-ｚァ-ヶー]+$/u', $value)) {
                        Sgmov_Component_Log::debug("全角カタカナでも英字でもない文字以外を含んでいます。値:{$value}");
                        //$this->_result           = self::INVALID_NOT_FULL_WIDTH_SQUARE_JAPANESE_SYLLABARY_CHARACTERS_OR_HALF_WIDTH_ALPHA_CHARACTERS;
                        //$this->_resultMessage    = self::INVALID_NOT_FULL_WIDTH_SQUARE_JAPANESE_SYLLABARY_CHARACTERS_OR_HALF_WIDTH_ALPHA_CHARACTERS_MESSAGE;
                        //$this->_resultMessageTop = self::INVALID_NOT_FULL_WIDTH_SQUARE_JAPANESE_SYLLABARY_CHARACTERS_OR_HALF_WIDTH_ALPHA_CHARACTERS_MESSAGE_TOP;
                        $this->_result           = self::INVALID_NOT_ALPHA_CHARACTERS_OR_FULL_WIDTH_SQUARE_JAPANESE_SYLLABARY_CHARACTERS;
                        $this->_resultMessage    = self::INVALID_NOT_ALPHA_CHARACTERS_OR_FULL_WIDTH_SQUARE_JAPANESE_SYLLABARY_CHARACTERS_MESSAGE;
                        $this->_resultMessageTop = self::INVALID_NOT_ALPHA_CHARACTERS_OR_FULL_WIDTH_SQUARE_JAPANESE_SYLLABARY_CHARACTERS_MESSAGE_TOP;
                        break;
                    }
                }
            }
        }
        return $this;
    }

    /**
     * 入力値が半角数字だけを含むことを確認します。
     * 入力値が空文字列の場合は合格となります。
     *
     * 「+」か「-」か数字で始まり、1文字以上の数字を持つ場合は合格になります。
     *
     * 最小値が指定されている場合は、最小値よりも小さい値の場合は不合格になります。
     * 最大値が指定されている場合は、最大値よりも大さい値の場合は不合格になります。
     *
     * @param integer $min [optional] 最小値
     * @param integer $max [optional] 最大値
     * @return Sgmov_Component_Validator 検査後の自インスタンス。
     */
    public function isInteger($min = NULL, $max = NULL) {
        if (!is_null($min) && !is_integer($min)) {
            throw new Sgmov_Component_Exception('$min には整数値を入力してください。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
        }
        if (!is_null($max) && !is_integer($max)) {
            throw new Sgmov_Component_Exception('$max には整数値を入力してください。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
        }

        if ($this->isValid()) {
            foreach ($this->_values as $value) {
                $len = Sgmov_Component_String::getCount($value);
                if ($len > 0) {
                    if (!preg_match('/^[-+]?[0-9]+$/u', $value)) {
                        Sgmov_Component_Log::debug("数値ではありません。値:{$value}");
                        $this->_result           = self::INVALID_NOT_INTEGER;
                        $this->_resultMessage    = self::INVALID_NOT_INTEGER_MESSAGE;
                        $this->_resultMessageTop = self::INVALID_NOT_INTEGER_MESSAGE_TOP;
                        break;
                    } else {
                        $intValue = intval($value);
                        if (!is_null($min)) {
                            if ($intValue < $min) {
                                Sgmov_Component_Log::debug("最小値よりも小さいです。値:{$value} 最小値:{$min} ");
                                $this->_result           = self::INVALID_INTEGER_TOO_SMALL;
                                $this->_resultMessage    = sprintf(self::INVALID_INTEGER_TOO_SMALL_MESSAGE, $min);
                                $this->_resultMessageTop = sprintf(self::INVALID_INTEGER_TOO_SMALL_MESSAGE_TOP, $min);
                                break;
                            }
                        }
                        if (!is_null($max)) {
                            if ($intValue > $max) {
                                Sgmov_Component_Log::debug("最大値よりも大きいです。値:{$value} 最大値:{$max}");
                                $this->_result           = self::INVALID_INTEGER_TOO_BIG;
                                $this->_resultMessage    = sprintf(self::INVALID_INTEGER_TOO_BIG_MESSAGE, $max);
                                $this->_resultMessageTop = sprintf(self::INVALID_INTEGER_TOO_BIG_MESSAGE_TOP, $max);
                                break;
                            }
                        }
                    }
                }
            }
        }
        return $this;
    }

    /**
     * 入力値が有効な日付文字列であることを確認します。
     * 全ての入力値が空文字列の場合は合格となります。
     *
     * 入力値の1番目が4桁の数字・2番目が2桁の数字・3番目が2桁の数字で
     * 3つの数字が有効な日付を示している場合に合格になります。
     *
     * 最小値が指定されている場合は、最小値よりも小さい値の場合は不合格になります。
     * 最大値が指定されている場合は、最大値よりも大さい値の場合は不合格になります。
     *
     * @param date $min [optional] 最小値のタイムスタンプ
     * @param date $max [optional] 最大値のタイムスタンプ
     * @return Sgmov_Component_Validator 検査後の自インスタンス。
     */
    public function isDate($min = NULL, $max = NULL) {
        if (!is_null($min) && !is_integer($min)) {
            throw new Sgmov_Component_Exception('$min には整数値を入力してください。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
        }
        if (!is_null($max) && !is_integer($max)) {
            throw new Sgmov_Component_Exception('$max には整数値を入力してください。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
        }

        if ($this->isValid()) {
            $value1 = $this->_values[0];
            $value2 = $this->_values[1];
            $value3 = $this->_values[2];

            $len1 = Sgmov_Component_String::getCount($value1);
            $len2 = Sgmov_Component_String::getCount($value2);
            $len3 = Sgmov_Component_String::getCount($value3);

            if ($len1 == 0 && $len2 == 0 && $len3 == 0) {
                // 全て未入力は合格
            } else if ($len1 > 0 && $len2 > 0 && $len3 > 0) {
                // 全て入力
                if (!preg_match('/^[0-9]{4}$/u', $value1) || !preg_match('/^[0-9]{2}$/u', $value2) || !preg_match('/^[0-9]{2}$/u', $value3)) {
                    // 形式が不正
                    if (Sgmov_Component_Log::isDebug()) {
                        $checkValueString = Sgmov_Component_String::toDebugString($this->_values);
                        Sgmov_Component_Log::debug("入力値の形式が不正です。値:{$checkValueString}");
                    }
                    $this->_result           = self::INVALID_DATE_FORM;
                    $this->_resultMessage    = self::INVALID_DATE_FORM_MESSAGE;
                    $this->_resultMessageTop = self::INVALID_DATE_FORM_MESSAGE_TOP;
                } else {
                    // 形式が正しい
                    $intValue1 = intval($value1);
                    $intValue2 = intval($value2);
                    $intValue3 = intval($value3);
                    if (!checkdate($intValue2, $intValue3, $intValue1)) {
                        // 日付として不正
                        if (Sgmov_Component_Log::isDebug()) {
                            $checkValueString = Sgmov_Component_String::toDebugString($this->_values);
                            Sgmov_Component_Log::debug("入力値の形式が不正です。値:{$checkValueString}");
                        }
                        $this->_result           = self::INVALID_DATE_NOT_EXIST;
                        $this->_resultMessage    = self::INVALID_DATE_NOT_EXIST_MESSAGE;
                        $this->_resultMessageTop = self::INVALID_DATE_NOT_EXIST_MESSAGE_TOP;
                    } else {
                        // 日付として正しい
                        $timeValue = mktime(0, 0, 0, $intValue2, $intValue3, $intValue1);
                        if (!is_null($min)) {
                            if ($timeValue < $min) {
                                Sgmov_Component_Log::debug("最小値よりも小さいです。値:{$timeValue} 最小値:{$min}");
                                $this->_result           = self::INVALID_DATE_TOO_SMALL;
                                $this->_resultMessage = sprintf(self::INVALID_DATE_TOO_SMALL_MESSAGE, strftime('%Y/%m/%d',
                                     $min));
                                $this->_resultMessageTop = sprintf(self::INVALID_DATE_TOO_SMALL_MESSAGE_TOP, strftime('%Y/%m/%d',
                                     $min));
                            }
                        }
                        if (!is_null($max)) {
                            if ($timeValue > $max) {
                                Sgmov_Component_Log::debug("最大値よりも大きいです。値:{$timeValue} 最大値:{$max}");
                                $this->_result           = self::INVALID_DATE_TOO_BIG;
                                $this->_resultMessage = sprintf(self::INVALID_DATE_TOO_BIG_MESSAGE, strftime('%Y/%m/%d',
                                     $max));
                                $this->_resultMessageTop = sprintf(self::INVALID_DATE_TOO_BIG_MESSAGE_TOP, strftime('%Y/%m/%d',
                                     $max));
                            }
                        }
                    }
                }
            } else {
                // 未入力と入力の項目がある
                if (Sgmov_Component_Log::isDebug()) {
                    $checkValueString = Sgmov_Component_String::toDebugString($this->_values);
                    Sgmov_Component_Log::debug("未入力の項目があります。値:{$checkValueString}");
                }
                $this->_result           = self::INVALID_DATE_CONTAINS_EMPTY;
                $this->_resultMessage    = self::INVALID_DATE_CONTAINS_EMPTY_MESSAGE;
                $this->_resultMessageTop = self::INVALID_DATE_CONTAINS_EMPTY_MESSAGE_TOP;
            }
        }
        return $this;
    }

    /**
     * 入力値が有効な時刻文字列であることを確認します。
     * 全ての入力値が空文字列の場合は合格となります。
     *
     * 入力値の1番目が2桁以内の数字・2番目が2桁以内の数字・3番目が2桁以内の数字で
     * 3つの数字が有効な時刻を示している場合に合格になります。
     *
     * 最小値が指定されている場合は、最小値よりも小さい値の場合は不合格になります。
     * 最大値が指定されている場合は、最大値よりも大さい値の場合は不合格になります。
     *
     * @return Sgmov_Component_Validator 検査後の自インスタンス。
     */
    public function isTime() {
        if ($this->isValid()) {
            $value1 = $this->_values[0];
            $value2 = $this->_values[1];
            $value3 = $this->_values[2];

            $len1 = Sgmov_Component_String::getCount($value1);
            $len2 = Sgmov_Component_String::getCount($value2);
            $len3 = Sgmov_Component_String::getCount($value3);

            if ($len1 == 0 && $len2 == 0 && $len3 == 0) {
                // 全て未入力は合格
            } elseif ($len1 > 0 && $len2 > 0 && $len3 > 0) {
                // 全て入力
                if (!preg_match('/^[0-9]{1,2}$/u', $value1) || !preg_match('/^[0-9]{1,2}$/u', $value2) || !preg_match('/^[0-9]{1,2}$/u', $value3)) {
                    // 形式が不正
                    if (Sgmov_Component_Log::isDebug()) {
                        $checkValueString = Sgmov_Component_String::toDebugString($this->_values);
                        Sgmov_Component_Log::debug("入力値の形式が不正です。値:{$checkValueString}");
                    }
                    $this->_result           = self::INVALID_TIME_FORM;
                    $this->_resultMessage    = self::INVALID_TIME_FORM_MESSAGE;
                    $this->_resultMessageTop = self::INVALID_TIME_FORM_MESSAGE_TOP;
                } else {
                    // 形式が正しい
                    $intValue1 = intval($value1);
                    $intValue2 = intval($value2);
                    $intValue3 = intval($value3);
                    if ($intValue1 < 0 || $intValue1 >= 24
                            || $intValue2 < 0 || $intValue2 >= 60
                            || $intValue3 < 0 || $intValue3 >= 60) {
                        // 時刻として不正
                        if (Sgmov_Component_Log::isDebug()) {
                            $checkValueString = Sgmov_Component_String::toDebugString($this->_values);
                            Sgmov_Component_Log::debug("入力値の形式が不正です。値:{$checkValueString}");
                        }
                        $this->_result           = self::INVALID_TIME_NOT_EXIST;
                        $this->_resultMessage    = self::INVALID_TIME_NOT_EXIST_MESSAGE;
                        $this->_resultMessageTop = self::INVALID_TIME_NOT_EXIST_MESSAGE_TOP;
                    }
                }
            } else {
                // 未入力と入力の項目がある
                if (Sgmov_Component_Log::isDebug()) {
                    $checkValueString = Sgmov_Component_String::toDebugString($this->_values);
                    Sgmov_Component_Log::debug("未入力の項目があります。値:{$checkValueString}");
                }
                $this->_result           = self::INVALID_TIME_CONTAINS_EMPTY;
                $this->_resultMessage    = self::INVALID_TIME_CONTAINS_EMPTY_MESSAGE;
                $this->_resultMessageTop = self::INVALID_TIME_CONTAINS_EMPTY_MESSAGE_TOP;
            }
        }
        return $this;
    }

    /**
     * 入力値が指定された値の中の1つに一致することを確認します。
     * 入力値が指定された値の中に含まれない場合に不合格となります。
     * 入力値には数値を指定することができます。
     *
     * $values = array(2, 3, 4)の場合<br />
     * '2'または2は合格、'2a'は不合格となります。
     *
     * $values = array('2', '3', '4')の場合<br />
     * '2'または2は合格、'2a'は不合格となります。
     *
     * @param array $checkValues この中に含まれるかどうかをチェックします。
     * @return Sgmov_Component_Validator 検査後の自インスタンス。
     */
    public function isIn($checkValues) {
        if (!is_array($checkValues)) {
            throw new Sgmov_Component_Exception('$checkValues には配列を入力してください。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
        }

        if ($this->isValid()) {
            foreach ($this->_values as $value) {
                $found = FALSE;
                foreach ($checkValues as $checkValue) {
                    if (!is_string($checkValue) && !is_integer($checkValue)) {
                        throw new Sgmov_Component_Exception('$checkValues には数値または文字列の配列を入力してください。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
                    }

                    if (strcmp($value, $checkValue) === 0) {
                        $found = TRUE;
                        break;
                    }
                }

                if (!$found) {
                    if (Sgmov_Component_Log::isDebug()) {
                        $checkValueString = Sgmov_Component_String::toDebugString($checkValues);
                        Sgmov_Component_Log::debug("有効な値に含まれていません。値:{$value} 有効値:{$checkValueString}");
                    }
                    $this->_result           = self::INVALID_NOT_IN;
                    $this->_resultMessage    = self::INVALID_NOT_IN_MESSAGE;
                    $this->_resultMessageTop = self::INVALID_NOT_IN_MESSAGE_TOP;
                    break;
                }
            }
        }
        return $this;
    }

    /**
     * 入力値が有効な電話番号であることを確認します。
     * 全ての入力値が半角数字で、文字数が18文字以下の場合に合格となります。
     * 全ての入力値が空文字列の場合は合格となります。
     * @return Sgmov_Component_Validator 検査後の自インスタンス。
     */
    public function isPhone() {
        if (count($this->_values) != 3) {
            throw new Sgmov_Component_Exception('$this->_values の項目数が不正です。' . Sgmov_Component_String::toDebugString($this->_values),
                 Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
        }

        if ($this->isValid()) {
            $value1 = $this->_values[0];
            $value2 = $this->_values[1];
            $value3 = $this->_values[2];

            $len1 = Sgmov_Component_String::getCount($value1);
            $len2 = Sgmov_Component_String::getCount($value2);
            $len3 = Sgmov_Component_String::getCount($value3);

            if ($len1 == 0 && $len2 == 0 && $len3 == 0) {
                // 全て未入力は合格
            } else if ($len1 > 0 && $len2 > 0 && $len3 > 0) {
                // 全て入力
                // 連結
                $value = $value1 . $value2 . $value3;
                if (!preg_match('/^[0-9]+$/u', $value)) {
                    Sgmov_Component_Log::debug("数値以外の文字を含んでいます。値:{$value}");
                    $this->_result           = self::INVALID_PHONE_NOT_NUMERIC;
                    $this->_resultMessage    = self::INVALID_PHONE_NOT_NUMERIC_MESSAGE;
                    $this->_resultMessageTop = self::INVALID_PHONE_NOT_NUMERIC_MESSAGE_TOP;
                } else {
                    if (Sgmov_Component_String::getCount($value) > 18) {
                        Sgmov_Component_Log::debug("18文字を超えています。値:{$value}");
                        $this->_result           = self::INVALID_PHONE_OVER_LENGTH;
                        $this->_resultMessage    = self::INVALID_PHONE_OVER_LENGTH_MESSAGE;
                        $this->_resultMessageTop = self::INVALID_PHONE_OVER_LENGTH_MESSAGE_TOP;
                    }
                }
            } else {
                // 未入力と入力の項目がある
                if (Sgmov_Component_Log::isDebug()) {
                    $checkValueString = Sgmov_Component_String::toDebugString($this->_values);
                    Sgmov_Component_Log::debug("未入力の項目があります。値:{$checkValueString}");
                }
                $this->_result           = self::INVALID_PHONE_CONTAINS_EMPTY;
                $this->_resultMessage    = self::INVALID_PHONE_CONTAINS_EMPTY_MESSAGE;
                $this->_resultMessageTop = self::INVALID_PHONE_CONTAINS_EMPTY_MESSAGE_TOP;
            }
        }

        return $this;
    }

    /**
     * 入力値が有効な電話番号であることを確認します。
     * 入力値が半角数字で、文字数が18文字以下の場合に合格となります。
     * 入力値が空文字列の場合は合格となります。
     * @return Sgmov_Component_Validator 検査後の自インスタンス。
     */
    public function isPhone1() {
        if (count($this->_values) !== 1) {
            throw new Sgmov_Component_Exception('$this->_values の項目数が不正です。' . Sgmov_Component_String::toDebugString($this->_values),
                 Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
        }

        if ($this->isValid()) {
            $value = $this->_values[0];

            if (Sgmov_Component_String::getCount($value) == 0) {
                // 未入力は合格
            } elseif (!ctype_digit($value)) {
                Sgmov_Component_Log::debug("数値以外の文字を含んでいます。値:{$value}");
                $this->_result           = self::INVALID_PHONE_NOT_NUMERIC;
                $this->_resultMessage    = self::INVALID_PHONE_NOT_NUMERIC_MESSAGE;
                $this->_resultMessageTop = self::INVALID_PHONE_NOT_NUMERIC_MESSAGE_TOP;
            } elseif (Sgmov_Component_String::getCount($value) > 18) {
                Sgmov_Component_Log::debug("18文字を超えています。値:{$value}");
                $this->_result           = self::INVALID_PHONE_OVER_LENGTH;
                $this->_resultMessage    = self::INVALID_PHONE_OVER_LENGTH_MESSAGE;
                $this->_resultMessageTop = self::INVALID_PHONE_OVER_LENGTH_MESSAGE_TOP;
            }
        }

        return $this;
    }

    /**
     * 入力値が有効な電話番号であることを確認します。
     * 全ての入力値が半角数字で及びハイフンの場合は合格となります。
     * @return Sgmov_Component_Validator 検査後の自インスタンス。
     */
    public function isPhoneHyphen() {
        if (count($this->_values) !== 1) {
            throw new Sgmov_Component_Exception('$this->_values の項目数が不正です。' . Sgmov_Component_String::toDebugString($this->_values),
                 Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
        }

        if ($this->isValid()) {
            $value = $this->_values[0];

            if (Sgmov_Component_String::getCount($value) == 0) {
                // 未入力は合格
            } elseif (!preg_match('/^[0-9-]+$/u', $value)) {
                Sgmov_Component_Log::debug("数値及びハイフン以外の文字を含んでいます。値:{$value}");
                $this->_result           = self::INVALID_PHONE_NOT_NUMERIC;
                $this->_resultMessage    = self::INVALID_PHONE_NOT_NUMERIC_MESSAGE;
                $this->_resultMessageTop = self::INVALID_PHONE_NOT_NUMERIC_MESSAGE_TOP;
            }
        }

        return $this;
    }

    /**
     * 入力値が有効な住所であることを確認します。
     * 入力値が「該当する住所がありません」の場合に不合格とします。
     * 入力値が空文字列の場合は合格となります。
     * @return Sgmov_Component_Validator 検査後の自インスタンス。
     */
    public function isAddress() {
        if (count($this->_values) != 1) {
            throw new Sgmov_Component_Exception('$this->_values の項目数が不正です。' . Sgmov_Component_String::toDebugString($this->_values),
                 Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
        }

        if ($this->isValid()) {
            if ($this->_values[0] === '該当する住所がありません') {
                Sgmov_Component_Log::debug('入力値が「該当する住所がありません」です。');
                $this->_result           = self::INVALID_ADDRESS_EMPTY;
                $this->_resultMessage    = self::INVALID_ADDRESS_EMPTY_MESSAGE;
                $this->_resultMessageTop = self::INVALID_ADDRESS_EMPTY_MESSAGE_TOP;
            }
        }
        return $this;
    }

    /**
     * 入力値が有効な郵便番号であることを確認します。
     * 入力値の1番目が3桁の数字・2番目が4桁の数字の場合、合格とします。
     * 入力値が両方とも空文字列の場合は合格となります。
     * 入力値のどちらかが空文字列の場合は不合格となります。
     * @return Sgmov_Component_Validator 検査後の自インスタンス。
     */
    public function isZipCode() {
        if (count($this->_values) != 2) {
            throw new Sgmov_Component_Exception('$this->_values の項目数が不正です。' . Sgmov_Component_String::toDebugString($this->_values),
                 Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
        }

        if ($this->isValid()) {
            $value1 = $this->_values[0];
            $value2 = $this->_values[1];

            $len1 = Sgmov_Component_String::getCount($value1);
            $len2 = Sgmov_Component_String::getCount($value2);
            if ($len1 == 0 && $len2 == 0) {
                // どちらも未入力の場合は合格
            } else if ($len1 > 0 && $len2 > 0) {
                // どちらも入力
                $value = $value1 . '-' . $value2;
                if (!preg_match('/^[0-9]{3}-[0-9]{4}$/u', $value)) {
                    if (Sgmov_Component_Log::isDebug()) {
                        $checkValueString = Sgmov_Component_String::toDebugString($this->_values);
                        Sgmov_Component_Log::debug("入力値の形式が不正です。値:{$checkValueString}");
                    }
                    $this->_result           = self::INVALID_ZIPCODE_FORM;
                    $this->_resultMessage    = self::INVALID_ZIPCODE_FORM_MESSAGE;
                    $this->_resultMessageTop = self::INVALID_ZIPCODE_FORM_MESSAGE_TOP;
                }
            } else {
                // どちらかが未入力
                $this->_result           = self::INVALID_ZIPCODE_CONTAINS_EMPTY;
                $this->_resultMessage    = self::INVALID_ZIPCODE_CONTAINS_EMPTY_MESSAGE;
                $this->_resultMessageTop = self::INVALID_ZIPCODE_CONTAINS_EMPTY_MESSAGE_TOP;
            }
        }
        return $this;
    }

    /**
     * 入力値が有効なメールアドレスであることを確認します。
     * 入力値がこのアプリケーション指定のメールアドレス形式に一致する場合に合格とします。
     * 入力値が空文字列の場合は合格となります。
     *
     * 判別部分は function.php のままです。条件は以下の通りです。
     * <ol><li>
     * 空白文字(半角スペース・タブ・改行・ラインフィード・復帰文字) [0文字以上]
     * </li><li>
     * 半角英数・記号 [1文字以上]
     * </li><li>
     * アットマーク(@)
     * </li><li>
     * 半角英数・下線 [1文字以上]
     * </li><li>
     * 半角英数・下線・ピリオド・ハイフン [0文字以上]
     * </li><li>
     * ピリオド
     * </li><li>
     * 半角英数・下線 [2文字以上4文字以下]
     * </li><li>
     * 空白文字(半角スペース・タブ・改行・ラインフィード・復帰文字) [0文字以上]
     * </li></ol>
     *
     * @return Sgmov_Component_Validator 検査後の自インスタンス。
     */
    public function isMail() {
        if (count($this->_values) != 1) {
            throw new Sgmov_Component_Exception('$this->_values の項目数が不正です。' . Sgmov_Component_String::toDebugString($this->_values),
                 Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
        }

        if ($this->isValid()) {
            $value = $this->_values[0];
            $len   = Sgmov_Component_String::getCount($value);
            if ($len > 0) {
                if (!preg_match('/^\s*[ -~]+@\w+[\w\.-]*\.\w{2,4}\s*$/u', $value)) {
                    Sgmov_Component_Log::debug("入力値の形式が不正です。値:{$value}");
                    $this->_result           = self::INVALID_MAIL;
                    $this->_resultMessage    = self::INVALID_MAIL_MESSAGE;
                    $this->_resultMessageTop = self::INVALID_MAIL_MESSAGE_TOP;
                }
            }
        }
        return $this;
    }

    /**
     * 郵便番号が存在する場合に合格とします。
     * 入力値が両方とも空文字列の場合は合格となります。
     * 入力値のどちらかが空文字列の場合は不合格となります。
     *
     * この処理はデータベースアクセスを含むので多少時間がかかります。
     * そのため、{@link isZipCode()}から分離して別メソッドとしています。
     *
     * @return Sgmov_Component_Validator 検査後の自インスタンス。
     */
    public function zipCodeExist() {
        // 形式を確認
        $this->isZipCode();
        if ($this->isValid()) {
            $value1 = $this->_values[0];
            $value2 = $this->_values[1];

            // isZipCode()を通っているので、どちらかが入力されていることを確認すればよい。
            if (Sgmov_Component_String::getCount($value1) > 0) {
                // 郵便番号検索
                $db           = Sgmov_Component_DB::getYubinPublic();
                $yubinService = new Sgmov_Service_Yubin();
                if ($yubinService->countZip($db, $value1 . $value2) == 0) {
                    if (Sgmov_Component_Log::isDebug()) {
                        $checkValueString = Sgmov_Component_String::toDebugString($this->_values);
                        Sgmov_Component_Log::debug("郵便番号が存在しません。値:{$checkValueString}");
                    }
                    $this->_result           = self::INVALID_ZIPCODE_NOT_EXIST;
                    $this->_resultMessage    = self::INVALID_ZIPCODE_NOT_EXIST_MESSAGE;
                    $this->_resultMessageTop = self::INVALID_ZIPCODE_NOT_EXIST_MESSAGE_TOP;
                }
            }
        }
        return $this;
    }

    /**
     * 郵便番号が存在する場合に合格とします。
     * 入力値が両方とも空文字列の場合は合格となります。
     * 入力値のどちらかが空文字列の場合は不合格となります。
     *
     * この処理はデータベースアクセスを含むので多少時間がかかります。
     * そのため、{@link isZipCode()}から分離して別メソッドとしています。
     *
     * @return Sgmov_Component_Validator 検査後の自インスタンス。
     */
    public function zipCodeExistSocket() {
        // 形式を確認
        $this->isZipCode();
        if ($this->isValid()) {
            $value1 = $this->_values[0];
            $value2 = $this->_values[1];

            // isZipCode()を通っているので、どちらかが入力されていることを確認すればよい。
            if (Sgmov_Component_String::getCount($value1) > 0) {
                // 郵便番号検索
                $socketZipCodeDllService = new Sgmov_Service_SocketZipCodeDll();
                $receive = $socketZipCodeDllService->searchByZipCode($value1 . $value2);
                if (empty($receive['FixPoint']) || $receive['FixPoint'] < 2) {
                    if (Sgmov_Component_Log::isDebug()) {
                        $checkValueString = Sgmov_Component_String::toDebugString($this->_values);
                        Sgmov_Component_Log::debug("郵便番号が存在しません。値:{$checkValueString}");
                    }
                    $this->_result           = self::INVALID_ZIPCODE_NOT_EXIST_SOCKET;
                    $this->_resultMessage    = self::INVALID_ZIPCODE_NOT_EXIST_SOCKET_MESSAGE;
                    $this->_resultMessageTop = self::INVALID_ZIPCODE_NOT_EXIST_SOCKET_MESSAGE_TOP;
                }
            }
        }
        return $this;
    }

    /**
     * 郵便番号が集荷不可地区に存在しない場合に合格とします。
     * 入力値が両方とも空文字列の場合は合格となります。
     * 入力値のどちらかが空文字列の場合は不合格となります。
     *
     * この処理はデータベースアクセスを含むので多少時間がかかります。
     * そのため、{@link isZipCode()}から分離して別メソッドとしています。
     *
     * @return Sgmov_Component_Validator 検査後の自インスタンス。
     */
    public function zipCodeCollectable() {
        // 形式を確認
        $this->isZipCode();
        if ($this->isValid()) {
            $value1 = $this->_values[0];
            $value2 = $this->_values[1];

            // isZipCode()を通っているので、どちらかが入力されていることを確認すればよい。
            if (Sgmov_Component_String::getCount($value1) > 0) {
                // 郵便番号検索
                $socketZipCodeDllService = new Sgmov_Service_SocketZipCodeDll();
                $receive = $socketZipCodeDllService->searchByZipCode($value1 . $value2);
                if (!empty($receive['ExchangeFlag'])
                    || !empty($receive['RelayFlag'])
                ) {
                    if (Sgmov_Component_Log::isDebug()) {
                        $checkValueString = Sgmov_Component_String::toDebugString($this->_values);
                        Sgmov_Component_Log::debug("郵便番号が集荷不可地区に存在しました。値:{$checkValueString}");
                    }
                    $this->_result           = self::INVALID_UNCOLLECTABLE_ZIPCODE;
                    $this->_resultMessage    = self::INVALID_UNCOLLECTABLE_ZIPCODE_MESSAGE;
                    $this->_resultMessageTop = self::INVALID_UNCOLLECTABLE_ZIPCODE_MESSAGE_TOP;
                }
            }
        }
        return $this;
    }

    /**
     * 入力値に半角カナが含まれないことを確認します。
     * 入力値に半角カナが含まれる場合に不合格となります。
     * 入力値が空文字列の場合は合格となります。
     *
     * @return Sgmov_Component_Validator 検査後の自インスタンス。
     */
    public function isNotHalfWidthKana() {
        if ($this->isValid()) {
            //配列の場合は1つずつ繰り返す
            foreach ($this->_values as $value) {
                if (preg_match('{[｡-ﾟ]}u', $value)) {
                    Sgmov_Component_Log::debug("半角カナが含まれています。値:{$value}");
                    $this->_result           = self::INVALID_NOT_HALF_WIDTH_KANA;
                    $this->_resultMessage    = self::INVALID_NOT_HALF_WIDTH_KANA_MESSAGE;
                    $this->_resultMessageTop = self::INVALID_NOT_HALF_WIDTH_KANA_MESSAGE_TOP;
                    break;
                }
            }
        }
        return $this;
    }

    /**
     * 入力値にWEBシステム側受付不可字が含まれないことを確認します。
     * 入力値にWEBシステム側受付不可字が含まれる場合に不合格となります。
     * 入力値が空文字列の場合は合格となります。
     *
     * 受付不可字
     * 使用不可能な機種依存文字(JISコード)
     * 34624～34716
     * 60736～61180
     * 64064～64588
     *
     * @return Sgmov_Component_Validator 検査後の自インスタンス。
     */
    public function isWebSystemNg() {
        //NG文字の16進数コードを配列にセット
        $before = array();
        for ($i = 34624; $i <= 34716; $i++) {
            $before[] .= $i;
        }
        for ($i = 60736; $i <= 61180; $i++) {
            $before[] .= $i;
        }
        for ($i = 64064; $i <= 64588; $i++) {
            $before[] .= $i;
        }

        if ($this->isValid()) {
            //配列の場合は1つずつ繰り返す
            foreach ($this->_values as $value) {
                $before_enc = mb_detect_encoding($value, "ASCII,JIS,UTF-8,CP51932,SJIS-win", true);
                $value      = mb_convert_encoding($value, 'sjis-win', $before_enc);
                for ($i = 0; $i < mb_strlen($value, 'sjis-win'); $i++) {
                    $ch  = mb_substr($value, $i, 1, 'sjis-win');
                    $hex = hexdec(bin2hex(mb_substr($value, $i, 1, 'sjis-win')));
                    if (in_array($hex, $before)) {
                        Sgmov_Component_Log::debug("使用不可能な文字を含んでいます。値:{$hex}");
                        $this->_result           = self::INVALID_NOT_WEBSYSTEM;
                        $this->_resultMessage    = self::INVALID_NOT_WEBSYSTEM_MESSAGE . '【' . mb_convert_encoding($ch, $before_enc, 'sjis-win') . '】';
                        $this->_resultMessageTop = self::INVALID_NOT_WEBSYSTEM_MESSAGE_TOP;
                        break;
                    }
                }
            }
        }
        return $this;
    }

    /**
     * 入力値が半角英数であることを確認します。
     *
     * @return Sgmov_Component_Validator 検査後の自インスタンス。
     */
    public function isFlg() {
        if (count($this->_values) != 1) {
            throw new Sgmov_Component_Exception('$this->_values の項目数が不正です。' . Sgmov_Component_String::toDebugString($this->_values),
                 Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
        }

        if ($this->isValid()) {
            $value = $this->_values[0];
            $len   = Sgmov_Component_String::getCount($value);
            if ($len > 0) {
                if (!preg_match('/^[a-zA-Z0-9]+$/', $value)) {
                    Sgmov_Component_Log::debug("入力値の形式が不正です。値:{$value}");
                    $this->_result           = self::INVALID_FLG;
                    $this->_resultMessage    = self::INVALID_FLG_MESSAGE;
                    $this->_resultMessageTop = self::INVALID_FLG_MESSAGE_TOP;
                }
            }
        }
        return $this;
    }

    /**
     * 入力値がDB重複していないことを確認します。
     *
     * @return Sgmov_Component_Validator 検査後の自インスタンス。
     */
    public function isFlgRepet($id) {
        $umu = "";
        if ($this->isValid()) {
            $value = $this->_values[0];

            // 他社連携キャンペーンの取得
            $db = Sgmov_Component_DB::getAdmin();

            $this->_OtherCampaignService = new Sgmov_Service_OtherCampaign();
            $spInfos = $this->_OtherCampaignService->OtherCampaignFlgCheck($db, $id);

            foreach ($spInfos as $spInfo) {
                if ($value == $spInfo['campaign_flg']) {
                    $umu = 1;
                }
            }
            if ($umu == 1) {
                Sgmov_Component_Log::debug('重複です。');
                $this->_result           = self::FLG_REPEAT;
                $this->_resultMessage    = self::FLG_REPEAT_MESSAGE;
                $this->_resultMessageTop = self::FLG_REPEAT_MESSAGE_TOP;
                //break;
            }
        }
        return $this;
    }

    /**
    *
    * 桁数チェック(Microsoft拡張文字もカウント)
    * エスケープを解除して入力データのバイト数をチェックする
    * 「magic_quotes_gpc」が「On」ならエスケープを解除する。
    *
    * @param チェック文字列 $s
    * @param 桁数 $maxCount
    * @return boolean : true:桁数以内、false:桁数オーバー
    */
    public function isOverKetasuMax($maxCount) {

        if (!is_integer($maxCount)) {
            throw new Sgmov_Component_Exception('$maxCount には整数を入力してください。', Sgmov_Component_ErrorCode::ERROR_SYS_ASSERT);
        }

        if ($this->isValid()) {
            foreach ($this->_values as $value) {
                if (get_magic_quotes_gpc()) {
                    $value = stripslashes($value);
                }
                $value = mb_convert_encoding($value, 'SJIS-win', 'utf8');
                $len = strlen(trim($value));
                if ($len > $maxCount) {
                    Sgmov_Component_Log::debug("最大バイトを超えています。最大バイト:{$maxCount}  値:{$value}  文字列バイト:{$len}");
                    $this->_result           = self::INVALID_OVER_LENGTH;
                    $this->_resultMessage    = sprintf('%sバイト以内で入力してください。', $maxCount);
                    $this->_resultMessageTop = sprintf('は%sバイト以内で入力してください。', $maxCount);
                    break;
                }
            }
        }
        return $this;
    }
    /**
     *isLengthLessThanOrEqualToForPhone
     *
     * @return Sgmov_Component_Validator 検査後の自インスタンス
     */
    public function isLengthLessThanOrEqualToForPhone() {
        if ($this->isValid()) {
            foreach ($this->_values as $value) {
                $len = Sgmov_Component_String::getCount($value);
                if (strpos($value, '-') !== false) {
                    if ($len > self::PHONE_LEN_DASH) {
                        Sgmov_Component_Log::debug("最大長を超えています。最大長:".(self::PHONE_LEN_DASH)."  値:{$value}  文字列長:{$len}");
                        $this->_result           = self::INVALID_OVER_LENGTH;
                        $this->_resultMessage    = sprintf(self::INVALID_OVER_LENGTH_MESSAGE, self::PHONE_LEN_DASH);
                        $this->_resultMessageTop = sprintf(self::INVALID_OVER_LENGTH_MESSAGE_TOP, self::PHONE_LEN_DASH);
                        break;
                    }
                } else {
                    if ($len > self::PHONE_LEN_NOT_DASH) {
                        Sgmov_Component_Log::debug("最大長を超えています。最大長:".(self::PHONE_LEN_NOT_DASH)."  値:{$value}  文字列長:{$len}");
                        $this->_result           = self::INVALID_OVER_LENGTH;
                        $this->_resultMessage    = sprintf(self::INVALID_OVER_LENGTH_MESSAGE, self::PHONE_LEN_NOT_DASH);
                        $this->_resultMessageTop = sprintf(self::INVALID_OVER_LENGTH_MESSAGE_TOP, self::PHONE_LEN_NOT_DASH);
                        break;
                    }
                }
            }
        }
        return $this;
    }
    
}