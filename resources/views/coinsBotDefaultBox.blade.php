<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="refresh" content="5">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            width: 320px;
            height: 315px;
            text-align: center;
            margin: 5px;
            font-family: arial;
            float: left;
        }

        .price {
            color: grey;
            font-size: 22px;
        }

        .card input {
            border: none;
            outline: 0;
            padding: 12px;
            color: white;
            background-color: #000;
            text-align: center;
            cursor: pointer;
            width: 100%;
            font-size: 18px;
        }

        .card button:hover {
            opacity: 0.7;
        }

        .greenCandle {
            background-color: green;
        }
    </style>
</head>
<body>
<a href="{{url('/coin/bot/box/setting')}}"><h3>add coin to setting</h3></a>
<h3>allProfit:{{$allProfit}}</h3>
<h3>profitPercentDaily:{{$profitPercentDaily}}</h3>
<h3>buyBot:{{$buyBot}}</h3>
<div style="width: 100%; float: right; height: 100vh;">
    @foreach($coinsBox as $value)
        <div class="card greenCandle">
            <p>symbol:{{$value['symbol']}}</p>
            <p>budget:{{$value['budget']}}</p>
            <p>purchase_amount:{{$value['purchase_amount']}}</p>
            <p>buy_percent:{{$value['buy_percent']}}</p>
            <p>sell_percent:{{$value['sell_percent']}}</p>
            <p>profit_all:{{$value['profit_percent']}}</p>
            <p>profit_daily:{{$value['profit_percent_daily']}}</p>
            <p>buyBot:{{$value['buyBot']}}</p>
            <p>countAll:{{$value['count_all']}}</p>
        </div>
    @endforeach
</div>

</body>
</html>


