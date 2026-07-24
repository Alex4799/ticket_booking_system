<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCL Cinema</title>

    <!-- bootstrap css  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css">

        <!-- font-awesome  -->
        <link rel="stylesheet" href="{{asset('fontawesome/css/all.min.css')}}"/>

    <!-- css  -->
    <link rel="stylesheet" href="{{{asset('admin/css/style.css')}}}">
</head>
<body id="auth-bg">
    @yield('content')
</body>

    <script src="{{asset('fontawesome/js/all.min.js')}}"></script>

    <!-- bootstrap js  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- js  -->
    <script src="{{asset('admin/js/script.js')}}"></script>
</html>
