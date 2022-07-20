<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
     
       <link rel="shortcut icon" href="<?php echo e(asset('assets/images/favicon.png')); ?>" type="image/x-icon">
      <link rel="icon" href="<?php echo e(asset('assets/images/favicon.png')); ?>" type="image/x-icon">
     

      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title> Spot </title>
      <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
      <link href="<?php echo e(asset('assets/css/style.css')); ?>" rel="stylesheet" type="text/css">
      <link href="<?php echo e(asset('assets/css/calendar.min.css')); ?>" rel="stylesheet" type="text/css">
   </head>

   <body>
  
      <!-- header -->
      <header>
         <!-- navigation -->
         <nav class="navbar loginNav navbar-expand-xl navbar-light">
         <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo e(url('/')); ?>"><img src="<?php echo e(asset('assets/images/logo.svg')); ?>"></a>
            <a href="<?php echo e(url('/signin')); ?>" class="btn btn-primary d-xl-none ms-auto me-2 me-lg-5">Sign In / Sign Up</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
               <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                  <li class="nav-item">
                     <a class="nav-link" id="my-offers" aria-current="page" href="<?php echo e(url('my-offers')); ?>">My Offers</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" id="subscriptions" href="<?php echo e(url('subscriptions')); ?>">Subscriptions</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" id="hotspot-updates" href="<?php echo e(url('hotspot-updates')); ?>">Hotspot Updates</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" id="report-management" href="<?php echo e(url('report-management')); ?>">Report Management</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" id="community-reviews" href="<?php echo e('community-reviews'); ?>">Community Reviews</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" id="contact-us" href="<?php echo e(url('contact-us')); ?>">Contact Us</a>
                  </li>

               </ul>
                <?php if(Session::has('id')): ?>
                <div class="nav-right d-none d-xl-block">
                     <a href="#" class="me-4"><span class="icon-notifications d-flex align-items-center justify-content-center"></span></a>
                     <a href="<?php echo e(route('my_account')); ?>" class="Nav-profile">

                        <img src="<?php echo e(Session::get('image')); ?>">

                     </a>
                  </div>
               <?php else: ?>

               <div class="nav-right d-none d-xl-block">
                  <a href="<?php echo e(url('signin')); ?>" id="login" class="btn btn-primary px-4">Sign In / Sign Up</a>
               </div>
            <?php endif; ?>
            </div>
         </div>
         </nav>
      </header><?php /**PATH C:\xampp\htdocs\development\wemarkthespot\resources\views/inc/header.blade.php ENDPATH**/ ?>