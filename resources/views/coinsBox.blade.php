<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="refresh" content="5">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            width: 300px;
            height: 460px;
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
            <form class="sell" action="{{ url('/coin/sell/'. $value['id'])}}" method="POST">
                @csrf
                <input type="submit" value="sell"/>
            </form>
            </p>
            <p>
            <form class="sellManual" action="{{ url('/coin/sell/manual/'. $value['id'])}}" method="POST">
                @csrf
                <input type="submit" value="sell manual"/>
            </form>
            </p>
            <p>
            <form class="delete" action="{{ url('/coin/delete/'. $value['id'])}}" method="POST">
                <input type="hidden" name="_method" value="DELETE">
                @csrf
                <input type="submit" value="Delete"/>
            </form>
            </p>
        </div>
    @endforeach
</div>

<script>
    $(".sell").on("submit", function () {
        return confirm("Are you sure to sell?");
    });
    $(".sellManual").on("submit", function () {
        return confirm("Are you sure to sell manual?");
    });
    $(".delete").on("submit", function () {
        return confirm("Are you sure to delete ?");
    });
</script>
</body>
</html>


