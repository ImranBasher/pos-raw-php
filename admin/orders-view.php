<?php include_once 'includes/header.php'; ?>
    <div class="container-fluid px-4">
        <div class="card mt-4 shadow-sm">
            <div class="card-header">
                <h4 class="mb-0"> Order View
                    <a href="orders-view-print.php?track=<?=$_GET['track']?>" class="btn btn-danger mx-2 btn-sm float-end">Print</a>
                    <a href="orders.php" class="btn btn-danger mx-2 btn-sm float-end">Back</a>
                </h4>
            </div>
            <div class="card-body">
                <?php alertMessage(); ?>

                <?php
                    if(isset($_GET['track'])){

                        if($_GET['track'] == ''){
                            ?>
                                <div class="text-center py-5">
                                    <h5>No Tracking number found</h5>
                                    <a href="orders.php" class="btn btn-primary mt-4 w-25">Go back to order</a>
                                </div>
                            <?php
                        }
                        $tracking_no = $_GET['track'];
                        $table = ['orders', 'customers'];
                        $joins = [
                                'customers ON orders.customer_id = customers.id'
                        ];
                        $conditions =[

                            "orders.tracking_number = $tracking_no"
                        ];
                        $limit = 1;

                        $orders = fetchAllFromMultipleTable($table,$joins,$conditions,null,$limit);



                        if ($orders['status'] == 200) {
                          //  $ordersId = $orders['data']['id'];
                            foreach ($orders['data'] as $anOrder):
                            ?>
                            <div class="card card-body shadow border-1 mb-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4>Order Details</h4>
                                        <label class="mb-1">
                                            Tracking No :
                                            <span> <?=$anOrder['tracking_number']?> </span>
                                        </label><br>

                                        <label class="mb-1">
                                            Order Date :
                                            <span> <?=$anOrder['order_date']?> </span>
                                        </label><br>

                                        <label class="mb-1">
                                            Order Status:
                                            <span> <?=$anOrder['status']?> </span>
                                        </label><br>

                                        <label class="mb-1">
                                            Payment Mode :
                                            <span> <?=$anOrder['payment_mode']?> </span>
                                        </label><br>

                                    </div>
                                    <div class="col-md-6 ">
                                        <h4>User Details </h4>
                                        <label class="mb-1">
                                            Full Name :
                                            <span> <?=$anOrder['name']?> </span>
                                        </label><br>

                                        <label class="mb-1">
                                            Email :
                                            <span> <?=$anOrder['email']?> </span>
                                        </label><br>

                                        <label class="mb-1">
                                            Phone Number :
                                            <span> <?=$anOrder['phone']?> </span>
                                        </label><br>

                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>


                            /**  # provide table names in an array. e.g, $tables = ['orders', 'customers', 'products'];
                            <?php

                                $orderItemQuery = "SELECT 
                                                            oi.quantity as order_item_quantity, 
                                                            oi.price as order_item_price, o.*, 
                                                            oi.*, 
                                                            p.* ,
                                                            pic.file as product_pic
                                                   FROM 
                                                            order_items as oi , 
                                                            orders as o, 
                                                            products as p,
                                                            photos as pic
                                                   WHERE        oi.order_id = o.id 
                                                            AND p.id = oi.product_id 
                                                            AND o.tracking_number = '$tracking_no'
                                                            AND pic.product_id = p.id";

                              //  debug($orderItemQuery);

                            $orderItems = orderItemQuery($orderItemQuery);

                                if ($orderItems) {
                                    if($orderItems['status'] == 200){
                                        ?>

                                        <h4 class="my-3">Order Items Details</h4>
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Total</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                foreach ($orderItems['data'] as $orderItem):
                                            ?>
                                                <tr>
                                                    <td>
                                                        <img src="<?= $orderItem['product_pic'] != '' ? '../assets/uploads/products/'.$orderItem['product_pic']:'../assets/uploads/products/no-image.jpg';  ?>"  style = 'width: 50px; height:50px' alt="">
                                                        <?= $orderItem['name'] ?>
                                                    </td>

                                                    <td style = "width: 15%" class = "fw-bold text-center"><?=number_format($orderItem['price'])?></td>

                                                    <td style = "width: 15%" class = "fw-bold text-center"><?=$orderItem['order_item_quantity']?></td>

                                                    <td style = "width: 15%" class = "fw-bold text-center"><?=number_format($orderItem['order_item_quantity'] * $orderItem['price']); ?></td>

                                                </tr>
                                                <?php endforeach;?>
                                            </tbody>
                                        </table>

                                            <?php
                                        for ($i = 0; $i < count($orderItems['data']); $i++) {
                                            echo '------<pre>';
                                            print_r($orderItems['data'][$i]);
                                            echo '</pre>----<br>';
                                        }
                                    }
                                }
                            ?>

                <?php
                        }else{
                            echo '<h5> No Data found <h5>';
                        }
                    }else{
                        ?>
                            <div class="text-center py-5">
                                <h5>No Tracking number found</h5>
                                <a href="orders.php" class="btn btn-primary mt-4 w-25">Go back to order</a>
                            </div>
                        <?php
                    }
                ?>

            </div>
        </div>
    </div>

<?php include_once 'includes/footer.php'; ?>
