<?php

require '../../main/Autoloader.php';

use codeminus\db as db;
use codeminus\main as main;

main\Autoloader::init();

if (isset($_POST['generateCode'])) {
  $dbconn = new db\Connection($_POST['db_host'], $_POST['db_user'], $_POST['db_pass'], $_POST['db_name']);
  $dbconn->escape_var($_POST);
  $namespace = trim($_POST['class_namespace']);
  if ($namespace != null) {
    $namespace = 'app\model\\' . $namespace;
  } else {
    $namespace = 'app\model';
  }
  try {
    $tc = new db\TableClass($dbconn, $_POST['db_table'], $namespace);
    $tc->create();
    $fcode = "<pre id=\"classCode\" class=\"code code-line-numbered code-highlight\">{$tc->getCode()}</pre>";
    if (is_dir('..\..\..\app\model\\')) {
      $savePath = '..\..\..\\' . $namespace . '\\' . ucfirst($_POST['db_table']) . '.php';
    }else{
      $savePath = null;
    }
    header('Content-Type: application/json');
    echo json_encode(array(
        'code' => '<?php ' . $tc->getCode(),
        'formattedCode' => $fcode,
        'savePath' => $savePath,
        'saveLabel' => '\\' . $namespace
    ));
  } catch (main\ExtendedException $e) {
    $error = $e->getFormattedMessage();
    header('Content-Type: application/json');
    echo json_encode(array('error' => $error));
  }
} elseif (isset($_POST['savePath'])) {
  echo codeminus\file\File::create($_POST['savePath'], $_POST['saveCode'], $_POST['replaceFile'], true);
}