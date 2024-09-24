$(document).ready(function(){
    alertify.set('notifier','position', 'top-right');
    $(document).on('click', '.increment', function(){
        var quantityInput = $(this).closest('.qtyBox').find('.qty');
        var productId  = $(this).closest('.qtyBox').find('.prodId').val();
        console.log("Product ID:", productId);
        var currentValue = parseInt(quantityInput.val());

        if(!isNaN(currentValue) ) {
            var qtyVal = currentValue + 1 ;
            quantityInput.val(qtyVal);
            quantityIncDec(productId, qtyVal);
        }
    })


    $(document).on('click', '.decrement', function(){
        var quantityInput = $(this).closest('.qtyBox').find('.qty');
        var productId  = $(this).closest('.qtyBox').find('.prodId').val();
        console.log("Product ID:", productId);
        var currentValue = parseInt(quantityInput.val());

        if(!isNaN(currentValue) && currentValue > 1) {
            var qtyVal = currentValue - 1 ;
            quantityInput.val(qtyVal);
            quantityIncDec(productId, qtyVal);
        }
    })


    function quantityIncDec(productId, qty){
        $.ajax({
            type: "POST",
            url : 'orders-code.php',
            data : {
                    'productIncDec' : true,
                    'product_id' : productId,
                    'quantity': qty
            },

            success : function (response){
                var res = JSON.parse(response);
                if(res.status == 200){
                  //  window.location.reload();
                    $('#productArea').load(' #productContent');
                    alertify.success(res.message);
                }else{
                    $('#productArea').load(' #productContent');
                    alertify.error(res.message);
                }
            }
        });
    }

    // proceed to place order button click
    $(document).on('click', '.proceedToPlace', function () {

        console.log('proceedToPlace');

        var payment_mode = $('#payment_mode').val();
        var customer_phone = $('#cphone').val();

        console.log("Customer Phone : " + customer_phone);

        if(payment_mode == ''){
            swal("Select Payment mood", "Select your payment mode", "warning");
            return false;
        }

        if(customer_phone == '' || !$.isNumeric(customer_phone)){
            swal("Enter Phone Number", "Enter valid phone number", "warning");
            return false;
        }
        var data = {
            'proceedToPlaceBtn': true,
            'customer_phone': customer_phone,
            'payment_mode': payment_mode,
        };
        $.ajax({

                type: "POST",
                url : 'orders-code.php',
                data : data,
                success : function (response) {
                    console.log(response);
                    try{
                        var res = JSON.parse(response);
                        if (res.status == 200) {
                            window.location.href = 'order-summary.php';
                        } else if (res.status == 404) {
                            swal(res.message, res.message, res.status_type, {
                                buttons: {
                                    catch: {
                                        text: 'Add Customer',
                                        value: "catch"
                                    },
                                    cancel: "Cancel"
                                }
                            })
                                .then((value) => {
                                    switch (value) {

                                        case "catch":
                                            $('#c_phone').val(customer_phone);
                                            $('#addCustomerModel').modal('show');
                                            // console.log('Pop the customer add model');
                                            break;
                                        default:
                                    }
                                });
                            } else {
                                swal(res.message, res.message, res.status_type);
                            }
                    }catch (e) {
                            console.error("Parsing error:", e);
                            swal("Error", "An error occurred while processing your request.", "error");
                        }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("AJAX Error: ", textStatus, errorThrown);
                swal("Error", "An unexpected error occurred. Please try again.", "error");
            }


        });

    });

    $(document).on('click', '.saveCustomer', function(){
        var customer_name = $('#c_name').val();
        var customer_email = $('#c_email').val();
        var customer_phone = $('#c_phone').val();


        if(customer_name != '' && customer_phone != ''){

                var data = {
                    'saveCustomerBtn' : true,
                    'name': customer_name,
                    'email': customer_email,
                    'phone': customer_phone
                };

                $.ajax({
                    url : 'orders-code.php',
                    type : 'POST',
                    data : data,
                    success : function (response) {
                        console.log(response);
                        var res = JSON.parse(response);
                        if(res.status == 200){
                            swal(res.message, res.message, res.status_type);
                            $('#addCustomerModel').modal('hide');
                        }else if(res.status == 422){
                            swal(res.message, res.message, res.status_type);
                        }else{
                            swal(res.message, res.message, res.status_type);
                        }
                    }
                });
        }else{
            swal("Enter valid phone number", "", "warning");
        }
    });

    $(document).on('click', '#saveOrder', function(){

            $.ajax({
            type: "POST",
            url : 'orders-code.php',
            data : { 'saveOrder' : true },
            success : function (response) {
                console.log(response);
                var res = JSON.parse(response);
                if(res.status == 200){
                    swal(res.message, res.message, res.status_type);
                    $('#orderPlaceSuccessMessage').text(res.message);
                    $('#orderSuccessModal').modal('show');
                }else{
                    swal(res.message, res.message, res.status_type);
                }
            }
        });
    });




})


function printMyBillingArea(){
    var divContents = document.getElementById('myBillingArea').innerHTML;
    var a = window.open('','');

    a.document.write('<html><title>POS SYSTEM IN PHP </title>');
    a.document.write('<body style="font-family:fangsong;" >');
    a.document.write(divContents);
    a.document.write('</body></html>');
    a.document.close();
    a.print();
}

    window.jsPDF = window.jspdf.jsPDF;
    var docPDF = new jsPDF();

    function downloadPDF(invoice_number){

        var elementHTML = document.querySelector("#myBillingArea");
        docPDF.html(elementHTML, {
            callback : function(){
                docPDF.save(invoice_number+'.pdf')
            },
            x:15,
            y:15,
            width:170,
            windowWidth: 650
        });

    }