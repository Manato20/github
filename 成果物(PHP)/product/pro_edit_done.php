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
    //ファイル読み込み
    require_once('../common/common.php');

    //前の画面からの入力データ受け取り
    $post = sanitize($_POST); 
    $pro_code = $post['code'];
    $pro_name = $post['name'];
    $pro_price = $post['price'];
    $pro_gazou_name_old = $post['gazou_name_old'];
    $pro_gazou_name = $post['gazou_name'];

    //データベース接続
    $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn, $user, $password);
    $dbh -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    //SQL文
    $sql = 'UPDATE mst_product SET name=?, price=?, gazou=? Where code=?';
    $stmt = $dbh->prepare($sql);  
    $data[] = $pro_name;
    $data[] = $pro_price;
    $data[] = $pro_gazou_name;
    $data[] = $pro_code;
    $stmt -> execute($data); //$stmtの中にデータが入っている

    //データベース切断
    $dbh = null;

    print '修正しました。<br>';

    //今の画像と前の画像が違っていたらファイルを削除する
    if($pro_gazou_name_old != $pro_gazou_name){
        if($pro_gazou_name_old != ""){
            unlink('./gazou/'.$pro_gazou_name_old); //ファイル名削除
        }
    }

}catch(Exception $e){
    print 'ただいま障害により大変ご迷惑をおかけしております。';
    exit();
}

?>

<a href ="pro_list.php">戻る</a>

</body>
</html>