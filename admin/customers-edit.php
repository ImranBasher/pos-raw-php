
<?php include_once 'includes/header.php'; ?>
<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <div class="mb-0"><h3>Edit Customer</h3> </div>
            <a href="customers.php" class="btn btn-danger float-end">Back</a>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>

            <form action="code.php" method = 'POST'>
                <?php

                $paramValue = checkParamId('id');
                if(!is_numeric($paramValue)){
                    echo '<h5>' . $paramValue . '</h5>';
                    return false;
                }
                $customerData = getById('customers', $paramValue);

                    if($customerData['status'] == 200){
                        ?>
                        <!----------------------------------------------------------------------------------------------------------------->
                        <div class="row">
                            <input type="hidden" name = "customerId"  value = "<?= $customerData['data']['id']?>">
                            <div class="col-md-12 mb-3">
                                <label for="">Name *</label>
                                <input type="text" name = "name"  required value = "<?= $customerData['data']['name']?>" class = "form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Email *</label>
                                <input type="email" name = "email"  required value = "<?= $customerData['data']['email']?>" class = "form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Phone Number *</label>
                                <input type="number" name = "phone"  required value = "<?= $customerData['data']['phone']?>" class = "form-control">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="">Status </label>
                                <input type="checkbox" name = "is_ban" <?= $customerData['data']['status'] == 0 ? 'checked' : ''; ?>  style = "width:30px; height: 30px">
                            </div>
                            <div class="col-md-12 mb-3 text-end">
                                <button type="submit" name = "updateCustomer" class="btn btn-primary">Update</button>
                            </div>
                        </div>

                        <!----------------------------------------------------------------------------------------------------------------->
                        <?php
                    }else{
                        echo '<h5>'.$customerData['message'].'</h5>';
                        return false;
                    }

                ?>

            </form>
        </div>
    </div>
</div>
<?php include_once 'includes/footer.php'; ?>