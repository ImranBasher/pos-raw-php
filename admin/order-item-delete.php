<?php

require '../config/function.php';
require '../config/helperFunction.php';


$paraResultId = checkParamId('index');

if (is_numeric($paraResultId)) {
    $indexValue = validate($paraResultId);


    if (isset($_SESSION['productItems']) && isset($_SESSION['productItemIds']) ) {

        unset($_SESSION['productItems'][$indexValue]);
        unset($_SESSION['productItemIds'][$indexValue]);
        redirect('order-create.php', 'Item Removed');
    } else {
        redirect('order-create.php', 'Item not found');
    }
} else {
    redirect('order-create.php', 'Parameter not a numeric value');
}
