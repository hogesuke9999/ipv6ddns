<html>
<head>
<title>IPv6 DDNS System ユーザ情報変更</title>
</head>

<body>
<h1>IPv6 DDNS System ユーザ情報変更</h1>
<?php

// ーーーーー 入力チェック ーーーーー
// チェック対象の変数
// ・ユーザ名:               $_POST["user"]
// ・現在のパスワード:        $_POST["pass_now"]
// ・新しいパスワード:        $_POST["pass_new1"]
// ・新しいパスワード(再入力): $_POST["pass_new2"]

// 入力チェック用フラグの初期化
$ERRFLAG = 0;

// ユーザ名の入力チェック(空だとNG)
if(empty($_POST["user"])) {
	print "ユーザ名が入力されていません<br>";
	$ERRFLAG = 1;
};

// 現在のパスワードの入力チェック(空だとNG)
if(empty($_POST["pass_now"])) {
	print "現在のパスワードが入力されていません<br>";
	$ERRFLAG = 1;
};

// 現在のパスワードの入力チェック(空だとNG)
if(empty($_POST["pass_new1"])) {
	print "新しいパスワードが入力されていません<br>";
	$ERRFLAG = 1;
};

// 再入力パスワードの入力チェック(空だとNG)
if(empty($_POST["pass_new2"])) {
	print "確認用パスワードが入力されていません<br>";
	$ERRFLAG = 1;
};

if($_POST["pass_new1"] !== $_POST["pass_new2"]) {
	print "新しいパスワードが正しく入力されていません<br>";
	$ERRFLAG = 1;
};

// パスワードに対するバリデーション
//  英文字(大文字・小文字)、数字のみを許可
if(! preg_match("/^[a-zA-Z0-9]+$/" , $_POST["pass_new1"])) {
	print "指定されたパスワードは許可されない文字が含まれます<br>";
	$ERRFLAG = 1;
}

if($ERRFLAG == 0) {
	// Databaseへの接続
	print "DBに接続します<br>";
	$db = pg_connect("host=2001:2e8:65f:0:2:1:0:5 port=5432 dbname=ddns user=ddns password=ddnspass")
 	or die("DBの接続に失敗しました<br>");

	// ユーザ名とパスワードの一致
	$result = pg_query_params(
		$db,
		'select count(*) from account_table where user_name = $1 and user_password = $2',
		array($_POST["user"], $_POST["pass_now"])
		);
	$ret_value_array = pg_fetch_row ($result);

	if($ret_value_array[0] == 0) {
		print "パスワードが正しく入力されていません<br>";
		$ERRFLAG = 1;
	} else {
		print "正しいパスワードが入力されました<br>";
	};

	print "<table>\n";

	print "<tr>\n";
		print "<td>ユーザ名:</td>\n";
		print "<td>" . $_POST["user"] . "</td>\n";
	print "</tr>\n";

	print "<tr>\n";
		print "<td>パスワード:</td>\n";
		print "<td>" . $_POST["pass_new1"] . "</td>\n";
	print "</tr>\n";

	print "<tr>\n";
		print "<td>IPアドレス:</td>\n";
		print "<td>" . $_SERVER["REMOTE_ADDR"] . "</td>\n";
	print "</tr>\n";

	print "</table>\n";

	$query = "update account_table set user_password = $2, create_date = now(), create_address = $3 where user_name = $1" ;
	$result = pg_query_params($db, $query, array($_POST["user"], $_POST["pass_new1"], $_SERVER["REMOTE_ADDR"]));

	// Databaseの接続解除
	print "DB接続を解除します<br>";
	pg_close($db);
};

?>
<hr>
<a href="ddns.html"  >トップページへ</a>
</body>

</html>
