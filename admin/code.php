<?php
    global $conn;
include('../config/function.php');
include('../config/helperFunction.php');

if(isset($_POST['saveAdmin'])) {

    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['phone']);
    $is_ban = isset($_POST['is_ban']) == true ? 1 : 0;

    if ($name != '' && $email != '' && $password != '') {

        $emailCheck = emailCheck('admins', $email);
        if($emailCheck['status'] == 200) {
            redirect('admins-create.php', 'Email Already Exist');
        }

        $bcrypt_password = password_hash($password, PASSWORD_BCRYPT);
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $bcrypt_password,
            'phone' => $phone,
            'is_ban' => $is_ban
        ];
        $result = insert('admins', $data);

        if ($result) {
            redirect('admins.php', 'Admin created Successfully');
        } else {
            redirect('admins-create.php', 'Something Went Wrong');
        }
    } else {
        redirect('admins-create.php', 'Please Fill All Fields');
    }
}

if(isset($_POST['updateAdmin'])){

    $adminId    = validate($_POST['adminId']);
        $adminData = getById('admins', $adminId);

        if($adminData['status'] != 200){
            redirect('admins-edit.php?id='.$adminId, 'Please Fill All Fields');
        }
    $name       = validate($_POST['name']);
    $email      = validate($_POST['email']);
    $password   = validate($_POST['password']);  // here, password can be null if you did not write because we did not show password at the edit time
    $phone      = validate($_POST['phone']);
    $is_ban     = isset($_POST['is_ban']) == true ? 1 : 0;


    if($password != ''){
        $hased_password = password_hash($password, PASSWORD_BCRYPT);
    }else{
        $hased_password = $adminData['data']['password'];// if the password already found(old password), for example, i you have not entered the password then you stored default entered password(means old password)
    }

    if ($name != '' && $email != '' ) {
        $emailCheck = emailCheck('admins', $email);

        if($emailCheck['status'] == 500) {
            redirect('admins-edit.php?id='.$adminId, ' something went wrong');
        }
        $data = [
            'name'      => $name,
            'email'     => $email,
            'password'  => $hased_password,
            'phone'     => $phone,
            'is_ban'    => $is_ban
        ];

        $result = update('admins', $adminId, $data);

        if ($result) {
            redirect('admins-edit.php?id='.$adminId, 'Admin updated Successfully');
        } else {
            redirect('admins-edit.php?id='.$adminId, 'Something Went Wrong');
        }

    }else{
        redirect('admins-create.php', 'Please Fill All Fields');
    }
}


    if(isset($_POST['saveCategory'])) {
        $name = validate($_POST['name']);
        $description = validate($_POST['description']);
        $status = isset($_POST['status']) == true ? 1 : 0;

        $data = [
            'name' => $name,
            'description' => $description,
            'status' => $status
        ];
        $result = insert('categories', $data);

        if ($result) {
            redirect('categories.php', 'A category created Successfully');
        } else {
            redirect('categories-create.php', 'Something Went Wrong');
        }

    }

    if(isset($_POST['updateCategory'])) {
        $categoryId     = validate($_POST['categoryId']);
        $name           = validate($_POST['name']);
        $description    = validate($_POST['description']);
        $status         = isset($_POST['status']) == true ? 1 : 0;

        $data = [
            'name' => $name,
            'description' => $description,
            'status' => $status
        ];
        $result = update('categories',$categoryId, $data);

        if ($result) {
            redirect('categories-edit.php?id='.$categoryId, 'A category updated Successfully');
        } else {
            redirect('categories-edit.php?id='.$categoryId, 'Something Went Wrong to update a category.');
        }
    }

    if(isset($_POST['saveProduct'])) {
        $categoryId     = validate($_POST['categoryId']);
        $name           = validate($_POST['name']);
        $description    = validate($_POST['description']);
        $price          = validate($_POST['price']);
        $quantity       = validate($_POST['quantity']);
        $status         = isset($_POST['status']) == true ? 1 : 0;
        $images         = $_FILES['images'];
            foreach ($images['name'] as $key => $image) {
                debuger("Image data From code.php: ". $image);
            }
        //debuger("Image data From code.php: ". $_FILES['images']['name']);
        $data = [
            'category_id'   => $categoryId,
            'name'          => $name,
            'description'   => $description,
            'price'         => $price,
            'quantity'      => $quantity,
            'status'        => $status
        ];
        
        $result             = insertWithImage('products', $data, $images, 'products' );

//        var_dump($result);
//        $lastInsertedId     = mysqli_insert_id($conn);
//            echo "last iserted id :". $lastInsertedId;
        if ($result) {
            redirect('products.php', 'A products created Successfully');
        } else {
            redirect('products-create.php', 'Something Went Wrong');
        }
    }

    if(isset($_POST['updateProduct'])) {
        $productId      = validate($_POST['productId']);
        $productData    = getById('products', $productId);
        if(!$productData){
            redirect('products.php', 'No Such Product Found');
        }
        $categoryId     = validate($_POST['categoryId']);
        $name           = validate($_POST['name']);
        $description    = validate($_POST['description']);
        $price          = validate($_POST['price']);
        $quantity       = validate($_POST['quantity']);
        $status         = isset($_POST['status']) == true ? 1 : 0;
        $imageToDelete  = $_POST['imageToDelete'];
        $images         = $_FILES['images'];
        foreach ($images['name'] as $key => $image) {
            debuger("Image data From code.php: ". $image);
        }
        //debuger("Image data From code.php: ". $_FILES['images']['name']);
        $data = [
            'category_id'   => $categoryId,
            'name'          => $name,
            'description'   => $description,
            'price'         => $price,
            'quantity'      => $quantity,
            'status'        => $status,
        ];
        $result             = updateWithImage($productId,'products', $data, $images, 'products', $imageToDelete );

//        var_dump($result);
//        $lastInsertedId     = mysqli_insert_id($conn);
//            echo "last iserted id :". $lastInsertedId;
        if ($result) {
            redirect('products-edit.php', 'A products created Successfully');
        } else {
            redirect('products-edit.php', 'Something Went Wrong');
        }
    }


    if(isset($_POST['saveCustomer'])) {

        $name       = validate($_POST['name']);
        $email      = validate($_POST['email']);
        $password   = validate($_POST['password']);
        $phone      = validate($_POST['phone']);
        $status     = isset($_POST['status']) == true ? 0 : 1;

            if ( $email != '') {
                $emailCheck = emailCheck('customers', $email);
                if($emailCheck['status'] == 200) {
                    redirect('customers.php', 'Email Already used By another Customer');
                }
                $data = [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'status' => $status
                ];

                $result = insert('customers', $data);
                if ($result) {
                    redirect('customers.php', 'Customer created Successfully');
                } else {
                    redirect('customers-create.php', 'Something Went Wrong');
                }
            } else {
                redirect('customers-create.php', 'Please Fill All Fields');
            }
    }

    if(isset($_POST['updateCustomer'])) {
        $customerId     = validate($_POST['customerId']);
        $name           = validate($_POST['name']);
        $email          = validate($_POST['email']);
        $phone          = validate($_POST['phone']);
        $status         = isset($_POST['status']) == true ? 0 : 1;
        if ( $email != '') {
            $emailCheck = emailCheck('customers', $email);

            if($emailCheck['status'] != 200) {
                redirect('customers-edit.php?id='.$customerId, 'Email Already used By another Customer');
            }
            $data = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'status' => $status
            ];

            $result = update('customers', $customerId, $data);
            if ($result) {
                redirect('customers-edit.php?id='.$customerId, 'Customer updated Successfully');
            }

        }
    }



















//
//if($_FILES['images']['name'] != ''){
//    $path               = '../assets/uploads/'.$directory;
//    $image_extention    = pathinfo($_FILES['images']['name'], PTHINFO_EXTENTION);
//    $filename           = $name.'_'.time().'.'.$image_extention;
//    move_uploaded_file($_FILES['images']['tmp_name'], $path."/".$filename);
//    $final_image        = "assets/uploads/".$directory."/".$filename;
//}


function debuger($value){
    echo '<pre>';
    print_r($value);
    echo '</pre>';
//    var_dump($value);
}
    ?>
