@extends('layouts.admin')
@section('content')
<style>
	.img_video {
		width: 80px;
		height: 80px;
		object-fit: cover;
		border-radius: 5px;
		display: block;
	}

	.img_video2 {
		width: 100% !important;
	}

	.close.closemodal {
		text-align: right;
		margin-bottom: 12px;
		cursor: pointer;
	}

	.carousel-control-prev,
	.carousel-control-next {
		background: #656262;
	}

	.carousel {
		width: 80px;
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
</style>


<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
	<div class="row page-titles">
		<div class="col-md-5 col-12 align-self-center">
			<h4 class="text-themecolor mb-0">Flagged Reviews</h4>
		</div>
		<div class="col-md-7 col-12 align-self-center d-none d-md-block">
			<ol class="breadcrumb mb-0 p-0 bg-transparent fa-pull-right">
				<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
				<li class="breadcrumb-item active">Flagged Reviews</li>
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
						<h4 class="card-title mb-0">Flagged Reviews List</h4>

					</div>
					<div class="card-body">
						<div class="table-responsive">
							<div class="result"></div>
							<table id="zero_config" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>Id.</th>
										<th>
											<div style="width:150px;">Reported By</div>
										</th>
										<th>
											<div style="width:150px;">Commented By</div>
										</th>

										<th>
											<div style="width:200px;">Comment's Image/Video</div>
										</th>
										<th>
											<div style="width:200px;">Comment</div>
										</th>
										<th>
											<div style="width:150px;">Post Date</div>
										</th>

										<th>
											<div style="width:150px;">Report Date</div>
										</th>
										<th>
											<div style="width:150px;">Status</div>
										</th>
										<th>
											<div style="width:131px;">Action</div>
										</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($buinessReports as $row=> $user)

									<tr>
										<td>{{ $user->id }}</td>

										<td>
											<div class="link img_box_fix">
												<img src="{{$user->user_image}}" alt="Not Found" width="30" height="30"
													class="rounded-circle">
												<span class="ml-2">{{ $user->user_name }}</span>
											</div>
										</td>

										<td style="display: table-cell;">
											<a href="javascript:void(0)" class="link">
												<span class="ml-2">{{ $user->business_username }}</span>
											</a>
										</td>

										<td>
											<?php
											if($user->imagevideocheck==1)
											{?>
											<div id="carouselExampleControls<?php echo $row;?>" class="carousel slide"
												data-bs-ride="carousel">
												<div class="carousel-inner">
													<?php
													 $imgarray = explode(",",$user->business_reviews_image);
													 foreach($imgarray as $key => $img)
													 {
														 if($img){
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
														 <img src="<?php echo $img;?>" data-image_video_status="1" data-src="<?php echo $img;?>" alt="Not Found" class="img_video">
																</div>
														<?php }else {echo ' No Media';} } ?>
														</div>
														<a class="carousel-control-prev" type="button"
															data-bs-target="#carouselExampleControls<?php echo $row;?>"
															data-bs-slide="prev">
															<span class="carousel-control-prev-icon"
																aria-hidden="true"></span>
															<span class="visually-hidden">Previous</span>
														</a>
														<a class="carousel-control-next" type="button"
															data-bs-target="#carouselExampleControls<?php echo $row;?>"
															data-bs-slide="next">
															<span class="carousel-control-next-icon"
																aria-hidden="true"></span>
															<span class="visually-hidden">Next</span>
														</a>
												</div>
												<?php
                                			}
											else if($user->imagevideocheck==0)
											{?>
												<div id="carouselExampleControls<?php echo $row;?>"
													class="carousel slide" data-bs-ride="carousel">
													<div class="carousel-inner">
														<?php
														$imgarray = explode(",",$user->business_reviews_image);
										  
                                             			foreach($imgarray as $key => $img)
                                             			{
												 			if($img){
                                                				if($key==0)
                                                				{
																	?>
																<div class="carousel-item active" id='{{$img}}'>
																	<?php
                                                				}
                                                				else
                                                 				{?>
																	<div class="carousel-item">
																<?php
																}  ?>

																<div class="position-relative">
																	<video controls data-src="<?php echo $img;?>"
																		data-image_video_status="2" class="img_video"
																		autoplay muted loop>
																		<source src="<?php echo $img;?>"
																			type="video/mp4">

																	</video>
																	<div class="imagevideocheck video_hidden_box img_video"
																		data-src="<?php echo $img;?>"
																		data-image_video_status="2"></div>
																</div>
															</div>
															<?php }else {echo ' No Media';} } ?>
															<a class="carousel-control-prev" type="button"
																data-bs-target="#carouselExampleControls<?php echo $row;?>"
																data-bs-slide="prev">
																<span class="carousel-control-prev-icon"
																	aria-hidden="true"></span>
																<span class="visually-hidden">Previous</span>
															</a>
															<a class="carousel-control-next" type="button"
																data-bs-target="#carouselExampleControls<?php echo $row;?>"
																data-bs-slide="next">
																<span class="carousel-control-next-icon"
																	aria-hidden="true"></span>
																<span class="visually-hidden">Next</span>
															</a>
														</div>
												<?php
												}
											?>
									</td>
									

										

										<td><textarea class="form-control" readonly rows="3"
												cols="10">{{ $user->comment }}</textarea></td>
										<td>{{$user->post_date}}</td>
										<td>{{ $user->business_reports_created_date }}</td>
										<td>
											@if($user->report_status==0)
											No action taken
											@elseif($user->report_status==1)
											Mail Send
											@elseif($user->report_status==2)
											Mail Send and Remove Comment
											@endif
										</td>
										<td>
											<div class="table_action">

												<a style="display: none;" href="javascript:void(0)"
													class="btn  btn-danger btn-sm list_delete "
													onclick="report_status_new(1,{{$user->id}},{{$user->business_id}},{{$user->user_id}},{{$user->review_id}})">
													<i class="mdi mdi-delete"></i>
												</a> <a href="javascript:void(0)"
													{{-- onclick="report_status_new(2,{{$user->id}},{{$user->business_id}},{{$user->user_id}},{{$user->review_id}})" --}}
													class="btn  btn-primary btn-sm ">
													<i class="mdi mdi-message"></i>
												</a>




												<span class="status">
													<label class="switch">
														@if($user->business_reviews_status==1)
														<input data-id="{{$user->business_user_id}}"
															data-review_id="{{$user->business_reviews_id}}"
															class="  switch-input"
															onchange="useractivedeactive({{$user->business_user_id}},'0',{{$user->business_reviews_id}});"
															type="checkbox" data-onstyle="success"
															data-offstyle="danger" data-toggle="toggle" data-on="Active"
															data-off="InActive">
														<span class="switch-label" data-on="Active"
															data-off="Deactive"></span>
														<span class="switch-handle"></span>
														@else
														<input data-id="{{$user->business_user_id}}"
															class="  switch-input"
															data-review_id="{{$user->business_reviews_id}}"
															onchange="useractivedeactive({{$user->business_user_id}},'1',{{$user->business_reviews_id}});"
															type="checkbox" data-onstyle="success"
															data-offstyle="danger" data-toggle="toggle"
															data-on="Deactive" data-off="InActive" checked>
														<span class="switch-label" data-on="Active"
															data-off="Deactive"></span>
														<span class="switch-handle"></span>
														@endif
													</label>
												</span>
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
					<button type="button" class="btn btn-light-danger text-danger font-weight-medium btn-close"
						data-bs-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal show" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="bg_popup"></div>
		<div class="modal-dialog">
			<div class="modal-content">

				<!-- Modal Header -->

				<!-- Modal body -->
				<div class="modal-body">

					<div class="close closemodal">✖</div>
					<div id="bodybox">
						<!-- <img id="showimage" class='form-control img_video2'/> -->

						<!-- <img src="https://builtenance.com/development/wemarkthespot/public/images/18d19861a3bb984aa35ba3343a2b9f4b0.PNG"
                     class="form-control img_video2"> -->

						<!-- <div id="img_video2">
                     <video controls  class="img_video" autoplay muted loop>
                     <source  id="showbideosrc" type="video/mp4">
                     </div>  -->
					</div>
				</div>

				<!-- Modal footer -->


			</div>
		</div>
	</div>
	<div class="modal" id="myModal">
	<div class="bg_popup"></div>
		<div class="modal-dialog">
			<div class="modal-content">

				<!-- Modal Header -->

				<!-- Modal body -->
				<div class="modal-body">

					<div class="close closemodal">✖</div>
					<div id="bodybox"></div>
				</div>

				<!-- Modal footer -->


			</div>
		</div>
	</div>
</div>

<style>
	.video_hidden_box {
		width: 80px;
		height: 80px;
		object-fit: cover;
		border-radius: 5px;
		position: absolute;
		top: 0;
		left: 0;
		opacity: 0;
	}
</style>
<script>
		$(document).ready(function () {


var data_table = $('#zero_config').DataTable();
data_table.order( [0,'desc'] ).draw();

});
</script>

<script type="text/javascript">

	$(".img_video").on("click", function () {
		src = $(this).data("src");
		image_video_status = $(this).data("image_video_status");
		//   alert(image_video_status +" " +src);
		$("#bodybox").empty();
		bodyhtml = '';

		if (image_video_status == 1) {
			$("#img_video2").hide();
			$("#showimage").show();

			//		$("#showimage").attr("src",src);
			bodyhtml += '<img src="' + src + '" class="form-control img_video2"/>';

		}
		else {
			$("#showimage").hide();
			$("#img_video2").show();
			//    $("#showbideosrc").attr("src",src);
			bodyhtml += '<video controls  class="img_video2"  style="width: 100%;"  autoplay muted loop><source src="' + src + '" type="video/mp4" ></video>';

		}
		$("#bodybox").html(bodyhtml);

		$('#exampleModal').toggle();
	});
	$(".closemodal").on("click", function () {
		$('#exampleModal').toggle();
	});

	function video(src) {
		console.log("video src==>" + src);
	}
	$(".imagevideocheck").on("click", function () {
		status = $(this).data("status");
		src = $(this).data("src");
		console.log(src);
		$("#bodybox").empty();
		bodyhtml = '';
		if (status == 1) {
			bodyhtml += '<img src="' + src + '" class="form-control img_video2"/>';
		}
		else {
			bodyhtml += '<video controls  class="img_video2"  style="width: 100%;"  autoplay muted loop><source src="' + src + '" type="video/mp4" ></video>';
		}
		$("#bodybox").html(bodyhtml);
		//	$("#myModal").modal('show');
	});

	$(".closemodal").on("click", function () {
		$("#myModal").modal('hide');
	});

	function report_status_new(report_status_value, business_report_id, business_id, user_id, review_id) {
		host_url = "/development/wemarkthespot/";
		if (report_status_value == 1) {
			if (confirm('Are you sure delete this report?')) {
				var token = $("meta[name='csrf-token']").attr("content");
				$.ajax({
					type: "POST",
					dataType: "json",
					url: host_url + 'report_status',
					data: { '_token': token, 'business_report_id': business_report_id, 'business_id': business_id, 'user_id': user_id, 'review_id': review_id, 'report_status_value': report_status_value },
					success: function (data) {
						//	var obj = JSON.parse(data);

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
					//	var obj = JSON.parse(data);

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
	function useractivedeactive($id, $status, $business_review_id) {

		host_url = "/development/wemarkthespot/";
		var status = $status; //$(this).prop('checked') == true ? 1 : 0; 

		var token = $("meta[name='csrf-token']").attr("content");
		var user_id = $id; //$(this).data('id'); 



		$.ajax({
			type: "POST",
			dataType: "json",
			url: host_url + 'report_business_status',
			data: { '_token': token, 'status': status, 'business_user_id': user_id, 'business_review_id': $business_review_id },
			success: function (data) {
				//	var obj = JSON.parse(data);

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