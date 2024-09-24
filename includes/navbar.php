<nav class="navbar navbar-expand-lg  bg-white shadow">
    <div class="container">

        <a class="navbar-brand" href="#">POS System in PHP MySQL</a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home</a>
                </li>

                <?php if(isset($_SESSION['loggedIn'])) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><?= $_SESSION['loggedInUser']['name'] ?></a>
                    </li>
                <li class="nav-item">
                    <a class="btn btn-danger" href="logout.php">Logout</a>
                </li>

                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>