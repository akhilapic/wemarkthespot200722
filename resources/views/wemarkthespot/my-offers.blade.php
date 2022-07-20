<?php

 $base_url =  URL::to('/');
?>

@include("inc/header");

<style>
   .select2-container {
      width: 100% !important;
   }
   label#category_id-error {
    position: absolute;
    left: 12px;
    bottom: 0px;
}
p.categorystyle{
   height: 100px;
    overflow: auto;
}
</style>
<meta name="csrf-token" content="{{ csrf_token() }}" />

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
 <main class="Myoffer">
         <div class="container-fluid">
            <h1 class="title">My Offers</h1>
            <label class="error" id="deleteoffermsg" style="font-size: 1rem;font-weight: 600;"></label>
            
            <div class="row gy-4">
               @if($offers)  
                  @foreach($offers as $offer) 
                  <div class="col-lg-4">
                  <div class="BoxShade offerBox">
                    <h6>Business Category</h6>
                    <p class="categorystyle">{{$offer->category_name}}</p>
                     <div class="offerType d-sm-flex justify-content-between my-4">
                        <div>
                           <p><strong>Offer</strong></p>
                           <p>{{$offer->offer_name}}</p>
                           <p><strong>Date of activation</strong></p>
                           <p>{{date('j \\ F Y', strtotime($offer->activation))}}</p>
                        </div>
                        <div>
                           <p><strong>Offer Type</strong></p>
                             
                              <p>{{$offer->offer_typeselected}}</p>
                              
                           <p><strong>Date of deactivation</strong></p>
                           <p>{{date('j \\ F Y', strtotime($offer->deactivation))}}</p>
                        </div>
                     </div>
                     <h6>Offer Message</h6>
                     <p>{{$offer->offer_message}}</p>
                     
                     <div class="text-center mt-4">
                        <a href="javascript:void(0)" class="editoffer" 
                     
                        onclick="editoffer1('{{$offer->id}}','{{$offer->user_id}}','{{$offer->name}}','{{$offer->category_id}}','{{$offer->offer_name}}','{{$offer->offer_type}}','{{$offer->activation}}','{{$offer->deactivation}}','{{$offer->offer_message}}')"
                        data-id="{{$offer->id}}" 
                        data-user_id="{{$offer->user_id}}"
                        data-name="{{$offer->name}}"
                        data-category_id="{{$offer->category_id}}"
                        data-offer_name="{{$offer->offer_name}}"
                        data-offer_type="{{$offer->offer_type}}"
                        data-activation="{{$offer->activation}}"
                        data-deactivation="{{$offer->deactivation}}"
                        data-offer_message="{{$offer->offer_message}}"
                        ><span class="icon-edit"></span></a>
                     <a href="javascript:void(0)" class="deleteoffer" 
                        data-id="{{$offer->id}}" ><span class="icon-delete"></span></a>
                     </div>
                  </div>
               </div>
               @endforeach
            </div>
            @endif
            <div class="text-center mt-5 mb-1"><button type="button" class="addmore btn btn-primary px-4">Add New Offer</button></div>
            
            <section class="addoffers">
               <h1 class="title">Add New Offer</h1>
               <label class="result error" style="font-size: 1rem;font-weight: 600;"></label>
               <div class="BoxShade my-4">
                  
                  <form id="offerform" action="javascript:void(0)" method="post" enctype="multipart/form-data"> 
                  <div class="row gy-5">
                        <div class="col-md-4 position-relative" >
                           <label class="form-label">Business Category</label>
                           <select multiple="multiple" class="form-select js-example-basic-multiple" aria-label="Default select example" style="padding-bottom: 0;padding-top: 0;" name="category_id[]" id="category_id" >
                              <option value="">Select Business Category</option>
                              @foreach($business_category as $b)
                              <option value="{{$b->id}}">{{$b->name}}</option>
                              @endforeach
                           </select>
                        </div>

                           <div class="col-md-4">
                              <label class="form-label">Name of Offer</label>
                              <input type="text" class="form-control" name="name" placeholder="Enter Name of Offer">
                           </div>
                           <div class="col-md-4">
                              <label class="form-label">Offer Type</label>
                              <select class="form-select" aria-label="Default select example" name="offer_type" id="addoffer_type" style="padding-bottom: 0;padding-top: 0;">
                                 <option value="0" selected>Select Offer Type</option>
                                    @foreach($offerTypes as $offer)
                                 <option value="{{$offer->id}}">{{$offer->name}}</option>
                                 @endforeach
                              </select>
                           </div>
                           <div class="col-md-6 datepiker">
                              <label class="form-label">Date of Activation</label>
                              <input type="date" class="form-control activationdate" value="<?php echo date("Y-m-d");?>" name="activation">
                             
                              <span class="open-button icon-calendar"></span>
                           </div>
                           <div class="col-md-6 datepiker">
                              <label class="form-label">Date of Deactivation</label>
                              <input type="date" class="form-control activationdate" value="<?php echo date("Y-m-d");?>" name="deactivation">
                              <span class="open-button icon-calendar"></span>
                           </div>
                           <div class="col-md-12">
                              <label class="form-label">Offer Message</label>
                              <textarea class="form-control" placeholder="Type Offer Message" name="offer_message"></textarea>
                           </div>
                  </div>
                  <!--addmore-->
                  <div class="text-center mt-5 mb-4"><input type="submit" class=" btn btn-primary px-4" value="Add Offer"/></div>
               </form>
               </div>
            </section>
            
            <section class="" id="editoffersection">
               <h1 class="title">Edit Offer</h1>
               <label class="result error" style="font-size: 1rem;font-weight: 600;"></label>
               <div class="BoxShade my-4">
                  
                  <form id="editofferform" action="javascript:void(0)" method="post" enctype="multipart/form-data"> 
                     <input type="hidden" name="id" id="id"/>
                  <div class="row gy-5">
                        <div class="col-md-4">
                           <label class="form-label">Business Category</label>
                           <select multiple="multiple" class="form-select js-example-basic-multiple" aria-label="Default select example" style="padding-bottom: 0;padding-top: 0;" name="category_id[]" id="editcategory_id">
                              <option value="">Select Business Category</option>
                           </select>
                        </div>

                           <div class="col-md-4">
                              <label class="form-label">Name of Offer</label>
                              <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name of Offer">
                           </div>
                           <div class="col-md-4">
                              <label class="form-label">Offer Type</label>
                              <select class="form-select" aria-label="Default select example" id="editoffertypes" name="offer_type" id="editoffer_type" style="padding-bottom: 0;padding-top: 0;">
                              </select>
                           </div>
                           <div class="col-md-6 datepiker">
                              <label class="form-label">Date of Activation</label>
                              <input type="date" class="form-control activationdate" value="<?php echo date("Y-m-d");?>" name="activation" id="activation">
                             
                              <span class="open-button icon-calendar"></span>
                           </div>
                           <div class="col-md-6 datepiker">
                              <label class="form-label">Date of Deactivation</label>
                              <input type="date" class="form-control activationdate" value="<?php echo date("Y-m-d");?>" name="deactivation" id="deactivation"> 
                              <span class="open-button icon-calendar"></span>
                           </div>
                           <div class="col-md-12">
                              <label class="form-label">Offer Message</label>
                              <textarea class="form-control" placeholder="Type Offer Message" name="offer_message" id="offer_message"></textarea>
                           </div>
                  </div>
                  <!--addmore-->
                  <div class="text-center mt-5 mb-4"><input type="submit" class=" btn btn-primary px-4" value="Edit Offer"/></div>
               </form>
               </div>
            </section>
         </div>
      </main>

      <div class="modal" tabindex="-1" id="ModalOfferDetails">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Offer Details</h5>
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

      @include("inc/footer");
     
     
      <style>
   label.error {
    display: inline-block;
    width: 100%;
    clear: both;
    margin-top: 8px;
    color: #db0707;
}
</style>
      <script src="{{asset('assets/js/jquery.min.js')}} "></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>

<script type="text/javascript">

   function editoffer1(id,user_id,name,category_id,offer_name,offer_type,activation,deactivation,offer_message){
     // alert(id+user_id+name+category_id+offer_name+offer_type+activation+deactivation);


      $(".addoffers").hide();
         $("#editoffersection").show();
         // id= $(".editoffer").data('id');
         // user_id= $(".editoffer").data('user_id');
         // category_id= $(".editoffer").data('category_id');
         // offer_name= $(".editoffer").data('offer_name');
         // offer_type= $(".editoffer").data('offer_type');
         // activation= $(".editoffer").data('activation');
         // deactivation= $(".editoffer").data('deactivation');
         // offer_message = $(".editoffer").data('offer_message');
        
         $("#name").val(offer_name);
         $("#activation").val(activation);
         $("#deactivation").val(deactivation);
         $("#offer_message").val(offer_message);
         OfferType='';
         OfferType+='<option value="">Select Offer Type</option>';
         var offer = @json($offerTypes);
            

         token = $('meta[name="csrf-token"]').attr('content');
          var formData= new FormData();
          formData.append("_token",$('meta[name="csrf-token"]').attr('content'));
          formData.append("category_id",category_id);
          //console.log(formData);
          jQuery.ajax({
             url: "getoffertypebycategory_id",
             type: "post",
             cache: false,
             data: formData,
             processData: false,
             contentType: false,
             
             success:function(data) { 
                let obj  = JSON.parse(data); 
                if(obj.status==true){
                 result = obj.data;
                 alloffer =obj.alloffer;
            //     console.log("alloffer"+alloffer);
                 offertype="";
                 if(alloffer.length>0)
                 {
                    
                    for(o=0;o<alloffer.length;o++)
                    {
                        if(offer_type== alloffer[o].id)
                        {
                           offertype+='<option selected value='+alloffer[o].id+'>'+alloffer[o].name+'</option>';
                        }
                        else
                        {
                           offertype+='<option value='+alloffer[o].id+'>'+alloffer[o].name+'</option>';
                         }
                    }
                    $("#editoffertypes").html(offertype);
                 }

                }
                
                else if(offer.length>0)
                  {

                     for(i=0;i<offer.length;i++)
                     {
                        if(offer_type== offer[i].id)
                        {
                           OfferType+='<option selected value='+offer[i].id+'>'+offer[i].name+'</option>';
                        }
                        else{
                           OfferType+='<option value='+offer[i].id+'>'+offer[i].name+'</option>';
                  
                        }
                     }
                     $("#editoffertypes").html(OfferType);
                  }
                  
             },
          });


         var business_category = @json($business_category);
     //    console.log("business_category"+business_category);
         if(business_category.length>0)
         {

            arraycategoryId = category_id.split(",");
          //  console.log(arraycategoryId);
            business_categoryhtml='';
            for(i=0;i<business_category.length;i++)
            {
               if(inArray(business_category[i].id,arraycategoryId))
              // if(category_id== business_category[i].id)
               {
                  business_categoryhtml+='<option selected value='+business_category[i].id+'>'+business_category[i].name+'</option>';
          
               }
               else{
                  business_categoryhtml+='<option value='+business_category[i].id+'>'+business_category[i].name+'</option>';
          
               }
           //    console.log(business_category[i].id);
         
            }
            $("#editcategory_id").html(business_categoryhtml);
         }
        

         $("#id").val(id);

         $("#editoffersection").show();

   }
   $(function(){
    var dtToday = new Date();
    
    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();
    
    var minDate= year + '-' + month + '-' + day;
    
    $('.activationdate').attr('min', minDate);
});

   $(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});

    $(document).ready(function(e) {
      $(".addmore").on("click",function(){
         $("#editoffersection").hide();
      });

      $("#category_id").on("change",function(){
       
         category_id = $(this).val();
         token = $('meta[name="csrf-token"]').attr('content');
            var formData= new FormData();
            formData.append("_token",$('meta[name="csrf-token"]').attr('content'));
            formData.append("category_id",category_id);
            //console.log(formData);
            jQuery.ajax({
               url: "getoffertypebycategory_id",
               type: "post",
               cache: false,
               data: formData,
               processData: false,
               contentType: false,
               
               success:function(data) { 
                  let obj  = JSON.parse(data); 
                 
                  if(obj.status==true){
                  //jQuery('#addoffer_type').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong style='color:red'>"+obj.message+".</strong> </div>");
                   result = obj.data;
                   console.log(result);
                   offertype="";
                   for(i=0;i<result.length;i++)
                   {
                     offertype+="<option value="+result[i].id+">"+result[i].name+"</option>";
                   }
                   $("#addoffer_type").html(offertype);
                  }
                  else if(obj.status==false){
                   result = obj.data;
                   console.log(result);
                   offertype="<option value='0'>Select Offer Type</option>";
                   for(i=0;i<result.length;i++)
                   {
                     offertype+="<option value="+result[i].id+">"+result[i].name+"</option>";
                   }
                   $("#addoffer_type").html(offertype);
                  }
               },
            });

      });

      $("#editcategory_id").on("change",function(){
       
       category_id = $(this).val();
       token = $('meta[name="csrf-token"]').attr('content');
          var formData= new FormData();
          formData.append("_token",$('meta[name="csrf-token"]').attr('content'));
          formData.append("category_id",category_id);
          //console.log(formData);
          jQuery.ajax({
             url: "getoffertypebycategory_id",
             type: "post",
             cache: false,
             data: formData,
             processData: false,
             contentType: false,
             
             success:function(data) { 
                let obj  = JSON.parse(data); 
                if(obj.status==true){
                 result = obj.data;
                 offertype="";
                 for(i=0;i<result.length;i++)
                 {
                   offertype+="<option value="+result[i].id+">"+result[i].name+"</option>";
                 }
                 $("#editoffer_type").html(offertype);
                }
                else if(obj.status==false){
                 result = obj.data;
                 offertype="<option selected value='0'>Select Offer Type</option>";
                 for(i=0;i<result.length;i++)
                 {
                   offertype+="<option value="+result[i].id+">"+result[i].name+"</option>";
                 }
                 $("#editoffer_type").html(offertype);
                }
             },
          });

    });
      $("#editoffersection").hide();

      $(".editoffer1").on("click",function(){
         $(".addoffers").hide();
         $("#editoffersection").show();
         id= $(".editoffer").data('id');
         user_id= $(".editoffer").data('user_id');
         category_id= $(".editoffer").data('category_id');
         offer_name= $(".editoffer").data('offer_name');
         offer_type= $(".editoffer").data('offer_type');
         activation= $(".editoffer").data('activation');
         deactivation= $(".editoffer").data('deactivation');
         offer_message = $(".editoffer").data('offer_message');
        
         $("#name").val(offer_name);
         $("#activation").val(activation);
         $("#deactivation").val(deactivation);
         $("#offer_message").val(offer_message);
         OfferType='';
         OfferType+='<option value="">Select Offer Type</option>';
         var offer = @json($offerTypes);
            

         token = $('meta[name="csrf-token"]').attr('content');
          var formData= new FormData();
          formData.append("_token",$('meta[name="csrf-token"]').attr('content'));
          formData.append("category_id",category_id);
          //console.log(formData);
          jQuery.ajax({
             url: "getoffertypebycategory_id",
             type: "post",
             cache: false,
             data: formData,
             processData: false,
             contentType: false,
             
             success:function(data) { 
                let obj  = JSON.parse(data); 
                if(obj.status==true){
                 result = obj.data;
                 alloffer =obj.alloffer;
            //     console.log("alloffer"+alloffer);
                 offertype="";
                 if(alloffer.length>0)
                 {
                    
                    for(o=0;o<alloffer.length;o++)
                    {
                        if(offer_type== alloffer[o].id)
                        {
                           offertype+='<option selected value='+alloffer[o].id+'>'+alloffer[o].name+'</option>';
                        }
                        else
                        {
                           offertype+='<option value='+alloffer[o].id+'>'+alloffer[o].name+'</option>';
                         }
                    }
                    $("#editoffertypes").html(offertype);
                 }

                }
                
                else if(offer.length>0)
                  {

                     for(i=0;i<offer.length;i++)
                     {
                        if(offer_type== offer[i].id)
                        {
                           OfferType+='<option selected value='+offer[i].id+'>'+offer[i].name+'</option>';
                        }
                        else{
                           OfferType+='<option value='+offer[i].id+'>'+offer[i].name+'</option>';
                  
                        }
                     }
                     $("#editoffertypes").html(OfferType);
                  }
                  
             },
          });


         var business_category = @json($business_category);
     //    console.log("business_category"+business_category);
         if(business_category.length>0)
         {

            arraycategoryId = category_id.split(",");
          //  console.log(arraycategoryId);
            business_categoryhtml='';
            for(i=0;i<business_category.length;i++)
            {
               if(inArray(business_category[i].id,arraycategoryId))
              // if(category_id== business_category[i].id)
               {
                  business_categoryhtml+='<option selected value='+business_category[i].id+'>'+business_category[i].name+'</option>';
          
               }
               else{
                  business_categoryhtml+='<option value='+business_category[i].id+'>'+business_category[i].name+'</option>';
          
               }
           //    console.log(business_category[i].id);
         
            }
            $("#editcategory_id").html(business_categoryhtml);
         }
        

         $("#id").val(id);

         $("#editoffersection").show();
      });

      //-------------delete
      $(".deleteoffer").on("click",function(){
         id= $(this).data('id');
         check = confirm("Are you sure delete this offer");
         if(check)
         {
            token = $('meta[name="csrf-token"]').attr('content');
            var formData= new FormData();
            formData.append("_token",$('meta[name="csrf-token"]').attr('content'));
            formData.append("id",id);
            //console.log(formData);
            jQuery.ajax({
               url: "deleteofferData",
               type: "post",
               cache: false,
               data: formData,
               processData: false,
               contentType: false,
               
               success:function(data) { 
                  let obj  = JSON.parse(data); 
                  if(obj.status==true){
                  jQuery('#deleteoffermsg').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong style='color:green'>"+obj.message+".</strong> </div>");
                     setTimeout(function(){
                           window.location.href = "{{url('my-offers')}}";
                        }, 5000);
                  }
                  else if(obj.status==false){
                     
                     $("#deleteoffermsg").show();
                     $("#deleteoffermsg").text(obj.message);
                  }
               },
            });
         }
         else{
           return false;
         }
      });
      //-------------------------
      $(".nav-item a").removeClass("active");
      $("#my-offers").addClass('active');
      
      $("#offerform").validate({
      rules: {
         category_id:{required:true,},
         name: {required: true,},
         offer_type: {required: true,},  
         activation: {required: true,},  
         deactivation: {required: true,},  
         offer_message: {required: true,},  
         },
      
      messages: {
         category_id:{required:"Please select business category",},
         name: {required: "Please enter offer name",},
         offer_type:{required:"Please select offer type",},
         activation: {required: "Please select activation date"},
         deactivation: {required: "Please select deactivation date"}, 
         offer_message:{required:"Please enter offer message",}  
      },
         submitHandler: function(form) {
            var formData= new FormData(jQuery('#offerform')[0]);
            formData.append("_token",$('meta[name="csrf-token"]').attr('content'));
           // u ="development/";
           
         jQuery.ajax({
               url: "offerData",
               type: "post",
               cache: false,
               data: formData,
               processData: false,
               contentType: false,
               
               success:function(data) { 
                  let obj  = JSON.parse(data); 
                  if(obj.status==true){
                     last_offer_id = obj.last_offer_id;
                     user_id = obj.user_id;
                     offerName = obj.offerName;
                     offeractivation = obj.offeractivation;
                     offerdeactivation = obj.offerdeactivation;
                     offer_msg = obj.offer_msg;
                   //  send_notification_newOffer(last_offer_id);
                     $(".result").show();
                    jQuery('.result').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong style='color:green'>"+obj.message+".</strong> </div>");

                     $("#msgs").text(obj.offer_msg);
                     $("#modal_offer_name").text("Offer Name :"+offerName);
                     $("#modal_offer_startDate").text("Offer Activation : "+offeractivation);
                     $("#modal_offer_endDate").text("Offer Deactivation"+offerdeactivation);
                       $('#ModalOfferDetails').modal('toggle');

                     setTimeout(function(){

                        $(".result").hide();
                        $('#ModalOfferDetails').modal('toggle');
                           window.location.href = "{{url('my-offers')}}";
                        }, 5000);
                  }
                  else if(obj.status==false){
                     
                     $(".result").show();
                  $(".result").text(obj.message);
                  }
               },
            });
         }
      });


   function send_notification_newOffer(last_offer_id)
   {
         

          jQuery.ajax({
         url: "{{url('send_notification_newOffer')}}/"+last_offer_id,
         type: "get",
         cache: false,
        
         processData: false,
         contentType: false,
         dataType:"JSON",
         success:function(data) { 
         if(data.status==true){
               
            localStorage.removeItem("last_register_id");
           
         }
    
         }
      });

   }
      //----------------------------edit

      $("#editofferform").validate({
      rules: {
         category_id:{required:true,},
         name: {required: true,},
         offer_type: {required: true,},  
         activation: {required: true,},  
         deactivation: {required: true,},  
         offer_message: {required: true,},  
         },
      
      messages: {
         category_id:{required:"Please select business category",},
         name: {required: "Please enter offer name",},
         offer_type:{required:"Please select offer type",},
         activation: {required: "Please select activation date"},
         deactivation: {required: "Please select deactivation date"}, 
         offer_message:{required:"Please enter offer message",}  
      },
         submitHandler: function(form) {
            var formData= new FormData(jQuery('#editofferform')[0]);
            formData.append("_token",$('meta[name="csrf-token"]').attr('content'));
           // u ="development/";
           
         jQuery.ajax({
               url: "editofferData",
               type: "post",
               cache: false,
               data: formData,
               processData: false,
               contentType: false,
               
               success:function(data) { 
                  let obj  = JSON.parse(data); 
                  if(obj.status==true){
                                       last_offer_id = obj.last_offer_id;
                     user_id = obj.user_id;
                     offerName = obj.offerName;
                     offeractivation = obj.offeractivation;
                     offerdeactivation = obj.offerdeactivation;
                     offer_msg = obj.offer_msg;
                     $(".result").show();
                    jQuery('.result').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong style='color:green'>"+obj.message+".</strong> </div>");

                     $("#msgs").text(obj.offer_msg);
                     $("#modal_offer_name").text("Offer Name :"+offerName);
                     $("#modal_offer_startDate").text("Offer Activation : "+offeractivation);
                     $("#modal_offer_endDate").text("Offer Deactivation"+offerdeactivation);
                       $('#ModalOfferDetails').modal('toggle');


                     setTimeout(function(){
                       $('#ModalOfferDetails').modal('toggle');

                        $(".result").hide();
                           window.location.href = "{{url('my-offers')}}";
                        }, 5000);
                  }
                  else if(obj.status==false){
                     
                     $(".result").show();
                  $(".result").text(obj.message);
                  }
               },
            });
         }
      });
   });

   function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}
 </script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
