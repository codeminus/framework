<?php
require_once '../db/Connection.php';
require_once '../db/TableClass.php';

use \org\codeminus\db as db;

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
        <title></title>
        <link rel="stylesheet" href="../css/codeminus.css" />
    </head>
    <body>
        <div class="header clearFix">
            <header class="">
                <img src="../img/cmf-medium.png" class="floatLeft"/>
                <div class="floatRight bold">TableClass v1.1</div>
            </header>
        </div>
        <div class="root">
            <section><h2>Table class generator</h2></section>
            <div class="floatLeft">
                <form name="" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                    <section class="default-container">
                        <header>Database connection parameters</header>
                        <section>
                            <label for="db_host">Host:</label>
                            <input type="text" name="db_host" id="db_host" value="<?php echo $db_host ?>" class="medium"/>
                            <label for="db_user">User:</label>
                            <input type="text" name="db_user" id="db_user" value="<?php echo $db_user ?>" class="medium"/>
                            <label for="db_pass">Password:</label>
                            <input type="text" name="db_pass" id="db_pass" value="<?php echo $db_pass ?>" class="medium"/>
                            <label for="db_name">Database:</label>
                            <input type="text" name="db_name" id="db_name" value="<?php echo $db_name ?>" class="medium"/>
                            <label for="db_table">Table name:</label>
                            <input type="text" name="db_table" id="db_table" value="<?php echo $db_table ?>" class="medium"/>
                        </section>

                    </section>
                    <section class="default-container">
                        <header>Class properties</header>
                        <section>
                            <label for="class_namespace">Namespace:</label>
                            <input type="text" name="class_namespace" id="class_namespace" value="<?php echo $class_namespace ?>"" class="medium"/>
                        </section>    
                    </section>
                    <input type="submit" name="cmd" value="generate" class="btn btn-blue"/>
                </form>
            </div>
            <div class="floatRight" style="width: 630px;">
                <section class="default-container">
                    <header>Output</header>
                    <section style="height: 445px; overflow-y: auto;">
                        
                        <?php
                        if (isset($_POST['cmd'])) {
                            $dbconn = new db\Connection($_POST['db_host'], $_POST['db_user'], $_POST['db_pass'], $_POST['db_name']);
                            $tc = new db\TableClass($dbconn, $_POST['db_table'], $_POST['class_namespace']);
                        ?>
                        <pre style="font-family: Courier; font-size: 0.9em"><?php echo $tc->create();?></pre>
                        <?php }?>    
                    </section>    
                </section>
                
            </div>
        </div>