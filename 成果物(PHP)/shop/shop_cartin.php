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

        //前の画面からURLパラメータ受け取り
        $pro_code = $_GET['procode'];

        //もしカートに商品が入っていれば
        if(isset($_SESSION['cart']) == true){
            $cart = $_SESSION['cart']; //現在のカート内容を$cartにコピー
            $kazu = $_SESSION['kazu'];
            if(in_array($pro_code,$cart) == true){
                print 'その商品はすでにカートに入っています。<br>';
                print '<a href="shop_list.php">商品一覧に戻る</a>';
                exit();
            }
        }

        $cart[] = $pro_code; //カートに商品を追加する
        $kazu[] = 1;
        $_SESSION['cart'] = $cart; //$_SESSIONにカートを保管する
        $_SESSION['kazu'] = $kazu;
        
    }catch(Exception $e){
        print 'ただいま障害により大変ご迷惑をお掛けしております。';
        exit();
    }

    ?>

    カートに追加しました。<br>
    <br>
    <a href="shop_list.php">商品一覧に戻る</a>

</body>
</html>