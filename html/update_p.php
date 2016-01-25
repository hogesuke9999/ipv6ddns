<html>
<head>
<title>IPv6 DDNS System アドレス更新</title>
</head>

<body>
<h1>IPv6 DDNS System アドレス更新</h1>
<?php

// ーーーーー 入力チェック ーーーーー
// チェック対象の変数
// ・ユーザ名:          $_POST["user"]
// ・パスワード:        $_POST["pass"]

# 入力チェック用フラグの初期化
$ERRFLAG = 0;

// ユーザ名の入力チェック(空だとNG)
if(empty($_POST["user"])) {
	print "ユーザ名が入力されていません<br>";
	$ERRFLAG = 1;
};

// パスワードの入力チェック(空だとNG)
if(empty($_POST["pass"])) {
	print "パスワードが入力されていません<br>";
	$ERRFLAG = 1;
};

// パスワードに対するバリデーション
//  英文字(大文字・小文字)、数字のみを許可
if(! preg_match("/^[a-zA-Z0-9]+$/" , $_POST["pass"])) {
	print "指定されたパスワードは許可されない文字が含まれます<br>";
	$ERRFLAG = 1;
}

if($ERRFLAG == 0) {
	print "<a href=update.php";
	print "?user=" . $_POST["user"];
	print "&pass=" . $_POST["pass"];
//	print "&address=" . $_SERVER["REMOTE_ADDR"];
	print ">アドレス更新</a>";
};

?>
<hr>
<a href="index.html">トップページへ</a>
</body>

</html>
