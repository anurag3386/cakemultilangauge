<!DOCTYPE html>
<html>
<head>
  <?php echo $this->Html->charset() ?>
  <?= $this->Html->meta('favicon.ico','/favicon.ico',    ['type' => 'icon']);
?>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Astrowow</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->

  <link rel="stylesheet" href="<?php echo $this->request->webroot; ?>bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->


  <link rel="stylesheet" href="<?php echo $this->request->webroot; ?>plugins/select2/select2.min.css">

  <link rel="stylesheet" href="<?php echo $this->request->webroot; ?>dist/css/AdminLTE.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo $this->request->webroot; ?>dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo $this->request->webroot; ?>plugins/iCheck/flat/blue.css">
  <!-- Morris chart -->

  <!--link rel="stylesheet" href="<?php echo $this->request->webroot; ?>plugins/morris/morris.css"-->

  <!-- jvectormap -->
  <link rel="stylesheet" href="<?php echo $this->request->webroot; ?>plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="<?php echo $this->request->webroot; ?>plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo $this->request->webroot; ?>plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?php echo $this->request->webroot; ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

  <link rel="stylesheet" href="<?php echo $this->request->webroot; ?>plugins/timepicker/bootstrap-timepicker.min.css">

  <link rel="stylesheet" href="<?php echo $this->request->webroot; ?>plugins/validation/validationEngine.jquery.css">

  <link rel="stylesheet" href="<?php echo $this->request->webroot; ?>plugins/colorbox/colorbox.css">



  <link rel="stylesheet" href="<?php echo $this->request->webroot; ?>dist/css/custom_style.css">

<!-- jQuery 2.2.3 -->
<script src="<?php echo $this->request->webroot; ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php echo $this->element('admin_header'); ?>
  <?php echo $this->element('admin_sidebar'); ?>
 

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Content Header (Page header) -->
   <!--  <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section> -->

    <!-- Main content -->
    <section class="content">
      
     <?php echo $this->Flash->render(); ?>

     <?php echo $this->fetch('content'); ?>

  </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; <?php echo date('Y'); ?>
    <?php echo $this->Html->link('Astrowow','/'); ?></strong> All rights reserved.
  </footer>

  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

<script src="<?php echo $this->request->webroot; ?>plugins/select2/select2.full.min.js"></script>

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo $this->request->webroot; ?>bootstrap/js/bootstrap.min.js"></script>


<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>

<!-- Sparkline -->
<script src="<?php echo $this->request->webroot; ?>plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="<?php echo $this->request->webroot; ?>plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo $this->request->webroot; ?>plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo $this->request->webroot; ?>plugins/knob/jquery.knob.js"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo $this->request->webroot; ?>plugins/daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="<?php echo $this->request->webroot; ?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo $this->request->webroot; ?>plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>

<script src="<?php echo $this->request->webroot; ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="<?php echo $this->request->webroot; ?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo $this->request->webroot; ?>plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo $this->request->webroot; ?>dist/js/app.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo $this->request->webroot; ?>dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo $this->request->webroot; ?>dist/js/demo.js"></script>
<script src="<?php echo $this->request->webroot; ?>plugins/validation/jquery.validationEngine.js"></script>
<script src="<?php echo $this->request->webroot; ?>plugins/validation/languages/jquery.validationEngine-en.js"></script>
<script src="<?php echo $this->request->webroot; ?>plugins/colorbox/jquery.colorbox-min.js"></script>
<script src="<?php echo $this->request->webroot; ?>js/common.js"></script>
</body>
</html>
