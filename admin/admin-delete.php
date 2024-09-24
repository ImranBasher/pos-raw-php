<?php
    require'../config/function.php';
    require'../config/helperFunction.php';


    $paraResultId = checkParamId('id');

    if(is_numeric($paraResultId)){
        $adminId = validate($paraResultId);

        $admin = getById('admins', $adminId);

        if($admin['status'] == 200){
            $adminDeleteRes = delete('admins', $adminId);

            if($adminDeleteRes){
                redirect('admins.php', 'An admin has been deleted.');
            }else{
                redirect('admins.php', 'Something went wrong');
            }
        }else{
            redirect('admins.php', $admin['message']);
        }
    }else{
        redirect('admins.php', 'Something went wrong');
    }








?>