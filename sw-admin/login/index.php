<?PHP session_start();
if(!empty($_SESSION['SESSION_USER']) && !empty($_SESSION['SESSION_ID'])){
            header('location:../');
 exit;}
 else{
     require_once'../../sw-library/sw-config.php';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login Administrator</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="Login">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">

    <!-- Icons -->
    <link rel="shortcut icon" href="../../sw-content/favicon.png">
    <link rel="apple-touch-icon" href="../../sw-content/favicon.png">

  <link rel="stylesheet" href="../sw-assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="../sw-assets/css/AdminLTE.min.css">
  <link rel="stylesheet" href="../sw-assets/css/skin-blue-light.css">
  <link rel="stylesheet" href="../sw-assets/css/font-awesome.css">
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<?php echo'
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="./"><img src="../../sw-content/'.$site_logo.'"  oncontextmenu="return false;" height="50"></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">';
  switch(@$_GET['op']){ 
    default:
    echo'
    <p class="login-box-msg">Silahkan masukkan username dan password :</p>
      <form class="login">
        <div class="form-group has-feedback">
          <input type="text" id="username" name="username" class="form-control" placeholder="Username">
          <span class="fa fa-user form-control-feedback"></span>
        </div>

        <div class="form-group has-feedback">
          <input type="password" id="password" name="password" class="form-control" placeholder="Password">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <hr>
        <button type="submit" class="btn btn-primary btn-block btn-flat">Login to Admin</button>
      </form>
         
     
      <hr>
      <a href="./?op=forgot">Lupa Password</a>';


    break;
    case 'forgot':
    echo'
     <p class="login-box-msg">Silahkan masukkan email untuk meresset password:</p>
      <form class="forgot">
        <div class="form-group has-feedback">
          <input type="text" name="email" class="form-control" placeholder="Masukkan email">
          <span class="fa fa-user form-control-feedback"></span>
        </div>

        <hr>
        <div class="row">
          <div class="col-xs-12">
            <button type="submit" class="btn btn-primary btn-block">Resset Password</button>
          </div>
          <!-- /.col -->
        </div>
      </forgot>
      <hr>
      <a href="./">Login</a>
      ';
    break;
  }
  echo'
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->';?>


<footer class="text-muted text-center">
    <small></span><span id="credits">DEWI NUR WIJAYANTI - ABSENSI DOJO HKC</span>
    </small>
</footer>

        <script src="../sw-assets/js/jquery.min.js"></script>
        <script src="../sw-assets/js/bootstrap.min.js"></script>
        <script src="../sw-assets/js/adminlte.js"></script>
        <script src="../sw-assets/js/demo.js"></script>
        <script src="../sw-assets/js/sweetalert.min.js"></script>
        <script src="./jquery-login.js"></script>
    </body>
</html>
<?php }?>