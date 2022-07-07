<?php
session_start(); //セッション確認
session_regenerate_id(true); //セッションID変更
if(isset($_SESSION['member_login']) == false){
    print 'ようこそゲスト様   ';
    print '<a href="member_login.html">会員ログイン</a><br>';
    print '<br>';
}else{
    print 'ようこそ';
    print $_SESSION['member_name'];
    print '様   ';
    print '<a href="member_logout.html">ログアウト</a><br>';
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
        //データベース接続
        $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
        $user = 'root';
        $password = '';
        $dbh = new PDO($dsn, $user, $password);
        $dbh -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        //SQL文
        $sql = 'SELECT code,name,price FROM mst_product WHERE 1';
        $stmt = $dbh->prepare($sql);
        $stmt -> execute(); //$stmtの中にデータが入っている

        //データベース切断
        $dbh = null;

        print '商品一覧<br><br>';

        while(true){
            $rec = $stmt -> fetch(PDO::FETCH_ASSOC); //$stmtから1レコードを取り出している

            if($rec == false){
                break;
            }

            print '<a href="shop_product.php?procode='.$rec['code'].'">';
            print $rec['name'].' --- ';
            print $rec['price'].'円';
            print '</a>';
            print '<br>';

        }

        print '<br>';
        print '<a href="shop_cartlook.php">カート見る</a><br>';
        
    }catch(Exception $e){
        print 'ただいま障害により大変ご迷惑をお掛けしております。';
        exit();
    }
    ?>

</body>
</html>