<html>

<head>
<title>DDNS System 11</title>
</head>

<body>
<?php

print "IPアドレスは" . $_SERVER["REMOTE_ADDR"] . "です<br>\n";

if(empty($_GET["user"])) {
	print "ユーザ名は指定されていません<br>\n";
} else {
	print "ユーザ名は" . $_GET["user"] . "です<br>\n";
}

if(empty($_GET["pass"])) {
	print "パスワードは指定されていません<br>\n";
} else {
	print "パスワードは" . $_GET["pass"] . "です<br>\n";
}

$IPADRS_b = inet_pton( $result[0][ip] );
$IPADRS_t = inet_ntop( $IPADRS_b );

print "整形IPアドレス(前)は" . $IPADRS_b . "です<br>\n";
print "整形IPアドレス(後)は" . $IPADRS_t . "です<br>\n";


phpinfo();
?>
</body>

</html>
