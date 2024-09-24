<?php include_once 'includes/header.php'; ?>
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-md-12">
                <h1 class="mt-4">Dashboard</h1>
                    <?php alertMessage(); ?>
            </div>


            <div class="col-md-3 mb-3">
                <div class="card card-body bg-primary p-3" >
                    <p class="text-sm mb-0 text-capitalize "> Total Category</p>
                    <h5 class="fw-bold mb-0">
                        <?= getCount('categories');?>
                    </h5>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card card-body bg-warning p-3" >
                    <p class="text-sm mb-0 text-capitalize "> Total Products</p>
                    <h5 class="fw-bold mb-0">
                        <?= getCount('products');?>
                    </h5>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card card-body bg-info p-3" >
                    <p class="text-sm mb-0 text-capitalize "> Total Customers</p>
                    <h5 class="fw-bold mb-0">
                        <?= getCount('customers');?>
                    </h5>
                </div>
            </div>

            <div class="col-md-12   mb-3">
                <hr>
                <h5>Orders</h5>
            </div>



            <div class="col-md-3  mb-3">
                <div class="card card-body  p-3" >
                    <p class="text-sm mb-0 text-capitalize "> Today's Orders</p>
                    <h5 class="fw-bold mb-0">
                        <?php
                            $todayDate = date("Y-m-d");
                            $todayOrder = "SELECT * FROM orders WHERE order_date = '$todayDate'";

                            $result = orderItemQuery($todayOrder);
                            if($result['status'] == 200){
                                $totalLength = count($result['data']);
                                echo $totalLength;
                            }else{
                                echo "0";
                            }
                        ?>
                    </h5>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card card-body  p-3" >
                    <p class="text-sm mb-0 text-capitalize "> Total Orders</p>
                    <h5 class="fw-bold mb-0">
                        <?= getCount('orders');?>
                    </h5>
                </div>
            </div>



        </div>
    </div>
<?php include_once 'includes/footer.php'; ?>
