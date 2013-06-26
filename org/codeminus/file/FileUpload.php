<?php

namespace org\codeminus\file;

use org\codeminus\main as main;

/**
 * File upload 
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 2.0
 */
class FileUpload {

  private $errorMessages = array();
  private $files = array();
  private $validFiles = array();
  private $invalidFiles = array();

  //Extensions filters

  const IMAGE = 'gif, jpg, jpeg, png, bmp';
  const IMAGE_GIF = 'gif';
  const IMAGE_JPG = 'jpg, jpeg';
  const IMAGE_PNG = 'png';
  const IMAGE_BMP = 'bmp';
  const TEXT = 'txt';
  const PDF = 'pdf';

  /**
   * File upload
   * @return FileUpload
   */
  public function __construct() {
    $this->setErrorMessages();
    $this->setFiles();
  }

  /**
   * Error message
   * @param int $code one of the UPLOAD constants
   * @return string error message
   */
  public function getErrorMessage($code) {
    return $this->errorMessages[$code];
  }

  private function setErrorMessages() {
    (isset($_POST['MAX_FILE_SIZE'])) ? $maxSize = $_POST['MAX_FILE_SIZE'] : $maxSize = null ;
    //Default upload error codes
    $this->errorMessages[0] = "The file uploaded to temporary folder with success.";
    $this->errorMessages[1] = "The uploaded file exceeds the upload_max_filesize of " . ini_get('upload_max_filesize') . ". Directive in php.ini. ";
    $this->errorMessages[2] = "The uploaded file exceeds the MAX_FILE_SIZE of " . $maxSize . " bytes. Directive that was specified in the HTML form.";
    $this->errorMessages[3] = "The uploaded file was only partially uploaded.";
    $this->errorMessages[4] = "No file was uploaded.";
    $this->errorMessages[6] = "Missing a temporary folder.";
    $this->errorMessages[7] = "Failed to write file to disk.";
    $this->errorMessages[8] = "A PHP extension stopped the file upload.";
    //FileUpload class error codes
    $this->errorMessages[-1] = "The uploaded file extension is invalid.";
    $this->errorMessages[-2] = "The uploaded file exceeds the maximum size defined with setMaxSize().";
  }

  /**
   * The reorganized $_FILES array
   * @return array like discribed below:<br/>
   * <b>$files['fileInputName'][0]['name']</b> file name<br/>
   * <b>$files['fileInputName'][0]['type']</b> file mime type according to 
   * browser<br/>
   * <b>$files['fileInputName'][0]['ext_filter']</b> list of extensions that 
   * will be used to validate file<br/>
   * <b>$files['fileInputName'][0]['tmp_name']</b> temporary filepath<br/>
   * <b>$files['fileInputName'][0]['error']</b> upload error code<br/>
   * <b>$files['fileInputName'][0]['error_msg']</b> error message<br/>
   * <b>$files['fileInputName'][0]['size']</b> file size<br/>
   * <b>$files['fileInputName'][0]['max_size']</b> the maximum file size that
   * will be used to validate file<br/>
   * <b>$files['fileInputName'][0]['dest_folder']</b> file destination folder
   * <br/>
   * <b>$files['fileInputName'][0]['replace']</b> if set to TRUE it will replace
   * the existent file<br/>
   */
  public function getFiles() {
    return $this->files;
  }

  /**
   * Reorganizes $_FILES array to this pattern: $files['varname'][index]['info']
   * @return void
   */
  private function setFiles() {
    if (empty($_FILES)) {
      throw new main\ExtException('<b>Error:</b> $_FILES superglobal is empty.');
    }
    foreach (array_keys($_FILES) as $varname) {
      //if it's an array within $_FILES
      if (is_array($_FILES[$varname]['name'])) {
        $varnameFileCount = count($_FILES[$varname]['name']);
        $fileCount = 0;
        for ($i = 0; $i < $varnameFileCount; $i++) {
          //if there's not file in this position
          if ($_FILES[$varname]['name'][$i] == null) {
            continue;
          }
          //adding file to array
          $this->files[$varname][$fileCount]['name'] = $_FILES[$varname]['name'][$i];
          $this->files[$varname][$fileCount]['type'] = $_FILES[$varname]['type'][$i];
          $this->files[$varname][$fileCount]['ext_filter'] = FileHandler::getFileExtension($_FILES[$varname]['name'][$i]);
          $this->files[$varname][$fileCount]['tmp_name'] = $_FILES[$varname]['tmp_name'][$i];
          $this->files[$varname][$fileCount]['error'] = $_FILES[$varname]['error'][$i];
          $this->files[$varname][$fileCount]['error_msg'] = $this->getErrorMessage($_FILES[$varname]['error'][$i]);
          $this->files[$varname][$fileCount]['size'] = $_FILES[$varname]['size'][$i];
          $this->files[$varname][$fileCount]['max_size'] = $_FILES[$varname]['size'][$i];
          $this->files[$varname][$fileCount]['dest_folder'] = null;
          $this->files[$varname][$fileCount]['replace'] = false;
          $fileCount++;
        }
        //if there's no file on $varname position
        if ($fileCount == 0) {
          $this->files[$varname] = null;
        }
        //if it's not an array within $_FILES
      } else {
        //if there's a file
        if ($_FILES[$varname]['name'] != null) {
          $this->files[$varname][0]['name'] = $_FILES[$varname]['name'];
          $this->files[$varname][0]['type'] = $_FILES[$varname]['type'];
          $this->files[$varname][0]['ext_filter'] = FileHandler::getFileExtension($_FILES[$varname]['name']);
          $this->files[$varname][0]['tmp_name'] = $_FILES[$varname]['tmp_name'];
          $this->files[$varname][0]['error'] = $_FILES[$varname]['error'];
          $this->files[$varname][0]['error_msg'] = $this->getErrorMessage($_FILES[$varname]['error']);
          $this->files[$varname][0]['size'] = $_FILES[$varname]['size'];
          $this->files[$varname][0]['max_size'] = $_FILES[$varname]['size'];
          $this->files[$varname][0]['dest_folder'] = null;
          $this->files[$varname][0]['replace'] = false;
        } else {
          $this->files[$varname] = null;
        }
      }
    }
  }

  /**
   * Valid files
   * @return array containing all files that passed filterFiles() validation and
   * can be saved
   */
  public function getValidFiles() {
    return $this->validFiles;
  }

  /**
   * Adds a valid file
   * @param array $validFile a file that pased the filterFiles()
   * @return void
   */
  private function addValidFile($validFile) {
    array_push($this->validFiles, $validFile);
  }

  /**
   * Invalid failes
   * @return array containing all files that fail filterFiles() validation and
   * can NOT be saved
   */
  public function getInvalidFiles() {
    return $this->invalidFiles;
  }

  /**
   * Add an invalid file
   * @param array $invalidFile a file that fail the filterFiles()
   * @return void
   */
  private function addInvalidFile($invalidFile) {
    array_push($this->invalidFiles, $invalidFile);
  }

  /**
   * Adds a prefix to a file
   * @param string $prefix the value to be prepended to the file name
   * @param string $varname[optional] the HTML form input name that contains the
   * files. If NULL is given, all files will be prepended with $prefix
   * @return void
   */
  public function addPrefix($prefix, $varname = null) {
    if ($varname !== null) {
      $fileCount = count($this->files[$varname]);
      for ($i = 0; $i < $fileCount; $i++) {
        $this->files[$varname][$i]['name'] = $prefix . $this->files[$varname][$i]['name'];
      }
    } else {
      foreach (array_keys($this->files) as $varname) {
        $fileCount = count($this->files[$varname]);
        for ($i = 0; $i < $fileCount; $i++) {
          $this->files[$varname][$i]['name'] = $prefix . $this->files[$varname][$i]['name'];
        }
      }
    }
  }

  /**
   * Renames a file
   * @param string $newname the value that will replace the original name
   * @param string $varname[optional] the HTML form input name that contains the
   * files. If NULL is given, all files will be renamed to $newname. If there's
   * more than one file, the new name will be appended with a sequence number. 
   * @return void
   */
  public function rename($newname, $varname = null) {
    if ($varname !== null) {
      $fileCount = count($this->files[$varname]);
      ($fileCount > 1) ? $suffix = 1 : $suffix = null;
      for ($i = 0; $i < $fileCount; $i++) {
        $ext = FileHandler::getFileExtension($this->files[$varname][$i]['name']);
        $this->files[$varname][$i]['name'] = $newname . $suffix . '.' . $ext;
        $suffix++;
      }
    } else {
      $suffix = null;
      foreach (array_keys($this->files) as $varname) {
        $fileCount = count($this->files[$varname]);
        if ($fileCount > 1 && $suffix == null) {
          $suffix = 1;
        }
        for ($i = 0; $i < $fileCount; $i++) {
          $ext = FileHandler::getFileExtension($this->files[$varname][$i]['name']);
          $this->files[$varname][$i]['name'] = $newname . $suffix . '.' . $ext;
          $suffix++;
        }
      }
    }
  }

  /**
   * Defines whether to replace the existent file or not 
   * @param boolean $replace[optional] If set to FALSE, it will NOT replace the
   * existent file
   * @param string $varname[optional] the HTML form input name that contains the
   * files. If NULL is given, all files will be replaced according to $replace
   * return void
   */
  public function replaceExistent($replace = true, $varname = null) {
    if ($varname !== null) {
      $fileCount = count($this->files[$varname]);
      for ($i = 0; $i < $fileCount; $i++) {
        $this->files[$varname][$i]['replace'] = $replace;
      }
    } else {
      foreach (array_keys($this->files) as $varname) {
        $fileCount = count($this->files[$varname]);
        for ($i = 0; $i < $fileCount; $i++) {
          $this->files[$varname][$i]['replace'] = $replace;
        }
      }
    }
  }

  /**
   * Defines the maximum file size
   * @param int $maxSize the maximum file size in bytes
   * @param string $varname[optional] the HTML form input name that contains the
   * files. If NULL is given, all files will be evaluated according to $maxSize 
   * @return void
   */
  public function setMaxSize($maxSize, $varname = null) {
    if ($varname !== null) {
      $fileCount = count($this->files[$varname]);
      for ($i = 0; $i < $fileCount; $i++) {
        $this->files[$varname][$i]['max_size'] = $maxSize;
      }
    } else {
      foreach (array_keys($this->files) as $varname) {
        $fileCount = count($this->files[$varname]);
        for ($i = 0; $i < $fileCount; $i++) {
          $this->files[$varname][$i]['max_size'] = $maxSize;
        }
      }
    }
  }

  /**
   * Defines the file destination folder
   * @param string $folder the folder that will receive the file
   * @param boolean $createFolder[optional] if set to TRUE and the folder
   * doesn't exist it will be created
   * @param string $varname[optional] the HTML form input name that contains the
   * files. If NULL is given, all files will be saved to $folder 
   * @return void
   */
  public function setDestinationFolder($folder, $createFolder = false, $varname = null) {
    if ($createFolder) {
      FileHandler::createDir($folder);
    }
    if ($varname !== null) {
      $fileCount = count($this->files[$varname]);
      for ($i = 0; $i < $fileCount; $i++) {
        $this->files[$varname][$i]['dest_folder'] = $folder;
      }
    } else {
      foreach (array_keys($this->files) as $varname) {
        $fileCount = count($this->files[$varname]);
        for ($i = 0; $i < $fileCount; $i++) {
          $this->files[$varname][$i]['dest_folder'] = $folder;
        }
      }
    }
  }

  /**
   * Defines the allowed file extensions
   * @param string $filter a list of file extensions without the .(dot) and
   * separated by .(comma)
   * @param string $varname[optional] the HTML form input name that contains the
   * files. If NULL is given, all files will be evaluated according to $filter
   * @return void
   */
  public function setExtensionFilter($filter, $varname = null) {
    if ($varname !== null) {
      $fileCount = count($this->files[$varname]);
      for ($i = 0; $i < $fileCount; $i++) {
        $this->files[$varname][$i]['ext_filter'] = $filter;
      }
    } else {
      foreach (array_keys($this->files) as $varname) {
        $fileCount = count($this->files[$varname]);
        for ($i = 0; $i < $fileCount; $i++) {
          $this->files[$varname][$i]['ext_filter'] = $filter;
        }
      }
    }
  }

  /**
   * Filters all files based on the following:<br/>
   * $file['error']<br/>
   * $file['ext_filter']<br/>
   * $file['max_size']<br/>
   * The $validFiles array receives the files that passed the filter and the
   * $invalidFiles array receives the files that fail the filter
   * @return void
   */
  protected function filterFiles() {
    foreach (array_keys($this->files) as $varname) {
      if (is_array($this->files[$varname])) {
        $fileCount = count($this->files[$varname]);
        for ($i = 0; $i < $fileCount; $i++) {
          //if the uploaded files arrived with no error
          if ($this->files[$varname][$i]['error'] == 0) {
            //if the file extension is valid
            if (FileHandler::validateExtension($this->files[$varname][$i]['name'], $this->files[$varname][$i]['ext_filter'])) {
              //if the file size is within maximum allowed size
              if ($this->files[$varname][$i]['size'] <= $this->files[$varname][$i]['max_size']) {
                $this->addValidFile($this->files[$varname][$i]);
              } else {
                //defining error
                $this->files[$varname][$i]['error'] = -2;
                $this->files[$varname][$i]['error_msg'] = $this->getErrorMessage(-2);
                $this->addInvalidFile($this->files[$varname][$i]);
              }
            } else {
              //defining error
              $this->files[$varname][$i]['error'] = -1;
              $this->files[$varname][$i]['error_msg'] = $this->getErrorMessage(-1);
              $this->addInvalidFile($this->files[$varname][$i]);
            }
          } else {
            $this->addInvalidFile($this->files[$varname][$i]);
          }
        }
      }
    }
  }

  /**
   * Saves the uploaded file to it's final destination
   * @param array $file an associative array with the following structure:<br/>
   * $file['name']<br/>
   * $file['tmp_name']<br/>
   * $file['dest_folder']<br/>
   * $file['replace']<br/>
   * @return boolean returns TRUE if the file was saved with success and FALSE
   * otherwise
   */
  public function moveUploadedFile($file) {
    $destination = $file['dest_folder'] . basename($file['name']);
    if (!file_exists($destination) || $file['replace']) {
      return move_uploaded_file($file['tmp_name'], $destination);
    } else {
      $file['error_msg'] = 'File already exists.';
      $this->addInvalidFile($file);
      return false;
    }
  }

  /**
   * Saves all files to its final destination
   * @param string $destinationFolder[optional] if set, all files with be saved
   * to $destinationFolder
   * @param boolean $replaceExistent[optional] if set, all files will be
   * replaced according to $replaceExistent
   * @return boolean
   */
  public function save($destinationFolder = null, $createFolder = false, $replaceExistent = null) {
    if ($destinationFolder) {
      $this->setDestinationFolder($destinationFolder, $createFolder);
    }
    if (isset($replaceExistent)) {
      $this->replaceExistent($replaceExistent);
    }
    $this->filterFiles();
    $fileCount = count($this->getValidFiles());
    for ($i = 0; $i < $fileCount; $i++) {
      $this->moveUploadedFile($this->validFiles[$i]);
    }
  }

}