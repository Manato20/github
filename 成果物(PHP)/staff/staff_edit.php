<?php
session_start(); //セッション確認
session_regenerate_id(true); //セッションID変更
if(isset($_SESSION['login']) == false){
    print 'ログインされていません。<br>';
    print '<a href="../staff_login/staff_login.html">ログイン画面へ</a>';
    exit();
}else{
    print $_SESSION['staff_name'];
    print 'さんログイン中<br>';
    print '<br>';

}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ろくまる農園</title>
</head>
<body>

<?php

try{

    //選択されたスタッフコードを受け取っている
    $staff_code = $_GET['staffcode'];

    //データベース接続
    $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn, $user, $password);
    $dbh -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    //SQL文
    $sql = 'SELECT name FROM mst_staff WHERE code=?';
    $stmt = $dbh -> prepare($sql);
    $data[] = $staff_code;
    $stmt -> execute($data); //$stmtの中にデータが入っている
    
    //$stmtからレコードを取り出している
    $rec = $stmt -> fetch(PDO::FETCH_ASSOC);
    $staff_name = $rec['name'];

    //データベース切断
    $dbh = null;
    
}catch(Exception $e){
    print 'ただいま障害により大変ご迷惑をお掛けしております。';
    exit();
}

?>

スタッフ修正<br>
<br>
スタッフコード<br>
<?php print $staff_code; ?>
<br>
<br>

<form method="post" action="staff_edit_check.php">
    <input type="hidden" name="code" value ="<?php print $staff_code; ?>">

    スタッフ名<br>
    <input type="text" name="name" style="width:200px" value="<?php print $staff_name ?>"><br> <!--初期値$staff_name-->

    パスワードを入力してください。<br>
    <input type="password" name="pass" style="width:100px"><br>
    パスワードをもう一度入力してください。<br>
    <input type="password" name="pass2" style="width:100px"><br>
    <br>
    <input type="button" onclick="history.back()" value="戻る">
    <input type="submit" value="OK">
</form>

</body>
</html>