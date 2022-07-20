@extends('layouts.admin')
@section('content')

<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h4 class="text-themecolor mb-0">Edit Offers Type</h4>
		</div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <ol class="breadcrumb mb-0 p-0 bg-transparent fa-pull-right">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Edit Offers Type </li>
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
					<h4 class="card-title mb-0">Edit Offers Type </h4>
				</div>
				<div class="card-body min_height">
					<form name="category_add" id="offertype_edit" method="post" action="javascript:void(0)" enctype="multipart/form-data">
						@csrf
					    <div class="row">
							<div class="">
								<!-- Alert Append Box -->
							<div class="result"></div>
							</div>
							<div class="mb-3 col-md-4">
                                <input type="hidden" id="id" name="id" value="{{$OfferTypes->id}}"/>
								<label for="Name" class="control-label" > Name:</label>
								<input type="text" id="name" name="name" value="{{$OfferTypes->name}}" class="form-control">
							</div>
							<div class="mb-3 col-md-4">
								<label for="Email" class="control-label">Select Business Category:</label>
                              
								<select id="category_id" name="category_id" class="form-control">
								<option value="0">Select Business Category</option>
									@foreach($category as $c)
                                        @if($OfferTypes->category_id == $c->id)
                                       <option selected value="{{$c->id}}">{{$c->name}}</option>
                                       @else
                                       <option  value="{{$c->id}}">{{$c->name}}</option>
                                        @endif
									@endforeach
								</select>
                            </div>
                            <div class="mb-3 col-md-4">
								<label for="Email" class="control-label">Short Information:</label>
								<input type="text" id="short_information" name="short_information" value="{{$OfferTypes->short_information}}" class="form-control">
								{{-- allready exit error --}}
								<label id="short_information_error" class="error"></label>
							</div>
						</div>
						<a type="button" href="{{ url('/manage_offer_type') }}"class="btn btn-dark fa-pull-left mt-3">Back</a>
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
	$("#offertype_edit").validate({
rules: {
	name: {required: true,},
//	short_information: {required: true,},  
	},
messages: {
	name: {required: "Please enter offers name",},
//	short_information: {required: "Please enter short information",},   
	
},
	submitHandler: function(form) {
	   var formData= new FormData(jQuery('#offertype_edit')[0]);
	  host_url = "/development/wemarkthespot/";
	jQuery.ajax({
			url: host_url+"editoffertype-data",
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
					window.location = host_url+"manage_offer_type";
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


