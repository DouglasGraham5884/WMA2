<?php 
// 踏まえた

require_once dirname(__FILE__) . "/../functions.php";
require_once dirname(__FILE__) . "/../server/env/dbconnect.php";
date_default_timezone_set("Asia/Tokyo");

class UserLogic {

    /**
     * 新規ユーザーを登録する処理
     * @param array $postData POSTされたユーザーの情報
     * @return array $result 結果
     */
    public static function registUser($postData) {
        // ユーザー入力のサニタイズ&バリデーション
        $user_name = h($postData["user_name"]);
        $email = h($postData["email"]);
        $password = h($postData["password"]);
        $password_conf = h($postData["password_conf"]);
        $token = h($postData["token"]);

        // 不正アクセスの検知
        if(!validateToken($token)) {
            return [
                "result" => false,
                "error" => "Invalid access"
            ];
        }
        
        // 入力漏れが無いか確認
        if(
            $user_name == "" ||
            $email == "" ||
            $password == "" ||
            $password_conf == ""
        ) {
            return [
                "result" => false,
                "error" => "empty"
            ];
        }

        // パスワードが制約通りか確認
        if(!preg_match("/\A[a-z\d]{8,100}+\z/i", $password)) {
            return [
                "result" => false,
                "error" => "パスワードは英数字8文字以上100文字以下にしてください。"
            ];
        }
                    
        // パスワードが一致するか確認
        if($password !== $password_conf) {
            return [
                "result" => false,
                "error" => "no match password"
            ];
        }

        // パスワードをハッシュ化
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            // 既に登録されているユーザーかチェックするSQLの準備
            $sql = "SELECT COUNT(*) FROM Users WHERE email = :email LIMIT 1";
            $stmt = connect() -> prepare($sql);
            $stmt -> bindValue("email", $email);
            $stmt -> execute();
            $SQLResult = $stmt -> fetch();

            if($SQLResult["COUNT(*)"] > 0) {
                return [
                    "result" => false,
                    "error" => "existed email"
                ];
            } else {
                // 新規ユーザーを挿入するSQLの準備
                $sql = "INSERT INTO Users(user_name, email, password_hash) VALUES(:user_name, :email, :password_hash)";
                $stmt = connect() -> prepare($sql);
                $stmt -> bindValue("user_name", $user_name);
                $stmt -> bindValue("email", $email);
                $stmt -> bindValue("password_hash", $password_hash);
                $SQLResult = $stmt -> execute();
                if($SQLResult) {
                    return [
                        "result" => true
                    ];
                } else {
                    return [
                        "result" => false,
                        "error" => "failed insert user"
                    ];
                }
            }
        } catch(\Exception $e) {
            var_error_log([
                "function" => "registUser",
                "getMessage" => $e -> getMessage(),
                "getLine" => $e -> getLine()
            ]);
            return [
                "result" => false,
                "error" => "fatal"
            ];
        }
    }

    /**
     * ログイン処理
     * @param array $postData POSTされたユーザーの情報
     * @return array $result 結果
     */
    public static function login($postData) {
        $email = $_POST["email"];
        $password = $_POST["password"];
        $token = $_POST["token"];

        // 不正アクセスの検知
        if(!validateToken($token)) {
            return [
                "result" => false,
                "error" => "Invalid access"
            ];
        }

        try {
            $sql = "SELECT * FROM Users WHERE email = :email";
            $stmt = connect() -> prepare($sql);
            $stmt -> bindValue("email", $email);
            $stmt -> execute();
            $SQLResult = $stmt -> fetch();

            if($SQLResult) {
                // パスワード検証
                if(password_verify($password, $SQLResult["password_hash"])) {
                    // セッションIDの再生成
                    session_regenerate_id(true);

                    // ユーザー情報をセッションに保存
                    $_SESSION["login_user"] = $SQLResult;
                    return [
                        "result" => true
                    ];
                } else {
                    return [
                        "result" => false,
                        "error" => "パスワードが違います。"
                    ];
                }
            } else {
                return [
                    "result" => false,
                    "error" => "ユーザーが存在しません。"
                ];
            }
        } catch(\Exception $e) {
            var_error_log([
                "function" => "login",
                "getMessage" => $e -> getMessage(),
                "getLine" => $e -> getLine()
            ]);
            return [
                "result" => false,
                "error" => "fatal"
            ];
        }
    }
    
}