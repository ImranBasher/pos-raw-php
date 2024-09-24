<?php

    include('../config/helperFunction.php');

    if($_SESSION['loggedIn']){

        $emailCheck = emailCheck('admins',validate($_SESSION['loggedInUser']['email']));

        if($emailCheck['status'] != 200){
            logoutSession();
            redirect('../login.php', 'Access Denied');
        }else {
            if ($emailCheck['data']['is_ban'] == 1) {
                logoutSession();
                redirect('../login.php', 'Your Account has been banned, please contact the administrator');
            }
        }
    }else{
        redirect('../login.php','Login to continue..');
    }
?>