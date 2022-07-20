<?php

$base_url =  URL::to('/');
?>

<?php echo $__env->make("inc/header", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>;
<style>
   label.error {
      display: inline-block;
      width: 100%;
      clear: both;
      margin-top: 8px;
      color: #db0707;
   }
</style>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
<main class="faqs">
   <div class="container-fluid">
      <h1 class="title">Account Settings</h1>
      <div class="row gy-5">
         <div class="col-md-4 pe-lg-5">
            <aside>
               <div class="BoxShade UserBox mb-4">
                  <figure>
                     <?php if($account->business_images): ?>
                     <img src="<?php echo e($account->business_images); ?>">
                     <?php elseif($account->image): ?>
                     <img src="<?php echo e($account->image); ?>">

                     else

                     <img src="<?php echo e(asset('assets/images/img-6.png')); ?>">
                     <?php endif; ?>

                  </figure>
                  <?php if($account->business_name): ?>
                  <p><?php echo e($account->business_name); ?></p>
                  <?php else: ?>
                  <p>Business Name</p>
                  <?php endif; ?>
                  <?php if($account->ratting): ?>

                  <p class="rating"><?php echo e($account->ratting); ?> <span class="icon-star"></span></p>
                  <?php else: ?>
                  <p class="rating">0.0 <span class="icon-star"></span></p>
                  <?php endif; ?>
                  <p class="verify">Verified</p>
               </div>
               <div class="BoxShade">
                  <ul>
                     <li><a href="<?php echo e(url('my_account')); ?>">My Profile</a></li>
                     <li><a href="<?php echo e(url('my-subscription')); ?>">My Subscription</a></li>
                     <li><a href="<?php echo e(url('notifications')); ?>">Notifications</a></li>
                     <li><a href="<?php echo e(url('ac-change-password')); ?>">Change Password</a></li>
                     <li><a href="<?php echo e(url('faqs')); ?>">FAQs</a></li>
                     <li class="active"><a href="<?php echo e(url('contact-us')); ?>">Contact Us</a></li>
                     <li><a href="<?php echo e(url('/websignout')); ?>">Sign Out</a></li>
                  </ul>
               </div>
            </aside>
         </div>
         <div class="col-md-8">
            <h4 class="acTitle">Contact Us</h4>
            <div class="row gy-4">
               <div class="col-md-12 col-lg-6 d-flex align-items-stretch">
                  <div class="BoxShade contactICon w-100">
                     <span class="icon-mail"></span>
                     <h6>Mail</h6>

                     <p>wemarkthespot@gmail.com</p>
                  </div>
               </div>
               <div class="col-md-12 col-lg-6 d-flex align-items-stretch">
                  <div class="BoxShade contactICon w-100">
                     <span class="icon-location"></span>
                     <h6>Address</h6>

                     <p>321 Route 440, St 164 Jersey City, NJ 07305</p>


                  </div>
               </div>
            </div>
            <div class="BoxShade mt-4">
               <h4 class="mb-4">Get in Touch</h4>
               <div class="result"></div>
               <form action="javascript:void(0)" id="contactusform" method="post" enctype="multipart/form-data">
                  <div class="row gy-4">
                     <div class="col-md-6 col-lg-6">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name">
                     </div>

                     <div class="col-md-6 col-lg-6">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" name="email" id="email" placeholder="Enter Email">
                     </div>

                     <div class="col-md-6 col-lg-6">
                        <label class="form-label">Phone Number</label>
                        <?php if($country_codedata): ?>
                        <div class="input-group">
                           <select id="country_code" name="country_code" class="form-select" style=" padding: 0px 15px;max-width: 200px;background-color: #f5f5f5;">
                              <?php $__currentLoopData = $country_codedata; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <option value="<?php echo e($c->code); ?>"><?php echo e($c->name); ?></option>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                           </select>
                           <?php endif; ?>

                           <input type="text" class="form-control numbersOnly" name="phone" id="phone" maxlength="10" minlength="10" placeholder="Enter Phone Number">
                        </div>
                     </div>
                        <div class="col-md-6 col-lg-6">
                           <label class="form-label">Comment</label>
                           <textarea class="form-control" name="comment" id="comment" placeholder="Type Comment"></textarea>
                        </div>

                     </div>
                     <div class="text-center"><input type="submit" id="contactsubmit" class="btn btn-primary mt-4" value="Send" /></div>
               </form>
            </div>
         </div>
      </div>
   </div>
</main>

<script src="<?php echo e(asset('assets/js/bootstrap.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/jquery.min.js')); ?> "></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>



<script>
   jQuery('.numbersOnly').keyup(function() {
      this.value = this.value.replace(/[^0-9\.]/g, '');
   });

   jQuery.validator.addMethod("emailExt", function(value, element, param) {
      return value.match(/^[a-zA-Z0-9_\.%\+\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,}$/);
   }, 'Please enter valid email');

   $("#contactusform").validate({
      rules: {
         name: {
            required: true,
         },
         email: {
            required: true,
            email: true,
            maxlength: 50,
            emailExt: true,
         },
         phone: {
            required: true,
            minlength: 10,
            maxlength: 10
         },
         comment: {
            required: true
         },

      },

      messages: {
         name: {
            required: "Please enter name",
         },
         email: {
            required: "Please enter valid email",
            email: "Please enter valid email",
         },
         phone: {
            required: "Please enter phone number",
         },
         comment: {
            required: "Please enter comment",
         },
      },
      submitHandler: function(form) {
         var formData = new FormData(jQuery('#contactusform')[0]);
         formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
         host_url = "/development/wemarkthespot/";
         jQuery.ajax({
            url: host_url + "contactusform",
            type: "POST",
            cache: false,
            data: formData,
            processData: false,
            contentType: false,

            success: function(data) {

               var obj = JSON.parse(data);

               if (obj.status == true) {

                  jQuery('.result').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong style='color:green' class='text-success'>" + obj.message + "</strong> </div>");
                  setTimeout(function() {
                     jQuery('.result').html('');
                     window.location.reload();
                  }, 2000);
               } else {
                  if (obj.status == false) {
                     jQuery('.result').html(obj.message);
                     jQuery('#name_error').css("display", "block");
                  }

               }
            }
         });
      }
   });

   //});
</script>

<?php echo $__env->make("inc/footer", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>;
<script type="text/javascript">
   $(document).ready(function(e) {
      $(".nav-sidebar a").removeClass("active");
      $("#contact-us").addClass('active');
   });
</script><?php /**PATH C:\xampp\htdocs\development\wemarkthespot\resources\views/wemarkthespot/contact-us.blade.php ENDPATH**/ ?>