<?php
require 'config/function.php';
require 'config/helperFunction.php';

    if(isset($_SESSION['loggedIn'])){
        logoutSession();
        redirect('login.php', 'Logged out successfully!');
    }

?>