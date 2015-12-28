<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Innoto">

    <title>Sign in Geozzy Admin</title>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="/vendor/bower/html5shiv/dist/html5shiv.js"></script>
        <script src="/vendor/bower/respond/dest/respond.min.js"></script>
    <![endif]-->
    {$client_includes}

</head>

<body>

  <div class="container">
    <div class="row">
      <div class="col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">
          <div class="panel-heading">
              <h3 class="panel-title">Sign In</h3>
          </div>
          <div class="panel-body">
            {$loginHtml}
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
