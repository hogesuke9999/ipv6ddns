<html>
<head>
<title>IPv6 DDNS System アドレス更新</title>
<?php
// 設定ファイルの読み込み
include "/opt/ipv6ddns/etc/ipv6ddns.cnf";
?>
</head>

<body>
<h1>IPv6 DDNS System アドレス更新</h1>
<?php

// echo phpinfo();

// ーーーーー 入力チェック ーーーーー
// チェック対象の変数
// ・ユーザ名:          $_GET["user"]
// ・パスワード:        $_GET["pass"]
// ・アドレス:          $_GET["address"] ＜ー引き渡しなし

// 入力チェック用フラグの初期化
$ERRFLAG = 0;

// ユーザ名の入力チェック(空だとNG)
if(empty($_GET["user"])) {
	print "ユーザ名が入力されていません<br>";
	$ERRFLAG = 1;
};

// ユーザ名に対するバリデーション
//  英文字(小文字)、数字、ハイフン(-)のみを許可
if(! preg_match("/^[a-z0-9\-]+$/" , $_GET["user"])) {
	print "指定されたユーザ名は許可されない文字が含まれます<br>";
	$ERRFLAG = 1;
}

// パスワードの入力チェック(空だとNG)
if(empty($_GET["pass"])) {
	print "パスワードが入力されていません<br>";
	$ERRFLAG = 1;
};

// パスワードに対するバリデーション
//  英文字(大文字・小文字)、数字のみを許可
if(! preg_match("/^[a-zA-Z0-9]+$/" , $_GET["pass"])) {
	print "指定されたパスワードは許可されない文字が含まれます<br>";
	$ERRFLAG = 1;
}

// // アドレスの入力チェック(空だとNG)
// if(empty($_GET["address"])) {
// 	print "パスワードが入力されていません<br>";
// 	$ERRFLAG = 1;
// };

// Databaseへの接続
print "DBに接続します<br>";
$db = pg_connect("host=" . $DB_HOST. " port=" . $DB_PORT . " dbname=" . $DB_NAME . " user=" . $DB_USER . " password=" . $DB_PASS)
 or die("DBの接続に失敗しました<br>");

// ユーザ名とパスワードの一致
$result = pg_query_params(
	$db,
	'select count(*) from account_table where user_name = $1 and user_password = $2',
	array($_GET["user"], $_GET["pass"])
);
$ret_value_array = pg_fetch_row ($result);

if($ret_value_array[0] == 0) {
	print "パスワードが正しく入力されていません<br>";
	$ERRFLAG = 1;
} else {
	print "正しいパスワードが入力されました<br>";
};

if($ERRFLAG == 0) {
	print "<table>\n";
		print "<tr>\n";
			print "<td>ユーザ名:</td>\n";
			print "<td>" . $_GET["user"] . "</td>\n";
		print "</tr>\n";
		print "<tr>\n";
			print "<td>IPアドレス:</td>\n";
			print "<td>" . $_SERVER["REMOTE_ADDR"] . "</td>\n";
		print "</tr>\n";
	print "</table>\n";

	$query = "insert into mapping_table (user_name, create_date, mapping_address ) values($1 , now(), $2);";
	$result = pg_query_params($db, $query, array($_GET["user"], $_SERVER["REMOTE_ADDR"]));

	$exec_cmd = "sed -e 's/%DOMAINNAME%/" . $_GET["user"] . ".ddns.hard-v6-today.net/' -e 's/%IPADRS%/" . $_SERVER["REMOTE_ADDR"] . "/' /opt/ipv6ddns/etc/ddns_template | nsupdate";
#	print "<p>" . $exec_cmd . "</p>\n";
	echo exec($exec_cmd);
};

// Databaseの接続解除
print "DB接続を解除します<br>";
pg_close($db);

?>
<hr>
<a href="index.html">トップページへ</a>
</body>

</html>
