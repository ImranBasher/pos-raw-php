<?php

require 'config/function.php';
require 'config/helperFunction.php';

if (isset($_POST['loginBtn'])) {
    $email      = validate($_POST['email']);
    $password   = validate($_POST['password']);
    echo '<pre>';
    print_r($email);
    echo '</pre>';
    if($email != "" && $password != "") {

        $emailCheck = emailCheck('admins', $email);
//debug($emailCheck);
        if($emailCheck['status'] == 200 ){

            $passwordCheck = passwordCheck('admins', $email, $password);

            if($passwordCheck['status'] != 200){
                redirect('login.php', 'Invalid Password');
            }

            $isBan = isBannedCheck('admins', $email);
            if($isBan['status'] != 200){
                redirect('login.php', 'Your account has been banned. Contact the administrator / admin.');
            }

            $_SESSION['loggedIn'] = true;
            
            $_SESSION['loggedInUser'] = [
                'user_id'   => $emailCheck['data']['id'],
                'name'      => $emailCheck['data']['name'],
                'email'     => $emailCheck['data']['email'],
                'phone'     => $emailCheck['data']['phone'],
            ];
            redirect('admin/index.php', 'Login Success');

        }else{
            redirect('login.php', 'Invalid Email');
        }
    }else{
        redirect("login.php", "All fields are required");
    }
}

