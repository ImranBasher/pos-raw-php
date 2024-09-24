<?php
    include_once 'includes/header.php';
    //require '../config/helperFunction.php';

    if(!isset($_SESSION['productItems'])){
       echo "<script>   window.location.href = 'order-create.php' </script>";
    }
    ?>

<!-----------------------Modal Start For Add Customer---------------------->

<!-- Modal -->
<div class="modal fade" id="addCustomerModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!---------------Model body------------>
            <div class="modal-body">
                <div class="mb-3 p-4">
                    <h5 id = "orderPlaceSuccessMessage"></h5>
                </div>
            </div>
            <!----------------------->
            <div class="modal-footer">
                <a href="orders.php" class="btn btn-secondary" >Close</a>>
                <a href="orders-view-print.php?track=<?=$_GET['track']?>" class="btn btn-danger mx-2 btn-sm float-end">Print</a>
                <button type="button" class="btn btn-danger" onclick="printMyBillingArea()">Print </button>
                <button type="button" class="btn btn-warning" onclick="downloadPDF('<?=$_SESSION['invoice_number'];?>')">Download PDF </button>
            </div>
        </div>
    </div>
</div>

<!-----------------------Modal End for add customer------------------------>


    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="mb-0">Order Summary
                            <a href="order-create.php" class="btn btn-danger float-end">Back to create order</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php  alertMessage(); ?>
                        <div id="myBillingArea">
                            <?php
                                if(isset($_SESSION['customer_phone'])){
                                    $phone = $_SESSION['customer_phone'];
                                    $invoiceNo = $_SESSION['invoice_number'];

                                    $customerQuery = getByColumn('customers', 'phone' , $phone);

                                    if($customerQuery){
                                        if($customerQuery['status'] == 200){
                                            ?>
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
                                                        <p style = "font-size: 14px; line-height:20px; margin:0px;padding:0">Customer Name :<?=$customerQuery['data']['name']?></p>
                                                        <p style = "font-size:14px; line-height:20px; margin:0px;padding:0">Customer Phone :<?=$customerQuery['data']['phone']?></p>
                                                        <p style = "font-size:14px; line-height:20px; margin:0px;padding:0">Customer Email :<?=$customerQuery['data']['email']?></p>
                                                    </td>
                                                    <td align="end">
                                                        <h4 style = "font-size:20px; line-height:30px; margin:0px;padding:0">Invoice Details</h4>
                                                        <p style = "font-size: 14px; line-height:20px; margin:0px;padding:0">Invoice No : <?=$invoiceNo?></p>
                                                        <p style = "font-size:14px; line-height:20px; margin:0px;padding:0"> Invoice Data : <?= date('d M Y');?> </p>
                                                        <p style = "font-size:14px; line-height:20px; margin:0px;padding:0">Address : 1st main road, New Market, CTG, BD</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                            <?php
                                        }
                                    }else{
                                        echo "<h5>Customer not found</h5>";
                                        return ;
                                    }

                                }
                            ?>

                            <?php
                                if(isset($_SESSION['productItems'])){
                                        $sessionProducts = $_SESSION['productItems'];
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
                                                    $totalPrice = 0;
                                                    foreach($sessionProducts as $key => $row) :
                                                        $totalPrice += $row['price'] * $row['quantity'];
                                                ?>
                                                    <tr>
                                                        <td style="border-bottom : 1px solid #ccc;  align = start" ><?= $i++; ?></td>
                                                        <td style="border-bottom : 1px solid #ccc;  align = start" ><?= $row['name']; ?></td>
                                                        <td style="border-bottom : 1px solid #ccc;  align = start" > <?=number_format($row['price'],0);  ?></td>
                                                        <td style="border-bottom : 1px solid #ccc;  align = start" ><?= $row['quantity']; ?></td>
                                                        <td style="border-bottom : 1px solid #ccc;  align = start" ><?= number_format($row['price'] * $row['quantity'], 0); ?></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                    <tr>
                                                        <td colspan = "4"  align = "end" style = "font-weight : bold;">Grand Total : </td>
                                                        <td colspan = "1"  style = "font-weight : bold;"> <?=number_format($totalPrice, 0);?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan = "5" > Payment Mode : <?= $_SESSION['payment_mode'];?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                            <?php
                                }else{
                                    echo "<h5 class ='text-center'>No Items added </h5>";
                                }

                            ?>
                        </div>

                        <?php if(isset($_SESSION['productItems'])): ?>
                        <div class="mt-4 text-end">
                            <button type = "button" class="btn btn-primary px-4 mx-1" id="saveOrder">Save</button>
                            <button type="button" class="btn btn-danger" onclick="printMyBillingArea()">Print </button>
                            <button type="button" class="btn btn-warning" onclick="downloadPDF('<?=$_SESSION['invoice_number'];?>')">Download PDF </button>
                        </div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- <h3>SESSION value :</h3>-->

<?php

// Check if there are any session values
if (!empty($_SESSION)) {
    // Loop through all session variables
    foreach ($_SESSION as $key => $value) {
        echo "------------------------------------<b>NEW</b>----------------------------------------------------<br>";
        echo "<br>-----------<b> 1 [ ".$key." ] </b> ------<br>";

       // echo "<pre>";
        // Check if the session value is an array
        if (is_array($value)) {


            foreach ($value as $key2 => $value2) {
                echo "<br>----------<b> 2 [".$key."] => [".$key2."]</b> ------<br>";
                // Check if the inner value is also an array
                if (is_array($value2)) {
                    foreach ($value2 as $key3 => $value3) {
                        echo "-----------<b>3 [".$key."] => [".$key2."] => [".$key3."]</b> ------<br>";
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;" . $key3 . " = " . $value3 . "<br>";
                    }
                } else {

                    echo "&nbsp;&nbsp;" . $key2 . " = " . $value2 . "<br>";
                }
            }
            echo "<br>---------end array----------<br>";
            echo "</pre>";

        } else {
            echo $key . " index :<br>";
            echo $key . " = " . $value . "<br>";
        }
      //  echo "</pre>";
        echo "<br>---------foreach [ ".$key." ]end</b> ----------<br><br><br>";
        echo "----------------------------------------------------------------------------------------<br><br><br>";
    }
} else {
    echo "No session data found.";
}
?>


<?php include_once 'includes/footer.php'; ?>


