<?php
namespace org\codeminus\file;

/**
 * File upload 
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.0
 */
final class FileUpload {

    private $files;
    private $uploadError;
    //1024KB => 1MB
    const DEFAULT_MAX_FILESIZE = 1024000;
    
    const ALL = 0;
    
    /**
     * Image MIME types:
     * 
     * GIF = image/gif 
     * 
     * Internet Explorer JPG = image/pjpeg 
     * Other browsers    JPG = image/jpeg
     * 
     * Internet Explorer PNG = image/x-png
     * Other browsers    PNG = image/png
     * 
     */
    const IMAGE_ONLY = 1;
    
    /**
     * Image JPG MIME types:
     * 
     * Internet Explorer JPG = image/pjpeg 
     * Other browsers    JPG = image/jpeg
     */
    const IMAGE_JPEG = 2;
    
    /**
     * Text MIME type
     * TXT = text/plain
     */
    const TXT_ONLY = 3;
    
    /**
     * SPREAD SHEET MIME type
     * 
     *  XLS = application/vnd.ms-excel
     * XLSX = application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
     * 
     */
    const SPREADSHEET_ONLY = 4;
    
    /**
     * WORD PROCESSING MIME type
     * 
     *  DOC = application/msword 
     * DOCX = application/vnd.openxmlformats-officedocument.wordprocessingml.document 
     * 
     */
    const WORDPROCESS_ONLY = 5;
    
    /**
     * PDF MIME type
     * 
     * PDF = application/pdf
     */
    const PDF_ONLY = 6;
    
    //Error messages
    const ERR_INVALIDTYPE = 'Invalid uploaded file type';
    
    /**
     * File Upload
     * @return object
     * @param array $files 
     */
    public function __construct($files) {
        $this->setFiles($files);
    }
    
    /**
     * Temporary uploaded files
     * @return void
     * @param type $files 
     */
    public function setFiles($files) {
        $this->files = $files;
    }

    /**
     * Temporary uploaded files
     * @return array 
     */
    public function getFiles() {
        return $this->files;
    }
    
    /**
     * Upload error code
     * @return int
     */
    public function getUploadError() {
        return $this->uploadError;
    }

    public function setUploadError($uploadError) {
        $this->uploadError = $uploadError;
    }
        
    /**
     * File extension
     * @param string $file
     * @return string
     * @example ".png" 
     */
    public function getExtension($file){
        $tmpArray = explode(".", $file);
        return "." . $tmpArray[count($tmpArray)-1];
    }
    
    /**
     * Validate file type
     * @param string $fileType
     * @param int $validType
     * @return boolean 
     */
    public function validateType($fileType, $validType){
        
        switch ($validType) {
            
            case self::ALL :
                
                return true;                
                break;
            
            case self::IMAGE_ONLY:
                
                if($fileType == "image/gif" || 
                   $fileType == "image/pjpeg" || $fileType == "image/jpeg" ||
                   $fileType == "image/x-png" || $fileType == "image/png"){
                    
                    return true;
                    
                }else{
                    return false;
                }

                break;

            case self::IMAGE_JPEG:
                
                if($fileType == "image/pjpeg" || $fileType == "image/jpeg"){
                    
                    return true;
                    
                }else{
                    return false;
                }

                break;    
                
            case self::TXT_ONLY:
                
                if($fileType == "text/plain"){
                    
                    return true;
                    
                }else{
                    return false;
                }

                break;    
            
            case self::SPREADSHEET_ONLY:
                
                if($fileType == "application/msword" || 
                   $fileType == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"){
                    
                    return true;
                    
                }else{
                    return false;
                }

                break;    
            
            case self::WORDPROCESS_ONLY:
                
                if($fileType == "application/msword" || 
                   $fileType == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"){
                    
                    return true;
                    
                }else{
                    return false;
                }

                break;
             
            case self::PDF_ONLY:
                
                if($fileType == "application/pdf"){
                    return true;
                }else{
                    return false;
                }

                break;
                
            default:
                return false;                
                break;
        }
        
    }
    
    /**
     * Move uploaded temporary file to its final destination
     * @param string $tmpFile
     * @param string $destination
     * @param boolean $replaceExistent
     * @return boolean 
     */
    private function moveUploadedFile($tmpFile, $destination, $replaceExistent = false){
        
        //Verifying if the destination file already exists and if it can be replaced
        if(!file_exists($destination) || $replaceExistent){
            return move_uploaded_file($tmpFile, $destination);            
        }else{            
            return false;
        }
        
    }
    
    /**
     * Save uploaded files
     * @param string $destination
     * @param int $type
     * @param boolean $replaceExistent
     * @param string $baseName
     * @return boolean 
     */
    public function save($destination, $type = self::ALL, $replaceExistent = false, $baseName = null){
        
        $fileArray = $this->getFiles();
        
        
        //If there's only one file to upload
        if(count($fileArray["error"]) == 1){
            
            if($fileArray["error"] == UPLOAD_ERR_OK){
                
                    if($this->validateType($fileArray["type"], $type)){

                        if(isset($baseName)){
                            
                            if($this->moveUploadedFile($fileArray["tmp_name"], $destination . $baseName . $this->getExtension($fileArray["name"]), $replaceExistent)){
                                return true;
                            }else{
                                $this->setErrorCode(SystemMessage::ERR_UPLOAD_WRITE);
                                return false;
                            }

                        }else{

                            if($this->moveUploadedFile($fileArray["tmp_name"], $destination . basename($fileArray["name"]), $replaceExistent)){
                                return true;
                            }else{
                                return false;
                            }

                        }

                    }else{
                        return false;
                    }


                }elseif($fileArray["error"] == UPLOAD_ERR_INI_SIZE || $fileArray["error"] == UPLOAD_ERR_FORM_SIZE){
                    
                    return false;
                    
                }else{
                    
                    return false;

                }
        
        //if there's more than one file to upload        
        }else{
        
            for($i = 0; $i < count($fileArray["error"]); $i++){

                if($fileArray["error"][$i] == UPLOAD_ERR_OK){

                    if($this->validateType($fileArray["type"][$i], $type)){

                        if(isset($baseName)){

                            if($this->moveUploadedFile($fileArray["tmp_name"][$i], $destination . $baseName . $i . $this->getExtension($fileArray["name"][$i]), $replaceExistent)){
                                return true;
                            }else{
                                return false;
                            }

                        }else{

                            if($this->moveUploadedFile($fileArray["tmp_name"][$i], $destination . basename($fileArray["name"][$i]), $replaceExistent)){
                                return true;
                            }else{
                                return false;
                            }

                        }

                    }else{
                        return false;
                    }

                /**
                 * If the uploaded file size is greater than the especified  on php.ini or greater than the especified on the html form
                 */
                }elseif($fileArray["error"][$i] == UPLOAD_ERR_INI_SIZE || $fileArray["error"][$i] == UPLOAD_ERR_FORM_SIZE){
                    
                    return false;

                }else{
                    
                    return false;

                }

            }
        
        }
        
    }

}