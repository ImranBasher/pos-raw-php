<?php include_once 'includes/header.php'; ?>
    <div class="container-fluid px-4">
        <div class="card mt-4 shadow-sm">
            <div class="card-header">
                <h4 class="mb-0">Products
                    <a href="products-create.php" class="btn btn-primary float-end">Add a Products </a>
                </h4>
            </div>
            <div class="card-body">
                <?php alertMessage(); ?>

                <?php
                $products = getAll('products');
                if(!$products){
                    echo  "<h4>Something went wrong.</h4>";
                }
                if(mysqli_num_rows($products)>0) {
                    ?>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($products as $itme) :  ?>
                                <tr>
                                    <td><?= $itme['id'] ?></td>

<!-----------------Showing Image--------------------->
                                    <td>
                                        <?php
                                        $photos = getPicturesAccordingToIds($itme['id']);

                                        // Check if the query was successful
                                        if (!$photos) {
                                            echo "<h4>Something went wrong.</h4>";
                                        } else {
                                            // Check if there are any photos
                                            if (mysqli_num_rows($photos) > 0): ?>
                                                <?php while ($photo = mysqli_fetch_assoc($photos)): ?>
                                                    <img src="../assets/uploads/products/<?= $photo['file']; ?>" style="height:50px; width:50px" alt="img">
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <h4>No photos found.</h4>
                                            <?php endif;
                                        }
                                        ?>
                                    </td>
<!---------------------END Showing Image------------------>
                                    <td><?= $itme['name'] ?></td>
                                    <td><?= $itme['price'] ?></td>
                                    <td><?= $itme['quantity'] ?></td>

                                    <td>
                                        <?php if($itme['status'] == 1): ?>
                                            <!--                                    <span style="background-color: green; border-radius:4px">Active</span>-->
                                            <span class="badge bg-primary">Visible</span>
                                        <?php
                                        else:
                                            ?>
                                            <!--                                    <span style="background-color: red; border-radius:4px">Inactive</span>-->
                                            <span class="badge bg-danger">Hidden</span>
                                        <?php
                                        endif;
                                        ?>
                                    </td>
                                    <td>
                                        <a href="products-edit.php?id=<?= $itme['id'] ?>" class="btn btn-success btn-sm">Edit</a>
                                        <a
                                                href="products-delete.php?id=<?= $itme['id'] ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick = "return confirm('are you sure want to delete this product.')"

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