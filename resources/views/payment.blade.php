<html>
<head>
    <script src="https://test-bankofceylon.mtf.gateway.mastercard.com/static/checkout/checkout.min.js" data-error="errorCallback" data-cancel="cancelCallback"></script>
    @if($session['result'] == 'SUCCESS')
    <script type="text/javascript">
        function errorCallback(error) {
            console.log(JSON.stringify(error));
        }
        function cancelCallback() {
            console.log('Payment cancelled');
        }
        Checkout.configure({
            session: {
                id: '{{$session['session']['id']}}',
                version: '{{$session['session']['version']}}'
            }
        });
    </script>
    @endif
    <title>Payment</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: #f2f2f2;
            border: 1px solid #ccc;
            padding: 20px;
            text-align: center;
            max-width: 400px;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<div class="container" >
    <span>
        <h1>Payment Information</h1>
         @if($session['result'] == 'SUCCESS')
          <p>Total Amount: {{$session_request['order']['amount']}} LKR</p>
        @else
          <p>ERROR: {{$session['result']}}</p>
        @endif
    </span>
    @if($session['result'] == 'SUCCESS')
        <div >
            <input type="button" class="btn" value="Pay with BOC" onclick="Checkout.showPaymentPage();" />
        </div>
    @endif

</div>

</body>
</html>
