<?php
$contents = array(
    'base' => false,
    'containers' => false,
    'navs' => false,
    'forms' => false,
    'tables' => false,
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
    <link rel="shortcut icon" type="image/x-icon" href="../img/favicon.ico">
    <link rel="stylesheet" href="base.css" />
    <link rel="stylesheet" href="containers.css" />
    <link rel="stylesheet" href="navs.css" />
    <link rel="stylesheet" href="forms.css" />
  </head>
  <body>
    <div class="container-header">
      <div class="container-centered">
        <img src="../img/cmf-medium.png" />
      </div>
    </div>
    <div class="container-centered">
      <ul class="nav nav-hlist">
        <li><a href="?c=base" class="<?php echo $contents['base'] ?>">Base</a></li>
        <li><a href="?c=containers" class="<?php echo $contents['containers'] ?>">Containers</a></li>
        <li><a href="?c=navs" class="<?php echo $contents['navs'] ?>">Navigations</a></li>
        <li><a href="?c=tables" class="<?php echo $contents['forms'] ?>">Forms</a></li>
        <li><a href="?c=tables" class="<?php echo $contents['tables'] ?>">Tables</a></li>
        <li><a href="?c=tables" class="<?php echo $contents['media'] ?>">Media</a></li>
      </ul>
      <div class="separator"></div>
      <?php if ($contents['base']) { ?>
        <h1>Header</h1>
        <h2>Header</h2>
        <h3>Header</h3>
        <h4>Header</h4>
        <h5>Header</h5>
        <h6>Header</h6>
      <?php } ?>
      <?php if ($contents['navs']) { ?>
        <ul class="nav nav-htabs block">
          <li><a href="#" class="selected">Menu Item</a></li>
          <li><a href="#">Menu Item</a></li>
          <li><a href="#">Menu Item</a></li>
          <li><a href="#">Menu Item</a></li>
        </ul>
        <ul class="nav nav-htabs block inverted">
          <li><a href="#" class="selected">Menu Item</a></li>
          <li><a href="#">Menu Item</a></li>
          <li><a href="#">Menu Item</a></li>
          <li><a href="#">Menu Item</a></li>
        </ul>
        <ul class="nav nav-vtabs ">
          <li><a href="#" class="selected">Menu Item</a></li>
          <li><a href="#">Menu Item</a></li>
          <li><a href="#">Menu Item</a></li>
          <li><a href="#">Menu Item</a></li>
        </ul>

        <ul class="nav nav-vtabs inverted">
          <li><a href="#" class="selected">Menu Item</a></li>
          <li><a href="#">Menu Item</a></li>
          <li><a href="#">Menu Item</a></li>
          <li><a href="#">Menu Item</a></li>
        </ul>
      <?php } ?>
      <?php if ($contents['containers']) { ?>
      <section class="container-box rounded">
        <header>Header</header>
        <section>Content</section>
        <footer>Footer</footer>
      </section>
      <section class="container-box rounded" style="width: 200px">
        <header>Header</header>
        <section>Content</section>
        <footer class="text-right">Footer right-aligned</footer>
      </section>
      <section class="container-box rounded">
        <section>Content</section>
        <section>Content</section>
        <section>Content</section>
      </section>
      <section class="container-box rounded">
        <header>Header</header>
        <section>Content</section>
      </section>
      <section class="container-box rounded">
        <header>Header</header>
        <footer>Footer</footer>
      </section>
      <div class="container-bubble">
        <section class="container-box" style="width: 200px">
          <header>container-bubble</header>
          <section>Content</section>
          <footer>Footer</footer>
        </section>
      </div>
      <div class="container-shadow">
        <section class="container-box" style="width: 200px">
          <header>container-shadow</header>
          <section>Content</section>
          <footer>Footer</footer>
        </section>
      </div>
      <div class="container-shadow">
        <section class="container-box" style="width: 200px">
          <section>Content</section>
        </section>
      </div>
      <?php } ?>
      
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
        <ul class="nav nav-simple inline">
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