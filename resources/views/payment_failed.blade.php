<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Payment Failed</title>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<style>
	body{
		background: #eef5f9;
		font-family: 'Poppins', sans-serif;
	}
	.table-padding {
	    max-width: 600px;
	    width: 100%;
	    padding-top: 30px;
	    background: #fff;
	    padding-bottom: 30px;
	    border-radius: 12px;
	}
	.top-table{
		max-width: 600px;
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

</style>

<body>

	<section>
        <table class="top-table">
           <tr>
                <td class="td-class" class="td-class">
                    <table class="table-padding">
                        <tr align="center">
                            <td class="text-primary" style="color: #F16522; font-size: 14px; line-height: 16px; vertical-align: top;">
                                <img src="{{asset('/public/assets/cancel.png')}}" alt="GO" width="50" style="border: 0; font-size: 0; margin: 0; max-width: 100%; padding: 0;">
                            </td>
                        </tr>
                        <tr align="center">
                            <td class="text-top" >Payment Failed</h1>
                            </td>
                        </tr>
					<tr align="left" style="display:none">
                            <td class="td-class">
                                <p >
                                    Hi [name], 
                                </p>
                            </td>
                        </tr>
                        <tr align="left">
                            <td class="td-class">
                                <p >Your transaction was Failed!</p>
                                <br>
                                <p style="display:none"><strong>Payment Details:</strong><br/>Amount: €$moneyFormatter.format(${amount}) <br/>Account: ${accountNumber}.<br/></p>
                                    <br>
                                <p style="display:none">We advise to keep this email for future reference.&nbsp;&nbsp;&nbsp;&nbsp;<br/></p>
                            </td>
                        </tr>
                        <tr height="30">
                            <td class="td-border"></td>
                        </tr>
                        <tr align="center" style="display:none">
                            <td class="td-class pd-top">
                                <p ><strong>Transaction reference: ${authorizationCode}</strong></p>
                                <p >Order date: [time] [date]</p>
                                <p ></p>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
        </table>
	</section>

</body>
</html>