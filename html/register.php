<html>
<head>
<title>IPv6 DDNS System ユーザ登録</title>
</head>

<body>
<h1>IPv6 DDNS System ユーザ登録</h1>
<?php

// ーーーーー 入力チェック ーーーーー
// チェック対象の変数
// ・ユーザ名:          $_POST["user"]
// ・パスワード:        $_POST["pass1"]
// ・パスワード(再入力): $_POST["pass2"]

// 入力チェック用フラグの初期化
$ERRFLAG = 0;

// ユーザ名の入力チェック(空だとNG)
if(empty($_POST["user"])) {
	print "ユーザ名が入力されていません<br>";
	$ERRFLAG = 1;
};

// ユーザ名に対するバリデーション
//  英文字(小文字)、数字、ハイフン(-)のみを許可
if(! preg_match("/^[a-z0-9\-]+$/" , $_POST["user"])) {
	print "指定されたユーザ名は許可されない文字が含まれます<br>";
	$ERRFLAG = 1;
}

// パスワードの入力チェック(空だとNG)
if(empty($_POST["pass1"])) {
	print "パスワードが入力されていません<br>";
	$ERRFLAG = 1;
};

// 再入力パスワードの入力チェック(空だとNG)
if(empty($_POST["pass2"])) {
	print "確認用パスワードが入力されていません<br>";
	$ERRFLAG = 1;
};

if($_POST["pass1"] !== $_POST["pass2"]) {
	print "パスワードが正しく入力されていません";
	$ERRFLAG = 1;
};

// パスワードに対するバリデーション
//  英文字(大文字・小文字)、数字のみを許可
if(! preg_match("/^[a-zA-Z0-9]+$/" , $_POST["pass1"])) {
	print "指定されたパスワードは許可されない文字が含まれます<br>";
	$ERRFLAG = 1;
}

if($ERRFLAG == 0) {
	// Databaseへの接続
	print "DBに接続します<br>";
	$db = pg_connect("host=2001:2e8:65f:0:2:1:0:5 port=5432 dbname=ddns user=ddns password=ddnspass")
	 or die("DBの接続に失敗しました<br>");


	print "以下の内容でユーザを登録しました\n";
	print "<table border=\"1\">\n";

	print "<tr>\n";
		print "<th>項目名</th>\n";
		print "<th>設定内容</th>\n";
		print "<th>備考</th>\n";
	print "</tr>\n";

	print "<tr>\n";
		print "<td>ユーザ名</td>\n";
		print "<td>" . $_POST["user"] . "</td>\n";
		print "<td>DNS名は " . $_POST["user"] . ".ddns.hard-v6-today.net となります</td>\n";
	print "</tr>\n";

	print "<tr>\n";
		print "<td>パスワード</td>\n";
		print "<td>" . $_POST["pass1"] . "</td>\n";
		print "<td></td>\n";
	print "</tr>\n";

	print "<tr>\n";
		print "<td>IPアドレス</td>\n";
		print "<td>" . $_SERVER["REMOTE_ADDR"] . "</td>\n";
		print "<td>このユーザ登録の接続元のIPアドレスです</td>\n";
	print "</tr>\n";

	print "</table>\n";

	$result = pg_query_params($db, 'select count(*) from account_table where user_name = $1', array($_POST["user"]));
	$ret_value_array = pg_fetch_row ($result);

	if($ret_value_array[0] == 0) {
		print "ユーザ名" . $_POST["user"] . "は存在しません(" . $ret_value_array[0] . ")<br>";

		print "ユーザ名" . $_POST["user"] . "を登録します<br>";
		$query = "insert into account_table (user_name, user_password, create_date, create_address ) values($1 , $2, now(), $3);";
		$result = pg_query_params($db, $query, array($_POST["user"], $_POST["pass1"], $_SERVER["REMOTE_ADDR"]));

	} else {
		print "ユーザ名" . $_POST["user"] . "は既に存在します(" . $ret_value_array[0] . ")<br>";
	}

	// Databaseの接続解除
	print "DB接続を解除します<br>";
	pg_close($db);
};

?>
<hr>
<a href="index.html">トップページへ</a>
</body>

</html>
