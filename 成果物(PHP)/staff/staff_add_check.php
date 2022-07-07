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

  //ファイル読み込み
  require_once('../common/common.php');

  //common.phpの変数読み込み
  $post = sanitize($_POST);

  //前の画面から入力データ受け取り
  $staff_name = $post['name']; 
  $staff_pass = $post['pass'];
  $staff_pass2 = $post['pass2'];

  //空判定
  if($staff_name == ''){

    print 'スタッフ名が入力されていません。<br>';

  }else{

    print 'スタッフ名：';
    print $staff_name;
    print '<br>';

  }

  //空判定
  if($staff_pass == ''){

    print 'パスワードが入力されていません。<br>';

  }

  //パスワードが同じか判定
  if($staff_pass != $staff_pass2){

    print 'パスワードが一致しません<br>';

  }

  //もし入力に問題があったら戻るボタンだけを表示する
  if($staff_name == '' || $staff_pass == '' || $staff_pass != $staff_pass2){

    print '<form>';
    print '<input type="button" onclick="history.back()" value="戻る">';
    print '</form>';

  }else{

    $staff_pass = md5($staff_pass); //パスワード暗号化
    print '<form method="post" action="staff_add_done.php">';
    print '<input type="hidden" name="name" value="'.$staff_name.'">';
    print '<input type="hidden" name="pass" value="'.$staff_pass.'">';
    print '<br>';
    print '<input type="button" onclick="history.back()" value="戻る">';
    print '<input type="submit" value="OK">';
    print '</form>';

  }

  ?>

</body>
</html>