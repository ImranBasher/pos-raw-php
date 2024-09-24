<?php include_once 'includes/header.php'; ?>
    <div class="container-fluid px-4">
        <div class="card mt-4 shadow-sm">
            <div class="card-header">
                <h4 class="mb-0">Customers
                    <a href="customers-create.php" class="btn btn-primary float-end">Add Customer</a>
                </h4>
            </div>
            <div class="card-body">
                <?php alertMessage(); ?>

                <?php
                $customers = getAll('customers');
                if(!$customers){
                    echo  "<h4>Something went wrong.</h4>";
                }
                if(mysqli_num_rows($customers)>0) {
                    ?>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>NAME</th>
                                <th>EMAIL</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($customers as $customerItem) :  ?>
                                <tr>
                                    <td><?= $customerItem['id'] ?></td>
                                    <td><?= $customerItem['name'] ?></td>
                                    <td><?= $customerItem['email'] ?></td>
                                    <td><?= $customerItem['phone'] ?></td>
                                    <td>
                                        <?php if($customerItem['status'] == 0): ?>
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
                                        <a href="customers-edit.php?id=<?= $customerItem['id'] ?>" class="btn btn-success btn-sm">Edit</a>
                                        <a
                                                href="customers-delete.php?id=<?= $customerItem['id'] ?>" class="btn btn-danger btn-sm"
                                                onclick = "return confirm('Are You sure want to delete this data?')"
                                        >
                                            Delete
                                        </a>
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
<?php include_once 'includes/footer.php'; ?>