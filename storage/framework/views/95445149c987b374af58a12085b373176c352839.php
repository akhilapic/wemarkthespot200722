
<?php $__env->startSection('content'); ?>


<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h4 class="text-themecolor mb-0">PromoCode </h4>
		</div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <ol class="breadcrumb mb-0 p-0 bg-transparent fa-pull-right">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">PromoCode</li>
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
					    <h4 class="card-title mb-0">PromoCode List</h4> 
						<a href="<?php echo e(url('/add_promocode')); ?>" class="btn btn-info btn-sm">
							Add PromoCode
						</a>               
					</div>
					<div class="card-body">
						<div class="table-responsive">
							  <div class="result"></div>
							<table id="zero_config" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>Id.</th>
										<th>Category Name</th>
										<th>Name</th>
										<th>Amount or (%)</th>
										<th>Promotion type</th>
										<th>Start Valid Date</th>
										<th>End Valid Date</th>
										<Th>Created Date</Th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
								<?php $__currentLoopData = $promocodelist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
								
									<tr>
										<td><?php echo e($user->id); ?></td>
										<td style="display: table-cell;">
											<a href="javascript:void(0)" class="link">
												<span class="ml-2"><?php echo e($user->category_name); ?></span>
											</a>
										</td>

										<td style="display: table-cell;">
											<a href="javascript:void(0)" class="link">
												<span class="ml-2"><?php echo e($user->name); ?></span>
											</a>
										</td>

										<td><?php echo e($user->amount); ?></td>
										<?php if($user->promotion_type==1): ?>
										<td>Flat</td>
										<?php else: ?>
										<td>Percentage</td>
										<?php endif; ?>
										<td><?php echo e($user->start_valid_date); ?></td>
										<td><?php echo e($user->end_valid_date); ?></td>
										
												<td><?php echo e($user->created_at); ?></td>
										<td>
											<div class="table_action" style="width: 103px">
												<!-- <a href="<?php echo e(url('/category-view')); ?>/<?php echo e($user->id); ?>" class="btn btn-success btn-sm list_view infoU"  data-id='"<?php echo e($user->id); ?>"' data-bs-whatever="@mdo">
													<i class="mdi mdi-eye"></i>
												</a>   -->
												<a href="<?php echo e(route('promocode_delete',$user->id)); ?>" class="btn  btn-danger btn-sm list_delete " onclick="return confirm('Are you sure delete this promocode')">
													<i class="mdi mdi-delete"></i>
												</a> 
												
												<a style="display: " href="<?php echo e(url('promocode_edit',$user->id)); ?>" class="btn btn-info btn-sm list_edit">
													<i class="mdi mdi-lead-pencil"></i>
												</a> 
												<span class="status" style="display:none">
													<label class="switch">

														<input data-id="<?php echo e($user->id); ?>" class=" category_statuss switch-input" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" <?php echo e($user->status ? 'checked' : ''); ?>>
														<span class="switch-label" data-on="Active" data-off="Deactive"></span> 
														<span class="switch-handle"></span> 
													</label>
												</span>

													<span class="status">
													<label class="switch">
														<?php if($user->status==1): ?>
														<input data-id="<?php echo e($user->id); ?>" class="  switch-input" onchange="useractivedeactive(<?php echo e($user->id); ?>,'0');" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive"  >
														<span class="switch-label" data-on="Active" data-off="Deactive"></span> 
														<span class="switch-handle"></span> 
														<?php else: ?>
														<input data-id="<?php echo e($user->id); ?>" class="  switch-input" onchange="useractivedeactive(<?php echo e($user->id); ?>,'1');" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Deactive" data-off="InActive" checked>
														<span class="switch-label" data-on="Active" data-off="Deactive"></span> 
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


<!-- This page plugin CSS -->

<!-- Blog Details -->


<script type="text/javascript">
		$(document).ready(function () {


			var data_table = $('#zero_config').DataTable();
			data_table.order( [0,'desc'] ).draw();

			});
		function useractivedeactive($id,$status){

		host_url = "/development/wemarkthespot/";
		var status =$status; //$(this).prop('checked') == true ? 1 : 0; 

		var token = $("meta[name='csrf-token']").attr("content");
		var user_id =$id; //$(this).data('id'); 
		
		
		
			$.ajax({
				type: "POST",
				dataType: "json",
				url: host_url+'promocode_status',
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
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\development\wemarkthespot\resources\views/Pages/promocode_list.blade.php ENDPATH**/ ?>