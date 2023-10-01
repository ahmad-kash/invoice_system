<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>حصل خطأ ما</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body>
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh">
        <section class="content">
            <div class="error-page">
                <h2 class="headline text-danger"> 404</h2>
                <div class="error-content">
                    <h3><i class="fas fa-exclamation-triangle text-danger"></i> حصل مشكلة ما </h3>
                    <p>
                        لم نستطع القيام بالعملية المطلوبة
                        العودة الى
                    <div>
                        <a href="{{ route('home') }}">الصفحة الرئيسية</a>
                    </div>
                    </p>
                </div>
            </div>
        </section>
    </div>
</body>

</html>
