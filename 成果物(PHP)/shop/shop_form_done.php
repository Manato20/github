<?php
session_start(); //セッション確認
session_regenerate_id(true); //セッションID変更
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
        $onamae = $post['onamae'];
        $email = $post['email'];
        $postal1 = $post['postal1'];
        $postal2 = $post['postal2'];
        $address = $post['address'];
        $tel = $post['tel'];
        $chumon = $post['chumon'];
        $pass = $post['pass'];
        $danjo = $post['danjo'];
        $birth = $post['birth'];

        print $onamae . "様<br>";
        print "ご注文ありがとうございました。<br>";
        print $email . "にメールを送りましたのでご確認ください。<br>";
        print "商品は以下の住所に発送させていただきます。<br>";
        print $postal1 . "-" . $postal2 . "<br>";
        print $address . "<br>";
        print $tel . '<br>';

        $honbun = "";
        $honbun .= $onamae . "様 \n\n この度はご注文ありがとうございました。\n";
        $honbun .= "\n";
        $honbun .= "ご注文商品\n";
        $honbun .= "--------------\n";

        $cart = $_SESSION['cart']; //保管していたカートの内容を戻す
        $kazu = $_SESSION['kazu'];
        $max = count($cart); //データ数

        //データベース接続
        $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
        $user = 'root';
        $password = '';
        $dbh = new PDO($dsn, $user, $password);
        $dbh -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        for($i=0; $i < $max; $i++){

            //SQL文
            $sql = 'SELECT name,price FROM mst_product WHERE code=?';
            $stmt = $dbh->prepare($sql);
            $data[0] = $cart[$i];
            $stmt -> execute($data); //$stmtの中にデータが入っている

            $rec = $stmt->fetch(PDO::FETCH_ASSOC); //$stmtから1レコードを取り出している

            $name = $rec['name'];
            $price = $rec['price'];
            $kakaku[] = $price;
            $suryo = $kazu[$i];
            $shokei = $price * $suryo;

            $honbun .= $name . " ";
            $honbun .= $price . "円 × ";
            $honbun .= $suryo . "個 = ";
            $honbun .= $shokei . "円 \n";

        }

        //SQL文(キュー開始)
        $sql = 'LOCK TABLES dat_sales WRITE,dat_sales_product WRITE';
        $stmt = $dbh -> prepare($sql);
        $stmt -> execute();

        $lastmembercode = 0;
        if($chumon == 'chumontouroku'){
            $sql = 'INSERT INTO dat_member(password,name,email,postal1,postal2,address,tel,danjo,born) VALUES(?,?,?,?,?,?,?,?,?)';
            $stmt = $dbh -> prepare($sql);
            $data = array();
            $data[] = md5($pass);
            $data[] = $onamae;
            $data[] = $email;
            $data[] = $postal1;
            $data[] = $postal2;
            $data[] = $address;
            $data[] = $tel;

            if($danjo == 'dan'){
                $data[] = 1;
            }else{
                $data[] = 2;
            }

            $data[] = $birth;
            $stmt -> execute($data); //$stmtの中にすべてのデータが入っている

            $sql = 'SELECT LAST_INSERT_ID()';
            $stmt = $dbh -> prepare($sql);
            $stmt -> execute();
            $rec=$stmt->fetch(PDO::FETCH_ASSOC); //$stmtから1レコードを取り出している
            $lastmembercode=$rec['LAST_INSERT_ID()'];

        }

        //SQL文
        $sql = 'INSERT INTO dat_sales (code_member,name,email,postal1,postal2,address,tel) VALUES(?,?,?,?,?,?,?)';
        $stmt = $dbh -> prepare($sql);
        $data = array(); //配列をクリア
        $data[] = $lastmembercode;
        $data[] = $onamae;
        $data[] = $email;
        $data[] = $postal1;
        $data[] = $postal2;
        $data[] = $address;
        $data[] = $tel;
        $stmt -> execute($data); //$stmtの中にすべてのデータが入っている

        //SQL文
        $sql='SELECT LAST_INSERT_ID()';
        $stmt=$dbh->prepare($sql);
        $stmt->execute(); //$stmtの中にすべてのデータが入っている
        $rec=$stmt->fetch(PDO::FETCH_ASSOC); //$stmtから1レコードを取り出している
        $lastcode=$rec['LAST_INSERT_ID()'];

        //SQL文
        for($i=0;$i<$max;$i++){
	        $sql='INSERT INTO dat_sales_product (code_sales,code_product,price,quantity) VALUES (?,?,?,?)';
	        $stmt=$dbh->prepare($sql);
	        $data=array();
            $data[]=$lastcode;
            $data[]=$cart[$i];
            $data[]=$kakaku[$i];
            $data[]=$kazu[$i];
            $stmt->execute($data); //$stmtの中にすべてのデータが入っている
        }

        //SQL文(キュー解除)
        $sql = 'UNLOCK TABLES';
        $stmt = $dbh -> prepare($sql);
        $stmt -> execute();

        //カートを空にする
        $_SESSION = array(); //セッションIDを空にする
        if(isset($_COOKIE[session_name()]) == true){
            setcookie(session_name(),'',time()-42000,'/');
        }
        session_destroy(); //セッションを破棄する

        //データベース切断
        $dbh = null;

        if($chumon == 'chumontouroku'){
            print '会員登録が完了いたしました。<br>';
            print '次回からメールアドレスとパスワードでログインしてください。<br>';
            print 'ご注文が簡単にできるようになります。';
            print '<br>';
        }

        $honbun .= "送料は無料です。\n";
        $honbun .= "--------------\n";
        $honbun .= "\n";
        $honbun .= "代金は以下の口座にお振込みください。\n";
        $honbun .= "ろくまる銀行　やさい支店　普通口座　1234567 \n";
        $honbun .= "入金確認が取れ次第、梱包、発送させていただきます。\n";
        $honbun .= "\n";

        if($chumon == 'chumontouroku'){
            $honbun .= " 会員登録が完了いたしました。 \n";
            $honbun .= " 次回からメールアドレスとパスワードでログインしてください。 \n";
            $honbun .= " ご注文が簡単にできるようになります。 \n";
            $honbun .= " \n";
        }

        $honbun .= "□□□□□□□□□□□□□□□□□□□□ \n";
        $honbun .= " ～安心野菜のろくまる農園～ \n";
        $honbun .= "\n";
        $honbun .= "兵庫県六丸群六丸村 123-4\n";
        $honbun .= "電話 090-6060-xxxx\n";
        $honbun .= "メール info@rokumarunouen.co.jp\n";
        $honbun .= "□□□□□□□□□□□□□□□□□□□□ \n";

        //後に確認する用
        //print '<br>';
        //print nl2br($honbun);

        //顧客当てメール
        $title = "ご注文ありがとうございます。"; //メールタイトル
        $header = "From:info@rokumarunouen.co.jp"; //送信元(お店側のメールアドレス)
        $honbun = html_entity_decode($honbun, ENT_QUOTES, 'UTF-8');
        mb_language('Japanese');
        mb_internal_encoding('UTF-8');
        mb_send_mail($email,$title,$honbun,$header); //メールを送信する命令($emailが送信先)

        //お店当てメール
        $title = "お客様からご注文がありました。"; //メールタイトル
        $header = "From:" . $email;
        $honbun = html_entity_decode($honbun, ENT_QUOTES, 'UTF-8');
        mb_language('Japanese');
        mb_internal_encoding('UTF-8');
        mb_send_mail("From:info@rokumarunouen.co.jp",$title,$honbun,$header);

    }catch(Exception $e){
        print "ただいま障害により大変ご迷惑をお掛けしております。";
        exit();
    }
    ?>

    <br>
    <a href="shop_list.php">商品画面へ</a>

</body>
</html>