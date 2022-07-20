@extends('layouts.admin')
@section('content')


<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 col-12 align-self-center">
            <h4 class="text-themecolor mb-0">Donation History</h4>
		</div>
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <ol class="breadcrumb mb-0 p-0 bg-transparent fa-pull-right">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Donation History</li>
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
					<div class="card-body">
						<div class="table-responsive">
							  <div class="result"></div>
							<table id="zero_config" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>Id.</th>
                                        <th>Order Id</th>
                                        <th>Transaction Id</th>
										<th>Customer Name</th>
                                        <th>User Name</th>
										<th>Email</th>

										<th>Amount</th>
										<Th>Created Date</Th>
									</tr>
								</thead>
								<tbody>
								@foreach ($donationhistory as $user) 
								
									<tr>
										<td>{{ $user->id }}</td>
                                        <td>{{$user->order_id}}</td>
                                        <td>{{$user->transaction_id}}</td>
                                        <td>{{$user->customer_name}}</td>
										<td style="display: table-cell;">
											<a href="javascript:void(0)" class="link">
												<span class="ml-2">{{ $user->business_name }}</span>
											</a>
										</td>
										<td>{{$user->billing_email}}</td>
                                        <td>{{$user->plan_price}}</td>
												<td>{{ $user->created_at }}</td>
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
<script>
		$(document).ready(function () {


var data_table = $('#zero_config').DataTable();
data_table.order( [0,'desc'] ).draw();

});
</script>


@stop