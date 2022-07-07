<?php

try{
    //ファイル読み込み
    require_once('../common/common.php');
    
    //common.phpの変数読み込み
    $post = sanitize($_POST);

    //前の画面からの入力データ受け取り
    $member_email = $post['email'];
    $member_pass = $post['pass'];

    //パスワード暗号化
    $member_pass = md5($member_pass);

    //データベース接続
    $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn,$user,$password);
    $dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //SQL文
    $sql = 'SELECT code,name FROM dat_member Where email=? AND password=?';
    $stmt = $dbh -> prepare($sql);
    $data[] = $member_email;
    $data[] = $member_pass;
    $stmt -> execute($data); //$stmtの中にデータが入っている

    //データベース切断
    $dbh = null;

    //$stmtからレコードを取り出している
    $rec = $stmt -> fetch(PDO::FETCH_ASSOC);

    //該当するスタッフがいるか確認
    if($rec == false){
        print 'メールアドレスかパスワードが間違ってます。';
        print '<a href="member_login.html">戻る</a>';
    }else{
        //セッション開始
        session_start();
        $_SESSION['member_login'] = 1;
        $_SESSION['member_code'] = $rec['code'];
        $_SESSION['member_name'] = $rec['name'];
        header('Location:staff_list.php');
        exit();
    }

}catch(Exception $e){
    print 'ただいま障害により大変ご迷惑をお掛けしております。';
    exit();
}

?>