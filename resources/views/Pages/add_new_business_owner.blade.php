@extends('layouts.admin')
@section('content')

<style>
.input_icon {
    position: absolute;
    right: 12px;
    top:16px;
    margin-top: -5px;
    font-size: 12px;
    cursor: pointer;
}
.valid{
   color:'black;';
}
</style>
<link rel="stylesheet" href="{{asset('assets/build/css/intlTelInput.css')}}">
   <link rel="stylesheet" href="{{asset('assets/build/css/intlTelInput.css')}}">
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h4 class="text-themecolor mb-0">Add New Business owner account</h4>
		</div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <ol class="breadcrumb mb-0 p-0 bg-transparent fa-pull-right">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Add New Business owner account </li>
			</ol>
		</div>
	</div>

	<div class="container-fluid">
		
		<div class="col-12">
			<div class="card">
				<div class="border-bottom title-part-padding">
					<h4 class="card-title mb-0">Business owner account </h4>
				</div>
				<div class="card-body min_height">
					<form name="add_new_business_by_admin" id="add_new_business_by_admin" method="post" action="javascript:void(0)" enctype="multipart/form-data">
						@csrf
					    <div class="row">
							<div class="">
								<!-- Alert Append Box -->
							<div class="result"></div>
							</div>
							<div class="mb-3 col-md-6">
								<label for="Name" class="control-label" >Business Owner Name: <span style="color:red"> *</span> </label>
								<input type="text" id="name" class="form-control" required  onkeypress="return /^[a-zA-Z \s]+$/i.test(event.key)" name="name" value="{{old('name')}}"
                              placeholder="Enter Business Owner Name">

							</div>
							
							 <div class="col-md-6">
							 	<input type="hidden" name="status" value="2"/>
	                           <label class="form-label">Business Name <span style="color:red"> *</span></label>
	                           <input type="text" class="form-control" required onkeypress="return /^[a-zA-Z \s]+$/i.test(event.key)" name="business_name"  value="{{old('business_name')}}" placeholder="Enter Business Name">
                       		</div>
							  <div class="col-md-6">
                           <label class="form-label">Email <span style="color:red"> *</span></label>
                           <input type="email"  class="form-control" required name="email" value="{{old('email')}}" placeholder="Enter Email">
                        </div>
                        <div class="col-md-6">
                        <label for="phone-number" class="form-label ">Phone Number <span>(Optional)</span></label><br/>
						
						    <div class="input-group">
                       		<select name="country_code" id="country_code"  style="padding: 0px 15px;max-width: 200px;background-color: #f5f5f5;">
                             @foreach($country_codedata as $c)
                             	<option value="{{$c->code}}">{{$c->name}}</option>
                         	@endforeach
                             </select>
                           <input type="text" class="form-control" oninput="this.value=this.value.replace(/[^0-9]/g,'');" name="phone" id="phone" value="{{old('phone')}}" maxlength="13" maxlength="10" placeholder="Enter Phone Number">
                        </div>
                        </div>
                        <div class="col-md-6">
                        <label for="phone-number" class="form-label ">Location <span style="color:red"> *</span> </label><br/>
						
						    <div class="input-group">
						    	<input type="text"  required class="form-control" style="height: auto;padding-right: 60px;overflow: hidden;
								    -o-text-overflow: ellipsis;
								    text-overflow: ellipsis;
								    display: -webkit-box;
								    -webkit-line-clamp: 1;
								    -webkit-box-orient: vertical;" name="location" value="{{old('location')}}" id="location"
                              placeholder="Enter Location">
                           <span class="icon-gps"></span>

                        </div>
                         <input type="hidden" id="latitude" name="lat" class="form-control">
                        <input type="hidden" name="long" id="longitude" class="form-control">
                        </div>
                        <div class="col-md-6"> 
                           <label for="location" class="form-label">Password</label>
                  <div class="mb-3 iconinput" style="position:relative;">
                           <input type="password" id="password" class="form-control special_characters_type"
                              value="{{old('password')}}" name="password" id="password1" placeholder="Enter Password">
                           <span class="fa fa-eye-slash input_icon" id="eye1" style="cursor: pointer ;color: #9f9a9a;"
                              data-name="password"></span>
                           <div class="password_hints" id="password_hints">
                              <h4>Password must meet the following requirements:</h4>
                              <ul>
                                 <li id="letter" class="invalid letter">At least <strong>one special character</strong>
                                 </li>
                                 <li id="capital" class="invalid capital">At least <strong>one capital letter</strong>
                                 </li>
                                 <li id="small" class="invalid small">At least <strong>one small letter</strong></li>
                                 <li id="number" class="invalid number">At least <strong>one number</strong></li>
                                 <li id="length" class="invalid length">Be at least <strong>6 characters</strong></li>
                              </ul>
                           </div>
                        </div>
                    </div>
                        <div class=" col-md-6">
                           <label for="location" class="form-label">Confirm Password</label>
                           <div class="iconinput" style="position:relative;">
                           <input type="password" class="form-control " required name="cpassword" id="cpassword"
                              value="{{old('cpassword')}}" placeholder="Enter Confirm Password">
                           <span class="fa fa-eye-slash input_icon" id="eye2" style="color: #9f9a9a;"
                              data-name="password"></span>
                        </div>
                          <span class="error alert alert-dange" id="errorcpassword"></span>
                        </div>
                          <div class="col-md-6">
                           <div><label for="businesType" class="form-label">Select Business Type</label></div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" checked name="business_type"
                                 id="inlineRadio31" value="1">
                              <label class="form-check-label" for="inlineRadio3">Online Only</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="business_type" id="inlineRadio41"
                                 value="2">
                              <label class="form-check-label" for="inlineRadio4">Physical Location</label>
                           </div>
                        </div>
                        <div class="col-md-6">
								<label for="username" class="control-label">Business owner profile image :</label>
								<input type="file" id="iamge" name="image"  class="form-control" accept="image/*" onchange="loadFile(event)">
								 <input type="hidden" id="b_user_image" value="0" />
								                        <!--  <label id="user_imageerror" style="color:red" class="errors"></label> -->
							{{-- allready exit error --}}
							<label id="image_error" class="error"></label>
							<img id="output" width="150" height="120" style="display:none;" />
						</div>

						<div class="mb-3 col-md-6">
								<label for="username" class="control-label">Business Image : <span style="color:red">*</span> </label>
								<input type="file" id="business_images" name="business_images"  class="form-control" accept="image/*" onchange="loadFile1(event)">
							{{-- allready exit error --}}
							<label id="business_image_error" class="error"></label>
							<img id="output2" width="150" height="120" style="display:none;" />
						</div>

						 <div class="col-md-6">
	                           <label class="form-label">Certificate of formation or Business Registration document (Docs, Pdf)<span style="color:red">*</span></label>
	                            <input class="file_message ms-auto" id="filename" disabled />
                          		<input
                                 type="file" accept=".pdf,.doc" name="upload_doc" class="uploaddocs" id="upload_docs" />
                              <input type="hidden" id="b_updoc" value="0" />
                           <span id="uploaddocerror"></span>
                       		</div>

						
                       		   <label id="b_updocerror" style="color:red" class="errors">Please upload commercial license
                           (Docs, Pdf)</label>
                        <script>
                           document.getElementById('upload_docs').onchange = uploadOnChange;

                           function uploadOnChange() {
                              var filename = this.value;
                              var lastIndex = filename.lastIndexOf("\\");
                              if (lastIndex >= 0) {
                                 filename = filename.substring(lastIndex + 1);

                                 var ext = filename.split('.').pop();
                                 if (ext == "pdf" || ext == "docx" || ext == "doc") {
                                    $('.btn_submit_tranning').prop('disabled', false);
                                    $("#b_updoc").val(1);
                                    $("#b_updocerror").hide();
                                    document.getElementById('filename').value = filename;

                                 } else {
                                    $("#b_updocerror").show();
                                    $("#b_updocerror").removeAttr("style");
                                    $("#b_updocerror").css("color", "red");
                                    $("#b_updoc").val(0);
                                    document.getElementById('filename').value = filename;
                                    return false;
                                 }
                              }

                           }
                        </script>
						
						</div>
						<a type="button" href="{{ url('/manager_business') }}"class="btn btn-dark fa-pull-left mt-3">Back</a>
						<input type="submit" id="submit" value="Save" class="btn btn-success btn_submit btn_submit_tranning fa-pull-right mt-3">

					</form>
				</div>
			</div>
		</div>
		
	</div>
	<!--start Location-->

<script>
	   $(function () {
      $("#b_updocerror").css("display", "none");
      // $("#cpassword").on('keyup',function(){
      //    cpassword = parseInt($(this).val());
      //    password = parseInt($("#password").val());
      //    if(password!=cpassword)
      //    {
      //       $("#errorcpassword").text("Password Not Match");
      //       return false;
      //    }
      //    else
      //    {
      //        $("#errorcpassword").hide();
      //      // return false;
      //    }

      // });
      //  country_code =$(".iti__selected-flag").attr("title");
      // const myArr = country_code.split(": ");
      // c_code =myArr[1];
      //$("#country_code").val(c_code);
      // console.log($("#country_code").val());

      $(".btn_submit_tranning").on("click", function () {
         //   $("#b_user_image")
         var myFile = $('#b_user_image').val();
         if (myFile == "0") {
            $("#user_imageerror").text("Please select profile image");

         }
         else {
            $("#user_imageerror").text("");
         }
         b_updoc = $("#b_updoc").val();

         filename = $("#filename").val();

         var ext = filename.split('.').pop();
         if (ext == "pdf" || ext == "docx" || ext == "doc") {
            //   $('.btn_submit_tranning').prop('disabled', false);

         } else {
            $("#b_updocerror").removeAttr("style");
            $("#b_updocerror").css("color", "red");
            // $('.btn_submit_tranning').prop('disabled', true);
         }

         if (b_updoc == "0") {
            $("#b_updocerror").text("Please upload commercial license  (Docs, Pdf)").css("display", "block");

         }
         else {

            $("#b_updocerror").text("").css("display", "none");
         }
         name1 = $("#name1").val();
         $("#name1").val(name1.trim());
         //  country_code =$(".iti__selected-flag").attr("title");
         // const myArr = country_code.split(": ");
         // c_code =myArr[1];
         //$("#country_code").val(c_code);
         //console.log($("#country_code").val());

      });
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
<style>
   .password_hints {
      position: absolute;
      top: 60px;
      bottom: -115px\9;
      right: 55px;
      width: 220px;
      padding: 15px;
      background: #fefefe;
      font-size: .875em;
      border-radius: 5px;
      box-shadow: 0 1px 3px #ccc;
      border: 1px solid #ddd;
      z-index: 9;
   }

   .position-relative {
      position: relative;
   }

   .password_hints ul {
      list-style: none;
      margin: 0;
      padding: 0;
   }

   .password_hints ul li {
      margin: 0;
      padding: 0;
      list-style: none;
      font-size: 13px;
      line-height: 25px;
   }

   .password_hints::before {
      content: "\25B2";
      position: absolute;
      top: -12px;
      left: 45%;
      font-size: 14px;
      line-height: 14px;
      color: #ddd;
      text-shadow: none;
      display: block;
   }

   .invalid {
      background-image: url(../images/invalid.png) no-repeat 0 50%;
      color: #ec3f41;
   }

   .valid {
      background-image: url(../images/valid.png) no-repeat 0 50%;
      color: #3a7d34;
   }


   .password_hints h4 {
      font-size: 13px;
   }

   .password_hints {
      display: none;
   }
</style>
<style type="text/css">
   label.error {
      display: inline-block;
      width: 100%;
      clear: both;
      margin-top: 8px;
      color: #db0707;
   }
</style>
<script>
   $(function () {
      $("#eye1").on("click", function () {

         type = $("input[name='" + $(this).data("name") + "']").attr("type");
         if (type == 'password') {
            $("#eye1").removeClass("fa-eye-slash");
            $("#eye1").addClass("fa-eye");
            $("input[name='" + $(this).data("name") + "']").attr("type", 'text');
         }
         else {
            $("#eye1").addClass("fa-eye-slash");
            $("#eye1").removeClass("fa-eye");
            $("input[name='" + $(this).data("name") + "']").attr("type", 'password');
         }


      });
      $("#eye2").on("click", function () {


         //    type=$("input[id='"+$("#cpassword").data("name")+"']").attr("type");
         type = $("#cpassword").attr("type");
         //      alert(type);
         if (type == 'password') {
            $("#eye2").removeClass("fa-eye-slash");
            $("#eye2").addClass("fa-eye");
            // $("input[id='"+$("#cpassword").data("name")+"']").attr("type",'text');
            $("#cpassword").attr("type", 'text');
         }
         else {
            $("#eye2").addClass("fa-eye-slash");
            $("#eye2").removeClass("fa-eye");
            $("#cpassword").attr("type", 'password');
         }


      });

   });

</script>
<script>



   jQuery.validator.addMethod("emailExt", function (value, element, param) {
      return value.match(/^[a-zA-Z0-9_\.%\+\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,}$/);
   }, 'Please enter valid email');


   //       jQuery.validator.addMethod("passwordcheck", function(value, element, param) {
   //     return value.match(/^[?,=,.,*,!,#,$,%,&,?,@, ,"]+[a-zA-Z]+[0-9]$/);
   // },'check password');

   patten = "^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$";


   jQuery.validator.addMethod("passwordcheck", function (value, element, param) {
      return value.match(patten);
   }, 'Please enter valid password');

   $("#add_new_business_by_admin").validate({


      rules: {
         name: { required: true, },
         email: { required: true, email: true, maxlength: 50, emailExt: true, },
         business_name:{required:true,},
         password: {
            required: true,
            minlength: 6,
            passwordcheck: true,
         },
         // termsconditions:{required: true}, 
         cpassword: {
            required: true,
            minlength: 6,
            equalTo: "#password"
         },
         location: { required: true },
        
         business_type: {
            required: true,
         },
         country_code: { required: true, },
         image: { required: true },
         business_images: {required : true },
      },

      messages: {
      	business_name:{ required: "Please enter business name", },
         business_images:{required :"Please select business images",},
         name: { required: "Please enter business owner  name", },
         email: { required: "Please enter valid email", email: "Please enter valid email", },
        
         password: { required: "Please enter password", },

         business_type: { required: "Please Select Business Type" },
        
         location: { required: "Please enter location" },
         image: { required: "Please select profile image" },
         cpassword: { required: "Please enter confirm password", equalTo: "Password and confirm password must be same" },

      },
      submitHandler: function (form) {
         var formData = new FormData(jQuery('#add_new_business_by_admin')[0]);
         formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
         // u ="development/";

         jQuery.ajax({
            url: "add_new_business_by_admin",
            type: "post",
            cache: false,
            data: formData,
            processData: false,
            contentType: false,

            success: function (data) {

               let obj = JSON.parse(data);

               console.log(obj.status + obj.message);

               if (obj.status == true) {

               		jQuery('.result').html("<div class='alert alert-danger alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong>Success - </strong> "+obj.message+"</div>");
               		  setTimeout(function () {
                         
                             window.location.reload();
                        }, 2000);
               }
               else {

                  if (obj.status == false) {
                     	jQuery('.result').html("<div class='alert alert-danger alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button> "+obj.message+"</div>");
                  }
               }
            }
         });
      }
   });

</script>
<script>

   jQuery(document).ready(function () {
      c1 = 0;
      c2 = 0;
      c3 = 0;
      c4 = 0;
      c5 = 0;
      jQuery('.special_characters_type').keyup(function () {



         var pswd = jQuery(this).val();
         if (pswd.length < 8) {

            console.log("1->" + $("#checksubmit").attr("data-val"));
            c1 = 0;
            if (c2 == '1' && c3 == '1' && c4 == '1' && c5 == '1') {
               console.log("2->" + $("#checksubmit").attr("data-val"));
               $("#checksubmit").attr("data-val", 1);
               $("#UserPassword").attr("data-val", 1);
            }
            else {
               console.log("3->" + "pswd.length===>" + pswd.length + $("#checksubmit").attr("data-val"));
               $("#checksubmit").attr("data-val", 0);
               $("#UserPassword").attr("data-val", 0);

            }
            jQuery('.length').removeClass('valid').addClass('invalid');
         } else {
            c1 = 1;

            if (c1 == '1' && c2 == '1' && c3 == '1' && c4 == '1' && c5 == '1') {
               console.log("4 if->" + "pswd.length===>" + pswd.length + $("#checksubmit").attr("data-val"));
               $("#checksubmit").attr("data-val", 1);
               console.log("3->" + $("#checksubmit").attr("data-val"));
               //$("#checksubmit").attr("data-val",1);
               $("#UserPassword").attr("data-val", 1);
            }
            else {
               console.log("5->" + "pswd.length===>" + pswd.length + $("#checksubmit").attr("data-val"));

               console.log("5->" + $("#checksubmit").attr("data-val"));
               $("#checksubmit").attr("data-val", 0);
               $("#UserPassword").attr("data-val", 0);

            }
            console.log("c1=dd=>" + c1);
            jQuery('.length').removeClass('invalid').addClass('valid');
         }
         //validate letter
         if (pswd.match(/[?,=,.,*,!,#,$,%,&,?,@, ,"]/)) {
            c2 = 1;
            if (c1 == '1' && c2 == '1' && c3 == '1' && c4 == '1' && c5 == '1') {
               console.log("6->" + $("#checksubmit").attr("data-val"));
               $("#checksubmit").attr("data-val", 1);
               $("#UserPassword").attr("data-val", 1);
            }
            else {
               console.log("7->" + $("#checksubmit").attr("data-val"));
               $("#checksubmit").attr("data-val", 0);

               $("#UserPassword").attr("data-val", 0);

            }

            console.log("c2==>" + c2);
            jQuery('.letter').removeClass('invalid').addClass('valid');
         } else {
            c2 = 0;
            if (c1 == '1' && c2 == '1' && c3 == '1' && c4 == '1' && c5 == '1') {
               console.log("8->" + $("#checksubmit").attr("data-val"));
               $("#checksubmit").attr("data-val", 1);
               $("#UserPassword").attr("data-val", 1);
            }
            else {
               console.log("9->" + $("#checksubmit").attr("data-val"));
               $("#checksubmit").attr("data-val", 0);
               $("#UserPassword").attr("data-val", 0);

            }
            jQuery('.letter').removeClass('valid').addClass('invalid');
         }

         //validate capital letter
         if (pswd.match(/[A-Z]/)) {
            c3 = 1;
            if (c1 == '1' && c2 == '1' && c3 == '1' && c4 == '1' && c5 == '1') {
               console.log("10->" + $("#checksubmit").attr("data-val"));
               $("#checksubmit").attr("data-val", 1);
               $("#UserPassword").attr("data-val", 1);
            }
            else {
               console.log("11->" + $("#checksubmit").attr("data-val"));
               $("#checksubmit").attr("data-val", 0);
               $("#UserPassword").attr("data-val", 0);

            }

            console.log("c3==>" + c3);
            jQuery('.capital').removeClass('invalid').addClass('valid');
         } else {
            c3 = 0;
            if (c1 == '1' && c2 == '1' && c3 == '1' && c4 == '1' && c5 == '1') {
               console.log("12->" + $("#checksubmit").attr("data-val"));
               $("#checksubmit").attr("data-val", 1);
               $("#UserPassword").attr("data-val", 1);
            }
            else {
               console.log("13->" + $("#checksubmit").attr("data-val"));
               $("#checksubmit").attr("data-val", 0);
               $("#UserPassword").attr("data-val", 0);

            }
            jQuery('.capital').removeClass('valid').addClass('invalid');
         }

         //validate capital letter
         if (pswd.match(/[a-z]/)) {
            c4 = 1;
            if (c1 == '1' && c2 == '1' && c3 == '1' && c4 == '1' && c5 == '1') {
               console.log("14->" + $("#checksubmit").attr("data-val"));
               $("#checksubmit").attr("data-val", 1);
               $("#UserPassword").attr("data-val", 1);
            }
            else {
               console.log("15->" + $("#checksubmit").attr("data-val"));
               $("#checksubmit").attr("data-val", 0);
               $("#UserPassword").attr("data-val", 0);

            }

            console.log("c4==>" + c4);
            jQuery('.small').removeClass('invalid').addClass('valid');
         } else {
            c4 = 0;
            if (c1 == '1' && c2 == '1' && c3 == '1' && c4 == '1' && c5 == '1') {
               console.log("17->" + $("#checksubmit").attr("data-val"));
               $("#checksubmit").attr("data-val", 1);
               $("#UserPassword").attr("data-val", 1);
            }
            else {
               console.log("18->" + $("#checksubmit").attr("data-val"));
               $("#checksubmit").attr("data-val", 0);
               $("#UserPassword").attr("data-val", 0);

            }
            jQuery('.small').removeClass('valid').addClass('invalid');

         }

         //validate number
         if (pswd.match(/\d/)) {
            c5 = 1;
            if (c1 == '1' && c2 == '1' && c3 == '1' && c4 == '1' && c5 == '1') {
               console.log("20->" + $("#checksubmit").attr("data-val"));
               $("#checksubmit").attr("data-val", 1);
               $("#UserPassword").attr("data-val", 1);
            }
            else {
               console.log("21->" + $("#checksubmit").attr("data-val"));
               $("#checksubmit").attr("data-val", 0);
               $("#UserPassword").attr("data-val", 0);

            }

            console.log("c5==>" + c5);
            jQuery('.number').removeClass('invalid').addClass('valid');
         } else {
            c5 = 0;
            if (c1 == '1' && c2 == '1' && c3 == '1' && c4 == '1' && c5 == '1') {
               console.log("22->" + $("#checksubmit").attr("data-val"));
               $("#checksubmit").attr("data-val", 1);
               $("#UserPassword").attr("data-val", 1);
            }
            else {
               console.log("23->" + $("#checksubmit").attr("data-val"));
               $("#checksubmit").attr("data-val", 0);
               $("#UserPassword").attr("data-val", 0);

            }
            jQuery('.number').removeClass('valid').addClass('invalid');
         }

      }).focus(function () {
         jQuery('#password_hints').show();
      }).blur(function () {
         jQuery('#password_hints').hide();
      });




   });


</script>   
<script
   src="https://maps.google.com/maps/api/js?key=AIzaSyDXendzNuPChFkDejwv7jbFtqunqRawrk0&libraries=geometry,places&callback=activatePlacesSearch"></script>

<!-- end Location-->
	    <script>
  var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
    	if(output.src!='')
    	{
    		$("#output").removeAttr("style");
    URL.revokeObjectURL(output.src) // free memory		
    	}
      
    }
  };

  var loadFile1= function(event) {
    var output = document.getElementById('output2');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
    	if(output.src!='')
    	{
    		$("#output2").removeAttr("style");
    		URL.revokeObjectURL(output.src) // free memory		
    	}
      
    }
  };
</script>
@stop
