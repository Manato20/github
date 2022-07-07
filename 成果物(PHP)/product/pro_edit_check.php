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

  //前の画面からの入力データ受け取り
  $post = sanitize($_POST);
  $pro_code = $post['code'];
  $pro_name = $post['name'];
  $pro_price = $post['price'];
  $pro_gazou_name_old = $post['gazou_name_old'];

  //受け取ったファイル情報を取り出す
  $pro_gazou = $_FILES['gazou'];

  //空判定
  if($pro_name == ''){

    print '商品名が入力されていません。<br>';

  }else{

    print '商品名：';
    print $pro_name;
    print '<br>';

  }

  //正規表現
  if(preg_match('/\A[0-9]+\z/',$pro_price) == 0){

    print '価格をきちんと入力してください。<br>';

  }else{

    print '価格：';
    print $pro_price;
    print '円<br>';
  }

  //画像サイズ判定
  if($pro_gazou['size'] > 0){
    if($pro_gazou['size'] > 100000){
      print '画像が大きすぎます';
    }else{
      move_uploaded_file($pro_gazou['tmp_name'],'./gazou/'.$pro_gazou['name']);
      print '<img src="./gazou/'.$pro_gazou['name'].'">';
      print '<br>';
    }
  }

  //入力に問題があれば戻るボタンを表示する
  if($pro_name == '' || preg_match('/\A[0-9]+\z/',$pro_price) == 0 || $pro_gazou['size'] > 100000){

    print '<form>';
    print '<input type="button" onclick="history.back()" value="戻る">';
    print '</form>';
  }else{

    print '上記のように変更します。<br>';
    print '<form method="post" action="pro_edit_done.php">';
    print '<input type="hidden" name="code" value="'.$pro_code.'">';
    print '<input type="hidden" name="name" value="'.$pro_name.'">';
    print '<input type="hidden" name="price" value="'.$pro_price.'">';
    print '<input type="hidden" name="gazou_name_old" value="'.$pro_gazou_name_old.'">';
    print '<input type="hidden" name="gazou_name" value="'.$pro_gazou['name'].'">';
    print '<br>';
    print '<input type="button" onclick="history.back()" value="戻る">';
    print '<input type="submit" value="OK">';
    print '</form>';

  }

?>

</body>
</html>