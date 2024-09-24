<?php include_once 'includes/header.php'; ?>
    <div class="container-fluid px-4">
        <div class="card mt-4 shadow-sm">
            <div class="card-header">
                <h4 class="mb-0"> Admins/Staff
                     <a href="admins-create.php" class="btn btn-primary float-end">Add Admin</a>
                </h4>
            </div>
            <div class="card-body">
                <?php alertMessage(); ?>

                <?php
                    $admins = getAll('admins');
                    if(!$admins){
                        echo  "<h4>Something went wrong.</h4>";
                    }
                    if(mysqli_num_rows($admins)>0) {
                ?>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>NAME</th>
                                <th>EMAIL</th>
                                <th>Is Ban</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    <tbody>
                            <?php foreach($admins as $adminItem) :  ?>
                        <tr>
                            <td><?= $adminItem['id'] ?></td>
                            <td><?= $adminItem['name'] ?></td>
                            <td><?= $adminItem['email'] ?></td>
                            <td>
                                <?php if($adminItem['is_ban'] == 0): ?>
<!--                                    <span style="background-color: green; border-radius:4px">Active</span>-->
                                <span class="badge bg-primary">Active</span>
                                <?php
                                else:
                                ?>
<!--                                    <span style="background-color: red; border-radius:4px">Inactive</span>-->
                                    <span class="badge bg-danger">Inactive</span>
                                    <?php
                                endif;
                                ?>
                            </td>
                            <td>
                                <a href="admins-edit.php?id=<?= $adminItem['id'] ?>" class="btn btn-success btn-sm">Edit</a>
                                <a href="admin-delete.php?id=<?= $adminItem['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                            <?php endforeach; ?>
                    </tbody>
                    </table>
                </div>
                        <?php
                    }else{
                        ?>
                        <tr>
                            <h4 class = "mb-0">No Record Found.</h4>
                        </tr>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>
<?php include_once 'includes/footer.php'; 

  
        
?>