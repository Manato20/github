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
        $pro_code = $_GET['procode'];

        //データベース接続
        $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
        $user = 'root';
        $password = '';
        $dbh = new PDO($dsn, $user, $password);
        $dbh -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        //SQL文
        $sql = 'SELECT name,price,gazou FROM mst_product WHERE code=?';
        $stmt = $dbh -> prepare($sql);
        $data[] = $pro_code;
        $stmt -> execute($data); //$stmtの中にデータが入っている

        //$stmtからレコードを取り出している
        $rec = $stmt -> fetch(PDO::FETCH_ASSOC);
        $pro_name = $rec['name'];
        $pro_price = $rec['price'];
        $pro_gazou_name_old = $rec['gazou'];

        //データベース切断
        $dbh = null;

        //画像があるか判定
        if($pro_gazou_name_old == ""){

            $disp_gazou = "";
        }else{

            $disp_gazou = '<img src="./gazou/'.$pro_gazou_name_old.'">';
        }
        
    }catch(Exception $e){
        print 'ただいま障害により大変ご迷惑をお掛けしております。';
        exit();
    }

    ?>

    商品修正<br>
    <br>
    商品コード<br>
    <?php print $pro_code; ?>
    <br>
    <br>

    <form method="post" action="pro_edit_check.php" enctype="multipart/form-data"> <!-- enctypeで画像が扱えるようになる -->
        <input type="hidden" name="code" value ="<?php print $pro_code; ?>">
        <input type="hidden" name="gazou_name_old" value="<?php print $pro_gazou_name_old; ?>">

        商品名<br>
        <input type="text" name="name" style="width:200px" value="<?php print $pro_name ?>"><br>

        価格を入力してください。<br>
        <input type="text" name="price" style="width: 50px" value="<?php print $pro_price; ?>"><br>
        <br>
        <?php print $disp_gazou; ?>
        <br>
        画像を選んでください。<br>
        <input type="file" name="gazou" style="width:400px"><br>
        <br>
        <input type="button" onclick="history.back()" value="戻る">
        <input type="submit" value="OK">
    </form>

</body>
</html>