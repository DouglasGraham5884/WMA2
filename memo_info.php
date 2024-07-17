<?php 

require_once dirname(__FILE__) . "/./classes/DataLogic.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["user_id"])) {
    if($_POST["mode"] == "upload_memo") {

// upload_memo
        DataLogic::uploadMemo($_POST);
    } elseif($_POST["mode"] == "delete_memo") {

// delete_memo
        if(isset($_POST["memo_id"])) {
            DataLogic::deleteMemo($_POST);
        } else {
            echo json_encode([
                "result" => false,
                "error" => "Invalid request"
            ]);
        }
    } elseif($_POST["mode"] == "info") {

// info
        if (isset($_POST['memo_id'])) {
            DataLogic::memoEditorReady($_POST["memo_id"]);
        } else {
            echo json_encode([
                "result" => false,
                "error" => "Invalid request"
            ]);
        }
    
    } elseif($_POST["mode"] == "display_info") {
        
// display_info
        DataLogic::showAllFiles($_POST["user_id"]);
    } elseif($_POST["mode"] == "upload_url") {

// upload_url
        if(isset($_POST["url"], $_POST["page_title"])) {
            DataLogic::uploadURL($_POST);
        } else {
            echo json_encode([
                "result" => false,
                "error" => "Invalid request"
            ]);
        }
    } elseif($_POST["mode"] == "delete_url") {

// delete_url
        if(isset($_POST["url_id"])) {
            DataLogic::deleteURL($_POST);
        } else {
            echo json_encode([
                "result" => false,
                "error" => "Invalid requist"
            ]);
        }
    }
} else {
    echo json_encode([
        "result" => false,
        "error" => "Invalid request"
    ]);
}