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
    //前の画面からの入力データ受け取り
    $pro_code = $_POST['code'];
    $pro_gazou_name = $_POST['gazou_name'];

    //データベース接続
    $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn, $user, $password);
    $dbh -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    //SQL文
    $sql = 'DELETE FROM mst_product WHERE code=?';
    $stmt = $dbh->prepare($sql);
    $data[] = $pro_code;
    $stmt -> execute($data); //$stmtの中にデータが入っている

    //データベース切断
    $dbh = null;

    //画像があればファイル名削除する
    if($pro_gazou_name != ""){
        unlink('./gazou/'.$pro_gazou_name);
    }

}catch(Exception $e){
    print 'ただいま障害により大変ご迷惑をおかけしております。';
    exit();
}

?>

削除しました。<br>
<br>
<a href ="pro_list.php">戻る</a>

</body>
</html>