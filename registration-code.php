<?php 
    require 'config/function.php';
    require 'config/helperFunction.php';

    if(isset($_POST['registerBtn'])){
        $name       = validate($_POST['name']);
        $email      = validate($_POST['email']);
        $password   = validate($_POST['password']);
        $phone      = validate($_POST['phone']);

       // debug($_POST);
        if($name != "" && $email != "" && $password != "") {
            $emailCheck = emailCheck('admins', $email);

            if($emailCheck['status'] == 404 ){

                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                $query = "INSERT INTO admins(name,email, password, phone) VALUES ('$name', '$email', '$hashed_password', '$phone')";

                    $result = mysqli_query($conn, $query);

                    if($result){
                        redirect('login.php', 'Registration successful! You can now log in.');
                    } else {
                        redirect('registration.php', 'An error occurred. Please try again.');
                    }    
            }
        } else {
            redirect('registration.php', 'Please fill in all required fields.');
        }
    }
// `created_at` date NOT NULL DEFAULT current_timestamp()
?>