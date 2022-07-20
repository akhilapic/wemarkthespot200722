<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

                <!-- <script type="text/javascript" src="https://js.stripe.com/v3/"></script>   -->
                <script src="https://js.stripe.com/v3/"></script>
  
                <meta name="csrf-token" content="{{ csrf_token() }}">

<script type="text/javascript">
    //set your publishable key
   // Stripe.setPublishableKey('pk_test_51K1fBCBdbqSZjN9WhipulmXOeD7t8yGXmM4Yw8gxim6EEVPC5l1fdgPHrdcdPprX8fgPBPF8xgzBPCYjw9UWyHuR00ok4CHd2b');
    
    var stripe = Stripe('pk_test_51K1fBCBdbqSZjN9WhipulmXOeD7t8yGXmM4Yw8gxim6EEVPC5l1fdgPHrdcdPprX8fgPBPF8xgzBPCYjw9UWyHuR00ok4CHd2b');


    //callback to handle the response from stripe
    function stripeResponseHandler(status, response) {
        if (response.error) {
            //enable the submit button
            $('#payBtn').removeAttr("disabled");
            //display the errors on the form
            // $('#payment-errors').attr('hidden', 'false');
            $('#payment-errors').addClass('alert alert-danger');
            $("#payment-errors").html(response.error.message);
             //location.href="/cartinvalid";
        } else {
            var form$ = $("#paymentFrm");
            //get token id
            var token = response['id'];
            console.log("token :::::"+token);
            //insert the token into the form
            form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
            //submit form to the server
            form$.get(0).submit();
        }
    }
    $(document).ready(function() {
        //on form submit
        $("#paymentFrm").submit(function(event) {
            //disable the submit button to prevent repeated clicks
            $('#payBtn').attr("disabled", "disabled");
            
//             stripe.createToken(
//                 {
//                     number: $('#card_num').val(),
//                 cvc: $('#card-cvc').val(),
//                 exp_month: $('#card-expiry-month').val(),
//                 exp_year: $('#card-expiry-year').val()
//                 }).then(function(result) {
//   // Handle result.error or result.token
  
// });
// const f = async ()=>{
//     const token = await stripe.tokens.create({
//   bank_account: {
//     country: 'US',
//     currency: 'usd',
//     account_holder_name: 'Jenny Rosen',
//     account_holder_type: 'individual',
//     routing_number: '110000000',
//     account_number: '000123456789',
//   },
// });
// }
// console.log("token"+ f);


// $stripe.createToken([
//   'card' => [
//     'number' => '4242424242424242',
//     'exp_month' => 5,
//     'exp_year' => 2023,
//     'cvc' => '314',
//   ],
// ]);

// Stripe.createToken({
//     type:"card",
//     number: $('#card_num').val(),
//                 cvc: $('#card-cvc').val(),
//                 exp_month: $('#card-expiry-month').val(),
//                 exp_year: $('#card-expiry-year').val()
//     }, stripeResponseHandler)

   //         create single-use token to charge the user
            // Stripe.createToken({
            //     number: $('#card_num').val(),
            //     cvc: $('#card-cvc').val(),
            //     exp_month: $('#card-expiry-month').val(),
            //     exp_year: $('#card-expiry-year').val()
            // }, stripeResponseHandler);
            
            //submit from callback
            return false;
        });
    });
     $(document).ready(function(){
        
       $('#paymentFrm').submit();
    });
</script>



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
            <div class="card-header bg-success text-white">Insert Your Card Information </div>
            <div class="card-body bg-light">
              
                <div id="payment-errors"></div>  
                 <form method="post" id="paymentFrm" enctype="multipart/form-data" action="{{route('strippayment')}}">
                    <input type="hidden" name="order_id" value="" />
                    <div class="form-group">
                        <input type="text" name="name" value="" class="form-control" placeholder="Name" required>
                    </div>  
                    @csrf

                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />                   
                    <div class="form-group">
                        <input type="email" name="email" value="" class="form-control" mailto:placeholder="email@you.com" required />
                    </div>

                     <div class="form-group">
                        <input type="text" name="card_num" id="card_num" value="" class="form-control" placeholder="Card Number" autocomplete="off"  required>
                    </div>
                   
                    <?php
                    $card=explode("/",$result['expairy_date']);
                    $edate=isset($card[0]) ? $card[0] :'';
                    $emonth=isset($card[1]) ? $card[1] : '';
                    ?>
                    <div class="row">

                        <div class="col-sm-8">
                             <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" value="" name="exp_month" maxlength="2" class="form-control" id="card-expiry-month" placeholder="MM"  required>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" value="" name="exp_year" class="form-control" maxlength="4" id="card-expiry-year" placeholder="YYYY" required="" >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="text" value="" name="cvc" id="card-cvc" maxlength="3" class="form-control" autocomplete="off" placeholder="CVC" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="number" value="" name="amount" id="amount" maxlength="3" class="form-control" autocomplete="off" placeholder="amount" required>
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


</form>




<div class="crl"></div>




<script>


//$('#paymentFrm').submit();


</script>