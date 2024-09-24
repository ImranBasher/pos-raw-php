<?php include('includes/header.php') ?>

    <div class="container-fluid px-4">
        <div class="card mt-4 shadow-sm">
            <div class="card-header">
                <h4 class="mb-0">Print Order
                    <a href="orders.php" class="btn btn-danger btn-sm float-end">Back</a>
                </h4>
            </div>
            <div class="card-body">
                <div id="myBillingArea">
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
    //                            echo "<pre>";
    //                            print_r($orders['data']);
    //                            echo "</pre>";

                                ?>

    <!--//-------------------------------->
                                <table style = 'width : 100%; margin-bottom : 20px'>
                                    <tbody>
                                    <tr>
                                        <td style = "text-align: center;" colspan="2">
                                            <h4 style = "font-size:23px; line-height:30px; margin:2px;padding:0">IBP Group Of Company</h4>
                                            <p style = "font-size:16px; line-height:24px; margin:2px;padding:0">#555, 15 street, sector 12, uttara, Dhaka, BD </p>
                                            <p style = "font-size:16px; line-height:24px; margin:2px;padding:0"> IBP private company LTD.</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td >
                                            <h4 style = "font-size:20px; line-height:30px; margin:0px;padding:0">Customer Details </h4>
                                            <p style = "font-size: 14px; line-height:20px; margin:0px;padding:0">Customer Name :<?=$orders['data'][0]['name']?></p>
                                            <p style = "font-size:14px; line-height:20px; margin:0px;padding:0">Customer Phone :<?=$orders['data'][0]['phone']?></p>
                                            <p style = "font-size:14px; line-height:20px; margin:0px;padding:0">Customer Email :<?=$orders['data'][0]['email']?></p>
                                        </td>
                                        <td align="end">
                                            <h4 style = "font-size:20px; line-height:30px; margin:0px;padding:0">Invoice Details</h4>
                                            <p style = "font-size: 14px; line-height:20px; margin:0px;padding:0">Invoice No : <?=$orders['data'][0]['invoice_number']?></p>
                                            <p style = "font-size:14px; line-height:20px; margin:0px;padding:0"> Invoice Data : <?= date('d M Y');?> </p>
                                            <p style = "font-size:14px; line-height:20px; margin:0px;padding:0">Address : 1st main road, New Market, CTG, BD</p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

    <!--//--------------------------------->
                        <?php
                            }else{
                                echo "<h5>No data found.</h5>";
                                return false;
                            }
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
                            $orderItems = orderItemQuery($orderItemQuery);

                            if ($orderItems) {
                                if($orderItems['status'] == 200){
                                    ?>
                                    <div class="table-responsive mb-3" >
                                        <table style="width: 100%;" cellpadding="5">
                                            <thead>
                                            <tr>
                                                <th align ="start" style="border-bottom : 1px solid #ccc; width = "5%" >ID</th>
                                                <th align ="start" style="border-bottom : 1px solid #ccc; width = "10%" >Product Name</th>
                                                <th align ="start" style="border-bottom : 1px solid #ccc; width = "10%" >Price</th>
                                                <th align ="start" style="border-bottom : 1px solid #ccc; width = "10%" >Quantity</th>
                                                <th align ="start" style="border-bottom : 1px solid #ccc; width = "15%" >Total Price</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $i = 1;

                                            foreach($orderItems['data'] as $key => $row) :

                                                ?>
                                                <tr>
                                                    <td style="border-bottom : 1px solid #ccc;  align = start" ><?= $i++; ?></td>
                                                    <td style="border-bottom : 1px solid #ccc;  align = start" ><?= $row['name']; ?></td>
                                                    <td style="border-bottom : 1px solid #ccc;  align = start" > <?=number_format($row['order_item_price'],0);  ?></td>
                                                    <td style="border-bottom : 1px solid #ccc;  align = start" ><?= $row['order_item_quantity']; ?></td>
                                                    <td style="border-bottom : 1px solid #ccc;  align = start" ><?= number_format($row['order_item_price'] * $row['order_item_quantity'], 0); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr>
                                                <td colspan = "4"  align = "end" style = "font-weight : bold;">Grand Total : </td>
                                                <td colspan = "1"  style = "font-weight : bold;"> <?=number_format($row['totalAmount'], 0);?></td>
                                            </tr>
                                            <tr>
                                                <td colspan = "5" > Payment Mode : <?= $row['payment_mode'];?></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>


                                    <?php
                                        }else{
                                    echo "<h5>No Data Found.</h5>";
                                }
                            }else{
                                echo "<h5>Something Went Wrong.</h5>";
                            }
                                    ?>



                        <?php
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
                <div class = "mt-4 text-end">
                    <button class = "btn btn-info px-4 mx-1" onclick="printMyBillingArea()">Print</button>
                    <button class = "btn btn-primary px-4 mx-1" onclick="downloadPDF('<?=$orders['data'][0]['invoice_number']?>')">Download PDF</button>
                </div>
            </div>
        </div>
    </div>

<?php include('includes/footer.php'); ?>