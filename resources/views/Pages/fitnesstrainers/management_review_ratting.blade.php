@extends('layouts.admin')
@section('content')

<style>
   svg {
      display: none !important;
   }
   .img_box_fix {
      position: relative;
      padding-left: 35px;
      padding-top: 0px;
   }

   .img_box_fix .rounded-circle {
      position: absolute;
      left: 0;
      top: 0px;
   }

   .img_video {
      width: 100%;
      height: 135px;
      object-fit: cover;
      border-radius: 5px;
      display: block;
   }

   .carousel-control-prev,
   .carousel-control-next {
      background: #656262;
   }

   .close.closemodal {
      text-align: right;
      margin-bottom: 12px;
      cursor: pointer;
   }

   .bg_popup {
      background: rgba(0, 0, 0, 0.7);
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
   }

   #exampleModal #bodybox video {
      height: 500px;
      border: 1px solid #d9d4d4;
   }

   #exampleModal #bodybox img {
      height: 500px;
      object-fit: cover;
   }
   /* .card {
      margin-bottom: 30px;
      padding-bottom: 30px;
   }
   p.text-sm.text-gray-700.leading-5 {
      margin-top: 12px;
      margin-left: 8px;
   } */
</style>
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
   <div class="row page-titles">
      <div class="col-md-5 col-12 align-self-center">
         <h4 class="text-themecolor mb-0">Review And Rating Management</h4>
      </div>
      <div class="col-md-7 col-12 align-self-center d-none d-md-block">
         <ol class="breadcrumb mb-0 p-0 bg-transparent fa-pull-right">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
            <li class="breadcrumb-item active">Review And Rating Management</li>
         </ol>
      </div>
   </div>
   <!-- ============================================================== -->
   <!-- Container fluid  -->
   <!-- ============================================================== -->
   <div class="container-fluid">
      <!-- basic table -->
      <div class="row">
         <div class="col-12">
            <div class="card">
               <div class="border-bottom title-part-padding d-flex justify-content-between">
                  <h4 class="card-title mb-0">Review And Rating List</h4>

               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <div class="result"></div>
                     <table id="zero_config" class="table table-striped table-bordered">
                        <thead>
                           <tr>
                              <th>
                                 <div style="width:60px;">Id.</div>
                              </th>
                              <th>
                                 <div style="width:250px;">Business Owner Name</div>
                              </th>
                              <th>
                                 <div style="width:200px;">Business Name</div>
                              </th>
                              <th>
                                 <div style="width:100px;">Rating </div>
                              </th>
                              <th>
                                 <div style="width:200px;">Review Image/Video</div>
                              </th>
                              <th style="text-align: center;">
                                 <div style="width:200px;">Feedback </div>
                              </th>
                              <th>
                                 <div style="width:200px;">User Name/Profile</div>
                              </th>
                              <th>
                                 <div style="width:200px;">Posted Review Date/Time</div>
                              </th>
                              <th>
                                 <div style="width:200px;">Business Rating</div>
                              </th>
                              <th>
                                 <div style="width:100px;">Action</div>
                              </th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php $sr=0; ?>
                           @foreach ($buinessReports as $row=> $user)

                           <tr>
                              <td>{{ $sr= $sr+1 }}</td>

                              <td style="display: table-cell;">
                                 <div class="img_box_fix">
                                    <!--public/images/userimage.png-->
                                    @if(!empty($user->business_owner_image))
                                    <img src="{{$user->business_owner_image}}" alt="Not Found" width="30" height="30"
                                       class="rounded-circle">
                                    <span class="ml-2"> @if($user->business_owner_name) {{ $user->business_owner_name }}
                                       @else No Busniess Owner Name @endif </span>
                                    @else
                                    <img src="{{asset('public/images/userimage.png')}}" alt="Not Found" width="30"
                                       height="30" class="rounded-circle">
                                    <span class="ml-2">@if($user->business_owner_name) {{ $user->business_owner_name }}
                                       @else No Busniess Owner Name @endif</span>
                                    @endif
                                 </div>
                              </td>

                              <td style="display: table-cell;">
                                 <div class="link img_box_fix">
                                    <!--public/images/userimage.png-->
                                    @if(!empty($user->business_image))
                                    <img src="{{$user->business_image}}" alt="Not Found" width="30" height="30"
                                       class="rounded-circle">
                                    <span class="ml-2"> @if($user->business_name) {{ $user->business_name }} @else No
                                       Busniess Name @endif </span>
                                    @else
                                    <img src="{{asset('public/images/userimage.png')}}" alt="Not Found" width="30"
                                       height="30" class="rounded-circle">
                                    <span class="ml-2">@if($user->business_name) {{ $user->business_name }} @else No
                                       Busniess Name @endif</span>
                                    @endif


                                 </div>
                              </td>

                              <td style="display: table-cell;">
                                 <a href="javascript:void(0)" class="link">
                                    <span class="ml-2">{{ $user->ratting }}</span>
                                 </a>
                              </td>

                              <td>
                                 <?php
                                if($user->image_video_status==1)
                                {?>
                                 <div id="carouselExampleControls<?php echo $row;?>" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                       <?php
                                   
                                       $imgarray = explode(",",$user->business_review_image);
                                    
                                       foreach($imgarray as $key => $img)
                                       {
                                          if($key==0)
                                          {
                                             ?>
                                       <div class="carousel-item active">
                                          <?php
                                                }
                                                else
                                                 {
                                                    ?>
                                          <div class="carousel-item">
                                             <?php
                                                 }  
                                             ?>
                                             <img src="<?php echo $img;?>" data-image_video_status="1"
                                                data-src="<?php echo $img;?>" alt="Not Found" class="img_video">
                                          </div>
                                          <?php } ?>
                                       </div>
                                       <a class="carousel-control-prev" type="button"
                                          data-bs-target="#carouselExampleControls<?php echo $row;?>"
                                          data-bs-slide="prev">
                                          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                          <span class="visually-hidden">Previous</span>
                                       </a>
                                       <a class="carousel-control-next" type="button"
                                          data-bs-target="#carouselExampleControls<?php echo $row;?>"
                                          data-bs-slide="next">
                                          <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                          <span class="visually-hidden">Next</span>
                                       </a>
                                    </div>
                                 </div>
                                    <?php
                                }
                                else if($user->image_video_status==2)
                                {?>
                                    <div id="carouselExampleControls<?php echo $row;?>" class="carousel slide" data-bs-ride="carousel">
                                       <div class="carousel-inner">
                                          <?php
                                          //   $imgarray = explode(",","https://builtenance.com/development/wemarkthespot/public/images/45a0c14234cc0f01d021d79c615f20b50.mp4,https://builtenance.com/development/wemarkthespot/public/images/45a0c14234cc0f01d021d79c615f20b50.mp4,");
                                            $imgarray = explode(",",$user->business_review_image);
                                             foreach($imgarray as $key => $img)
                                             {
                                                if($key==0)
                                                {
                                                   ?>
                                          <div class="carousel-item active">
                                             <?php
                                                }
                                                else
                                                 {
                                                    ?>
                                             <div class="carousel-item">
                                                <?php
                                                 }  
                                             ?>
                                                <video controls data-src="<?php echo $img;?>"
                                                   data-image_video_status="2" class="img_video" autoplay muted loop>
                                                   <source src="<?php echo $img;?>" type="video/mp4">
                                                </video>
                                             </div>
                                             <?php } ?>
                                          </div>
                                          <a class="carousel-control-prev" type="button"
                                             data-bs-target="#carouselExampleControls<?php echo $row;?>"
                                             data-bs-slide="prev">
                                             <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                             <span class="visually-hidden">Previous</span>
                                          </a>
                                          <a class="carousel-control-next" type="button"
                                             data-bs-target="#carouselExampleControls<?php echo $row;?>"
                                             data-bs-slide="next">
                                             <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                             <span class="visually-hidden">Next</span>
                                          </a>
                                       </div>
                                    </div>
                                       <?php } ?>
                              </td>

                              <td>
                                  <textarea class="form-control" readonly cols="50" style="min-height: 140px;">{{ $user->review }}</textarea>
                              </td>

                              <td style="display: table-cell;">
                                 <div class="link img_box_fix">
                                    <img src="{{$user->user_image}}" alt="user" width="30" height="30"
                                       class="rounded-circle">
                                    <span>{{ $user->user_name }}</span>
                                 </div>
                              </td>

                              <td>{{$user->post_date}}</td>
                              <td>{{$user->overall_rating_of_business}}</td>

                              <td>
                                 <div class="table_action">
                                    <a href="javascript:void(0)" class="btn  btn-danger btn-sm list_delete "
                                       onclick="report_status_new(1,{{$user->business_id}},{{$user->user_id}},{{$user->business_reviews_id}})">
                                       <i class="mdi mdi-delete"></i>
                                    </a>

                                 </div>

                              </td>
                           </tr>

                           @endforeach
                           <meta name="csrf-token" content="{{ csrf_token() }}">
                        </tbody>
                      
                     </table>

                  </div>
               </div>

            </div>
         </div>
      </div>

   </div>
   <!-- ============================================================== -->
   <!-- End Container fluid  -->
   <!-- ============================================================== -->
   <div class="modal show" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="bg_popup"></div>
      <div class="modal-dialog">
         <div class="modal-content">

            <!-- Modal Header -->

            <!-- Modal body -->
            <div class="modal-body">

               <div class="close closemodal">✖</div>
               <div id="bodybox">
                  <img id="showimage" class='form-control img_video2' />

                  <!-- <img src="https://builtenance.com/development/wemarkthespot/public/images/18d19861a3bb984aa35ba3343a2b9f4b0.PNG"
                     class="form-control img_video2"> -->

                  <div id="img_video2">
                     <video controls class="img_video" autoplay muted loop>
                        <source id="showbideosrc" type="video/mp4">
                  </div>
               </div>
            </div>

            <!-- Modal footer -->


         </div>
      </div>
   </div>

   <!-- This page plugin CSS -->

   <!-- Blog Details -->
   <div class="modal fade" id="customer_details_modal" tabindex="-1" aria-labelledby="exampleModalLabel1">
      <div class="modal-dialog modal-lg">
         <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
               <h4 class="modal-title" id="exampleModalLabel1">User Details</h4>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
               <div id="user-data">
                  {{-- modal data here --}}
               </div>
            </div>

            <div class="modal-footer">
               <button type="button" class="btn btn-light-danger text-danger font-weight-medium"
                  data-bs-dismiss="modal">Close</button>
            </div>
         </div>
      </div>
   </div>

   <script type="text/javascript">

$(document).ready(function () {


var data_table = $('#zero_config').DataTable();
data_table.order( [0,'desc'] ).draw();

});
   
      function report_status_new(report_status_value, business_id, user_id, review_id) {

         host_url = "/development/wemarkthespot/";
         if (report_status_value == 1) {
            if (confirm('Are you sure remove this review and rating?')) {
               var token = $("meta[name='csrf-token']").attr("content");
               $.ajax({
                  type: "POST",
                  dataType: "json",
                  url: host_url + 'business_review__remove',
                  data: { '_token': token, 'business_id': business_id, 'user_id': user_id, 'review_id': review_id, 'report_status_value': report_status_value },
                  success: function (data) {
                     // var obj = JSON.parse(data);

                     if (data.status == true) {
                        jQuery('.result').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong>Success - </strong> " + data.message + "</div>");

                        setTimeout(function () {
                           jQuery('.result').html('');
                           window.location.reload();
                        }, 3000);
                     }

                  }
               });
            }

         }
         else if (report_status_value == 2) {
            var token = $("meta[name='csrf-token']").attr("content");
            $.ajax({
               type: "POST",
               dataType: "json",
               url: host_url + 'report_status',
               data: { '_token': token, 'business_report_id': business_report_id, 'business_id': business_id, 'user_id': user_id, 'review_id': review_id, 'report_status_value': report_status_value },
               success: function (data) {
                  // var obj = JSON.parse(data);

                  if (data.status == true) {
                     jQuery('.result').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong>Success - </strong> " + data.message + "</div>");

                     setTimeout(function () {
                        jQuery('.result').html('');
                        window.location.reload();
                     }, 3000);
                  }

               }
            })
         }


      }
   </script>
   <script type="text/javascript">
      $(".img_video").on("click", function () {
         src = $(this).data("src");
         image_video_status = $(this).data("image_video_status");
         //  alert(image_video_status);
         $("#bodybox").empty();
         bodyhtml = "";
         if (image_video_status == 1) {
            $("#img_video2").hide();
            $("#showimage").show();
            //         $("#showimage").attr("src",src);
            bodyhtml += '<img src="' + src + '" class="form-control img_video2"/>';

         }
         else {
            $("#showimage").hide();
            $("#img_video2").show();
            //    $("#showbideosrc").attr("src",src);
            bodyhtml += '<video controls  class="img_video2" style="width: 100%;"  autoplay muted loop><source src="' + src + '" type="video/mp4" ></video>';
         }
         $("#bodybox").html(bodyhtml);
         $('#exampleModal').toggle();
      });
      $(".closemodal").on("click", function () {
         $('#exampleModal').toggle();
      });
      function useractivedeactive($id, $status) {

         host_url = "/development/wemarkthespot/";
         var status = $status; //$(this).prop('checked') == true ? 1 : 0; 

         var token = $("meta[name='csrf-token']").attr("content");
         var user_id = $id; //$(this).data('id'); 



         $.ajax({
            type: "POST",
            dataType: "json",
            url: host_url + 'category_status',
            data: { '_token': token, 'status': status, 'id': user_id },
            success: function (data) {
               // var obj = JSON.parse(data);

               if (data.status == true) {
                  jQuery('.result').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong>Success - </strong> " + data.message + "</div>");

                  setTimeout(function () {
                     jQuery('.result').html('');
                     window.location.reload();
                  }, 3000);
               }

            }
         });


      }
   </script>
   @stop