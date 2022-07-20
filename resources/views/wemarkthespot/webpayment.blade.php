<?php
   $startDate  = Session::get('startDate');
   $endDate    = Session::get('endDate');
   $amount     = Session::get('amount');
   $price      = substr($amount, 1);
   $planId     = Session::get('planId');

   if(empty($startDate) || empty($endDate) || empty($amount) || empty($planId)){
      echo "Unauthorize Access";
      die; 
   }

   $base_url =  URL::to('/');
?>

@include("inc/header")

<style type="text/css">
   label.error {
      display: inline-block;
      width: 100%;
      clear: both;
      margin-top: 8px;
      color: #db0707;
   }
</style>

<main class="payment">
         <div class="container-fluid">

         <form 
            role="form" 
            action="{{ route('submitSubcriptionPayment') }}" 
            method="post" 
            class="require-validation"
            data-cc-on-file="false"
            data-stripe-publishable-key="{{ env('PUBLISH_KEY') }}"
            id="payment-form">

            <h1 class="title">Payment</h1>
            <h2>Billing Information</h2>
            <div class="BoxShade p-md-5 mb-5 mt-4 mt-lg-5">

               <!-- <form id="payment_submit_from" method="POST"> -->
                  @csrf
                  <div class="row gx-md-5 gy-4">

                     <input type="hidden" class="form-control" value="{{$startDate}}" name="startDate">
                     <input type="hidden" class="form-control" value="{{$endDate}}" name="endDate">
                     <input type="hidden" class="form-control" value="{{$amount}}" name="amount">
                     <input type="hidden" class="form-control" value="{{$planId}}" name="planId">

                     <div class="col-md-6">
                        <label class="form-label">Customer Name</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Enter Customer Name" value="{{$userData->name}}" required>
                     </div>
                     <div class="col-md-6">
                        <label class="form-label">Billing Email</label>
                        <input type="text" class="form-control" id="billing_email" name="billing_email" placeholder="Enter Billing Email" value="{{$userData->email}}" required>
                     </div>
                     <div class="col-md-6">
                        <label class="form-label">Billing Address</label>
                        <input type="text" class="form-control" id="billing_add" name="billing_add" placeholder="Enter Billing Address" value="{{$userData->location}}" required>
                     </div>
                     <div class="col-md-6">
                        <label class="form-label">Country</label>
                        <input type="text" class="form-control" id="country" name="country" placeholder="Enter Country" value="" required>
                     </div>
                     <div class="col-md-6">
                        <label class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city" placeholder="Enter City" value="" required> 
                     </div>
                     <div class="col-md-6">
                        <label class="form-label">ZIP Code</label>
                        <input type="text" class="form-control" id="zip_code" name="zip_code" placeholder="Enter ZIP Code" value="" required>
                     </div>
                  </div>
               <!-- </form> -->
            </div>

            <h2>Payment Information</h2>
            <div class="row gx-lg-5 gy-4 my-3">
               <div class="col-md-6">
                  <div class="BoxShade p-md-5">
                        <div class="mb-3">
                        <div class="error" style="color:Red"></div>
                        <label class="form-label">Card Number</label>
                           <input type="number" class="form-control card-number" id="card_number" name="card_number" placeholder="Enter Card Number" value="" required>
                        </div>

                        <div class="mb-3">
                           <label class="form-label">Validity</label>
                           <div class="d-flex">
                              <div class="col-md-5">
                                 <input type="text" class="form-control card-expiry-month" id="month" name="month" placeholder="MM" value="" required>
                              </div>
                              <div class="col-md-1"></div>
                              <div class="col-md-6">
                                 <input type="text" class="form-control card-expiry-year" id="year" name="year" placeholder="YYYY" value="" required>
                              </div>
                           </div>
                        </div>
                        <div class="mb-3">
                           <label class="form-label">CVV</label>
                           <input type="number" class="form-control card-cvc" id="cvv" name="cvv" placeholder="Enter CVV" value="" required>
                        </div>
                        <div class="mb-3 form-check ps-0">
                           <input type="checkbox" class="form-check-input" id="save_checkbox" name="save_checkbox" value="">
                           <label class="form-check-label" for="save_checkbox">Save card for future use</label>
                        </div>
                        <div class="text-center"><button type="submit" id="paynow_button" class="btn btn-primary mt-5 px-4">Pay Now</button></div>

                     <!-- </form> -->
                  </div>
               </div>
            </div>

            <form>

         </div>
      </main>


<!-- <script src="{{url('/assets/old/js/jquery.min.js')}}"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!-- //========Form Submit - Start==================================================== -->
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
  
<script type="text/javascript">

  $(function() {
     
      var $form = $(".require-validation");
     
      $('form.require-validation').bind('submit', function(e) {
          var $form     = $(".require-validation"),
          inputSelector = ['input[type=email]', 'input[type=password]',
                           'input[type=text]', 'input[type=file]','input[type=number]',
                           'textarea'].join(', '),
          $inputs       = $form.find('.required').find(inputSelector),
          $errorMessage = $form.find('div.error'),
          valid         = true;
          $errorMessage.addClass('hide');
    
          $('.has-error').removeClass('has-error');
          $inputs.each(function(i, el) {
            var $input = $(el);
            if ($input.val() === '') {
              $input.parent().addClass('has-error');
              $errorMessage.removeClass('hide');
              e.preventDefault();
            }
          });
     
          if (!$form.data('cc-on-file')) {
            e.preventDefault();
            Stripe.setPublishableKey($form.data('stripe-publishable-key'));
            Stripe.createToken({
              number: $('.card-number').val(),
              cvc: $('.card-cvc').val(),
              exp_month: $('.card-expiry-month').val(),
              exp_year: $('.card-expiry-year').val()
            }, stripeResponseHandler);
          }
    });
    
      function stripeResponseHandler(status, response) {
         
         if(response.error){
            $(".error").text(response.error.message);
     
            $('.error')
               .removeClass('hide')
               .find('.alert')
               .text(response.error.message);
         }else{
            /* token contains id, last4, and card type */
            var token = response['id'];
                 
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
         }
      }
     
  });
  
</script>
<!-- //========Form Submit - End====================================================== -->

<script>
   $(document).ready(function(e) {
      $(".nav-item a").removeClass("active");
      $("#subscriptions").addClass('active'); 
   });
</script>

<script src="{{asset('assets/js/calendar.min.js')}} "></script>
<script src="{{asset('assets/js/chart.min.js')}}"></script>

@include("inc/footer");