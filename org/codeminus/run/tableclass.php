<?php
require '../main/ExtendedException.php';
require '../db/Connection.php';
require '../db/TableClass.php';

use \org\codeminus\db as db;
use \org\codeminus\main as main;

if (isset($_POST['cmd'])) {

  $db_host = $_POST['db_host'];
  $db_user = $_POST['db_user'];
  $db_pass = $_POST['db_pass'];
  $db_name = $_POST['db_name'];
  $db_table = $_POST['db_table'];
  $class_namespace = $_POST['class_namespace'];
} else {
  $db_host = "localhost";
  $db_user = "root";
  $db_pass = "";
  $db_name = "";
  $db_table = "";
  $class_namespace = "";
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>CMF - Table class generator</title>
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicon.ico">
    <link rel="stylesheet" href="../css/codeminus.css" />
  </head>
  <body>
    <div class="container-header">
      <header class="container-centered">
        <img src="../assets/img/codeminus-php-framework-300x73.png" class="float-left"/>
        <div class="float-right bold">TableClass v1.1</div>
      </header>
    </div>
    <div class="container-centered">
      <section><h5>Table class generator</h5></section>
      <div class="float-left margined-right">
          <form name="" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <section class="container-box rounded block margined-bottom">
              <header>Database connection parameters</header>
            <section class="text-right">
                <label for="db_host">Host</label>
                <input type="text" name="db_host" id="db_host" 
                     value="<?php echo $db_host ?>" /><br/>
                <label for="db_user">User</label>
                <input type="text" name="db_user" id="db_user" 
                     value="<?php echo $db_user ?>" /><br/>
                <label for="db_pass">Password</label>
                <input type="text" name="db_pass" id="db_pass" 
                     value="<?php echo $db_pass ?>" /><br/>
                <label for="db_name">Database</label>
                <input type="text" name="db_name" id="db_name" 
                     value="<?php echo $db_name ?>" /><br/>
                <label for="db_table">Table name</label>
                <input type="text" name="db_table" id="db_table" 
                       value="<?php echo $db_table ?>"/>
              </section>
            </section>
            <section class="container-box rounded block">
              <header>Class properties</header>
              <section>
                <label for="class_namespace">Namespace</label><br/>
                <span class="input-group">
                  <span>app\models\</span>
                  <input type="text" name="class_namespace"
                         id="class_namespace" value="<?php echo $class_namespace ?>" />
                </span>
              </section>
            </section>
            <input type="submit" name="cmd" value="generate" class="btn-blue"/>
          </form>
        </div>
      <div class="float-left" style="width: 64%">
          <section class="container-box rounded block">
            <header>Output</header>
            <section style="height: 492px; overflow: auto;">
              <?php
              if (isset($_POST['cmd'])) {
                $dbconn = new db\Connection($_POST['db_host'], $_POST['db_user'], $_POST['db_pass'], $_POST['db_name']);
                $namespace = trim($_POST['class_namespace']);
                ($namespace != null) ? $namespace = 'app\models\\' . $namespace : $namespace = 'app\models';
                try {
                  $tc = new db\TableClass($dbconn, $_POST['db_table'], $namespace);
                  $tc->create();
                  ?>
                  <pre class="code code-line-numbered code-highlight"><?php echo $tc->getCode(); ?></pre>
                  <?php
                } catch (main\ExtendedException $e) {
                  echo $e->getMessage();
                }
              }
              ?>    
            </section>    
          </section>
        </div>
      </div>
    <script src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/codeminus.js"></script>
  </body>
</html>