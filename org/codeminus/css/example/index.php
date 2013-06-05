<?php
$contents = array(
    'base' => false,
    'containers' => false,
    'navs' => false,
    'forms' => false,
    'tables' => false,
    'medias' => false,
);
if (isset($_GET['c'])) {
  $contents[$_GET['c']] = 'selected';
} else {
  $contents['base'] = 'selected';
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>Codeminus CSS Library</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../img/favicon.ico">
    <link rel="stylesheet" href="../base.css" />
    <link rel="stylesheet" href="../containers.css" />
    <link rel="stylesheet" href="../navs.css" />
    <link rel="stylesheet" href="../forms.css" />
  </head>
  <body>
    <div class="container-header">
      <div class="container-centered">
        <img src="../../img/cmf-medium.png" />
      </div>
    </div>
    <div class="container-centered">
      <ul class="nav nav-hlist">
        <li><a href="?c=base" class="<?php echo $contents['base'] ?>">Base</a></li>
        <li><a href="?c=containers" class="<?php echo $contents['containers'] ?>">Containers</a></li>
        <li><a href="?c=navs" class="<?php echo $contents['navs'] ?>">Navigators</a></li>
        <li><a href="?c=forms" class="<?php echo $contents['forms'] ?>">Forms</a></li>
        <li><a href="?c=tables" class="<?php echo $contents['tables'] ?>">Tables</a></li>
        <li><a href="?c=medias" class="<?php echo $contents['medias'] ?>">Medias</a></li>
      </ul>
      <div class="separator"></div>
      <?php
      if ($contents['base']) {
        require 'base.php';
      }
      if ($contents['containers']) {
        require 'containers.php';
      }
      if ($contents['navs']) {
        require 'navs.php';
      }
      if ($contents['forms']) {
        require 'forms.php';
      }
      ?>
      <div class="container-upper-foot">
        <ul class="nav nav-simple">
          <li class="header">Codeminus</li>
          <li><a href="#">About us</a></li>
          <li><a href="#">Join us</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
        <ul class="nav nav-simple">
          <li class="header">Products</li>
          <li><a href="#">Admos</a></li>
          <li><a href="#">Codeminus Framework</a></li>
          <li><a href="#">Odel</a></li>
          <li><a href="#">WhoWorks</a></li>
          <li><a href="#">Domain Registration</a></li>
          <li><a href="#">Domain Hosting</a></li>
        </ul>
      </div>
      <div class="container-lower-foot clearfix">
        <ul class="nav nav-simple inline" style="width: auto;">
          <li><a href="#">Menu Item</a></li>
          <li><a href="#">Menu Item</a></li>
          <li><a href="#">Menu Item</a></li>
          <li><a href="#">Menu Item</a></li>
        </ul>
        <span class="float-right">&copy; 2013 Codeminus. All rights reserved</span>
      </div>
    </div>
  </body>
</html>