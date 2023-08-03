<html>
<head>
    <title>Payment Summary</title>
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
        <h1>Payment Summary</h1>
         @if($resultData->status ==='COMPLETED')
            <p>Total Amount: {{$resultData->amount}} LKR</p></br>
            <p>Reference No: {{$resultData->transaction_Reference_id}}</p>
        @else
          <p>ERROR: Payment Process Error</p>
        @endif
    </span>
</div>

</body>
</html>
