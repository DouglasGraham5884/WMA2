<?php

$place_name = "Login";

// セッションクッキーの寿命を20分に設定
$cookieLifetime = 1200; // 秒
ini_set('session.gc_maxlifetime', $cookieLifetime);
session_set_cookie_params($cookieLifetime);
session_start();

require_once dirname(__FILE__) . "/./classes/UserLogic.php";

// ログインしていた場合ホームへ遷移
if(isset($_SESSION["login_user"])) {
    header("Location: ./home.php");
}
if($_SERVER["REQUEST_METHOD"] == "POST") {
	$loginResult = UserLogic::login($_POST);
	if($loginResult["result"]) {
		header("Location: ./home.php");
	} else {
		$message = $loginResult["error"];
	}
}
if(isset($_SESSION["message"])) {
	$message = $_SESSION["message"];
	removeMessage();
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
	<link rel="stylesheet" href="./css/login.css">

<!-- Google Adsense -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5987367501461422" crossorigin="anonymous"></script>
</head>
<body>
	<div class="container-M">
		<!-- <h1 class="logo logo-login"><span class="logo-W">W</span><span class="logo-M">M</span><span class="logo-A">A</span><span class="logo-2">2</span></h1> -->
		<p class="wls"><img class ="wma2-logo" src="./img/WMA2.png" alt="wma2_logo"></p>
		<?php if(isset($message)) : ?>
		<p class="session-message"><?= $message; ?></p>
		<?php endif; ?>
		<form action="" class="login" method="POST">
			<input id="token" type="hidden" name="token" value="<?= h(setToken()); ?>">
			<input id="email" type="email" name="email" spellcheck="false" placeholder="email" autofocus>
			<input id="password" type="password" name="password" spellcheck="false" placeholder="password※英数字8～100">
			<button class="submit" type="submit">Login</button>
			<p class="register-link"><a href="./register.php">新規登録</a></p>
		</form>
	</div>
</body>
</html>
