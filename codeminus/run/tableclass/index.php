<?php
require '../../main/Autoloader.php';

use codeminus\main as main;

main\Autoloader::init();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>CMF - Table class generator</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/img/favicon.ico">
    <link rel="stylesheet" href="../../css/codeminus.css" />
  </head>
  <body>
    <div class="container-header">
      <header class="container-centered">
        <img src="../../assets/img/codeminus-php-framework-300x74.png"/>
        <span class="text-bold">v<?php echo main\Framework::VERSION; ?></span>
      </header>
    </div>
    <div class="container-centered">
      <section><h4 class="text-normal">Table class generator v2.0</h4></section>
      <form id="classForm">
        <input type="hidden" name="generateCode" />
        <div class="row">
          <section class="container-box rounded span6">
            <header>Database connection parameters</header>
            <section class="text-align-right" id="dbParams">
              <label for="db_host">Host</label>
              <input type="text" name="db_host" value="localhost" id="db_host" /><br/>
              <label for="db_user">User</label>
              <input type="text" name="db_user" value="root" id="db_user" /><br/>
              <label for="db_pass">Password</label>
              <input type="text" name="db_pass" id="db_pass" /><br/>
              <label for="db_name">Schema</label>
              <input type="text" name="db_name" id="db_name" /><br/>
              <label for="db_table">Table name</label>
              <input type="text" name="db_table" id="db_table" />
            </section>
          </section>
          <section class="container-box rounded span6">
            <header>Class properties</header>
            <section data-height-from="dbParams">
              <label for="class_namespace">Namespace</label><br/>
              <span class="input-group">
                <span>app\model\</span>
                <input type="text" name="class_namespace" id="class_namespace"/>
              </span>
            </section>
          </section>
        </div>
        <div class="row">
          <input type="submit" id="submitBtn" value="generate" class="btn-blue"/>
        </div>
      </form>
      <div id="codeTools" class="margined-top hide">
        <span id="className" class="text-xlarge info"
              style="text-transform: capitalize"></span>
        <div class="float-right">
          <form id="saveForm" method="POST" class="inline">
            <input type="hidden" name="saveCode" />
            <input type="hidden" name="savePath" />
            <input type="hidden" name="replaceFile" value="0" />
            <span class="input-group">
              <input type="button" name="replaceBtn" class="btn-danger"
                     data-toggle="button" value="replace file" />
              <input type="hidden" name="saveBtnLabel" />
              <input type="submit" name="saveBtn" class="btn-success" />
            </span>
          </form>
          <button id="selectClass">select all</button>
        </div>
      </div>
      <div id="codeContainer" style="min-height: 500px;"></div>
    </div>
    <script src="../../js/jquery.js"></script>
    <script src="../../js/codeminus.js"></script>
    <script src="main.js"></script>
  </body>
</html>