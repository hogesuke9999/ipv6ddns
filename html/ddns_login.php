<html>
<head>
<title>DDNS System 02</title>
</head>

<body>
<h1>DDNS System ユーザ登録確認ページ</h1>
<?php

# ーーーーー 入力チェック ーーーーー

# 入力チェック用フラグの初期化
$ERRFLAG = 0;

if(empty($_POST["user"])) {
	print "ユーザ名が入力されていません";
	$ERRFLAG = 1;
};

# ユーザ名に対するバリデーション
#  英文字(小文字)、数字、ハイフン(-)のみを許可
if(! preg_match("/^[a-z0-9\-]+$/" , $_POST["user"])) {
	print "指定されたユーザ名は許可されない文字が含まれます";
	$ERRFLAG = 1;
}

if(empty($_POST["pass1"])) {
	print "パスワードが入力されていません";
	$ERRFLAG = 1;
};

if(empty($_POST["pass2"])) {
	print "確認用パスワードが入力されていません";
	$ERRFLAG = 1;
};

if($_POST["pass1"] !== $_POST["pass2"]) {
	print "パスワードが正しく入力されていません";
	$ERRFLAG = 1;
};

if($ERRFLAG == 0) {
	print "<table>\n";

	print "<tr>\n";
		print "<td>ユーザ名:</td>\n";
		print "<td>" . $_POST["user"] . "</td>\n";
	print "</tr>\n";

	print "<tr>\n";
		print "<td>パスワード:</td>\n";
		print "<td>" . $_POST["pass1"] . "</td>\n";
	print "</tr>\n";

	print "<tr>\n";
		print "<td>IPアドレス:</td>\n";
		print "<td>" . $_SERVER["REMOTE_ADDR"] . "</td>\n";
	print "</tr>\n";

	print "</table>\n";

	print "DBに接続します<br>";
	$db = pg_connect("host=2001:2e8:65f:0:2:1:0:5 port=5432 dbname=ddns user=ddns password=ddnspass")
	 or die("DBの接続に失敗しました<br>");

	$result = pg_query_params($db, 'select count(*) from ddns where user_name = $1', array($_POST["user"]));
	$ret_value_array = pg_fetch_row ($result);
//	print "ユーザ名" . $_POST["user"] . "は" . $ret_value_array[0] . "です<br>";

	if($ret_value_array[0] == 0) {
		print "ユーザ名" . $_POST["user"] . "は存在しません(" . $ret_value_array[0] . ")<br>";

		print "ユーザ名" . $_POST["user"] . "を登録します<br>";
		$query = "insert into ddns (user_name, user_password, create_date, create_address ) values($1 , $2, now(), $3);";
		$result = pg_query_params($db, $query, array($_POST["user"], $_POST["pass1"], $_SERVER["REMOTE_ADDR"]));

	} else {
		print "ユーザ名" . $_POST["user"] . "は既に存在します(" . $ret_value_array[0] . ")<br>";
	}

//	$sql = "insert into ddns (user_name, user_password, create_date, create_address )";
//	$sql = $sql . " values('" . $_POST["user"] . "', '" . $_POST["pass1"] . "', now(), '". $_SERVER["REMOTE_ADDR"] . "' );";
//	print $sql . "<br>";
//	$result = pg_query($db, $sql);

	print "DB接続を解除します<br>";
	pg_close($db);

};

?>
</body>

</html>
