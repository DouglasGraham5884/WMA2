<?php

/**
 * エスケープ処理
 * @param string $str 対象の文字列
 * @return string $result 処理された文字列
 */
function h($str) {
    if($str) {
        return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
    } else {
        return NULL;
    }
}

/**
 * ワンタイムトークンをセットする
 * @param void
 * @return string token
 */
function setToken() {
    return $_SESSION["token"] = bin2hex(random_bytes(32));
}

/**
 * 正しいフォームから送られた情報かを確認する
 * @param string token 送られてきたトークン
 * @return bool 不正なアクセスかどうかの結果
 */
function validateToken($token) {
    if(
        empty($_SESSION["token"]) ||
        $_SESSION["token"] !== filter_input(INPUT_POST, "token")
    ) {
        return false;
    }
    removeToken();
    return true;
}

/**
 * セッションのメッセージを削除する
 * @param void
 * @return void
 */
function removeMessage() {
    // $_SESSION["message"] = array();
    unset($_SESSION["message"]);
}

/**
 * セッションのトークンを削除する
 * @param void
 * @return void
 */
function removeToken() {
    unset($_SESSION["token"]);
}

/**
 * var_dump_logにvar_dump結果を追記する
 * var_dump_logを常にtail -f var_dump_log等でディスプレイしておくように
 * @param array var_dump_content var_dumpする配列
 * @return void
 */
function var_dump_log($var_dump_content) {
    ob_start();
    var_dump($var_dump_content);
    $out = ob_get_contents();
    ob_end_clean();

    $line = "---------------".date('y/m/d_H:i:s')."---------------";
    file_put_contents('var_dump_log', $line."\n", FILE_APPEND);
    file_put_contents('var_dump_log', $out."\n", FILE_APPEND);
}

/**
 * error_logにvar_dump結果を追記する
 * var_dump_logを常にtail -f var_dump_log等でディスプレイしておくように
 * @param array var_dump_content var_dumpする配列
 * @return void
 */
function var_error_log($var_dump_content) {
    ob_start();
    var_dump($var_dump_content);
    $out = ob_get_contents();
    ob_end_clean();

    $line = "---------------".date('y/m/d_H:i:s')."---------------";
    file_put_contents('var_error_log', $line."\n", FILE_APPEND);
    file_put_contents('var_error_log', $out."\n", FILE_APPEND);
}