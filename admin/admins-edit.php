
<?php include_once 'includes/header.php'; ?>
    <div class="container-fluid px-4">
        <div class="card mt-4 shadow-sm">
            <div class="card-header">
                <div class="mb-0"><h3>Edit Admin</h3> </div>
                <a href="admins.php" class="btn btn-danger float-end">Back</a>
            </div>
            <div class="card-body">
                <?php alertMessage(); ?>

                <form action="code.php" method = 'POST'>
                    <?php

                        if(isset($_GET['id'])){
                            if($_GET['id'] != ''){

                                    $admin_id = $_GET['id'];


                            }else{
                                echo '<h5> NO ID found!</h5>';
                                return false;
                            }
                        }else{
                            echo '<h5> NO ID given in params!</h5>';
                            return false;
                        }

                        $adminData = getById('admins',$admin_id);
                        if($adminData){
                            if($adminData['status'] == 200){
                                ?>
<!----------------------------------------------------------------------------------------------------------------->
                                <div class="row">
                                    <input type="hidden" name = "adminId"  value = "<?= $adminData['data']['id']?>">
                                    <div class="col-md-12 mb-3">
                                        <label for="">Name *</label>
                                        <input type="text" name = "name"  required value = "<?= $adminData['data']['name']?>" class = "form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Email *</label>
                                        <input type="email" name = "email"  required value = "<?= $adminData['data']['email']?>" class = "form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Password *</label>
                                        <input type="password" name = "password"   class = "form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Phone Number *</label>
                                        <input type="number" name = "phone"  required value = "<?= $adminData['data']['phone']?>" class = "form-control">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="">Is Ban </label>
                                        <input type="checkbox" name = "is_ban" <?= $adminData['data']['is_ban'] == true ? 'checked' : ''; ?>  style = "width:30px; height: 30px">
                                    </div>
                                    <div class="col-md-12 mb-3 text-end">
                                        <button type="submit" name = "updateAdmin" class="btn btn-primary">Update</button>
                                    </div>
                                </div>

<!----------------------------------------------------------------------------------------------------------------->
                    <?php
                            }else{
                                echo '<h5>'.$adminData['message'].'</h5>';
                            }
                        }else{
                            echo  '<h5> Something went wrong.</h5>';
                            return false;
                        }
                    ?>

                </form>
            </div>
        </div>
    </div>
<?php include_once 'includes/footer.php'; ?>

