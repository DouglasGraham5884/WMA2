<?php

// セッションを開始
$place_name = "Register";
session_start();
require_once dirname(__FILE__) . "/./classes/UserLogic.php";
// require_once dirname(__FILE__) . "/./classes/DataLogic.php";

// ログインしていた場合ホームへ遷移
if(isset($_SESSION["login_user"])) {
    header("Location: ./home.php");
}
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $registUserResult = UserLogic::registUser($_POST);
    if($registUserResult["result"]) {
        $_SESSION["message"] = '登録が完了しました。';
        header("Location: ./index.php");
    } else {
        $message = $registUserResult["error"];
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $place_name; ?> | WMA2</title>

	<!-- CSS -->
	<!-- <base href="/wma2/"> -->
	<link rel="stylesheet" href="./css/styles.css">
	<link rel="stylesheet" href="./css/register.css">
</head>
<body>
	<div class="container-M">
		<!-- <h1 class="logo logo-register"><span class="logo-W">W</span><span class="logo-M">M</span><span class="logo-A">A</span><span class="logo-2">2</span></h1> -->
		<p class="wls"><img class ="wma2-logo" src="./img/WMA2.png" alt="wma2_logo"></p>
		<?php if(isset($message)) : ?>
		<p class="session-message"><?= $message; ?></p>
		<?php endif; ?>
		<form action="" class="register" method="POST">
            <input id="token" type="hidden" name="token" value="<?= h(setToken()); ?>">
            <input id="user_name" type="text" name="user_name" spellcheck="false" placeholder="user_name">
			<input id="email" type="email" name="email" spellcheck="false" placeholder="email" autofocus>
			<input id="password" type="password" name="password" spellcheck="false" placeholder="password※英数字8～100">
			<input id="password_conf" type="password" name="password_conf" spellcheck="false" placeholder="password_conf">
			<button class="submit" type="submit">Sign in</button>
			<p class="login-link"><a href="./index.php">ログイン</a></p>
		</form>
	</div>
</body>
</html>
