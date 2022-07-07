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
    require_once('../common/common.php');

    //前の画面から入力データ受け取り
    $post = sanitize($_POST);
    $pro_name = $post['name'];
    $pro_price = $post['price'];
    $pro_gazou_name= $post['gazou_name'];

    //データベース接続
    $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn, $user, $password);
    $dbh -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    //SQL文
    $sql = 'INSERT INTO mst_product(name,price,gazou) VALUES (?,?,?)';
    $stmt = $dbh->prepare($sql);
    $data[] = $pro_name;
    $data[] = $pro_price;
    $data[] = $pro_gazou_name;
    $stmt -> execute($data); //$stmtの中にデータが入っている

    //データベース切断
    $dbh = null;

    print $pro_name;
    print 'を追加しました。<br>';

}catch(Exception $e){
    print 'ただいま障害により大変ご迷惑をおかけしております。';
    exit();
}

?>

<a href ="pro_list.php">戻る</a>

</body>
</html>