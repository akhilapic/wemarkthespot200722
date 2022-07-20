<?php
 $base_url =  URL::to('/');
?>

<?php echo $__env->make("inc/header", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>;
<!-- <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" /> -->

<!-- <script type="text/javascript">
 
</script> -->


<style>
   .btn-close-icon{
       position: absolute;
       background: #c4c4c4;
       border: 1px solid #c4c4c4;
       width: 32px;
       height: 32px;
       border-radius: 50%;
       color: #fff;
       font-size: 25px;
       line-height: 0px;
       right: 14px;
       top: 10px;
   }
</style>

   <main class="subscription">
         <div class="container-fluid">
         <h1 class="title">Subscription Plans</h1>
  
    <?php echo e(Session::has('subscriptionsmsgerror')); ?>  
<?php if(Session::has('subscriptionsmsg')): ?>
<div class="alert alert-success" id="subscriptionsmsg">
  <?php echo e(Session::get('subscriptionsmsg')); ?>

</div>
<?php endif; ?>
<?php if(Session::has('subscriptionsmsgerror')): ?>
<div class="alert alert-success" id="subscriptionsmsgerror">
  <?php echo e(Session::get('subscriptionsmsgerror')); ?>

</div>

<?php endif; ?>


         <div class="row gy-4">
            <div class="col-md-6 col-lg-4 d-flex align-items-stretch">
               <div class="BoxShade package text-center w-100">
                  <h6>Business of the Week</h6>
                  <p class="price" id="WeekBussAmt"><?php echo e($subscriptions->weekBusiness); ?> <span>Per Week</span></p>
                  <ul>
                     <li><span class="icon-checkmark"></span>Lorem ipsum dolor sit amet.</li>
                     <li><span class="icon-checkmark"></span>Lorem ipsum.</li>
                     <li><span class="icon-checkmark"></span>Sed diam voluptua.</li>
                     <li><span class="icon-close"></span>Consetetur sadipscing elitr.</li>
                     <li><span class="icon-close"></span>Lorem ipsum dolor.</li>
                     <li><span class="icon-close"></span>Diam voluptua sed.</li>
                     <li><span class="icon-close"></span>Consetetur sadipscing elitr.</li>
                  </ul>
                  <button type="button" class="btn btn-primary mt-5 px-4" id="selectOneWeekOnly" data-bs-backdrop="static" data-bs-keyboard="false">Buy Now</button>
               </div>
            </div>
            <div class="col-md-6 col-lg-4 d-flex align-items-stretch">
               <div class="BoxShade package text-center w-100">
                  <h6>Featured Business</h6>
                  <p class="price" id="threeWeekBussAmt"><?php echo e($subscriptions->featuredBusiness); ?> <span>Per Week</span></p>
                  <ul>
                     <li><span class="icon-checkmark"></span>Lorem ipsum dolor sit amet.</li>
                     <li><span class="icon-checkmark"></span>Lorem ipsum.</li>
                     <li><span class="icon-checkmark"></span>Sed diam voluptua.</li>
                     <li><span class="icon-close"></span>Consetetur sadipscing elitr.</li>
                     <li><span class="icon-close"></span>Lorem ipsum dolor.</li>
                     <li><span class="icon-close"></span>Diam voluptua sed.</li>
                     <li><span class="icon-close"></span>Consetetur sadipscing elitr.</li>
                  </ul>
                  <button type="button" class="btn btn-primary mt-5 px-4" id="selectThreeWeekOnly" >Buy Now</button>
               </div>
            </div>
            <div class="col-md-6 col-lg-4 d-flex align-items-stretch">
               <div class="BoxShade package text-center activePack w-100">
                  <!-- <span class="active icon-checkmark"></span> -->
                  <h6>Business of the Week & Featured Business</h6>
                  <p class="price" id="allWeekBussAmt"><?php echo e($subscriptions->weekAndFeatured); ?> <span>Per Week</span></p>
                  <ul>
                     <li><span class="icon-checkmark"></span>Lorem ipsum dolor sit amet.</li>
                     <li><span class="icon-checkmark"></span>Lorem ipsum.</li>
                     <li><span class="icon-checkmark"></span>Sed diam voluptua.</li>
                     <li><span class="icon-close"></span>Consetetur sadipscing elitr.</li>
                     <li><span class="icon-close"></span>Lorem ipsum dolor.</li>
                     <li><span class="icon-close"></span>Diam voluptua sed.</li>
                     <li><span class="icon-close"></span>Consetetur sadipscing elitr.</li>
                  </ul>
                  <button type="button" class="btn btn-primary mt-5 px-4" id="selectAllWeekOnly">Buy Now</button>
               </div>
            </div>
         </div>
         <section class="currentPlan">
            <div class="row">
               <div class="col-md-6">
                  <h1 class="title">My Current Plan</h1>
                  <div class="BoxShade MycurrentPlan">
                    <table class="table table-borderless">
                        <?php 
                           if(!empty($current)){
         
                              if($current['plan_name'] == 'featuredBusiness'){
                                 $plan_name = 'Featured Business';
                              }else if($current['plan_name'] == 'weekBusiness'){
                                 $plan_name = 'Week Business';
                              }else if($current['plan_name'] == 'weekAndFeatured'){
                                 $plan_name = 'Business of the Week & Featured Business';
                              }
                        ?>
                        <tbody>
                           <tr>
                              <th scope="row">Name Of plan</th>
                              <td><?php echo e($plan_name); ?></td>
                           </tr>
                           <tr>
                              <th scope="row">Date of Activation</th>
                              <td><?php echo e(date('d M Y', strtotime($current['startDate']))); ?></td>
                           </tr>
                           <tr>
                              <th scope="row">Date of Expiration</th>
                              <td><?php echo e(date('d M Y', strtotime($current['endDate']))); ?></td>
                           </tr>
                           <tr>
                              <th scope="row">Mode of Payment</th>
                              <td>
                                 <div class="d-lg-flex cardDetal">
                                    <div>
                                       <p class="mb-0">Card</p>
                                    </div>
                                 </div>
                              </td>
                           </tr>
                        </tbody>
                        <?php
                           }else{
                              echo "NO ACTIVE PLANS!";
                           }
                        ?>
                     </table>
                  </div>
               </div>
               <div class="col-md-6">
                  <h1 class="title">Payment History</h1>
                  <div class="table-responsive BoxShade PaymentHistory">
                     <table class="table table-borderless">
                        <thead>
                           <tr>
                              <th>Order Id</th>
                              <th>Date</th>
                              <th>Amount</th>
                              <th>Status</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                              if(isset($payments)){
                                 foreach($payments as $kry=>$pay){
                                    echo "<tr>
                                    <td>".$pay['order_id']."</td>
                                    <td>". date('d M Y', strtotime($pay['created_at'])) ."</td>
                                    <td>";
                                       if(!empty($pay['amount_captured'])){
                                          echo '$'. $pay['amount_captured']/100;
                                       }
                                    echo "</td>
                                    <td>".$pay['payment_status']."</td>
                                    </tr>";
                                 }
                              }else{
                                 echo "No Payment History Found!";
                              }
                           ?>
                        </tbody>
                     </table>
                  </div>
               </div>
         </section>
         </div>
      </main>
      <!-- Modal -->
      <div class="modal modelStyle hide fade" id="calendarView" data-keyboard="false" data-backdrop="static">
         <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
               <!-- <div class="modal-header">
               
               
               </div> -->
               <div class="modal-body text-center position-relative">
                  <button type="button" class="close btn-close-icon" data-dismiss="modal">&times;</button>
                  <h4>Select Week</h4>
                  <p id="selectShowError" style="color:red;font-weight:600;"></p>
                  <div class="week-picker cts_calendar"></div>
                  <!-- <label>Week :</label> <span id="startDate"></span> - <span id="endDate"></span> -->
                  <div id="showSubmitButtonSelect"></div>  <!--to show submit button-->

               </div>
            </div>
         </div>
      </div>

       <div class="modal modelStyle fade" id="calendarView2">
         <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
               <!-- <div class="modal-header">
               
               
               </div> -->
               <div class="modal-body text-center position-relative">
                  <button type="button" class="close btn-close-icon" data-dismiss="modal">&times;</button>
                  <h4>Select Week</h4>
                  <p id="selectShowError" style="color:red;font-weight:600;"></p>
                  <div class="week-picker1 cts_calendar"></div>
                  <!-- <label>Week :</label> <span id="startDate"></span> - <span id="endDate"></span> -->
                  <div id="showSubmitButtonSelect2"></div>  <!--to show submit button-->

               </div>
            </div>
         </div>
      </div>

       <div class="modal modelStyle fade" id="calendarView3">
         <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
               <div class="modal-body text-center">

                  <h4>Select Week</h4>
                  <p id="selectShowError" style="color:red;font-weight:600;"></p>
                  <div class="week-picker2 cts_calendar"></div>
                  <!-- <label>Week :</label> <span id="startDate"></span> - <span id="endDate"></span> -->
                  <div id="showSubmitButtonSelect"></div>  <!--to show submit button-->

               </div>
            </div>
         </div>
      </div>

<?php echo $__env->make("inc/footer", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>;

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.min.js"></script>    
<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/themes/base/jquery-ui.css">

<style>
   .cts_calendar .ui-datepicker {
    width: 100%;
    border: none;
   }
   .cts_calendar .ui-datepicker a.ui-state-default {
      background: #fff;
      text-align: center;
      height: 45px;
      border: none;
      line-height: 38px;
   }
   .ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active {
      background: #1dc2c2 !important;
      color: #fff;
      border-radius: 7px;
   }
   .ui-datepicker-header.ui-widget-header.ui-helper-clearfix.ui-corner-all {
      background: transparent;
      border: none;
   }
   .ui-state-hover, .ui-widget-content .ui-state-hover, .ui-widget-header .ui-state-hover, .ui-state-focus, .ui-widget-content .ui-state-focus, .ui-widget-header .ui-state-focus {
      background: transparent;
      border: none;
   }
   .ui-datepicker .ui-datepicker-prev, .ui-datepicker .ui-datepicker-next {
      cursor: pointer;
   }
   .ui-datepicker td {
      border: 0;
      padding: 0px;
   }

</style>


<script type="text/javascript">
   $('.close').click(function(){
      location.reload();
   })
   //to check comes from which plan
   $("#selectOneWeekOnly").click(function(){
        $("#calendarView").modal({
        show: false,
        backdrop: 'static'
    });
        $('#calendarView').modal('show');

      $("#showSubmitButtonSelect").html('<button class="btn btn-primary my-4" id="subBuyButtonOneWeek"> Buy Now</button>');
   }); 
   
   $("#selectThreeWeekOnly").click(function(){
        $("#calendarView2").modal({
        show: false,
        backdrop: 'static'
    });
        $('#calendarView2').modal('show');
      $("#showSubmitButtonSelect2").html('<button class="btn btn-primary my-4" id="subBuyButtonThreeWeek2"> Buy Now</button>');
   });

   $("#selectAllWeekOnly").click(function(){
        $("#calendarView3").modal({
        show: false,
        backdrop: 'static'
    });
        $('#calendarView3').modal('show');
      $("#showSubmitButtonSelect").html('<button class="btn btn-primary my-4" id="subBuyButtonAllWeek"> Buy Now</button>');
   });


$(document).ready(function(e){ 

   setTimeout(function () {
                     $('#subscriptionsmsg').hide();
                     $('#subscriptionsmsgerror').hide();
                     
                 
                     
                 }, 10000);


   //----Calender start ------------------------------//
   $(function() {   //for calender//
      var startDate;
      var endDate;
      
      var selectCurrentWeek = function() {
         window.setTimeout(function () {
               $('.week-picker').find('.ui-datepicker-current-day a').addClass('ui-state-active')
                $('.week-picker1').find('.ui-datepicker-current-day a').addClass('ui-state-active')
         }, 1);
      }
      var ranges = [];
      <?php
       $mydate = date('Y-m-d');
      $mydate1 = date('m/d/Y');
   $today= date('l', strtotime($mydate)); 
   if($today!='Monday')
   {
         $nextTuesday = strtotime('next sunday');
         $weekNo = date('W');
         $weekNoNextTuesday = date('m/d/Y', $nextTuesday); 

          $startDate=explode("/",$mydate1);
            $endDate=explode("/",$weekNoNextTuesday); 
         ?>
         ranges.push({ start: new Date(<?php echo $startDate[2] ?>,<?php echo $startDate[0]-1 ?>,<?php echo $startDate[1] ?>), end: new Date(<?php echo $endDate[2] ?>, <?php echo $endDate[0]-1 ?>, <?php echo $endDate[1] ?>) });
         <?php
   }
      //print_r($payments);
         foreach ($payments as $key => $value) {
            $startDate=explode("/",$value->startDate);
            $endDate=explode("/",$value->endDate);
            ?>
            //var myArray = text.split("/");
            ranges.push({ start: new Date(<?php echo $startDate[2] ?>,<?php echo $startDate[0]-1 ?>,<?php echo $startDate[1] ?>), end: new Date(<?php echo $endDate[2] ?>, <?php echo $endDate[0]-1 ?>, <?php echo $endDate[1] ?>) });
            <?php       
         }
      ?>
      console.log(ranges);


        var ranges2 = [];

      <?php
      $mydate = date('Y-m-d');
      $mydate1 = date('m/d/Y');
   $today= date('l', strtotime($mydate)); 
   if($today!='Monday')
   {
         $nextTuesday = strtotime('next sunday');
         $weekNo = date('W');
         $weekNoNextTuesday = date('m/d/Y', $nextTuesday); 

          $startDate=explode("/",$mydate1);
            $endDate=explode("/",$weekNoNextTuesday); 
         ?>
         ranges2.push({ start: new Date(<?php echo $startDate[2] ?>,<?php echo $startDate[0]-1 ?>,<?php echo $startDate[1] ?>), end: new Date(<?php echo $endDate[2] ?>, <?php echo $endDate[0]-1 ?>, <?php echo $endDate[1] ?>) });
         <?php
   }
      //print_r($payments);
         foreach ($getWeekData as $key => $value) {
            $startDate=explode("/",$value->startDate);
            $endDate=explode("/",$value->endDate);
            if($value->totakSub >=3 || $value->user_id==$userId){


            ?>
            //var myArray = text.split("/");
            ranges2.push({ start: new Date(<?php echo $startDate[2] ?>,<?php echo $startDate[0]-1 ?>,<?php echo $startDate[1] ?>), end: new Date(<?php echo $endDate[2] ?>, <?php echo $endDate[0]-1 ?>, <?php echo $endDate[1] ?>) });
            <?php       
         }
      }
      ?>
      console.log(ranges2);
     $('.week-picker1').datepicker({
         firstDay: 1,  //First day starts from monday //RR
         showOtherMonths: true,
         selectOtherMonths: true,
         minDate: 0,  //Disabled previous dates //RR
         onSelect: function(dateText, inst) { 
            var date = $(this).datepicker('getDate');
            // console.log(date);
            // startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 1);
            // endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 7);
            
            startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + (date.getDay() ? 1 : -7 ));
            endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + (date.getDay() ? 7 : 0 ));

            
            var dateFormat = inst.settings.dateFormat || $.datepicker._defaults.dateFormat;
            $('#startDate').text($.datepicker.formatDate( dateFormat, startDate, inst.settings ));
            $('#endDate').text($.datepicker.formatDate( dateFormat, endDate, inst.settings ));
            // selectCurrentWeek();

            var start = $.datepicker.formatDate(dateFormat, startDate, inst.settings); //12/18/2021
            var end   = $.datepicker.formatDate(dateFormat, endDate, inst.settings);  //mm/dd/yyy
            selectCurrentWeek();

            var d = new Date();  //Current Date
            // var currDate = (d.getMonth()+1) + "/" + d.getDate() + "/" + d.getFullYear();  //current date
            // alert(currDate); //test

            $("#subBuyButtonOneWeek").click(function(){  //For One Week

               if(start == "" || end == ""){
                  $("#selectShowError").text("Please select a week.");
                  return false;
               }else if(d > startDate){  //Compare with current date
                  $("#selectShowError").text("You can not select previous weeks.");
                  return false;
               }else{
                  $("#selectShowError").text(" ");
                  var stDate = start;
                  var edDate = end;
                  var amtWeek1 = $("#WeekBussAmt").html();
                  var amountWeek1 = amtWeek1.slice(0,-22);

                  $.ajax({
                     url: "oneweek",
                     type: "POST",
                     cache: false,
                     // data: {startDate:stDate, endDate:edDate, amount:amountWeek1},
                     data: {startDate:stDate, endDate:edDate, amount:amountWeek1, _token:'<?php echo e(csrf_token()); ?>'},
                     
                     success:function(data) {
                        let obj  = JSON.parse(data); 
                       
                        console.log(obj.status+ obj.message);

                        if(obj.status==true){
                           if(obj.message == "Success"){
                              location.href='webpayment';
                           }
                        }else if(obj.status==false){
                           $("#selectShowError").text(obj.message);
                           setTimeout(function(){
                              window.location.reload(1);
                           }, 4000);
                        }
                     }
                  });
               }
            });   
            
            $("#subBuyButtonThreeWeek2").click(function(){  //For Three Week

               if(start == "" || end == ""){
                  $("#selectShowError").text("Please select a week.");
                  return false;
               }else if(d > startDate){  //Compare with current date
                  $("#selectShowError").text("You can not select previous weeks.");
                  return false;
               }else{
                  $("#selectShowError").text(" ");
                  var stDatea = start;
                  var edDatea = end;
                  var amtWeek2 = $("#threeWeekBussAmt").html();
                  var amountWeek2 = amtWeek2.slice(0,-22);

                  $.ajax({
                     // url: "<?php echo e(route('threeweek')); ?>",
                     url: "threeweek",
                     type: "POST",
                     cache: false,
                     data: {startDate:stDatea, endDate:edDatea, amount:amountWeek2, _token:'<?php echo e(csrf_token()); ?>'},
                     // processData: false,
                     // contentType: false,
                     
                     success:function(data) {

                        let obj  = JSON.parse(data); 
                        console.log(obj.status+ obj.message);
                        
                        if(obj.status==true){
                           if(obj.message == "Success"){
                           location.href='webpayment';
                           }
                        }else if(obj.status==false){
                           $("#selectShowError").text(obj.message);
                           setTimeout(function(){
                              window.location.reload(1);
                           }, 4000);
                        }
                     }
                  });
               }
            });  

            $("#subBuyButtonAllWeek").click(function(){  //For All Week

               if(start == "" || end == ""){ 
                  $("#selectShowError").text("Please select a week.");
                  return false;
               }else if(d > startDate){  //Compare with current date
                  $("#selectShowError").text("You can not select previous weeks.");
                  return false;
               }else{
                  $("#selectShowError").text(" ");
                  var stDateb = start;
                  var edDateb = end;
                  var amtWeek3 = $("#allWeekBussAmt").html();
                  var amountWeek3 = amtWeek3.slice(0,-22);
 
                  $.ajax({
                     url: "allweek",
                     // url: "<?php echo e(route('allweek')); ?>",
                     type: "POST",
                     cache: false,
                     data: {startDate:stDateb, endDate:edDateb, amount:amountWeek3, _token:'<?php echo e(csrf_token()); ?>'},
                     // processData: false,
                     // contentType: false,
                     
                     success:function(data) {

                        let obj  = JSON.parse(data); 
                        console.log(obj.status+ obj.message);
                        
                        if(obj.status==true){
                           if(obj.message == "Success"){
                              location.href='webpayment';
                           }
                        }else if(obj.status==false){
                           $("#selectShowError").text(obj.message);
                           setTimeout(function(){
                              window.location.reload(1);
                           }, 4000);
                        }
                     }
                  });
               }
            });  
  
         },
         beforeShowDay: function(date) {
            var cssClass = '';
            for(var i=0; i<ranges2.length; i++) {
          if(date >= ranges2[i].start && date <= ranges2[i].end) return [false, ''];
        }
        //return [true, ''];

            if(date >= startDate && date <= endDate)
               cssClass = 'ui-datepicker-current-day';
            return [true, cssClass];
         },
         onChangeMonthYear: function(year, month, inst) {
            selectCurrentWeek();
         }
      });

      $('.week-picker').datepicker({
         firstDay: 1,  //First day starts from monday //RR
         showOtherMonths: true,
         selectOtherMonths: true,
         minDate: 0,  //Disabled previous dates //RR
         onSelect: function(dateText, inst) { 
            var date = $(this).datepicker('getDate');
            // console.log(date);
            // startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 1);
            // endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 7);
            
            startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + (date.getDay() ? 1 : -7 ));
            endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + (date.getDay() ? 7 : 0 ));

            
            var dateFormat = inst.settings.dateFormat || $.datepicker._defaults.dateFormat;
            $('#startDate').text($.datepicker.formatDate( dateFormat, startDate, inst.settings ));
            $('#endDate').text($.datepicker.formatDate( dateFormat, endDate, inst.settings ));
            // selectCurrentWeek();

            var start = $.datepicker.formatDate(dateFormat, startDate, inst.settings); //12/18/2021
            var end   = $.datepicker.formatDate(dateFormat, endDate, inst.settings);  //mm/dd/yyy
            selectCurrentWeek();

            var d = new Date();  //Current Date
            // var currDate = (d.getMonth()+1) + "/" + d.getDate() + "/" + d.getFullYear();  //current date
            // alert(currDate); //test

            $("#subBuyButtonOneWeek").click(function(){  //For One Week

               if(start == "" || end == ""){
                  $("#selectShowError").text("Please select a week.");
                  return false;
               }else if(1!=1){  //Compare with current date
                 // $("#selectShowError").text("You can not select previous weeks.");
                  //return false;
               }else{
                  $("#selectShowError").text(" ");
                  var stDate = start;
                  var edDate = end;
                  var amtWeek1 = $("#WeekBussAmt").html();
                  var amountWeek1 = amtWeek1.slice(0,-22);

                  $.ajax({
                     url: "oneweek",
                     type: "POST",
                     cache: false,
                     // data: {startDate:stDate, endDate:edDate, amount:amountWeek1},
                     data: {startDate:stDate, endDate:edDate, amount:amountWeek1, _token:'<?php echo e(csrf_token()); ?>'},
                     
                     success:function(data) {
                        let obj  = JSON.parse(data); 
                       
                        console.log(obj.status+ obj.message);

                        if(obj.status==true){
                           if(obj.message == "Success"){
                              location.href='webpayment';
                           }
                        }else if(obj.status==false){
                           $("#selectShowError").text(obj.message);
                           setTimeout(function(){
                              window.location.reload(1);
                           }, 4000);
                        }
                     }
                  });
               }
            });   
            
            $("#subBuyButtonThreeWeek").click(function(){  //For Three Week

               if(start == "" || end == ""){
                  $("#selectShowError").text("Please select a week.");
                  return false;
               }else if(d > startDate){  //Compare with current date
                  $("#selectShowError").text("You can not select previous weeks.");
                  return false;
               }else{
                  $("#selectShowError").text(" ");
                  var stDatea = start;
                  var edDatea = end;
                  var amtWeek2 = $("#threeWeekBussAmt").html();
                  var amountWeek2 = amtWeek2.slice(0,-22);

                  $.ajax({
                     // url: "<?php echo e(route('threeweek')); ?>",
                     url: "threeweek",
                     type: "POST",
                     cache: false,
                     data: {startDate:stDatea, endDate:edDatea, amount:amountWeek2, _token:'<?php echo e(csrf_token()); ?>'},
                     // processData: false,
                     // contentType: false,
                     
                     success:function(data) {

                        let obj  = JSON.parse(data); 
                        console.log(obj.status+ obj.message);
                        
                        if(obj.status==true){
                           if(obj.message == "Success"){
                              //location.href='webpayment';
                           }
                        }else if(obj.status==false){
                           $("#selectShowError").text(obj.message);
                           /*setTimeout(function(){
                              window.location.reload(1);
                           }, 4000);*/
                        }
                     }
                  });
               }
            });  

            $("#subBuyButtonAllWeek").click(function(){  //For All Week

               if(start == "" || end == ""){ 
                  $("#selectShowError").text("Please select a week.");
                  return false;
               }else if(d > startDate){  //Compare with current date
                  $("#selectShowError").text("You can not select previous weeks.");
                  return false;
               }else{
                  $("#selectShowError").text(" ");
                  var stDateb = start;
                  var edDateb = end;
                  var amtWeek3 = $("#allWeekBussAmt").html();
                  var amountWeek3 = amtWeek3.slice(0,-22);
 
                  $.ajax({
                     url: "allweek",
                     // url: "<?php echo e(route('allweek')); ?>",
                     type: "POST",
                     cache: false,
                     data: {startDate:stDateb, endDate:edDateb, amount:amountWeek3, _token:'<?php echo e(csrf_token()); ?>'},
                     // processData: false,
                     // contentType: false,
                     
                     success:function(data) {

                        let obj  = JSON.parse(data); 
                        console.log(obj.status+ obj.message);
                        
                        if(obj.status==true){
                           if(obj.message == "Success"){
                              location.href='webpayment';
                           }
                        }else if(obj.status==false){
                           $("#selectShowError").text(obj.message);
                           setTimeout(function(){
                              window.location.reload(1);
                           }, 4000);
                        }
                     }
                  });
               }
            });  
  
         },
         beforeShowDay: function(date) {
            var cssClass = '';
            for(var i=0; i<ranges.length; i++) {
          if(date >= ranges[i].start && date <= ranges[i].end) return [false, ''];
        }
        //return [true, ''];

            if(date >= startDate && date <= endDate)
               cssClass = 'ui-datepicker-current-day';
            return [true, cssClass];
         },
         onChangeMonthYear: function(year, month, inst) {
            selectCurrentWeek();
         }
      });
      
      $('.week-picker .ui-datepicker-calendar tr').live('mousemove', function() { $(this).find('td a').addClass('ui-state-hover'); });
      $('.week-picker .ui-datepicker-calendar tr').live('mouseleave', function() { $(this).find('td a').removeClass('ui-state-hover'); });
   });
   //----Calender end ------------------------------//


   $(".nav-item a").removeClass("active");
   $("#subscriptions").addClass('active');

});

</script>
<script src="<?php echo e(asset('assets/js/calendar.min.js')); ?> "></script>
<script src="<?php echo e(asset('assets/js/chart.min.js')); ?>"></script>
<?php /**PATH C:\xampp\htdocs\development\wemarkthespot\resources\views/wemarkthespot/subscriptions.blade.php ENDPATH**/ ?>