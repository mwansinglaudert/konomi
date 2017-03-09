<?php
    $POST = $_POST;
    if($isLoggedIn){
        $pass->delete();
        header("Location: ./");
    }else {
        $USER = (isset($POST['ko_user'])) ? userId($POST['ko_user']) : '';
        $PASS = (isset($POST['ko_pass'])) ? md5($POST['ko_pass']) : '';

        if(isPassword($USER, $PASS)){
            $pass->set('user',$USER);
            $pass->set('pass',$PASS);
            echo 'success';
        }
        else{
            header("Location: ./");
            echo 'wrong pw';
        }
    }
?>

