<?php

 $base_url =  URL::to('/');
?>
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
                        <p class="rating"><?php if($account->ratting): ?> <?php echo e($account->ratting); ?> <?php else: ?> 0.0 <?php endif; ?>  <span class="icon-star"></span></p>
                  
                        <p class="verify">Verified</p>
                     </div>
                     <div class="BoxShade">
                        <ul>
                           <li><a href="<?php echo e(url('my_account')); ?>">My Profile</a></li>
                           <li class="active"><a href="<?php echo e(url('my-subscription')); ?>">My Subscription</a></li>
                           <li ><a href="<?php echo e(url('notifications')); ?>">Notifications</a></li>
                           <li ><a href="<?php echo e(url('ac-change-password')); ?>">Change Password</a></li>
                           <li ><a href="<?php echo e(url('faqs')); ?>">FAQs</a></li>
                           <li ><a href="<?php echo e(url('contact-us')); ?>">Contact Us</a></li>
                           <li><a href="<?php echo e(url('/websignout')); ?>">Sign Out</a></li>
                        </ul>
                     </div>
                  </aside>
               </div>
               <div class="col-md-8">
                  <h4 class="acTitle">My Subscription</h4>
                  
                  
                  <?php
                    if(!empty($payment_data)){
                        foreach($payment_data as $data){
                  ?>
                        <div class="BoxShade offerBox my-4">
                            <div class="offerType d-sm-flex justify-content-between">
                                <div class="w-100 mb-5 mb-md-0">
                                <p><strong>Name of Plan</strong></p>
                                <p><?php 
                                    // echo $data['plan_name']; 
                                    if($data['plan_name'] == 'featuredBusiness'){
                                        echo "Featured Business";
                                    }else if($data['plan_name'] == 'weekAndFeatured'){
                                        echo "Business of the Week & Featured Business";
                                    }else if($data['plan_name'] == 'weekBusiness'){
                                        echo "Business of the Week";
                                    }
                                
                                ?></p>
                                <p class="gap">&nbsp;</p>
                                <p><strong>Date of Activation</strong></p>
                                <p><?php echo date("d M Y", strtotime($data['startDate'])); ?></p>
                                </div>
                                <div class="w-100 mb-4 mb-md-0 ps-md-5">
                                <p><strong>Mode of Payment</strong></p>
                                <p>Card</p>
                                <p class="gap">&nbsp;</p>
                                <p><strong>Date of Expiration</strong></p>
                                <p><?php echo date("d M Y", strtotime($data['endDate'])); ?></p>
                                </div>
                            </div>
                        </div>
                  <?php
                        }
                    }
                  ?>
                  
                  
                  <!--<div class="BoxShade offerBox my-4">-->
                  <!--   <div class="offerType d-sm-flex justify-content-between">-->
                  <!--      <div class="w-100 mb-5 mb-md-0">-->
                  <!--         <p><strong>Name of Plan</strong></p>-->
                  <!--         <p>Business of the week</p>-->
                  <!--         <p class="gap">&nbsp;</p>-->
                  <!--         <p><strong>Date of Activation</strong></p>-->
                  <!--         <p>2 Jun 2021</p>-->
                  <!--      </div>-->
                  <!--      <div class="w-100 mb-4 mb-md-0 ps-md-5">-->
                  <!--         <p><strong>Mode of Payment</strong></p>-->
                  <!--         <p>Card</p>-->
                  <!--         <p class="gap">&nbsp;</p>-->
                  <!--         <p><strong>Date of Expiration</strong></p>-->
                  <!--         <p>3 Jul 2021</p>-->
                  <!--      </div>-->
                  <!--   </div>-->
                  <!--</div>-->
                  
               </div>
            </div>
         </div>
      </main>
<?php echo $__env->make("inc/footer", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>;
<?php /**PATH C:\xampp\htdocs\development\wemarkthespot\resources\views/wemarkthespot/my-subscription.blade.php ENDPATH**/ ?>