<?php
/* clase MiEcobici */
include('../php/miecobici.php');

/* inicializamos y procesamos request */
$miEcobici = new MiEcobici($_SERVER);
$miEcobici->request($_REQUEST);
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Mi Ecobici</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
    <!-- css y js de bootstrap ----------------------------------- -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- css local ----------------------------------------------- -->
    <link href="css/miecobici.css" rel="stylesheet">
    <link href="css/about.css" rel="stylesheet">
    <link href="css/home.css" rel="stylesheet">
    <link href="css/results.css" rel="stylesheet">
    <!-- analytics ----------------------------------------------- -->
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
      ga('create', 'UA-47828704-1', 'auto');
    </script>
  </head>
  <body>
  <!-- inicio miecobici -------------------------------------------- -->
  <?php $miEcobici->html(); ?>
  <!-- fin miecobici ------------------------------------------- -->
  </body>
</html>
