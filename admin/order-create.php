<?php include_once 'includes/header.php'; ?>

<!-----------------------Modal Start For Add Customer---------------------->

    <!-- Button trigger modal -->
    <!-- Modal -->
    <div class="modal fade" id="addCustomerModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                                    <!---------------Model body------------>
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="">Enter Customer Name</label>
                        <input type="text" class = "form-control" id = "c_name">
                    </div>

                    <div class="mb-3">
                        <label for="">Enter Customer Phone</label>
                        <input type="text" class = "form-control" id = "c_phone">
                    </div>

                    <div class="mb-3">
                        <label for="">Enter Customer email</label>
                        <input type="text" class = "form-control" id = "c_email">
                    </div>

                </div>
                                            <!----------------------->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary saveCustomer">Save </button>
                </div>
            </div>
        </div>
    </div>

<!-----------------------Modal End for add customer------------------------>



    <div class="container-fluid px-4">
        <div class="card mt-4 shadow-sm">
            <div class="card-header">
                <h4 class="mb-0"> Create Order
                    <a href="products.php" class="btn btn-danger float-end">Back</a>
                </h4>
            </div>
            <div class="card-body">


                <?php alertMessage(); ?>

                <form action="orders-code.php" method = 'POST' enctype="multipart/form-data">

                    <div class="row">

                        <div class="col-md-3 mb-3">
                            <label for="">Select Product</label>
                            <select name="productID" id="" class="form-select mySelect2">
                                <!--<option value="">Select Category</option>-->
                                <?php
                                    $products  = getAll('products');
                                    if($products){
                                        if(mysqli_num_rows($products) > 0){
                                            foreach($products as $product){
                                                echo '<option value="'.$product['id'].'">'.$product['name'].'</option>';
                                            }
                                        }else{
                                            echo '<option value="">No Product Found</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label for=""> Quantity</label>
                            <input type="number" name = "quantity"  value ='1' class = "form-control">
                        </div>


                        <div class="col-md-3 mb-3 text-end">
                            <br/>
                            <button type="submit" name = "addItem" class="btn btn-primary">Add Item</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>




        <div class="card mt-3">
            <div class="card-header">
                <h4 class="mb-0">Products</h4>
                <div class="card-body" id = "productArea">
                    <?php
                        if(isset($_SESSION['productItems'])){
                            $sessionProducts = $_SESSION['productItems'];

                            if(empty($sessionProducts)){
                                unset($_SESSION['productItems']);
                                unset($_SESSION['productItemIds']);
                            }
                            ?>
                            <div class="table-responsive mb-3" id ="productContent">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Product Name</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total Price</th>
                                            <th>Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $i = 1;
                                        foreach ($sessionProducts as $key => $item): ?>
                                            <tr>
                                                <td><?= $i++; ?></td>
                                                <td><?= $item['name']; ?></td>
                                                <td><?= $item['price'] ?></td>
                                                <td>
                                                    <div class="input-group qtyBox">
                                                        <input type="hidden" value="<?=$item['productID']?>" class="prodId">
                                                        <button class="input-group-text decrement">-</button>
                                                            <input type="text" value="<?= $item['quantity'];?>" class = "qty quantityInput ">
                                                        <button class="input-group-text increment">+</button>
                                                    </div>
                                                </td>
                                                <td><?= number_format($item['price'] * $item['quantity'], 0); ?></td>
                                                <td>
                                                    <a href="order-item-delete.php?index=<?= $key; ?>"  class="btn btn-danger"> Remove </a>
                                                </td>

                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                </table>
                            </div>

                            <div class="mt-2">
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for=""> Select Payment Mode </label>
                                        <select name="" id="payment_mode" class = "form-select">
                                            <option value="">--Select Payment--</option>
                                            <option value="cash_payment"> Cash Payment</option>
                                            <option value="online_payment"> Online Payment</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="customer_phone">Enter Customer Phone Number</label>
                                        <input type="number" id="cphone" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <br/>
<!--                                        <button type = "button" class = "btn btn-warning w-100 proceedToPlace ">Processed to place order </button>-->
                                        <button type="button" class="btn btn-warning w-100 proceedToPlace">Processed to place order</button>
                                    </div>
                                </div>
                            </div>

                          
                            <?php
                        }else{
                            echo "<h5>No Item Added</h5>";
                        }
                    ?>
                </div>
            </div>
        </div>















        <pre>
            <?php
            print_r($_SESSION['productItems']);
            print_r($_SESSION['productItemIds']);
            ?>
        </pre>
    </div>
<!--    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
<!--<script>-->
<!--    $(document).ready(function() {-->
<!--        $('.proceedToPlace').on('click', function () {-->
<!---->
<!--            console.log('proceedToPlace');-->
<!---->
<!--            var payment_mode = $('#payment_mode').val();-->
<!--            var customer_phone = $('#cphone').val();-->
<!---->
<!--            console.log("Customer Phone : " + customer_phone);-->
<!---->
<!--            if(payment_mode == ''){-->
<!--                swal("Select Payment mood", "Select your payment mode", "warning");-->
<!--                return false;-->
<!--            }-->
<!---->
<!--            if(customer_phone == '' || !$.isNumeric(customer_phone)){-->
<!--                swal("Enter Phone Number", "Enter valid phone number", "warning");-->
<!--                return false;-->
<!--            }-->
<!--            var data = {-->
<!--                'proceedToPlaceBtn': true,-->
<!--                'customer_phone': customer_phone,-->
<!--                'payment_mode': payment_mode,-->
<!--            };-->
<!--            $.ajax({-->
<!---->
<!--                type: "POST",-->
<!--                url : 'orders-code.php',-->
<!--                data : data,-->
<!--                success : function (response) {-->
<!--                    console.log(response);-->
<!--                    try{-->
<!--                        var res = JSON.parse(response);-->
<!--                        if (res.status == 200) {-->
<!--                            window.location.href = 'order-summary.php';-->
<!--                        } else if (res.status == 404) {-->
<!--                            swal(res.message, res.message, res.status_type, {-->
<!--                                buttons: {-->
<!--                                    catch: {-->
<!--                                        text: 'Add Customer',-->
<!--                                        value: "catch"-->
<!--                                    },-->
<!--                                    cancel: "Cancel"-->
<!--                                }-->
<!--                            })-->
<!--                                .then((value) => {-->
<!--                                    switch (value) {-->
<!---->
<!--                                        case "catch":-->
<!--                                            $('#addCustomerModel').modal();-->
<!--                                            // console.log('Pop the customer add model');-->
<!--                                            break;-->
<!--                                        default:-->
<!--                                    }-->
<!--                                });-->
<!--                        } else {-->
<!--                            swal(res.message, res.message, res.status_type);-->
<!--                        }-->
<!--                    }catch (e) {-->
<!--                        console.error("Parsing error:", e);-->
<!--                        swal("Error", "An error occurred while processing your request.", "error");-->
<!--                    }-->
<!--                },-->
<!--                error: function(jqXHR, textStatus, errorThrown) {-->
<!--                    console.error("AJAX Error: ", textStatus, errorThrown);-->
<!--                    swal("Error", "An unexpected error occurred. Please try again.", "error");-->
<!--                }-->
<!---->
<!---->
<!--            });-->
<!---->
<!--        });-->
<!--    });-->
<!--</script>-->

<?php include_once 'includes/footer.php'; ?>