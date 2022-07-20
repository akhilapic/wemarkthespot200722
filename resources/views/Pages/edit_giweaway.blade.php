@extends('layouts.admin')
@section('content')
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h4 class="text-themecolor mb-0">Edit Giveaway</h4>
		</div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <ol class="breadcrumb mb-0 p-0 bg-transparent fa-pull-right">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Edit Giveaway </li>
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
					<h4 class="card-title mb-0">Edit Giveaway </h4>
				</div>
				<div class="card-body min_height">
					<form name="Faq_add" id="giweaway_edit1" method="post" action="javascript:void(0)" enctype="multipart/form-data">
						@csrf
					    <div class="row">
							<div class="">
								<!-- Alert Append Box -->
							<div class="result"></div>
							</div>
							<div class="mb-3 col-md-6">
                                <input type="hidden" id="id" name="id" value="{{$giweaway->id}}"/>
								<label for="Name" class="control-label" >Giveaway Name:</label>
								<!--<input type="text" id="question" name="name"  value="{{$giweaway->name}}"class="form-control">-->
							    <input type="text" id="question" name="name"  value="<?php
                                    if(!empty($giweaway->name)){
                                        echo $giweaway->name;
                                    }
                                ?>"class="form-control">
							</div>
							<div class="mb-3 col-md-6">
								<label for="Name" class="control-label" >Giveaway Image:</label>
								<input type="file" id="file" name="image"  class="form-control">
								<img id="imgPreview" width="180" height="150" style="display:none">
								<?php
								    if(!empty($giweaway->image)){
								?>
								        <img id="imgPreviewss" src="<?php echo $giweaway->image; ?>" width="180" height="150" style="display:block">
								<?php
								    }
								?>
							</div>
							<div class="mb-3 col-md-6">
                                <input type="hidden" id="id" name="id" value="{{$giweaway->id}}"/>
								<label for="Name" class="control-label" >Select Business User Name:</label>
								<select class="form-control" name="user_id">
								<option value="1" @if($giweaway->user_id==1) selected @endif>Select</option>
									@foreach($business_namelist as $b)
									
										@if($giweaway->user_id == $b->id)
										<option value="{{$b->id}}" selected>{{$b->business_name}}</option>

										@else
										<option value="{{$b->id}}">{{$b->business_name}}</option>

										@endif
									@endforeach
								</select>
							</div>
							
							<div class="mb-3 col-md-12">
								<label for="Email" class="control-label">Description:</label>
								<textarea id="answers" name="description" class="form-control" style="min-height:200px;">{{$giweaway->description}}</textarea>
								{{-- allready exit error --}}
								<label id="answers_error" class="error"></label>
							</div>
						
						</div>
						<a type="button" href="{{ url('/business_giveaways') }}"class="btn btn-dark fa-pull-left mt-3">Back</a>
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

    $('#file').change(function(){
 
      let reader = new FileReader();
      reader.onload = (e) => { 
         $('#imgPreview').attr('src', e.target.result); 
      }
       $("#imgPreview").attr('style', 'display:block');
       $('#imgPreviewss').attr('style', 'display:none');
      reader.readAsDataURL(this.files[0]); 
     
    });
    
    
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
	$("#giweaway_edit1").validate({
rules: {
// 	name: {required: true,},
	user_id:{required:true},
	description: {required: true},
	},
messages: {
// 	name: {required: "Please enter name",},
	user_id:{required:"Please select business user name"},
	description: {required: "Please enter description",},
},
	submitHandler: function(form) {
	   var formData= new FormData(jQuery('#giweaway_edit1')[0]);
	  host_url = "/development/wemarkthespot/";
	jQuery.ajax({
			url: host_url+"giweaway_update",
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
					window.location = host_url+"business_giveaways";
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


