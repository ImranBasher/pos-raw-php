<?php
require'../config/function.php';
require'../config/helperFunction.php';


$paraResultId = checkParamId('id');



    if(is_numeric($paraResultId)){
        $customerId = validate($paraResultId);

        $customer = getById('customers', $customerId);

        if($customer['status'] == 200){
            $customerDeleteRes = normalDelete('customers', $customerId);

            if($customerDeleteRes){
                redirect('customers.php', 'A customer has been deleted.');
            }else{
                redirect('customers.php', 'Something went wrong');
            }
        }else{
            redirect('customers.php', $customer['message']);
        }
    }else{
        redirect('customers.php', 'Something went wrong');
    }



?>