<?php
require '../main/ExtendedException.php';
require '../image/ImageHandler.php';
require '../util/ClassLog.php';
require 'FileHandler.php';
require 'FileUpload.php';

use codeminus\main as main;
use codeminus\util as util;
use codeminus\image as image;
use codeminus\file as file;
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title></title>
    <link rel="stylesheet" type="text/css" href="../css/codeminus.css" />
    <script src="../js/jquery.js" ></script>
  </head>
  <body>
    <form enctype="multipart/form-data" class="margined childs-table"
           action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
      <input type="hidden" name="sendfiles" value="1" />
      <!-- MAX_FILE_SIZE must precede the file input field -->
      <input type="hidden" name="MAX_FILE_SIZE" value="500000" />
      <label>user files:</label>
      <input name="userfile[]" type="file" />
      <input name="userfile[]" type="file" />
      <label>another file:</label>
      <input name="anotherfile" type="file" />
      <input type="submit" value="Send File" />
    </form>
    <pre class="margined"><?php
      if (isset($_POST['sendfiles'])) {
        try {
          $fileUp = new file\FileUpload();
          $fileUp->setExtensionFilter(file\FileUpload::IMAGE);
          $fileUp->filterFiles();
          
          
          foreach($fileUp->getValidFiles('userfile') as $file){
            echo 'Formatting '.$file['name'].':<br/>';
            $img = new image\ImageHandler($file['tmp_name']);
            echo 'Fitting it into 200x200 dimension.<br/>';
            $img->fitIntoDimension(200);
            echo 'Changing color to gray scale.<br/>';
            $img->setGrayscale();
            echo 'Saving temporary image.<br/>';
            $img->save();
            echo '---------------<br/>';
          }
          echo 'Saving '.$fileUp->getValidFilesCount().' uploaded files';
          $fileUp->save('img/', true, true);
          
        } catch (main\ExtendedException $e) {
          echo $e->getMessage();
        }
      }
      ?>
    </pre>
    <script src="../js/codeminus.js"></script>
  </body>
</html>
