@extends('layouts.admin')
@section('content')
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h4 class="text-themecolor mb-0">Edit Promo Code</h4>
		</div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <ol class="breadcrumb mb-0 p-0 bg-transparent fa-pull-right">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active"> Edit Promo Code </li>
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
					<h4 class="card-title mb-0">Edit Promo Code </h4>
				</div>
				<div class="card-body min_height">
					<form name="promocode_edit" id="promocode_edit" method="post" action="javascript:void(0)" enctype="multipart/form-data">
						@csrf
					    <div class="row">
							<div class="">
								<!-- Alert Append Box -->
							<div class="result"></div>
							</div>
							<div class="mb-3 col-md-6">
								<label for="Name" class="control-label" >Category Name: <span style="color:red">*</span> </label>
								<input type="hidden" name="id" value="{{$promocodes->id}}"/>
								<select id="category_id" name="business_category_id" required class="form-control">
									<option value="">Select Business Cagegory</option>
									@if(!empty($categorylist))
										@foreach($categorylist as $catlist)
											@if($promocodes->business_category_id == $catlist->id)
											<option selected value="{{$catlist->id}}">{{$catlist->name}}</option>
											@else
										<option value="{{$catlist->id}}">{{$catlist->name}}</option>
											@endif

										@endforeach
									@endif
								</select>

							</div>
							<div class="mb-3 col-md-6">
								<label for="Email" class="control-label">Promo Code Name: <span style="color:red">*</span></label>
								<input type="text" id="name" name="name" value="{{$promocodes->name}}" required class="form-control">
							</div>
							<div class="mb-3 col-md-6">
								<label for="Email" class="control-label">Promo Amount: <span style="color:red">*</span></label>
								<input type="text" required id="amount" value="{{$promocodes->amount}}" required name="amount" class="form-control">
							</div>

								<div class="mb-3 col-md-6">
								<label for="Email" class="control-label">select Promotion type: <span style="color:red">*</span></label>
								<select name="promotion_type" required class="form-control">
									<option value="">Select Promotion type</option>
									@if($promocodes->promotion_type==1)

									<option selected value="1">Flat</option>
									<option value="2">Percentage</option>
									@else
									<option value="1">Flat</option>
									<option selected value="2">Percentage</option>
									@endif
								</select>
							</div>
							<input type="hidden" name="type" value="edit">
									<div class="mb-3 col-md-6">
								<label for="Email" class="control-label">Start Valid Date: <span style="color:red">*</span></label>
								<input type="date" required id="start_valid_date" value="{{$promocodes->start_valid_date}}" required name="start_valid_date" class="form-control activationdate">
							</div>
									<div class="mb-3 col-md-6">
								<label for="date" class="control-label">End Valid Date: <span style="color:red">*</span></label>
								<input type="date" required id="end_valid_date" value="{{$promocodes->end_valid_date}}" required name="end_valid_date" class="form-control activationdate">
							</div>
							
						</div>
						<a type="button" href="{{ url('/promocode_list') }}"class="btn btn-dark fa-pull-left mt-3">Back</a>
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
  $(function(){



  	//=----------------
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
</script>

<script>
	$("#promocode_edit").validate({
rules: {
	business_category_id:{required : true,},
	name: {required: true,},
	amount: {required: true,},  
	promotion_type: {required: true,},
	start_valid_date:{required:true,},
	end_valid_date:{required:true,},
	},
messages: {
	name: {required: "Please enter category",},
	business_category_id: {required: "Please select business category",},   
	
	amount: {required: "Please enter amount or %",},
	promotion_type:{required:"Please enter promotion type",},
	start_valid_date:{required:"Please enter start validate date",},
		end_valid_date:{required:"Please enter end validate date",},

},
	submitHandler: function(form) {
	   var formData= new FormData(jQuery('#promocode_edit')[0]);
	  host_url = "/development/wemarkthespot/";
	jQuery.ajax({
			url: host_url+"promocode-data",
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
					window.location = host_url+"promocode_list";
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


