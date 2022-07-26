<?php
 $base_url =  URL::to('/');
?>

<?php echo $__env->make("inc/header", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>;
<link rel="stylesheet" href="<?php echo e(asset('assets/build/css/intlTelInput.css')); ?>">

<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"> -->
    <!-- Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />


<!-- <link rel="stylesheet" href="<?php echo e(asset('assets/build/css/demo.css')); ?>"> -->
  <style>
   .form-check.form-check-inline .form-check-input {
      padding: 0px;
      width: 18px;
      height: 18px;
   }
   .form-check.form-check-inline .form-check-input:checked::before {
      top: 4px;
   }
   .iti.iti--allow-dropdown {
      width: 100%;
   }
label.error {
    display: inline-block;
    width: 100%;
    clear: both;
    margin-top: 8px;
    color: #db0707;
}


     </style>
   <main class="my-accont">
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
                     <p class="rating">
                     <?php if($account->ratting): ?>  
                     <?php echo e($account->ratting); ?>

                     <?php else: ?>
                     0.0
                     <?php endif; ?>
                     <span class="icon-star"></span></p>

                     </div>
                     <div class="BoxShade">
                        <ul>
                           <li class="active"><a href="<?php echo e(url('my_account')); ?>">My Profile</a></li>
                           <li><a href="<?php echo e(url('my-subscription')); ?>">My Subscription</a></li>
                           <li><a href="<?php echo e(url('notifications')); ?>">Notifications</a></li>
                           <li><a href="<?php echo e(url('ac-change-password')); ?>">Change Password</a></li>
                           <li><a href="<?php echo e(url('faqs')); ?>">FAQs</a></li>
                           <li><a href="<?php echo e(url('contact-us')); ?>">Contact Us</a></li>
                           <li><a href="<?php echo e(url('/websignout')); ?>">Sign Out</a></li>
                        </ul>
                     </div>
                  </aside>
               </div>
               <?php echo e(session('message')); ?>

               <div class="col-md-8">
                  <h4 class="acTitle">My Profile</h4>
 <!--                  <span class="result" style="color:red"></span> -->
                  <div class="alert alert-warning result"></div>

                 <form  action="javascript:void(0)" id="my_profile_edit" method="post" enctype="multipart/form-data">
                     <div class="thumb-up mb-5">
                        <div class="profile-box d-flex flex-wrap align-content-center">
                           <?php if($account->image): ?>
                           <img class="profile-pic" src="<?php echo e($account->image); ?>">   
                           <?php else: ?>
                        <img class="profile-pic" src="<?php echo e(asset('assets/images/user-thumb.png')); ?>">
                        <?php endif; ?>
                     </div>
                        <div class="p-image">
                           <button type="button" value="login" class="btn upload-button"><span class="icon-camera"></span></button>
                           <input class="file-upload" type="file" name="image" accept="image/*">
                        </div>
                     </div>
                     <div class="row gy-4">
                        <div class="col-md-6">
                           <label class="form-label">Business Owner Name</label>
                           <input type="text" class="form-control" name="name" value="<?php echo e($account->name); ?>" placeholder="Enter Business Owner Name">
                        </div>
                        <div class="col-md-6">
                           <label class="form-label">Business Name</label>
                           <input type="text" class="form-control" onkeypress="return /^[a-zA-Z \s]+$/i.test(event.key)" name="business_name"  value="<?php echo e($account->business_name); ?>" placeholder="Enter Business Name">
                        </div>
                        <div class="col-md-6">
                        <label for="phone-number" class="form-label ">Phone Number</label><br/>
						
						    <div class="input-group">
                       <select name="country_code" id="country_code" class="form-select" style="padding: 0px 15px;max-width: 200px;background-color: #f5f5f5;">
                             <?php $__currentLoopData = $country_codedata; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            
                              <?php if($account->country_code == $c->code): ?>
                              <option value="<?php echo e($c->code); ?>" Selected ><?php echo e($c->name); ?></option>
                                 <?php else: ?>
                              <option value="<?php echo e($c->code); ?>"><?php echo e($c->name); ?></option>
                              <?php endif; ?>
                             <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <!-- <option data-countryCode="DZ" value="213" <?php if($account->country_code = "213"): ?> Selected <?php endif; ?>>Algeria (+213)</option> -->
                             </select>
                          <!--  <input type="text" class="form-control" oninput="this.value=this.value.replace(/[^0-9]/g,'');" name="phone" id="phone" value="<?php echo e($account->country_code); ?><?php echo e($account->phone); ?>" maxlength="13" maxlength="10" placeholder="Enter Phone Number"> -->

                           <input type="text" class="form-control" oninput="this.value=this.value.replace(/[^0-9]/g,'');" name="phone" id="phone" value="<?php echo e($account->phone); ?>" maxlength="13" maxlength="10" placeholder="Enter Phone Number">
                        </div>
                        </div>
                        <div class="col-md-6">
                           <label class="form-label">Email</label>
                           <input type="text" readonly class="form-control" name="email" value="<?php echo e($account->email); ?>" placeholder="Enter Email">
                        </div>
                        
                        <div class="col-md-6">
                           <label class="form-label">Opening Hours</label>
                           <input type="time" name="opeing_hour"  value="<?php echo e($account->opeing_hour); ?>" class="form-control without_ampm" value="">
                        </div>
                        <div class="col-md-6">
                           <label class="form-label">Closing Hours</label>
                           <input type="time" name="closing_hour" value="<?php echo e($account->closing_hour); ?>"  class="form-control without_ampm" value="">
                        </div>
                        <div class="col-md-12 iconinput">
                           <label for="location" class="form-label">Location</label>
                           <input type="text" class="form-control" style="height: auto;padding-right: 60px;overflow: hidden;
    -o-text-overflow: ellipsis;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;" id="location" name="location" value="<?php echo e($account->location); ?>" placeholder="Enter Location">
                           <span class="icon-gps"></span>
                        </div>
                         <input type="hidden" id="latitude" name="lat" value="<?php echo e($account->lat); ?>" class="form-control">
                                <input type="hidden" name="long" value="<?php echo e($account->long); ?>" id="longitude" class="form-control">
                        <div class="col-md-6">
                           <div><label class="form-label">Select Business Type</label></div>
                           <div class="form-check form-check-inline">
                           <input class="form-check-input" type="radio" name="business_type" id="inlineRadio1" value="1" <?php if($account->business_type == '1'): ?> checked <?php endif; ?>>
                              <label class="form-check-label" for="inlineRadioOptions">Online Only</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="business_type" id="inlineRadio2" value="2"<?php if($account->business_type == '2'): ?> checked <?php endif; ?>>
                              <label class="form-check-label" for="inlineRadioOptions">Physical Location</label>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <input type="hidden" value="<?php echo e($account->business_category); ?>," id="selcheckboxcategory"/>
                           <div><label class="form-label">Select Business Category <span style="color:Red">*</span>      <span id="select_buiness_categoryerror" style="color:red">Please select business category </span></label></div>
                              <?php $__currentLoopData = $categorylist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <?php if(in_array($list->id,$selectbusiness_categoryArray)): ?>
                              <div class="form-check form-check-inline">
                                 <input checked class="form-check-input select_buiness_category" date-id= "<?php echo e($list->id); ?>" type="checkbox" name="business_category[]"  value="<?php echo e($list->id); ?>"id="<?php echo e($list->id); ?>">
                                 <label class="form-check-label" for="<?php echo e($list->id); ?>"><?php echo e($list->name); ?></label>
                              </div>
                              <?php else: ?>
                                 <div class="form-check form-check-inline">
                                 <input class="form-check-input select_buiness_category" id="<?php echo e($list->id); ?>" date-id= "<?php echo e($list->id); ?>" type="checkbox" name="business_category[]"  value="<?php echo e($list->id); ?>">
                                 <label class="form-check-label" for="<?php echo e($list->id); ?>"><?php echo e($list->name); ?></label>
                              </div>
                              <?php endif; ?>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="col-md-6">
                           <div><label class="form-label">Certificate of formation or Business Registration document </label></div>
                          <?php if('public/images/<?php echo e($account->upload_doc); ?>'): ?>
                          <a href= "<?php echo e($account->upload_doc); ?>" target="_blank" class="btn btn-primary" >View</a>
                       <!--  <img src="<?php echo e($account->upload_doc); ?>" style="width: 100px;height: 50px;"> -->
                        <?php endif; ?>
                        </div>
                            
                        <div class="col-md-6">
                            <input type="checkbox" class="form-check-input" name="hablamos_espanol" id="exampleCheck1" <?php
                                if(isset($account->hablamos_espanol) && $account->hablamos_espanol == 1){
                                    echo 'checked';
                                }
                            ?>>
                            <label class="form-check-label" for="exampleCheck1">Hablamos Espanol</label>
                        <!-- </div>
                        <div class="col-md-6"> -->
                            <input type="checkbox" class="form-check-input" name="religious_spiritual" id="exampleCheck2" <?php
                                if(isset($account->religious_spiritual) && $account->religious_spiritual == 1){
                                    echo 'checked';
                                }
                            ?>>
                            <label class="form-check-label" for="exampleCheck2">Religious/Spiritual</label>
                        </div>

                        <input type="hidden" id="hiddenbusiness_category" value="<?php echo e($account->business_category); ?>"/>
                        <input type="hidden" id="hiddenbusiness_sub_category" value="<?php echo e($account->business_sub_category); ?>"/>
                        
                        <div class="col-md-6">
                           <div><label class="form-label">Select Sub Category <span style="color:Red">*</span></label></div>
                           <select id="sub_category" multiple="multiple" class="form-select js-example-basic-multiple" name="business_sub_category[]" style="padding: 0px 15px;">
                           
                                 <option value="">Select Sub Category</option>
                                 
                           </select>
                        </div>
                        
                        <div class="col-md-6">
                           <div><label class="form-label">Business Images</label></div>
                          <input class="form-control" type="file" onchange="loadFile(event)"  name="business_images" name="image" id="img" value="">
                      <input type="hidden" name="old_business_images" value='<?php echo e($account->business_images); ?>'/>


                        <img id="output"/>
                        <?php if($account->business_images): ?>
                        <img src="<?php echo e($account->business_images); ?>" id="business_images_view"/>
                        <?php endif; ?>
                        </div>

                       <!--  <div class="col-md-12">
                           <label for="location" class="form-label">Reason for not uploading commercial license</label>
                           <input type="text" class="form-control" placeholder="Type Reason">
                        </div> -->
                        <div class="col-md-12">
                           <label for="location" class="form-label">Short Description</label>
                           <textarea class="form-control" name="description" placeholder="Type Short Description"><?php echo e($account->description); ?></textarea>
                        </div>
                     </div>

                     <button type="submit" class="btn btn-primary mt-5 mb-2 px-4 pb-2 btn_submit_tranning">Update Profile</button>
                  </form>
                 
               </div>
            </div>
         </div>
      </main>


      <!-- Modal -->
      <div class="modal fade modelStyle show" id="staticBackdrop">
         <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
               <div class="modal-body text-center">
                  <p>Your business will not be verified unless you include a 
                     commercial license
                  </p>
                  <button type="button" class="btn btn-primary mt-4" data-bs-dismiss="modal" aria-label="Close">Ok</button>
               </div>
            </div>
         </div>
      </div>
      <div class="modal" tabindex="-1" role="dialog" id="updateProfile">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Message</h5>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
      </div>
      <div class="modal-body">
        <p id="message"></p>
      </div>
      <div class="modal-footer d-none">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php echo $__env->make("inc/footer", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script src="<?php echo e(asset('assets/build/js/intlTelInput.js')); ?>"></script>
<style>
#output,#business_images_view {
     width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 50px;
    margin-top: 15px;
}
</style>
<script>
   $("#output").hide();
  var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      $("#output").show();
      $("#business_images_view").hide();
      URL.revokeObjectURL(output.src) // free memory
    }
  };
</script>
<!--start Location-->
 
        <script>
$(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});
        function activatePlacesSearch() {
            var input = document.getElementById('location');
            var autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
                $('#latitude').val(place.geometry['location'].lat());
                $('#longitude').val(place.geometry['location'].lng());

                
            });
        }
    </script>
     <script 
        src="https://maps.google.com/maps/api/js?key=AIzaSyDXendzNuPChFkDejwv7jbFtqunqRawrk0&libraries=geometry,places&callback=activatePlacesSearch"></script>

  <!-- end Location-->
<script type="text/javascript">


   $(document).ready(function() {
      $(".result").hide();
      
      $("#select_buiness_categoryerror").hide();
      // country_code =$(".iti__selected-flag").attr("title");
      //    const myArr = country_code.split(": ");
      //    c_code ='+1';//myArr[1];
      //    $("#country_code").val(c_code);
         // console.log($("#country_code").val());
    
    $(".btn_submit_tranning").on("click",function(){
        // country_code =$(".iti__selected-flag").attr("title");
        //  const myArr = country_code.split(": ");
        //  c_code ='+91';//myArr[1];
        //  $("#country_code").val(c_code);
        //   console.log($("#country_code").val());
      
       var  category_id = $("input[type='checkbox'].select_buiness_category:checked").val();
     
       if (typeof category_id === "undefined") {
               $("#select_buiness_categoryerror").show();
         return false;
         }
      });
   });
    $(function(){
//   var    category_id = $("input[type='checkbox'].select_buiness_category:checked").val();

var hiddenbusiness_category = $("#hiddenbusiness_category").val();

//selcheckboxcategory
   var  hiddenbusiness_sub_category = $("#hiddenbusiness_sub_category").val();
   //alert(hiddenbusiness_sub_category);
      if(hiddenbusiness_category!='')
      {
         host_url = "/development/wemarkthespot/";
		   var token = $("meta[name='csrf-token']").attr("content");
      //alert(host_url);
      $.ajax({
         type: 'POST',
			dataType: "json",
			url: host_url+'sub_category_by_category_id',
			data: {'_token':  token,'category_id': hiddenbusiness_category},
			success: function(response){
            
            if(response.status==true)
            {
               selectsubCategoryArr  =hiddenbusiness_sub_category.split(",");

               console.log(selectsubCategoryArr);
               $("#sub_category").empty();
               $.each(response.data,function(index,value){
                  op="";
                  if(inArray(value.id,selectsubCategoryArr))
                 // if(selectsubCategoryArr[index]==value.id)
                  {
                  
                     op+="<option Selected value="+value.id+">"+value.name+"</option>";
                  }
                   else{
                     op+="<option value="+value.id+">"+value.name+"</option>";
                   }
                  
                  $("#sub_category").append(op);
               });
            }
			  setTimeout(function(){
				  jQuery('.result').html('');
				 // window.location = "/user_list";
			  }, 1000);
			}
		});
      }

        country_code =$(".iti__selected-flag").attr("title");
         const myArr = country_code.split(": ");
         c_code =myArr[1];
         $("#country_code").val(c_code);
         // console.log($("#country_code").val());
    
    $(".btn_submit_tranning").on("click",function(){
        country_code =$(".iti__selected-flag").attr("title");
         const myArr = country_code.split(": ");
         c_code =myArr[1];
         $("#country_code").val(c_code);
          console.log($("#country_code").val());
    
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
<!--   <script>
    var input = document.querySelector("#phone");
    window.intlTelInput(input, {
      // allowDropdown: false,
      // autoHideDialCode: false,
      // autoPlaceholder: "off",
      // dropdownContainer: document.body,
      // excludeCountries: ["us"],
      // formatOnDisplay: false,
      // geoIpLookup: function(callback) {
      //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
      //     var countryCode = (resp && resp.country) ? resp.country : "";
      //     callback(countryCode);
      //   });
      // },
      // hiddenInput: "full_number",
      // initialCountry: "auto",
      // localizedCountries: { 'de': 'Deutschland' },
      // nationalMode: false,
      // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
      // placeholderNumberType: "MOBILE",
      // preferredCountries: ['cn', 'jp'],
      // separateDialCode: true,
      utilsScript: "<?php echo e(asset('assets/build/js/utils.js')); ?>",
    });
  </script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script>

$(".select_buiness_category").on("change",function(){
   selcheckboxcategory = $("#selcheckboxcategory").val();
      if($(this).is(':checked'))
      {
         selcheckboxcategory =selcheckboxcategory+this.value+",";
         $("#selcheckboxcategory").val(selcheckboxcategory);
      }
      else
      {
         selcheckboxcategory = $("#selcheckboxcategory").val();
         $("#selcheckboxcategory").val((selcheckboxcategory.replace(this.value,"")));
      }
      subcategoryget($("#selcheckboxcategory").val());
   });

   const subcategoryget = (category_id)=>{
    //  console.log("category_id"+category_id);

      host_url = "/development/wemarkthespot/";
		var token = $("meta[name='csrf-token']").attr("content");
       $("#select_buiness_categoryerror").hide();
      $.ajax({
         type: 'POST',
			dataType: "json",
			url: host_url+'sub_category_by_category_id',
			data: {'_token':  token,'category_id': category_id},
			success: function(response){
//console.log(response.data);
         //   var obj=JSON.parse(data);
            
            if(response.status==true)
            {
               $("#sub_category").empty();
                 op="";
                  op1 = "<option value=''>Select Sub Category</option>";
                     $("#sub_category").append(op1);
               $.each(response.data,function(index,value){
                
                  op="<option value="+value.id+">"+value.name+"</option>";
                  $("#sub_category").append(op);
               });
            }
			  setTimeout(function(){
				  jQuery('.result').html('');
				 // window.location = "/user_list";
			  }, 1000);
			}
		});

   }

   $(".select_buiness_category1").on("change",function(){
      var category_id = $("input[type='checkbox'].select_buiness_category:checked").val();
      alert(category_id);
      host_url = "/development/wemarkthespot/";
		var token = $("meta[name='csrf-token']").attr("content");
       $("#select_buiness_categoryerror").hide();
      $.ajax({
         type: 'POST',
			dataType: "json",
			url: host_url+'sub_category_by_category_id',
			data: {'_token':  token,'category_id': category_id},
			success: function(response){
//console.log(response.data);
         //   var obj=JSON.parse(data);
            
            if(response.status==true)
            {
               $("#sub_category").empty();
                 op="";
                  op1 = "<option value=''>Select Sub Category</option>";
                     $("#sub_category").append(op1);
               $.each(response.data,function(index,value){
                
                  op="<option value="+value.id+">"+value.name+"</option>";
                  $("#sub_category").append(op);
               });
            }
			  setTimeout(function(){
				  jQuery('.result').html('');
				 // window.location = "/user_list";
			  }, 1000);
			}
		});



   
   });
     jQuery.validator.addMethod("emailExt", function(value, element, param) {
    return value.match(/^[a-zA-Z0-9_\.%\+\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,}$/);
},'Please enter valid email');

   $("#my_profile_edit").validate({
      
   rules: {
      name: {required: true,},
     email: {required: true,email: true,maxlength:50,emailExt: true,},  
   
    
      business_category:{required:true},
      phone:{ 
      
      minlength:10,
      maxlength:10
      }, 
      business_type:{
         required:true,
      },
      business_name:{
         required:true,
      },
      business_sub_category:{required:true},
    
      
      },
   
   messages: {
      name: {required: "Please enter name",},
      business_name:{required:"Please enter business name",},
      email: {required: "Please enter valid email",email: "Please enter valid email",},   
      phone: {required: "Please enter mobile number",},
      business_type:{required:"Please select business type",},
      business_category:{required:"Please business category",},
      business_sub_category:{required:"Please select business sub category",},
     // business_images:{required:"please select business image",},
   },
      submitHandler: function(form) {
         var formData= new FormData(jQuery('#my_profile_edit')[0]);
         formData.append("_token",$('meta[name="csrf-token"]').attr('content'));
        // u ="development/";
        
      jQuery.ajax({
            url: "my_profile_edit",
            type: "post",
            cache: false,
            data: formData,
            processData: false,
            contentType: false,
            
            success:function(data) { 
               let obj  = JSON.parse(data); 
            
             // redirecturl = location.href='otp-verifiction/'+obj.last_id;
             // $("#okbtn").attr("onclick",redirecturl);

            if(obj.status==true){
            
  //          $(".result").show();
              $("#message").text(obj.message); 
            $('#updateProfile').modal('toggle');

//              $(".result").text(obj.message);
               setTimeout(function(){
                   $(".result").hide();
           //   $(".result").html(obj.message);
             window.location.href = "<?php echo e(route('my_account')); ?>";
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

   $("#sub_category").select2({
          placeholder: "Select a programming language",
          allowClear: true
      });

</script>


<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
    <!-- Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script><?php /**PATH C:\xampp\htdocs\development\wemarkthespot\resources\views/wemarkthespot/my-account.blade.php ENDPATH**/ ?>