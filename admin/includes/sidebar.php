<?php
    //  $page = $_SERVER['SCRIPT_NAME']; // contains the path of the currently executing script
    //$string = "Hello, World!";
    //$substring = substr($string, 7, 5);  // used to extract a portion of a string

    //$filepath = "/var/www/html/index.php";
    //
    //// Find the position of the last slash in the file path
    // $lastSlashPosition = strrpos($filepath, '/');
    // $lastWord = substr($sentence, $lastSpacePosition + 1);

    $url = $_SERVER['SCRIPT_NAME'];
    $page = substr($url,  strrpos($url, "/")+1);





?>
<?=$page == '' ? 'active' : ''; ?>
<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link <?= $page == 'index.php' ? 'active' : ''; ?>" href="index.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>

<!-------------Start  Order----------------->
                <a class="nav-link <?= $page == 'order-create.php' ? 'active' : ''; ?>" href="order-create.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-bell"></i></div>
                    Create Order
                </a>

                <a class="nav-link <?= $page == 'orders.php' ? 'active' : ''; ?>" href="orders.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                    Orders
                </a>
<!-------------End Order----------------->


                <div class="sb-sidenav-menu-heading">Interface</div>
<!-------------START Category------------------->
                <a class="nav-link
                    <?= ( $page == 'categories-create.php') || ($page == 'categories.php') ? 'collapse active' : 'collapsed'; ?>"

                   href="#"
                   data-bs-toggle="collapse"
                   data-bs-target="#collapseCategory"
                   aria-expanded="false"
                   aria-controls="collapseCategory">

                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Categories
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div
                        class="collapse
                        <?= ( $page == 'categories-create.php') || ($page == 'categories.php') ? 'show' : ''; ?>"

                        id="collapseCategory"
                        aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">

                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link <?=$page == 'categories-create.php' ? 'active' : ''; ?>" href="categories-create.php">Create Category</a>
                        <a class="nav-link <?=$page == 'categories.php' ? 'active' : ''; ?>" href="categories.php">View Categories</a>
                    </nav>
                </div>
<!-------------END CATEGORY-------------------->

                <!-------------Start Product------------------->

                <a class="nav-link
                    <?= ( $page == 'products-create.php') || ($page == 'products.php') ? 'collapse active' : 'collapsed'; ?>
                    " href="#"
                   data-bs-toggle="collapse"
                   data-bs-target="#collapseProduct"
                   aria-expanded="false"
                   aria-controls="collapseProduct">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Products
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse <?= ( $page == 'products-create.php') || ($page == 'products.php') ? 'show' : ''; ?> "
                     id="collapseProduct" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link <?= $page == 'products-create.php' ? 'active' : ''; ?>" href="products-create.php">Create Product</a>
                        <a class="nav-link <?= $page == 'products.php' ? 'active' : ''; ?>" href="products.php">View Products</a>
                    </nav>
                </div>

                <!-------------END product--------------------->

                <!-------------Start Customer------------------->
                <a class="nav-link
                    <?= ( $page == 'customers-create.php') || ($page == 'customers.php') ? 'collapse active' : 'collapsed'; ?> "
                   href="#"
                   data-bs-toggle="collapse"
                   data-bs-target="#collapseCustomer"
                   aria-expanded="false" aria-controls="collapseCustomer">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Customer
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse
                            <?= ( $page == 'customers-create.php') || ($page == 'customers.php') ? 'show' : ''; ?> "

                     id="collapseCustomer" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link <?= $page == 'customers-create.php' ? 'active' : ''; ?>" href="customers-create.php">Create Customer</a>
                        <a class="nav-link <?= $page == 'customers.php' ? 'active' : ''; ?>" href="customers.php">View Customer</a>
                    </nav>
                </div>
                <!-------------END Customer--------------------->


                <a
                        class="nav-link

                        href="#"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapsePages"
                        aria-expanded="false"
                        aria-controls="collapsePages">

                        <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                            Pages
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>

                <div class="collapse
                        <?= ( $page == 'login.html') || ($page == 'register.html') || ($page == 'password.html')  ? 'collapse active' : 'collapsed'; ?> "

                        id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                            Authentication
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse
                            <?= ( $page == 'login.html') || ($page == 'register.html') || ($page == 'password.html')  ? 'show' : ''; ?> "

                             id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link <?= $page == 'login.html' ? 'active' : ''; ?>" href="login.html">Login</a>
                                <a class="nav-link <?= $page == 'register.html' ? 'active' : ''; ?>" href="register.html">Register</a>
                                <a class="nav-link <?= $page == 'password.html' ? 'active' : ''; ?>" href="password.html">Forgot Password</a>
                            </nav>
                        </div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                            Error
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="401.html">401 Page</a>
                                <a class="nav-link" href="404.html">404 Page</a>
                                <a class="nav-link" href="500.html">500 Page</a>
                            </nav>
                        </div>
                    </nav>
                </div>
                <div class="sb-sidenav-menu-heading">Manage Users</div>

                    <a class="nav-link
                            <?= ( $page == 'admins-create.php') || ($page == 'admins.php') ? 'collapse active' : 'collapsed'; ?> "
                       href="#"
                       data-bs-toggle="collapse"
                       data-bs-target="#collapseAdmins"
                       aria-expanded="false"
                       aria-controls="collapseAdmins">

                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Admins/Staff
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse
                <?= ( $page == 'admins-create.php') || ($page == 'admins.php') ? 'show' : ''; ?> "
                     id="collapseAdmins" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link <?= $page == 'admins-create.php' ? 'active' : ''; ?>" href="admins-create.php">Add Admins</a>
                        <a class="nav-link <?= $page == 'admins.php' ? 'active' : ''; ?>" href="admins.php">View Admins</a>
                    </nav>
                </div>
                 </div>
        </div>
       <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            Start Bootstrap
        </div>
    </nav>
</div>