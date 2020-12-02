<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<h2>buy Forms</h2>

<form action="{{url('buy')}}" method="post">
    @csrf
    <label for="user">user:</label><br>
    <select name="user" id="user">
        <option value="yaser">yaser</option>
        <option value="aras">aras</option>
        <option value="zeinab">zeinab</option>
    </select>
    <br>
    <label for="symbol">symbol:</label><br>
    <input type="text" id="symbol" name="symbol"><br>
    <label for="amount">amount:</label><br>
    <input type="text" id="amount" name="amount"><br><br>
    <label for="total">total:</label><br>
    <input type="text" id="total" name="total"><br><br>
    <input type="submit" value="Submit">
</form>


</body>
</html>
