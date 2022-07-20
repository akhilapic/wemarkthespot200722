<?php

 $base_url =  URL::to('/');
?>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
   
<style>
   label.error {
    display: inline-block;
    width: 100%;
    clear: both;
    margin-top: 8px;
    color: #db0707;
}
#eye1 {
    position: absolute;
    right: 15px;
    top: 48px;
}
#eye2 {
    position: absolute;
    right: 15px;
    top: 48px;
}
#eye3 {
    position: absolute;
    right: 15px;
    top: 48px;
}
</style>
<?php echo $__env->make("inc/header", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>;
 <main class="my-notification">
         <div class="container-fluid">
            <h1 class="title">Account Settings</h1>
            <div class="row gy-5">
               <div class="col-md-4 pe-lg-5">
                  <aside>
                     <div class="BoxShade UserBox mb-4">
                     <figure> <?php if($account->business_images): ?>   
                        <img src="<?php echo e($account->business_images); ?>">
                        <?php elseif($account->image): ?>
                        <img src="<?php echo e($account->image); ?>">
                        else
                        <img src="<?php echo e(asset('assets/images/img-6.png')); ?>">
                        <?php endif; ?></figure>
                     <?php if($account->business_name): ?>  
                     <p><?php echo e($account->business_name); ?></p>
                     <?php else: ?>
                     <p>Business Name</p>
                     <?php endif; ?>
                        <p class="rating"><?php if($account->ratting): ?> <?php echo e($account->ratting); ?> <?php else: ?> 0 <?php endif; ?>  <span class="icon-star"></span></p>
                        <p class="verify">Verified</p>
                     </div>
                     <div class="BoxShade">
                        <ul>
                           <li><a href="<?php echo e(url('my_account')); ?>">My Profile</a></li>
                           <li><a href="<?php echo e(url('my-subscription')); ?>">My Subscription</a></li>
                           <li><a href="<?php echo e(url('notifications')); ?>">Notifications</a></li>
                           <li class="active"><a href="<?php echo e(url('ac-change-password')); ?>">Change Password</a></li>
                           <li ><a href="<?php echo e(url('faqs')); ?>">FAQs</a></li>
                           <li ><a href="<?php echo e(url('contact-us')); ?>">Contact Us</a></li>
                           <li><a href="<?php echo e(url('/websignout')); ?>">Sign Out</a></li>
                        </ul>
                     </div>
                  </aside>
               </div>
               <div class="col-md-8">
                  <label class="result error" style="font-size: 1rem;
    font-weight: 600;">
    
   </label>
                  <h4 class="acTitle">Change Password</h4>
                  <form  action="javascript:void(0)" id="business_user_change_psd" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>"/>
                   <input type="hidden" name="id" value="<?php echo e($account->id); ?>"/>
                     <div class="mb-4" style="
    position: relative;
">
                        <label class="form-label">Current Password</label>
                        <input type="text" class="form-control" name ="old_password" id ="old_password"  placeholder="Enter Current Password">
                        <span class="fa fa-eye-slash input_icon" id="eye1" style="cursor: pointer ;color: #9f9a9a;" data-name="password"></span>
                     </div>
                     <div class="mb-4" style="
    position: relative;
">
                        <label class="form-label">New Password</label>
                        <input type="text" class="form-control" id="new_password" name ="new_password" placeholder="Enter New Password">
                        <span class="fa fa-eye-slash input_icon" id="eye2" style="cursor: pointer ;color: #9f9a9a;" data-name="password"></span>
                     </div>
                     <div class="mb-4" style="
    position: relative;
">
                        <label class="form-label">Confirm Password</label>
                        <input type="text" class="form-control" name ="confirm_password" id="confirm_password" placeholder="Enter Confirm Password">
                        <span class="fa fa-eye-slash input_icon" id="eye3" style="cursor: pointer ;color: #9f9a9a;" data-name="password"></span>
                     </div>
                     <div class="text-center"><input type="submit" id="admin_change_psd" class="btn btn-primary mt-4" value="Update" /></div>
                  </form>
                  
               </div>
            </div>
         </div>
      </main>
  
    <div class="modal" tabindex="-1" id="ModalOfferDetails">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Password Update</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <p id="msgs"></p>
        <p id="modal_offer_add_text"></p>
         <p id="modal_offer_name"></p>
         <p id="modal_offer_startDate"></p>
         <p id="modal_offer_endDate"></p>
      </div>
      <div class="modal-footer">
<!--         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>


      <script src="<?php echo e(asset('assets/js/bootstrap.min.js')); ?>"></script>
      <script src="<?php echo e(asset('assets/js/jquery.min.js')); ?> "></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
      <script>
$("#eye1").on("click",function(){
		 	
          type=$("#old_password").attr("type");
               if(type=='password')
                {
                   $("#eye1").removeClass("fa-eye-slash");
                   $("#eye1").addClass("fa-eye");
                   $("#old_password").attr("type",'text');
                }
                else
                {
                   $("#eye1").addClass("fa-eye-slash");
                   $("#eye1").removeClass("fa-eye");
                   $("#old_password").attr("type",'password');
                }
       });
       $("#eye2").on("click",function(){
		 	
          type=$("#new_password").attr("type");
               if(type=='password')
                {
                   $("#eye2").removeClass("fa-eye-slash");
                   $("#eye2").addClass("fa-eye");
                   $("#new_password").attr("type",'text');
                }
                else
                {
                   $("#eye2").addClass("fa-eye-slash");
                   $("#eye2").removeClass("fa-eye");
                   $("#new_password").attr("type",'password');
                }
       });
       $("#eye3").on("click",function(){
		 	
          type=$("#confirm_password").attr("type");
               if(type=='password')
                {
                   $("#eye3").removeClass("fa-eye-slash");
                   $("#eye3").addClass("fa-eye");
                   $("#confirm_password").attr("type",'text');
                }
                else
                {
                   $("#eye3").addClass("fa-eye-slash");
                   $("#eye3").removeClass("fa-eye");
                   $("#confirm_password").attr("type",'password');
                }
       });
         patten ="^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$";


jQuery.validator.addMethod("passwordcheck", function(value, element, param) {
return value.match(patten);
},'Please enter valid password');

         	$("#business_user_change_psd").validate({
		rules: {
			old_password: {required: true,passwordcheck:true,},
			
			new_password: {required: true,passwordcheck:true,},
			
			confirm_password : {
				required: true,
				equalTo : "#new_password"
			}
		},
			
		messages: {
	
			old_password: {required: "Please enter current password",},
			new_password:{required:"Please enter new password",},
	
		
		confirm_password:{required:"Please enter confirm password", equalTo:"Password and confirm password must be same"},
		},
			submitHandler: function(form) {
			   var formData= new FormData(jQuery('#business_user_change_psd')[0]);
            host_url = "/development/wemarkthespot/";
			jQuery.ajax({
					url: host_url+"business_user_change_psd",
					type: "POST",
					cache: false,
					data: formData,
					processData: false,
					contentType: false,
					
					success:function(data) { 
					
					var obj = JSON.parse(data);
					
					if(obj.status==true){
						jQuery('#name_error').html('');
						jQuery('#email_error').html('');
						jQuery('.result').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong style='color:red'>Change Password Successfully.</strong> </div>");
    $('#ModalOfferDetails').modal('toggle');
					    
                   $("#msgs").text("Password updated Successfully");

               	setTimeout(function(){
							jQuery('.result').html('');
          $('#ModalOfferDetails').modal('toggle');

							window.location = host_url+"websignout";
						}, 3000);
					}
					else{
						if(obj.status==false){
							jQuery('.result').html(obj.message);
							jQuery('#name_error').css("display", "block");
						}
						
					}
					}
				});
			}
		});
      </script>
<?php echo $__env->make("inc/footer", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>;
<?php /**PATH C:\xampp\htdocs\development\wemarkthespot\resources\views/wemarkthespot/ac-change-password.blade.php ENDPATH**/ ?>