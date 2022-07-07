<?php
session_start(); //セッション確認
session_regenerate_id(true); //セッションID変更
if(isset($_SESSION['login']) == false){
    print 'ログインされていません。<br>';
    print '<a href="../start_login/staff_login.html">ログイン画面へ</a>';
    exit();
}

if(isset($_POST['disp'])==true){

    if(isset($_POST['staffcode'])==false){
        header('Location:staff_ng.php');
        exit();
    }

    $staff_code = $_POST['staffcode'];
    header('Location:staff_disp.php?staffcode='.$staff_code);
    exit();
}

if(isset($_POST['add'])==true){

    header('Location:staff_add.php');
    exit();
}

if(isset($_POST['edit'])==true){

    if(isset($_POST['staffcode']) == false){
        header('Location:staff_ng.php');
        exit();
    }

    $staff_code = $_POST['staffcode'];
    header('Location:staff_edit.php?staffcode='.$staff_code);
    exit();
}

if(isset($_POST['delete'])==true){
    
    if(isset($_POST['staffcode']) == false){
        header('Location:staff_ng.php');
        exit();
    }

    $staff_code = $_POST['staffcode'];
    header('Location:staff_delete.php?staffcode='.$staff_code);
    exit();
}

?>