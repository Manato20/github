<?php
session_start(); //セッション確認
$_SESSION = array(); //セッションIDを空にする
if(isset($_COOKIE[session_name()]) == true){
    setcookie(session_name(),'',time()-42000,'/');
}
session_destroy(); //セッションを破棄する
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ろくまる農園</title>
</head>
<body>

ログアウトしました。<br>
<br>
<a href="shop_list.php">商品一覧へ</a>
</body>
</html>