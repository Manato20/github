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

        //前の画面から入力データ受け取り
        $staff_name = $post['name'];
        $staff_pass = $post['pass'];

        //データベース接続
        $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
        $user = 'root';
        $password = '';
        $dbh = new PDO($dsn, $user, $password);
        $dbh -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        //SQL文
        $sql = 'INSERT INTO mst_staff(name,password) VALUES (?,?)';//入れたいデータは?で表現
        $stmt = $dbh->prepare($sql);
        $data[] = $staff_name; //?にセットしたいデータが入っている順番に書く
        $data[] = $staff_pass;
        $stmt -> execute($data); //SQL文で指令を出すための命令

        //データベース切断
        $dbh = null;

        print $staff_name;
        print 'さんを追加しました。<br>';

    }catch(Exception $e){
        print 'ただいま障害により大変ご迷惑をおかけしております。';
        exit();
    }

    ?>

<a href ="staff_list.php">戻る</a>

</body>
</html>