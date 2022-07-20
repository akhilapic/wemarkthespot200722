<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        
        <script type="text/javascript" src="https://js.stripe.com/v2/"></script>    
        
        <script type="text/javascript">
            //set your publishable key
            Stripe.setPublishableKey('pk_test_51IpYXxKeN71b4QWVbYXSvbckLBrNiI3d9qKivGyQVP22uvDOwL2eLjqghsSdXYpaixEDz8vmb9zslbhswbsjxeS700rJvTgFXG');//pk_test_npAflnOwoQOckN49eDWjH6xi00LMruqQfZ
            
            //callback to handle the response from stripe
            function stripeResponseHandler(status, response) {
                if (response.error) {
                    $("#payment-errors").show();
                    //enable the submit button
                    $('#payBtn').removeAttr("disabled");
                    //display the errors on the form
                    // $('#payment-errors').attr('hidden', 'false');
                    $('#payment-errors').addClass('alert alert-danger');
                    console.log(response.error.message);
                    $("#payment-errors").html(response.error.message);
                    //  location.href="/cartinvalid";
                    } else {
                    var form$ = $("#paymentFrm");
                    //get token id
                    var token = response['id'];
                    //    console.log(token);
                    //insert the token into the form
                    form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
                    //submit form to the server
                    form$.get(0).submit();
                }
            }
            $(document).on('input', "#card_num", function() {
        var regex =/^[0-9]{1,10}$/;// /^\d{0,3}?$/;
        var value = $(this).val();
		if(value.length>16)
		{
			$("#card_numerror").text("Please Enter 16 digit card number");
			return false;
		}
		else
		{
			$("#card_numerror").text("");
		}
      });
      $(document).on('input', "#card-expiry-month", function() {
        var regex =/^[0-9]{1,10}$/;// /^\d{0,3}?$/;
        var value = $(this).val();
		if(value.length>2)
		{
			$("#montherror").text("Please Enter vaid month");
			return false;
		}
		else
		{
			$("#montherror").text("");
		}
      });
      $(document).on('input', "#card-expiry-year", function() {
        var regex =/^[0-9]{1,10}$/;// /^\d{0,3}?$/;
        var value = $(this).val();
		if(value.length>4)
		{
			$("#card-expiry-yearerror").text("Please Enter vaid card expiry year");
			return false;
		}
		else
		{
			$("#card-expiry-yearerror").text("");
		}
      });
      $(document).on('input', "#card-cvc", function() {
        var regex =/^[0-9]{1,10}$/;// /^\d{0,3}?$/;
        var value = $(this).val();
		if(value.length>3)
		{
			$("#cvcerror").text("Please Enter vaid CVV number");
			return false;
		}
		else
		{
			$("#cvcerror").text("");
		}
      });

            $(document).ready(function() {
                
                $("#payment-errors").hide();
                
                
                $("#card-expiry-month").keyup(function(){
                    if($(this).val().length < 2){
                        $("#montherror").show();
                        $("#montherror").text("Please enter valid card month");
                        return false;
                    }
                    else
                    {
                        $("#montherror").hide();
                    }
                });
                
                $("#card-expiry-year").keyup(function(){
                    var currentYear= new Date().getFullYear(); 
                    if($(this).val() < currentYear){
                        $("#card-expiry-yearerror").show();
                        $("#card-expiry-yearerror").text("Please enter valid expiry year");
                        return false;
                    }
                    else
                    {
                        $("#card-expiry-yearerror").hide();
                    }
                });
                
                $("#card-cvc").keyup(function(){
                    if($(this).val().length < 3){
                        $("#cvcerror").show();
                        $("#cvcerror").text("Please enter 3 digit CVV number");
                        return false;
                    }
                    else
                    {
                        $("#cvcerror").hide();
                    }
                });
                
                $("#card_num").keyup(function(){
                    if($(this).val().length < 16){
                        $("#card_numerror").show();
                        $("#card_numerror").text("Please enter 16 digit card number");
                        return false;
                    }
                    else
                    {
                        $("#card_numerror").hide();
                    }
                });
                
                //on form submit
                
                $("#paymentFrm").submit(function(event) {
                    //disable the submit button to prevent repeated clicks
                    $('#payBtn').attr("disabled", "disabled");
                    
                    //create single-use token to charge the user
                    Stripe.createToken({
                        number: $('#card_num').val(),
                        cvc: $('#card-cvc').val(),
                        exp_month: $('#card-expiry-month').val(),
                        exp_year: $('#card-expiry-year').val()
                    }, stripeResponseHandler);
                    
                    //submit from callback
                    return false;
                });
            });
            $(document).ready(function(){
                
                $('#paymentFrm').submit();
            });
        </script>
    </head>

    <style>
        .title-font {
            font-size: 18px;
            padding-bottom: 6px;
        }
        .inner-page-content {
            padding: 50px 0px;
        }
    </style>
    <body>
        
        <div class="crl"></div>
        
        
        <section class="top-title-in" style="display:none;">
            
            <div class="container">
                <div class="row">
                    <div class="col-md-4"> </div>
                    <div class="col-md-4">
                        <div class="shop-1"> <span>Please fill the below details...</span>
                            
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </section>
        <section class="inner-page-content">
            
            <div class="container">
                <div class="row">
                    <div class="col-md-12"> 
                        
                        
                        <div class="card">
                            <div class="title-font">Insert Your Card Information </div>
                            <div style="display:none">
                                <h5 >You Have Donate ₹1000</h5>
                            </div>
                            <div class="card-body bg-light">
                                
                                <div id="payment-errors"></div>  
                                <form method="post" id="paymentFrm" enctype="multipart/form-data" action="{{route('strippayment')}}">
                                    @csrf
                                    <input type="hidden" name="user_id" value="<?php echo $result['user_id'];?>" />
                                    
                                    <input type="hidden" name="order_id" value="<?php echo uniqid();?>" />
                                    <div class="form-group">
                                        <input type="text" name="name" value="<?php echo $result['name']?>" readonly class="form-control" placeholder="Name" required>
                                    </div>  
                                    
                                    <div class="form-group">
                                        <input type="email" name="email" value="<?php echo $result['email']?>" readonly class="form-control" mailto:placeholder="email@you.com" placeholder="email@you.com" required />
                                    </div>
                                    
                                    <div class="form-group">
                                        <input type="text" name="card_num" id="card_num" maxlength="16" m="16" value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1')" class="form-control"  placeholder="Card Number" autocomplete="off"  required>
                                    </div>
                                    <span id="card_numerror" style="color:Red"></span>
                                    <div class="row">
                                        
                                        <div class="col-sm-8">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <input type="text" minlength="2" maxlength="2" value="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1')" name="exp_month"  class="form-control" id="card-expiry-month" placeholder="MM"  required>
                                                    </div>
                                                    <span id="montherror" style="color:Red"></span>
                                                </div>
                                                
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <input type="text" minlength="4" maxlength="4" value="" name="exp_year" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1')" class="form-control" maxlength="3" minlength="4" id="card-expiry-year" placeholder="YYYY" required="" >
                                                    </div>
                                                    <span style="color:Red"  id="card-expiry-yearerror"></span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <input type="text" value="" name="cvc" id="card-cvc" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1')" maxlength="3" class="form-control" minlength="3" maxlength="3" autocomplete="off" placeholder="CVV" required>
                                            </div>
                                            <span style="color:Red" id="cvcerror"></span>
                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <input type="text" value="<?php echo $result['amount']?>" readonly name="amount" id="amount"  class="form-control" autocomplete="off" placeholder="amount" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                    
                                    
                                    <div class="form-group text-right">
                                        <button class="btn btn-secondary" type="reset">Reset</button>
                                        <button type="submit" id="payBtn" class="btn btn-success">Submit Payment</button>
                                    </div>
                                </form>     
                            </div>
                        </div> 
                        
                        
                        
                    </div>
                    
                    
                    
                    
                    
                    
                    
                    
                </div>
        </section>
            
            
        
        
        
        <div class="crl"></div>
        <script>
            
            
            //$('#paymentFrm').submit();
            
            
        </script>
    </body>
</html>
