<?php
include ('../config/function.php');
include ('../config/helperFunction.php');

    if(!isset($_SESSION['productItems'])){
        $_SESSION['productItems'] = [];
    }

    if(!isset($_SESSION['productItemIds'])){
        $_SESSION['productItemIds'] = [];
    }

    if(isset($_POST['addItem'])){
        $productID = validate($_POST['productID']);
        $quantity = validate($_POST['quantity']);

        $checkProduct = getById('products',  $productID );

        if($checkProduct['status'] == 200){
            if($checkProduct['data']['quantity'] < $quantity){
                redirect('order-create.php', 'only '.$checkProduct['data']['quantity'].' quantity available');
            }
            $productData = [
                'productID'     => $checkProduct['data']['id'],
                'name'          => $checkProduct['data']['name'],
                'price'         => $checkProduct['data']['price'],
                'quantity'      => $quantity
            ];

            if(!in_array($checkProduct['data']['id'], $_SESSION['productItemIds'])){
                array_push($_SESSION['productItemIds'], $checkProduct['data']['id']);
                array_push($_SESSION['productItems'], $productData);
            }else{
                foreach($_SESSION['productItems'] as $key => $prodSessionItem){
                    if($prodSessionItem['productID'] == $checkProduct['data']['id']){
                        $newQuantity    = $prodSessionItem['quantity'] + $quantity;

                        $productData = [
                            'productID'     => $checkProduct['data']['id'],
                            'name'          => $checkProduct['data']['name'],
                            'price'         => $checkProduct['data']['price'],
                            'quantity'      => $newQuantity,
                        ];
                        $_SESSION['productItems'][$key] = $productData;
                    }
                }
            }
            redirect('order-create.php', "Item Added: ".$checkProduct['data']['name']);
        }else{
            redirect('order-create.php', 'Something went wrong');
        }
    }


    if(isset($_POST['productIncDec'])){
        $productID = validate($_POST['product_id']);
        $quantity = validate($_POST['quantity']);

        $flag = false;
        foreach($_SESSION['productItems'] as $key => $item){
            if($item['productID'] == $productID){
                $flag = true;
                $_SESSION['productItems'][$key]['quantity'] = $quantity;
            }
        }
        if($flag ){
            jsonResponse(200, 'success', 'Quantity Updated');
        }else{
            jsonResponse(500, 'error', 'Something went wrong');
        }
    }


    if(isset($_POST['proceedToPlaceBtn'])){
        $phone = validate($_POST['customer_phone']);
        $payment_mode = validate($_POST['payment_mode']);

        // checking for customer

        $checkCustomer = getByColumn('customers', 'phone', $phone);
        if($checkCustomer){
            if($checkCustomer['status'] == 200){
                $_SESSION['invoice_number'] = "INV-".rand(111111,999999);
                $_SESSION['customer_phone'] = $phone;
                $_SESSION['payment_mode'] = $payment_mode;
                jsonResponse('200', 'success', 'Customer found');
            }else{
                $_SESSION['customer_phone'] =$phone;
                jsonResponse(404, 'error', 'Customer not found');
            }
        }else{
            jsonResponse(500, 'error', 'Something went wrong to retrive customer information according to phone number');
        }
    }

if(isset($_POST['saveCustomerBtn'])) {

    $name       = validate($_POST['name']);
    $email      = validate($_POST['email']);
    $phone      = validate($_POST['phone']);

    if ( $name != '' &&  $phone != '') {

        $data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
        ];

        $result = insert('customers', $data);
        if ($result) {
            jsonResponse('200', 'success', 'Customer added successfully');
        } else {
            jsonResponse('500', 'error', 'Something went wrong');
        }
    } else {
        jsonResponse('422', 'warning', 'Please filed required fields');
    }
}


if(isset($_POST['saveOrder'])) {

    global $conn;

    // Start the transaction
    mysqli_begin_transaction($conn);

    try {
        $phone          = validate($_SESSION['customer_phone']);
        $payment_mode   = validate($_SESSION['payment_mode']);
        $invoice_number = validate($_SESSION['invoice_number']);
        $order_placed_by_id = $_SESSION['loggedInUser']['user_id'];

        $checkCustomer = getByColumn('customers', 'phone', $phone);
        if($checkCustomer['status'] != 200) {
            throw new Exception('Customer not found');
        }

        if(!isset($_SESSION['productItems'])) {
            throw new Exception('No Item to place order');
        }

        $totalAmount = 0;
        foreach($_SESSION['productItems'] as $key => $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        $data = [
            'customer_id'        => $checkCustomer['data']['id'],
            'tracking_number'    => rand(111111,999999),
            'invoice_number'     => $invoice_number,
            'totalAmount'        => $totalAmount,
            'order_date'         => date('Y-m-d'),
            'order_status'       => 'booked',
            'payment_mode'       => $payment_mode,
            'order_placed_by_id' => $order_placed_by_id
        ];

        $result = insert('orders', $data);
        $lastOrderId = mysqli_insert_id($conn);

        foreach($_SESSION['productItems'] as $key => $item) {
            $productID = $item['productID'];
            $price = $item['price'];
            $quantity = $item['quantity'];

            $dataOrderItem = [
                'order_id'   => $lastOrderId,
                'product_id' => $productID,
                'price'      => $price,
                'quantity'   => $quantity
            ];

            $orderItemQuery = insert('order_items', $dataOrderItem);

            $checkingProductQuantityQuery = getByColumn('products', 'id', $productID);
            if(!$checkingProductQuantityQuery) {
                throw new Exception('Product not found');
            }

            $totalProductQuantity = $checkingProductQuantityQuery['data']['quantity'] - $quantity;

            $newQuantity = ['quantity' => $totalProductQuantity];
            $updateProductQueantityQuery = update('products', $productID, $newQuantity);
        }

        // If everything is successful, commit the transaction
        mysqli_commit($conn);

        // Unset session variables
        unset($_SESSION['productItems']);
        unset($_SESSION['productItemIds']);
        unset($_SESSION['customer_phone']);
        unset($_SESSION['payment_mode']);
        unset($_SESSION['invoice_number']);

        jsonResponse(200, 'success', 'Order placed successfully');

    } catch (Exception $e) {
        // Rollback the transaction in case of error
        mysqli_rollback($conn);
        jsonResponse(500, 'error', $e->getMessage());
    }
}






















?>