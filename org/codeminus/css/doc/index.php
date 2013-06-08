<?php
$contents = array(
    'home' => false,
    'base' => false,
    'containers' => false,
    'navs' => false,
    'forms' => false,
    'tables' => false,
    'media' => false,
    'glyphicons' => false,
    'famfamfam' => false,
);
if (isset($_GET['c'])) {
  $contents[$_GET['c']] = 'selected';
} else {
  $contents['home'] = 'selected';
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>Codeminus CSS Library</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../img/favicon.ico">
    <link rel="stylesheet" href="../codeminus.css" />
  </head>
  <body>
    <div class="container-header">
      <div class="container-centered">
        <img src="../../img/cmf-medium.png" />
      </div>
    </div>
    <div class="container-centered">
      <ul class="nav nav-hlist">
        <li><a href="?c=home" class="<?php echo $contents['home'] ?>">Get Started</a></li>
        <li><a href="?c=base" class="<?php echo $contents['base'] ?>">Base</a></li>
        <li><a href="?c=containers" class="<?php echo $contents['containers'] ?>">Containers</a></li>
        <li><a href="?c=navs" class="<?php echo $contents['navs'] ?>">Navigators</a></li>
        <li><a href="?c=forms" class="<?php echo $contents['forms'] ?>">Forms</a></li>
        <li><a href="?c=tables" class="<?php echo $contents['tables'] ?>">Tables</a></li>
        <li><a href="?c=media" class="<?php echo $contents['media'] ?>">Media</a></li>
        <li>
          <a href="#" class="nav-dropdown <?php echo $contents['glyphicons'].$contents['famfamfam'] ?>">
            Icons&blacktriangledown;
          </a>
          <ul class="nav nav-vlist nav-dropdown-menu">
            <li><a href="?c=glyphicons">Glyphicons</a></li>
            <li><a href="?c=famfamfam">famfamfam</a></li>
          </ul>
        </li>
      </ul>
      <div class="separator"></div>
      <?php
      if ($contents['home']) {
        require 'home.php';
      }
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
      if ($contents['glyphicons']) {
        require 'glyphicons.php';
      }
      if ($contents['famfamfam']) {
        require 'famfamfam.php';
      }
      ?>
      <div class="container-upper-foot">
        <ul class="nav nav-simple inline">
          <li class="header">Codeminus</li>
          <li><a href="http://github.com/codeminus" target="blank">on GitHub</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
      </div>
      <div class="container-lower-foot clearfix">
        <span class="float-right">&copy; 2013 Codeminus. All rights reserved</span>
      </div>
    </div>
    <script src="assets/jquery.js"></script>
    <script type="text/javascript" src="../../js/codeminus.js"></script>
  </body>
</html>