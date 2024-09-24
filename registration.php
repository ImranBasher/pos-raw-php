
<?php   include ("includes/header.php");

if(isset($_SESSION['loggedIn'])){
    ?>
    <script> window.location.href = 'index.php'</script>
        <?php
}
?>
<div class="py-5 bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow rounded-4">

                    <?php alertMessage(); ?>
                    <div class="p-5">
                        <h4>Register for POS System</h4>
                        <form action="registration-code.php" method="POST">
                            <!-- Name -->
                            <div class="mb-3">
                                <label for="name">Name:</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email">Email:</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password">Password:</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <!-- Phone (optional) -->
                            <div class="mb-3">
                                <label for="phone">Phone (optional):</label>
                                <input type="text" name="phone" class="form-control">
                            </div>
                            <!-- Submit Button -->
                            <div class="mb-3">
                                <button type="submit" name="registerBtn" class="btn btn-primary w-100 mt-2">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php  include("includes/footer.php");    ?>