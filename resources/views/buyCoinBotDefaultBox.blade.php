<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<h2>Add Setting</h2>

<form action="{{url('coin/bot/box')}}" method="post">
    @csrf
    <label for="symbol">symbol:</label><br>
    <input style="text-transform: uppercase;" type="text" id="symbol" name="symbol"><br>
    <label for="budget">budget:</label><br>
    <input type="text" id="budget" name="budget" value="1000"><br><br>
    <label for="purchase_amount">purchase_amount:</label><br>
    <input type="text" id="purchase_amount" name="purchase_amount" value="100"><br><br>
    <label for="buy_percent">buy_percent:</label><br>
    <input type="text" id="buy_percent" name="buy_percent" value="0.5"><br><br>
    <label for="sell_percent">sell_percent:</label><br>
    <input type="text" id="sell_percent" name="sell_percent" value="0.2"><br><br>
    <input type="submit" value="Submit">
</form>
</body>
</html>
