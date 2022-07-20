<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Payment Success</title>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<style>
	body{
		background: #eef5f9;
		font-family: 'Poppins', sans-serif;
	}
	.table-padding {
	    /* max-width: 600px; */
	    width: 100%;
		margin: 0 auto;
	    padding-top: 30px;
	    background: #fff;
	    padding-bottom: 30px;
	    border-radius: 12px;
	}
	.top-table{
		width: 100%;
		align-items: center;
		background: #eef5f9;
		margin: 0 auto;
	}
	.td-class {
	    color: #464646;
	    font-size: 14px;
	    line-height: 16px;
	    vertical-align: top;
	    padding: 0px 40px;
	}
	td.td-class p {
	    font-size: 17px;
	    line-height: 30px;
	    margin: 2px;
	}
	td.text-top {
	    font-size: 20px;
	    padding-top: 16px;
	    padding-bottom: 30px;
	    font-weight: 600;
	}
	.pd-top{
		padding-top: 20px;
	}
	.td-border{
		border-bottom: 1px solid #D3D1D1;
	}
	.payment-text{
		text-align: center;
		font-size: 22px;
		padding-top: 10px;
		font-weight: 500;
	}
	.img-center{
		text-align: center ;
	}


</style>

<body>

	<section style="    padding: 60px 20px;">
        <div class="top-table">
                    <div class="table-padding">
						<div class="text-primary img-center" style="color: #F16522; font-size: 14px; line-height: 16px; vertical-align: top;">
							<img src="http://dgtlmrktng.s3.amazonaws.com/go/emails/generic-email-template/tick.png" alt="GO" width="50" style="border: 0; font-size: 0; margin: 0; max-width: 100%; padding: 0;">
						</div>

                        <div class="payment-text">Payment received</div>
                        <div >
                            <div class="td-class">
							<p >Hi {{$paymentDetails->customer_name}},  </p>
                                <p >Your transaction was successful!</p>
                                <p ><strong>Payment Details:</strong><br/>Amount: {{$paymentDetails->plan_price}} </p>
                                <p >We advise to keep this email for future reference.&nbsp;&nbsp;&nbsp;&nbsp;<br/></p>
							</div>
						</div>
                        <div height="30">
                            <div class="td-border"></div>
						</div>
                        <div >
                            <div class="td-class pd-top">
                                <p ><strong>Transaction reference: {{$paymentDetails->order_id}}</strong></p>
                                <p >Order date: {{$paymentDetails->created_at}}</p>
							</div>
						</div>
					</div>
		</div>
	</section>

</body>
</html>