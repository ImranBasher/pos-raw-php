<?php include_once 'includes/header.php'; ?>
        <div class="container-fluid px-4">
            <div class="card mt-4 shadow-sm">
                <div class="card-header">
                    <div class="row">
                       <div class="col-md-4">
                           <h4 class="mb-0"> Orders  </h4>
                       </div>
                        <div class="col-md-8">
                            <form action="" method="GET">
                                <div class="row g-1">
                                    <div class="col-md-4">
                                        <input type="date" name="date" value="<?= isset($_GET['date']) ? $_GET['date']: ''; ?>" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <select name="payment_status" class="form-select">

                                            <option value="cash_payment"
                                                <?=
                                                        isset($_GET['payment_status'])
                                                            ?
                                                            ($_GET['payment_status'] == 'cash_payment'  ? 'selected' : '')
                                                            : ''; ?>
                                            >
                                                Cash Payment</option>
                                            <option value="online_payment"
                                                <?=
                                                isset($_GET['payment_status'])
                                                    ?
                                                    ($_GET['payment_status'] == 'online_payment'  ? 'selected' : '')
                                                    : ''; ?>
                                            >

                                                Online Payment</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class = "btn btn-primary">Filter</button>
                                        <a href="orders.php" class="btn btn-danger">Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <?php alertMessage(); ?>

                <?php

                    if(isset($_GET['date']) || isset($_GET['payment_status'])){
                        $orderDate = '';
                        $paymentStatus = '';

                        if(isset($_GET['date'])){
                            $orderDate = $_GET['date'];
                        }

                        if(isset($_GET['payment_status'])){
                            $paymentStatus =$_GET['payment_status'];
                        }
                        $op = "orders.payment_mode = ".$paymentStatus;
                        $od = "orders.order_date = ".$orderDate;
                        echo $op;
                        debug( ' Date :'.$orderDate .' payment :'.$paymentStatus ."<br>");
                        if($orderDate != '' && $paymentStatus == ''){

                            $tables     = ['orders','customers'];
                            $joins      = ['customers ON orders.customer_id = customers.id'];
//                            $conditions = [ "orders.order_date = '".$orderDate."'"];
                            $conditions = [ $od ];
                            $orderBy    = 'orders.id';
                            $allOrders  = fetchAllFromMultipleTable($tables, $joins, $conditions);

                        }elseif($orderDate == '' && $paymentStatus != ''){

                            $tables     = ['orders','customers'];
                            $joins      = ['customers ON orders.customer_id = customers.id'];

//                            $conditions = [ "orders.payment_mode = '".$paymentStatus."'"];
                            $conditions = [$op ];
                            $orderBy    = 'orders.id';
                            $allOrders  = fetchAllFromMultipleTable($tables, $joins, $conditions);

                        }elseif($orderDate != '' && $paymentStatus != ''){

                            $tables     = ['orders','customers'];
                            $joins      = ['customers ON orders.customer_id = customers.id'];
//                            $conditions = [ "orders.order_date = '".$orderDate."'","orders.payment_mode = '".$paymentStatus."'"];
                            $conditions = [ $od, $op];
                            $orderBy    = 'orders.id';
                            $allOrders  = fetchAllFromMultipleTable($tables, $joins, $conditions);

                        }else{
                            $allOrders = getMultipleTableData('orders', 'customers', 'customer_id', 'id','id');
                        }

                    }else{
                        $allOrders = getMultipleTableData('orders', 'customers', 'customer_id', 'id','id');
                    }

                        if($allOrders){
                            if($allOrders['status'] == 200){
                                $orders = $allOrders['data'];

                ?>
                        <table class = "table table-striped table-bordered align--items-center justify-content">
                            <thead>
                                <tr>
                                    <th>Tracking NO</th>
                                    <th>Customer<br>Name</th>
                                    <th>Customer<br>Phone</th>
                                    <th>Order Date</th>
                                    <th>Order Status</th>
                                    <th>Payment<br>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($orders as $order):  ?>
                                    <tr>
                                        <td> <?= $order['tracking_number']?></td>
                                        <td> <?= $order['name']?></td>
                                        <td> <?= $order['phone']?></td>
                                        <td> <?= $order['order_status']?></td>
                                        <td> <?= date('d M,Y', strtotime($order['order_date']));?> </td>
                                        <td> <?= $order['payment_mode']?></td>
                                        <td>
                                            <a href="orders-view.php?track=<?= $order['tracking_number']?>" class="btn btn-info mb-0 px-2 btn-sm">VIEW</a>
                                            <a href="orders-view-print.php?track=<?= $order['tracking_number']?>" class="btn btn-primary mb-0 px-2 btn-sm">Print</a>
                                        </td>

                                    </tr>

                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php
                            }else{
                                echo "<h5>No Record available.</h5>";
                            }
                        }else{
                            echo "<h5>Something went wrong to fetch data by query.</h5>";
                        }
                    ?>

                </div>
            </div>
        </div>

<?php include_once 'includes/footer.php'; ?>