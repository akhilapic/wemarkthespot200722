@extends('layouts.admin')
@section('content')


<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h4 class="text-themecolor mb-0">Payment Details</h4>
		</div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <ol class="breadcrumb mb-0 p-0 bg-transparent fa-pull-right">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Payment Details</li>
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
					    <h4 class="card-title mb-0">Payment Details</h4> 
						<!-- <a href="{{ url('/admin_add_plans') }}" class="btn btn-info btn-sm">
							Add Plan
						</a> -->
					</div>
					<div class="card-body">
						<div class="table-responsive">
							  <div class="result"></div>
							<table id="zero_config" class="table table-striped table-bordered">
								<thead>
									<tr>
                                        <th>Sl No.</th>
										<th>Plan Name</th>
										<th>Cust. Name</th>
										<th>Billing Email</th>
										<th>Transaction Id</th>
                                        <th>Status</th>
										<th>Start Date</th>
										<th>End Date</th>
                                        <th>Created At</th>
									</tr>
								</thead>
								<tbody>
								@foreach ($paymants as $c=>$user) 
									<tr>
										<td>{{ $c+1 }}</td>
										<td style="display: table-cell;">
											

											<?php
											$plan_nm = '';
											if($user->plan_name=='weekBusiness'){
												$plan_nm = 'Business of the Week';
											}else if($user->plan_name=='featuredBusiness'){
												$plan_nm = 'Featured Business';
											}else if($user->plan_name=='weekAndFeatured'){
												$plan_nm = 'Business of the Week & Featured Business';
											}

											?>

											<span class="ml-2">{{ $plan_nm }}</span>
										</td>

                                        <td style="display: table-cell;">
										
												<span class="ml-2">{{ $user->customer_name }}</span>
											
										</td>

                                        <td style="display: table-cell;">
											
												<span class="ml-2">{{ $user->billing_email }}</span>
											
										</td>

                                        <td style="display: table-cell;">
									
												<span class="ml-2">{{ $user->transaction_id }}</span>
											
										</td>

                                        <td style="display: table-cell;">
								
												<span class="ml-2">
                                                    <?php
                                                        if($user->payment_status == 'succeeded'){
                                                            echo "<span style='color:green;'><b>$user->payment_status</b></span>";
                                                        }else{
                                                            echo "<span style='color:red;'><b>$user->payment_status</b></span>";
                                                        }
                                                    ?>
                                                </span>
										
										</td>

                                        <td style="display: table-cell;">
											
												<span class="ml-2">{{ $user->startDate }}</span>
										
										</td>

                                        <td style="display: table-cell;">
											
												<span class="ml-2">{{ $user->endDate }}</span>
											
										</td>

                                        <td style="display: table-cell;">
										
												<span class="ml-2">{{ $user->created_at }}</span>
											
										</td>
										
										<!-- <td>
											<div class="table_action" style="width: 103px">
                                                <a style="display:" href="{{ url('subscriptions_edit',$user->id) }}" class="btn btn-info btn-sm list_edit">
													<i class="mdi mdi-lead-pencil"></i>
												</a> 
											</div>	  
										</td> -->
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
<!-- <div class="modal fade" id="customer_details_modal" tabindex="-1" aria-labelledby="exampleModalLabel1">
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
                <button type="button" class="btn btn-light-danger text-danger font-weight-medium" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div> -->

<script type="text/javascript">

	// function useractivedeactive($id,$status)
    // {
	// 	host_url = "/development/wemarkthespot/";
	// 	var status =$status; //$(this).prop('checked') == true ? 1 : 0; 

	// 	var token = $("meta[name='csrf-token']").attr("content");
	// 	var user_id =$id; //$(this).data('id'); 
	
    //     $.ajax({
    //         type: "POST",
    //         dataType: "json",
    //         url: host_url+'category_status',
    //         data: {'_token':  token,'status': status, 'id': user_id},
    //         success: function(data){
    //         //	var obj = JSON.parse(data);
        
    //             if(data.status==true)
    //             {
    //                 jQuery('.result').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong>Success - </strong> "+data.message+"</div>");

    //                 setTimeout(function(){
    //                 jQuery('.result').html('');
    //                 window.location.reload();
    //             }, 3000);
    //             }
                
    //         }
    //     });
	// }

</script>
@stop