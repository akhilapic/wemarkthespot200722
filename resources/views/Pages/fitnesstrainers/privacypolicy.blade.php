@extends('layouts.admin')
@section('content')
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h4 class="text-themecolor mb-0">Privacy Policy</h4>
		</div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <ol class="breadcrumb mb-0 p-0 bg-transparent fa-pull-right">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Privacy Policy </li>
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
					<h4 class="card-title mb-0">Privacy Policy </h4>
				</div>
				<div class="card-body min_height">
				
				<form name="privacypolicy" id="privacypolicy" method="post" action="javascript:void(0)" enctype="multipart/form-data">
						@csrf
					    <div class="row">
							<div class="">
								<!-- Alert Append Box -->
							<div class="result"></div>
							</div>
								<input type="hidden" id="id" name="id" value="{{$privacypolicyDetails->id}}">
							<div class="mb-3 col-md-12">
                             	<label for="Email" class="control-label">Information:</label>
                                <textarea name="description" class="form-control" name="description" id="" style="min-height:300px;">{{$privacypolicyDetails->description}}</textarea>
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
	<script src="{{ asset('assets/admin/libs/ckeditor/ckeditor.js') }}"></script>
	<script src="{{ asset('assets/admin/libs/ckeditor/samples/js/sample.js') }}"></script>
    <script>
	//default
	initSample();
	
	//inline editor
	// We need to turn off the automatic editor creation first.
	CKEDITOR.disableAutoInline = true;
	
	CKEDITOR.inline('editor2', {
		extraPlugins: 'sourcedialog',
		removePlugins: 'sourcearea'
	});
	
	var editor1 = CKEDITOR.replace('editor1', {
		extraAllowedContent: 'div',
		height: 460
	});
	editor1.on('instanceReady', function () {
		// Output self-closing tags the HTML4 way, like <br>.
		this.dataProcessor.writer.selfClosingEnd = '>';
		
		// Use line breaks for block elements, tables, and lists.
		var dtd = CKEDITOR.dtd;
		for (var e in CKEDITOR.tools.extend({}, dtd.$nonBodyContent, dtd.$block, dtd.$listItem, dtd.$tableContent)) {
			this.dataProcessor.writer.setRules(e, {
				indent: true,
				breakBeforeOpen: true,
				breakAfterOpen: true,
				breakBeforeClose: true,
				breakAfterClose: true
			});
		}
		// Start in source mode.
		this.setMode('source');
	});
</script>
<script data-sample="1">
	CKEDITOR.replace('testedit', {
		height: 150
	});
</script>
<script data-sample="2">
	CKEDITOR.replace('testedit1', {
		height: 400
	});
</script>
<script data-sample="3">
	CKEDITOR.replace('testedit2', {
		height: 400
	});
</script>
<script data-sample="4">
	CKEDITOR.replace('tool-location', {
		toolbarLocation: 'bottom',
		// Remove some plugins that would conflict with the bottom
		// toolbar position.
		removePlugins: 'elementspath,resize'
	});
</script>
<script>
	$("#privacypolicy").validate({
rules: {
	testedit1: {required: true},
	},
messages: {
	testedit1: {required: "Please enter description",},
},
	submitHandler: function(form) {
	   var formData= new FormData(jQuery('#privacypolicy')[0]);
    //   desc = CKEDITOR.instances['testedit1'].getData();
	//			formData.append("testedit1", desc);
	  host_url = "/development/wemarkthespot/";
	jQuery.ajax({
			url: "privacy-update",
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
				//	window.location = host_url+"faq";
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


