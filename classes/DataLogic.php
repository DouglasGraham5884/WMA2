<?php

require_once dirname(__FILE__) . "/../functions.php";
require_once dirname(__FILE__) . "/../server/env/dbconnect.php";

class DataLogic {

    /**
     * エディターに表示するためのメモの情報をmemo_idをもとに取得する
     * json形式で結果を表示し、失敗時にはエラーログを記述する
     * @param string $memo_id
     * @param array $result
     */
    public static function memoEditorReady($memo_id) {
        try {
            $sql = "SELECT Memos.title, Memos.created_at, Memos.updated_at, TextMemos.content, URLs.url_id, URLs.url, URLs.page_title FROM Memos JOIN TextMemos ON Memos.memo_id = TextMemos.memo_id LEFT JOIN MemoURLs ON Memos.memo_id = MemoURLs.memo_id LEFT JOIN URLs ON MemoURLs.url_id = URLs.url_id WHERE Memos.memo_id = :memo_id";
            $stmt = connect() -> prepare($sql);
            $stmt -> bindValue("memo_id", $memo_id, PDO::PARAM_INT);
            $stmt -> execute();
            $SQLResult = $stmt -> fetchAll();

            if($SQLResult) {
                $result = [
                    "result" => true,
                    "memoData" => $SQLResult
                ];
                echo json_encode($result);
            } else {
                $result = [
                    "result" => false,
                    "error" => "Not found"
                ];
                echo json_encode($result);
            }
        } catch(\Exception $e) {
            var_error_log([
                "function" => "memoEditorReady",
                "getMessage" => $e -> getMessage(),
                "getLine" => $e -> getLine()
            ]);
            $result = [
                "result" => false,
                "error" => "fatal"
            ];
            echo json_encode($result);
        }
    }

    /**
     * データベースのMemosテーブルのデータを更新したり追加したりする処理
     * 関連するテーブル（TextMemos）も操作する
     * json形式で結果を表示し、失敗時にはエラーログを記述する
     * @param array $formData
     * @return array $result
     */
    public static function uploadMemo($formData) {
        
        // Memos
        $user_id = $formData["user_id"];
        $title = $formData["title"];
        $content = $formData["content"];
        $memo_id = $formData["memo_id"] ?? NULL;
        // 最初にMemosテーブルの登録・更新を済ませる
        try {
            $pdo = connect();
            $pdo -> beginTransaction();
            if(!$memo_id) {
                // 新しいメモを作成
                $sql = "INSERT INTO Memos(user_id, title) VALUES(:user_id, :title)";
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindValue("user_id", $user_id, PDO::PARAM_INT);
                $stmt -> bindValue("title", $title);
                $stmt -> execute();
                $memo_id = $pdo -> lastInsertId(); // 新しいメモのIDを取得
            } else {
                // 既存のメモを更新
                $sql = "UPDATE Memos SET title = :title, updated_at = CURRENT_TIMESTAMP  WHERE memo_id = :memo_id AND user_id = :user_id";
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindValue("title", $title);
                $stmt -> bindValue("memo_id", $memo_id, PDO::PARAM_INT);
                $stmt -> bindValue("user_id", $user_id, PDO::PARAM_INT);
                $stmt -> execute();
            }
            
            // TextMemosテーブルの更新
            $sql = "INSERT INTO TextMemos(memo_id, content) VALUES(:memo_id, :content) ON DUPLICATE KEY UPDATE content = :content";
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindValue("memo_id", $memo_id, PDO::PARAM_INT);
            $stmt -> bindValue("content", $content);
            $stmt -> execute();

            $pdo -> commit(); // トランザクションのコミット
            $result = [
                "result" => true,
                "memo_id" => $memo_id
            ];
            echo json_encode($result);
        } catch(\Exception $e) {
            $pdo -> rollBack();
            var_error_log([
                "function" => "uploadMemo",
                "getMessage" => $e -> getMessage(),
                "getLine" => $e -> getLine()
            ]);
            $result = [
                "result" => false,
                "error" => "fatal"
            ];
            echo json_encode($result);
        }
    }

    /**
     * ユーザーのすべてのメモ情報をuser_idをもとに取得する
     * json形式で結果を表示し、失敗時にはエラーログを記述する
     * @param string $user_id
     * @return array $result
     */
    public static function showAllFiles($user_id) {
        try {
            $pdo = connect();
            $sql = "SELECT Memos.memo_id, Memos.title, Memos.created_at, Memos.updated_at, TextMemos.content FROM Memos JOIN TextMemos ON Memos.memo_id = TextMemos.memo_id WHERE Memos.user_id = :user_id ORDER BY Memos.updated_at DESC";
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindValue("user_id", $user_id, PDO::PARAM_INT);
            $stmt -> execute();
            $result = [
                "result" => true,
                "fileData" => $stmt -> fetchAll()
            ];
            echo json_encode($result);
            
            
        } catch(\Exception $e) {
            var_error_log([
                "function" => "showAllFiles",
                "getMessage" => $e -> getMessage(),
                "getLine" => $e -> getLine()
            ]);
            $result = [
                "result" => false,
                "error" => "fatal"
            ];
            echo json_encode($result);
        }
    }

    /**
     * データベースのURLsテーブルのデータを更新したり追加したりする処理
     * 関連するテーブル（Memos, TextMemos）も操作する
     * json形式で結果を表示し、失敗時にはエラーログを記述する
     * @param array $formData
     * @return array $result
     */
    public static function uploadURL($formData) {
        $pdo = connect();

        // Memos
        $user_id = $formData["user_id"];
        $title = $formData["title"] ?? "";
        $content = $formData["content"] ?? "";
        $url = $formData["url"];
        $page_title = $formData["page_title"];
        $memo_id = $formData["memo_id"] ?? NULL;
        $url_id = $formData["url_id"] ?? NULL;

        try {
            $pdo -> beginTransaction(); // トランザクションの開始
            
            // 新規メモの場合、先にMemosテーブルに登録する
            if(!$memo_id) {
                // Memosテーブルの更新
                $sql = "INSERT INTO Memos(user_id, title) VALUES(:user_id, :title)";
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindValue("user_id", $user_id, PDO::PARAM_INT);
                $stmt -> bindValue("title", $title);
                $stmt -> execute();
                $memo_id = $pdo -> lastInsertId(); // 新しいメモのIDを取得

                // TextMemosテーブルの更新
                $sql = "INSERT INTO TextMemos(memo_id, content) VALUES(:memo_id, :content) ON DUPLICATE KEY UPDATE content = :content";
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindValue("memo_id", $memo_id, PDO::PARAM_INT);
                $stmt -> bindValue("content", $content);
                $stmt -> execute();
            }

            // URLsテーブルの更新
            if(isset($url_id)) {
                $sql = "UPDATE URLs SET url = :url, page_title = :page_title WHERE url_id = :url_id";
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindValue("url", $url);
                $stmt -> bindValue("page_title", $page_title);
                $stmt -> bindValue("url_id", $url_id, PDO::PARAM_INT);
                $stmt -> execute();
            } else {
                $sql = "INSERT INTO URLs(url, page_title) VALUES(:url, :page_title)";
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindValue("url", $url);
                $stmt -> bindValue("page_title", $page_title);
                $stmt -> execute();
                $url_id = $pdo -> lastInsertId(); // 新しいURLのIDを取得

                $sql = "INSERT INTO MemoURLs(memo_id, url_id) VALUES(:memo_id, :url_id)";
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindValue("memo_id", $memo_id, PDO::PARAM_INT);
                $stmt -> bindValue("url_id", $url_id, PDO::PARAM_INT);
                $stmt -> execute();
            }

            // Memosテーブルのupdated_atフィールドを更新
            $sql = "UPDATE Memos SET updated_at = CURRENT_TIMESTAMP WHERE memo_id = :memo_id";
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindValue("memo_id", $memo_id, PDO::PARAM_INT);
            $stmt -> execute();

            $pdo -> commit(); // トランザクションのコミット
            $result = [
                "result" => true,
                "memo_id" => $memo_id
            ];
            echo json_encode($result);
        } catch(\Exception $e) {
            $pdo -> rollBack();
            var_error_log([
                "function" => "insertNewURL",
                "getMessage" => $e -> getMessage(),
                "getLine" => $e -> getLine()
            ]);
            $result = [
                "result" => false,
                "error" => "fatal"
            ];
            echo json_encode($result);
        }
    }

    /**
     * データベースのURLsテーブルのデータをurl_idをもとに削除する処理
     * ONE DELETE CASCADEによりMemoURLsテーブルも自動更新
     * json形式で結果を表示し、失敗時にはエラーログを記述する
     */
    public static function deleteURL($formData) {
        $pdo = connect();

        $memo_id = $formData["memo_id"];
        $url_id = $formData["url_id"];

        try {
            $pdo -> beginTransaction();

            $sql = "DELETE FROM URLs WHERE url_id = :url_id";
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindValue("url_id", $url_id, PDO::PARAM_INT);
            $stmt -> execute();

            // Memosテーブルのupdated_atフィールドを更新
            $sql = "UPDATE Memos SET updated_at = CURRENT_TIMESTAMP WHERE memo_id = :memo_id";
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindValue("memo_id", $memo_id, PDO::PARAM_INT);
            $stmt -> execute();

            $pdo -> commit();
            $result = [
                "result" => true
            ];
            echo json_encode($result);
        } catch(\Excepiton $e) {
            $pdo -> rollBack();
            var_error_log([
                "function" => "deleteURL",
                "getMessage" => $e -> getMessage(),
                "getLine" => $e -> getLine()
            ]);
            $result = [
                "result" => false,
                "error" => "fatal"
            ];
            echo json_encode($result);
        }
    }

    /**
     * データベースのMemosテーブルのデータをmemo_idをもとに削除する処理
     * ONE DELETE CASCADEによりMemoURLsテーブルも自動更新
     * json形式で結果を表示し、失敗時にはエラーログを記述する
     */
    public static function deleteMemo($formData) {
        $pdo = connect();

        $memo_id = $formData["memo_id"];

        try {
            $pdo -> beginTransaction();

            $sql = "DELETE FROM Memos WHERE memo_id = :memo_id";
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindValue("memo_id", $memo_id, PDO::PARAM_INT);
            $stmt -> execute();

            $pdo -> commit();
            $result = [
                "result" => true
            ];
            echo json_encode($result);
        } catch(\Exception $e) {
            $pdo -> rollBack();
            var_error_log([
                "function" => "deleteMemo",
                "getMessage" => $e -> getMessage(),
                "getLine" => $e -> getLine()
            ]);
            $result = [
                "result" => false,
                "error" => "fatal"
            ];
            echo json_encode($result);
        }
    }
    
}