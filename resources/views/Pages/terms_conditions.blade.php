@extends('layouts.admin')
@section('content')
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<script src="https://cdn.ckeditor.com/4.17.1/standard-all/ckeditor.js"></script>
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h4 class="text-themecolor mb-0">Terms Conditions</h4>
		</div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <ol class="breadcrumb mb-0 p-0 bg-transparent fa-pull-right">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Terms Conditions </li>
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
					<h4 class="card-title mb-0">Terms Conditions </h4>
				</div>
				<div class="card-body min_height">
					<form name="category_add" id="terms_conditions" method="post" action="javascript:void(0)" enctype="multipart/form-data">
						@csrf
					    <div class="row">
							<div class="">
								<!-- Alert Append Box -->
							<div class="result"></div>
							</div>
								<input type="hidden" id="id" name="id" value="{{$terms_conditions->id}}">
							<div class="mb-3 col-md-12">
                             	<label for="Email" class="control-label">Information:</label>
                                <textarea name="description" class="form-control" id="" style="min-height:300px;">{{$terms_conditions->description}}</textarea>
								{{-- allready exit error --}}
								<label id="short_information_error" class="error"></label>
							</div>
						
                        
						
						</div>
						<!-- <a type="button" href="{{ url('/') }}"class="btn btn-dark fa-pull-left mt-3">Back</a> -->
						<input type="submit" id="submit" value="Save" class="btn btn-success btn_submit fa-pull-right mt-3">
					</form>
				</div>
			</div>
		</div>
		
	</div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
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
</script>
<script>
    CKEDITOR.replace('editor1', {
      fullPage: true,
      extraPlugins: 'docprops',
      // Disable content filtering because if you use full page mode, you probably
      // want to  freely enter any HTML content in source mode without any limitations.
      allowedContent: true,
      height: 320,
      removeButtons: 'PasteFromWord'
    });
  </script>
<script>
	$("#terms_conditions").validate({
rules: {
	description: {required: true,},
	},
messages: {
	description: {required: "Please enter description",},

},
	submitHandler: function(form) {
	   var formData= new FormData(jQuery('#terms_conditions')[0]);
	  host_url = "/development/wemarkthespot/";
	jQuery.ajax({
			url: "terms_conditions_data",
			type: "post",
			cache: false,
			data: formData,
			processData: false,
			contentType: false,
			
			success:function(data) { 
			var obj = JSON.parse(data);
			if(obj.status==true){
				jQuery('#name_error').html('');
				jQuery('#email_error').html('');
				jQuery('.result').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong>Success - </strong> "+obj.message+"</div>");
				setTimeout(function(){
					jQuery('.result').html('');
					window.location = host_url+"manage_terms_conditions";
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


