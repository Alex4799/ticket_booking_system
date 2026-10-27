<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form id="pgwForm" method="POST" action="https://pgw.transactease.com.mm/Payments/Request">
        @foreach($transacteaseData as $key => $value)
        <div>
            <input type="text" name="{{ $key }}" value="{{ $value }}">
        </div>
        @endforeach
    </form>
    <script>
        // document.getElementById('pgwForm').submit();
    </script>

</body>
</html>
