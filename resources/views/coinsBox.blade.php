<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="refresh" content="5">
    <style>
        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            width: 300px;
            height: 400px;
            text-align: center;
            margin: 5px;
            font-family: arial;
            float: left;
        }

        .price {
            color: grey;
            font-size: 22px;
        }

        .card button {
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

        .redCandle {
            background-color: red;
        }

        .yellowCandle {
            background-color: yellow;
        }

        .greenCandle {
            background-color: green;
        }
    </style>
</head>
<body>
<h3>allProfit:{{$allProfit}}</h3>
<h3>allProfitPercent:{{$allProfitPercent}}</h3>
<div style="width: 100%; float: right; height: 100vh;">
    @foreach($coinsBox as $value)
        <div class="card {{ $value['profit_percent'] < 0 ? 'redCandle' : 'yellowCandle' }}
        {{ $value['profit_percent'] > 3 ? 'greenCandle' : '' }}">
            <p>symbol:{{$value['symbol']}}</p>
            <p>amount:{{$value['amount']}}</p>
            <p>total:{{$value['total']}}</p>
            <p>current_price:{{$value['current_price']}}</p>
            <p>current_total:{{$value['current_total']}}</p>
            <p>profit:{{$value['profit']}}</p>
            <p>profit_percent:{{$value['profit_percent']}}</p>
            <p>price_change_percent:{{$value['price_change_percent']}}</p>
            <p>
                <button>sell</button>
            </p>
            <p>
                <button>delete</button>
            </p>
        </div>
    @endforeach
</div>
</body>
</html>


