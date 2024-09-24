<?php

    session_start();


    function logoutSession(){
        unset($_SESSION['loggedIn']);
        unset($_SESSION['loggedInUser']);
    }


    // redirect from 1 page to another page with the message(status)
    function redirect( $url, $status){
        $_SESSION['status'] = $status;
        header('Location: ' . $url);
        exit(0);
    }

    // Display messages or status after any process
    function alertMessage(){
        if(isset($_SESSION['status'])){
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <h6>'.$_SESSION['status'].'</h6>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            unset($_SESSION['status']);
        }
        if (isset($_SESSION['exception'])) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h6>' . $_SESSION['exception'] . '</h6>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
            unset($_SESSION['exception']);
        }
    }

    function jsonResponse($status, $status_type, $message){
        $response = [
            'status' => $status,
            'status_type' => $status_type,
            'message' => $message
        ];
        echo json_encode($response);
        return;
    }


?>