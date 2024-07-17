<?php

session_start();

// ログアウトして遷移
$_SESSION = array();
header("Location: ./index.php");

?>