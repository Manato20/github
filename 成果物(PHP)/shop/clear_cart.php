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

カートを空にしました。<br>

</body>
</html>