<?php $__env->startSection('content'); ?>


<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />

<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h4 class="text-themecolor mb-0">Contact Us</h4>
		</div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <ol class="breadcrumb mb-0 p-0 bg-transparent fa-pull-right">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Contact Us</li>
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
					    <h4 class="card-title mb-0">Contact Us List</h4> 
						             
					</div>
					<div class="card-body">
						<div class="table-responsive">
							  <div class="result"></div>
							<table id="zero_config" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>Id.</th>
										<th>Name</th>
										<th>Email</th>
										<th>Phone</th>
										<th>Comment</th>
										<Th>Created Date</Th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
								<?php $__currentLoopData = $contactus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
								
									<tr>
										<td><?php echo e($user->id); ?></td>
										<td style="display: table-cell;">
											<a href="javascript:void(0)" class="link">
												<span class="ml-2"><?php echo e($user->name); ?></span>
											</a>
										</td>

										<td><?php echo e($user->email); ?></td>
										<td><?php echo e($user->phone); ?></td>
										<td><textarea readonly rows="5" cols="20"><?php echo e($user->comment); ?></textarea></td>
										
												<td><?php echo e($user->created_at); ?></td>
										<td>
											<div class="table_action" style="width: 103px">
												<a style="display:none" href="<?php echo e(url('/category-view')); ?>/<?php echo e($user->id); ?>" class="btn btn-success btn-sm list_view infoU"  data-id='"<?php echo e($user->id); ?>"' data-bs-whatever="@mdo">
													<i class="mdi mdi-eye"></i>
												</a>  
												<a style="display:none" href="<?php echo e(route('category_delete',$user->id)); ?>" class="btn  btn-danger btn-sm list_delete " onclick="return confirm('Are you sure delete this category？')">
													<i class="mdi mdi-delete"></i>
												</a> 
												
												<a style="display:none" href="<?php echo e(url('category_edit',$user->id)); ?>" class="btn btn-info btn-sm list_edit">
													<i class="mdi mdi-lead-pencil"></i>
												</a> 
												<span class="status" style="display:none">
													<label class="switch">

														<input data-id="<?php echo e($user->id); ?>" class=" category_statuss switch-input" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" <?php echo e($user->status ? 'checked' : ''); ?>>
														<span class="switch-label" data-on="Active" data-off="Deactive"></span> 
														<span class="switch-handle"></span> 
													</label>
												</span>
												<button class="btn btn-primary reply" data-id="<?php echo e($user->id); ?>">Reply</button>
													
													<span class="status" style="display: none;">
													<label class="switch resolve_box" >
														<?php if($user->status==1): ?>
														<input data-id="<?php echo e($user->id); ?>" class="  switch-input" onchange="useractivedeactive(<?php echo e($user->id); ?>,'0');" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive"  >
														<span class="switch-label" data-on="Unresolved" data-off="Unresolved"></span> 
														<span class="switch-handle"></span> 
														<?php else: ?>
														<input data-id="<?php echo e($user->id); ?>" class="  switch-input" onchange="useractivedeactive(<?php echo e($user->id); ?>,'1');" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Deactive" data-off="InActive" checked>
														<span class="switch-label" data-on="Reply" data-off="Reply"></span> 
														<span class="switch-handle"></span> 
														<?php endif; ?>
													</label>
												</span>
											</div>
											  
										</td>
									</tr>
									
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
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

<style>
.switch.resolve_box {
    width: 112px;
}
.switch-input:checked ~ .switch-handle {
    left: 86px;
}
</style>
<!-- This page plugin CSS -->

<!-- Blog Details -->
<div class="modal fade " id="customer_details_modal" tabindex="-1" aria-labelledby="exampleModalLabel1">
	<div class="modal-dialog modal-md ">
		<div class="modal-content">
			<div class="modal-header d-flex align-items-center">
				<h4 class="modal-title" id="exampleModalLabel1">Reply</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<div id="user-data">
				<form id="Contact" method="Post" >
					<span class="result"></span>
					<input type ="hidden" value="" name="id" id="contact_user_id"/>
					<label>Message</label>
					<textarea required name="message"  id="message" class="form-control"></textarea>
					<span id="messageError" style="color:red"></span>
				</form>
				</div>
			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger font-weight-medium" data-bs-dismiss="modal">Close</button>
				<input type="submit" id="replybtn" class="btn btn-light-primary text-danger font-weight-medium" value="Send"/>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function () {


var data_table = $('#zero_config').DataTable();
data_table.order( [0,'desc'] ).draw();

});

	$("#replybtn").on("click",function(){
		const message = $("#message").val();
		if(message=='')
		{
			$("#messageError").text("please enter message");
			return false;
		}
		else
		{
			var formData= new FormData(jQuery('#Contact')[0]);
			jQuery.ajax({
					url: host_url+"ContactReply",
					type: "post",
					cache: false,
					data: formData,
					processData: false,
					contentType: false,
					headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
					success:function(data) { 
					
					var obj = JSON.parse(data);
					
					if(obj.status==true){
				
						jQuery('.result').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong>"+obj.message+"</strong></div>");
					setTimeout(function(){
							jQuery('.result').html('');
								location.reload();
						
						}, 2000);
					}
					else{
						if(obj.status==false){
							$(".result").show();
							jQuery('.result').html("<div class='alert alert-danger alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong>"+obj.message+"</strong></div>");
							jQuery('#messageError').css("display", "block");
							setTimeout(function(){
							jQuery('.result').html('');
								location.reload();
						
						}, 2000);
						}
						
					}
					}
				});
		}
	});
	$(".reply").on("click",function(e){
	//	$(".result").hide();
		$("#message").val("");
		$("#messageError").text("");
		$("#contact_user_id").val($(this).data("id"));
		$("#customer_details_modal").modal("toggle");
	});
		console.log($(".switch-input").attr("data-on"));
		function useractivedeactive($id,$status){

			if($status=="0")
			{
				$(".switch").css("width", "112px");
			}
		host_url = "/development/wemarkthespot/";
		var status =$status; //$(this).prop('checked') == true ? 1 : 0; 

		var token = $("meta[name='csrf-token']").attr("content");
		var user_id =$id; //$(this).data('id'); 
		
		
		
			$.ajax({
				type: "POST",
				dataType: "json",
				url: host_url+'contact_status',
				data: {'_token':  token,'status': status, 'id': user_id},
				success: function(data){
				//	var obj = JSON.parse(data);
			
					if(data.status==true)
					{
						jQuery('.result').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong>Success - </strong> "+data.message+"</div>");

						 setTimeout(function(){
					  jQuery('.result').html('');
					  window.location.reload();
				  }, 3000);
					}
				 
				}
			});
		
		
	}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\development\wemarkthespot\resources\views/Pages/fitnesstrainers/manager_contactus.blade.php ENDPATH**/ ?>