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

        //common.phpの変数読み込み
        $post = sanitize($_POST);
        
        //前の画面からの入力データ受け取り
        $staff_code = $post['code'];
        $staff_name = $post['name'];
        $staff_pass = $post['pass'];

        //データベース接続
        $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
        $user = 'root';
        $password = '';
        $dbh = new PDO($dsn, $user, $password);
        $dbh -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        //SQL文
        $sql = 'UPDATE mst_staff SET name=?, password=? WHERE code=?';
        $stmt = $dbh->prepare($sql);
        $data[] = $staff_name;
        $data[] = $staff_pass;
        $data[] = $staff_code;
        $stmt -> execute($data);

        //データベース切断
        $dbh = null;

    }catch(Exception $e){
        print 'ただいま障害により大変ご迷惑をおかけしております。';
        exit();
    }

    ?>

修正しました。<br>
<br>
<a href ="staff_list.php">戻る</a>

</body>
</html>