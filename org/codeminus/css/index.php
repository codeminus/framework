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
    <style type="text/css">

    </style>
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
        <li><a href="?c=forms" class="<?php echo $contents['forms'] ?>">Forms</a></li>
        <li><a href="?c=tables" class="<?php echo $contents['tables'] ?>">Tables</a></li>
        <li><a href="?c=media" class="<?php echo $contents['media'] ?>">Media</a></li>
      </ul>
      <div class="separator"></div>
      <?php if ($contents['base']) { ?>
      <h1>h1</h1>
        <h2>h2</h2>
        <h3>h3</h3>
        <h4>h4</h4>
        <h5>h5</h5>
        <h6>h6</h6>
      <?php } ?>

      <?php if ($contents['containers']) { ?>
        <section class="container-box margined">
          <header>.container-box</header>
          <section>Content</section>
          <footer>Footer</footer>
        </section>
        <section class="container-box rounded margined">
          <header>.container-box .rounded</header>
          <section>Content</section>
          <footer class="text-right">.text-right</footer>
        </section>
        <section class="container-box rounded margined">
          <section>.container-box .rounded</section>
          <section>Content</section>
          <section>Content</section>
        </section>
        <section class="container-box rounded margined">
          <header>.container-box .rounded</header>
          <section>Content</section>
        </section>
        <section class="container-box rounded margined">
          <header>.container-box .rounded</header>
          <footer>Footer</footer>
        </section>
        <div class="container-bubble margined">
          <section class="container-box">
            <header>.container-bubble</header>
            <section>Content</section>
            <footer>Footer</footer>
          </section>
        </div>
        <div class="container-bubble margined">
          <section class="container-box style02">
            <header>.container-bubble .style02</header>
            <section>Content</section>
            <footer>Footer</footer>
          </section>
        </div>
        <div class="container-shadow margined">
          <section class="container-box">
            <header>.container-shadow</header>
            <section>Content</section>
            <footer>Footer</footer>
          </section>
        </div>
        <div class="container-shadow margined">
          <section class="container-box">
            <section>.container-shadow</section>
          </section>
        </div>
      <?php } ?>

      <?php if ($contents['navs']) { ?>
        <div class="container-bubble margined">
          <section class="container-box">
            <section>
              <ul class="nav nav-vlist">
                <li class="header">.nav-vlist</li>
                <li><a href="#" class="selected">Menu Item</a></li>
                <li><a href="#">Menu Item</a></li>
                <li><a href="#">Menu Item</a></li>
                <li><a href="#">Menu Item</a></li>
              </ul>
            </section>
          </section>
        </div>
        <div class="container-bubble margined">
          <ul class="nav nav-vlist style02">
            <li class="header">.nav-vlist .style02</li>
            <li><a href="#" >Menu Item</a></li>
            <li><a href="#" class="selected">Menu Item</a></li>
            <li><a href="#">Menu Item</a></li>
            <li><a href="#">Menu Item</a></li>
            <li class="footer">.footer</li>
          </ul>
        </div>
        <div class="container-bubble  margined">
          <section class="container-box block">
            <section>
              <ul class="nav nav-vtabs">
                <li class="header">.nav-vtabs</li>
                <li><a href="#" class="selected">Menu Item</a></li>
                <li><a href="#">Menu Item</a></li>
                <li><a href="#">Menu Item</a></li>
                <li><a href="#">Menu Item</a></li>
              </ul>
              <span>Content</span>
            </section>
          </section>
        </div>
        <div class="container-bubble  margined">
          <section class="container-box block">
            <section>
              <span>content</span>
              <ul class="nav nav-vtabs inverted">
                <li class="header">.nav-vtabs .inverted</li>
                <li><a href="#" class="selected">Menu Item</a></li>
                <li><a href="#">Menu Item</a></li>
                <li><a href="#">Menu Item</a></li>
                <li><a href="#">Menu Item</a></li>
              </ul>
            </section>
          </section>
        </div>
        <div class="container-bubble margined">
          <section class="container-box">
            <section>
              <ul class="nav nav-hlist">
                <li class="header">.nav-hlist</li>
                <li><a href="#" class="selected">Menu Item</a></li>
                <li><a href="#">Menu Item</a></li>
                <li><a href="#">Menu Item</a></li>
                <li><a href="#">Menu Item</a></li>
              </ul>
            </section>
          </section>
        </div>
        <div class="container-bubble margined">
          <section class="container-box">
            <section>
              <ul class="nav nav-htabs block">
                <li><a href="#" class="selected">Menu Item</a></li>
                <li><a href="#">Menu Item</a></li>
                <li><a href="#">Menu Item</a></li>
                <li><a href="#">Menu Item</a></li>
              </ul>
              <span>.nav-htabs</span>
            </section>
          </section>
        </div>
        <div class="container-bubble margined">
          <section class="container-box">
            <section>
              <span>.nav-htabs .inverted</span>
              <ul class="nav nav-htabs inverted block">
                <li><a href="#" class="selected">Menu Item</a></li>
                <li><a href="#">Menu Item</a></li>
                <li><a href="#">Menu Item</a></li>
                <li><a href="#">Menu Item</a></li>
              </ul>
            </section>
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