<?php

require '../config/function.php';
require '../config/helperFunction.php';


$paraResultId = checkParamId('id');

if (is_numeric($paraResultId)) {
    $categoryId = validate($paraResultId);

    $category = getById('categories', $categoryId);

    if ($category['status'] == 200) {

        $response = delete('categories', $categoryId);

        if ($response) {
            redirect('categories.php', 'A Category has been deleted.');
        } else {
            redirect('categories.php', 'Something went wrong');
        }
    } else {
        redirect('categories.php', $category['message']);
    }
} else {
    redirect('categories.php', 'Something went wrong');
}



