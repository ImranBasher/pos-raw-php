<?php

require '../config/function.php';
require '../config/helperFunction.php';


$paraResultId = checkParamId('id');

if (is_numeric($paraResultId)) {
    $productId = validate($paraResultId);

    $product = getById('products', $productId);

    if ($product['status'] == 200) {

        $response = delete('products', $productId, 'products');
        debug($response);

        if ($response) {
            redirect('products.php', 'A Category has been deleted.');
        } else {
            redirect('products.php', 'Something went wrong');
        }
    } else {
        redirect('products.php', $product['message']);
    }
} else {
    redirect('products.php', 'Something went wrong');
}



