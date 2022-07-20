@extends('layouts.admin')
@section('content')
<style>
	.loadingbtnap{
		float: right;
		color:#ffffff;
		background-color: var(--btn-bg) !important;
    border-color: var(--btn-bg) !important;		
	    opacity: 1 !important;
	}

</style>
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h4 class="text-themecolor mb-0">Edit Introductory Video</h4>
		</div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <ol class="breadcrumb mb-0 p-0 bg-transparent fa-pull-right">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active"> Edit Introductory Video </li>
			</ol>
		</div>
	</div>
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
		
		<div class="col-12">
			<div class="card">
				<div class="border-bottom title-part-padding">
					<h4 class="card-title mb-0">Edit Introductory Video </h4>
				</div>
				<div class="card-body min_height">
					<form name="IntroductorVideoedit" id="IntroductorVideoedit" method="post" action="javascript:void(0)" enctype="multipart/form-data">
						@csrf
					    <div class="row">
							<div class="">
								<!-- Alert Append Box -->
							<div class="result"></div>
							</div>
							
							<div class="mb-3 col-md-6">
                                <input type="hidden" name="id" value="{{$quoates->id}}"/>
								<label for="username" class="control-label">Introductory video :  </label><br><span style="color:red">For better speed and quality, please upload a video of 2 MB.</span>

									<input type="file" class="form-control" id="video" name="video" accept="video/*"/>

								{{-- allready exit error --}}
							<label id="detail_information_error" class="error"></label>
							</div>
						</div>
						<a type="button" href="{{ url('/Introductory_Video') }}"class="btn btn-dark fa-pull-left mt-3">Back</a>
						<button class="btn btn-dark fa-pull-left mt-3 ms-2 loadingbtnap" type="button"  style="display: none"  disabled>
                                          <span class="spinner-grow spinner-grow-sm n-success" role="status" aria-hidden="true"></span>
                                          Loading...
                                        </button>
						<input type="submit" id="submit" value="Save" class="btn btn-success btn_submit btnapproved fa-pull-right mt-3">
					</form>
				</div>
			</div>
		</div>
		
	</div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
<script>
	$("#IntroductorVideoedit").validate({
	rules: {
		video: {required: true,},
	
		},
	messages: {
		video: {required: "Please select video file",},
	
	},
		submitHandler: function(form) {
		   var formData= new FormData(jQuery('#IntroductorVideoedit')[0]);
		jQuery.ajax({
				url: host_url+"IntroductorVideo-edit",
				type: "post",
				cache: false,
				data: formData,
				processData: false,
				contentType: false,
				beforeSend: function(msg){
            $(".loadingbtnap").css("display","block");
            $(".btnapproved").hide();
        
      },

				success:function(data) { 
				var obj = JSON.parse(data);
				if(obj.status==true){
					jQuery('#name_error').html('');
					jQuery('#email_error').html('');
					jQuery('.result').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong>Success - </strong> "+obj.message+"</div>");
					setTimeout(function(){
						jQuery('.result').html('');
						$(".loadingbtnap").css("display","none");
            $(".btnapproved").show();
        
						window.location = host_url+"Introductory_Video";
					}, 2000);
				}
				else{
					if(obj.status==false){
						jQuery('.result').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong>Success - </strong> "+obj.message+"</div>");
					}
					
				}
				}
			});
		}
	});
	</script>
@stop


