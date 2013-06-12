<?php
$contents = array(
    'getstarted' => false,
    'base' => false,
    'containers' => false,
    'navs' => false,
    'forms' => false,
    'tables' => false,
    'images' => false,
    'js' => false,
    'glyphicons' => false,
    'famfamfam' => false,
);
if (isset($_GET['c'])) {
  $contents[$_GET['c']] = 'active';
} else {
  $contents['home'] = 'active';
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>Codeminus Front-end Framework</title>
    <meta name="author" content="Codeminus.org"></meta>
    <meta name="description" content="Codeminus: Less code, fast development, more Results. PHP MVC Framework and Front-End Framework."></meta>
    <link rel="shortcut icon" type="image/x-icon" href="../../img/favicon.ico">
    <link rel="stylesheet" href="../codeminus.css" />
    <link rel="stylesheet" href="../famfamfam.css" />
    <script src="../../js/jquery.js"></script>
  </head>
  <body>
    <div class="container-header">
      <div class="container-centered">
        <img src="../../img/codeminus-front-end-framework-300x73.png" />
      </div>
    </div>
    <div class="container-centered">
      <ul class="nav nav-hlist">
        <li><a href="?c=getstarted" class="<?php echo $contents['getstarted'] ?>">Get Started</a></li>
        <li><a href="?c=base" class="<?php echo $contents['base'] ?>">Base</a></li>
        <li><a href="?c=containers" class="<?php echo $contents['containers'] ?>">Containers</a></li>
        <li><a href="?c=navs" class="<?php echo $contents['navs'] ?>">Navigators</a></li>
        <li><a href="?c=forms" class="<?php echo $contents['forms'] ?>">Forms</a></li>
        <li><a href="?c=tables" class="<?php echo $contents['tables'] ?>">Tables</a></li>
        <li><a href="?c=images" class="<?php echo $contents['images'] ?>">Images</a></li>
        <li><a href="?c=js" class="<?php echo $contents['js'] ?>">Javascript</a></li>
        <li>
          <a href="#" class="dropdown <?php echo $contents['glyphicons'] . $contents['famfamfam'] ?>">
            Icons &blacktriangledown;
          </a>
          <ul class="nav nav-vlist dropdown-menu rounded">
            <li><a href="?c=glyphicons">Glyphicons</a></li>
            <li><a href="?c=famfamfam">famfamfam</a></li>
          </ul>
        </li>
      </ul>
      <div class="divider"></div>
      <?php
      if ($contents['base']) {
        require 'base.php';
      } elseif ($contents['containers']) {
        require 'containers.php';
      } elseif ($contents['navs']) {
        require 'navs.php';
      } elseif ($contents['forms']) {
        require 'forms.php';
      } elseif ($contents['tables']) {
        require 'tables.php';
      } elseif ($contents['glyphicons']) {
        require 'glyphicons.php';
      } elseif ($contents['famfamfam']) {
        require 'famfamfam.php';
      } else {
        require 'getstarted.php';
      }
      ?>
    </div>
    <div class="container-upper-foot">
      <div class="container-centered">
        <ul class="nav nav-simple inline">
          <li class="header">Codeminus</li>
          <li><a href="http://github.com/codeminus" target="blank">on GitHub</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
      </div>
    </div>
    <div class="container-centered">
      <div class="container-lower-foot">
        <div class="float-right">&copy; 2013 Codeminus. All rights reserved</div>
      </div>
    </div>
    <script type="text/javascript" src="../../js/codeminus.js"></script>
  </body>
</html>