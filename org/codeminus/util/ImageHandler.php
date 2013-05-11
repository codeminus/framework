<?php
namespace org\codeminus\util;

/**
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.1
 */
final class ImageHandler {

    private $image;
    private $source;
    private $tempImage;
    
    //Quality constants are needed because the quality values for JPG are different from PNG files
    const QUALITY_HIGH = 101;
    const QUALITY_MEDIUM = 102;
    const QUALITY_HALF = 103;
    
    /**
     * Image Handler
     * @return object
     * @param string $imageSource 
     */
    public function __construct($imageSource) {
        $this->setImage(getimagesize($imageSource));
        $this->setSource($imageSource);
    }

    /**
     * Image Info
     * @return void
     * @param array $image 
     */
    public function setImage($image) {
        $this->image = $image;
    }
    
    /**
     * Image info
     * @return array 
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * Image source
     * @return void
     * @param string $source 
     */
    public function setSource($source) {
        $this->source = $source;
    }
    
    /**
     * Image source
     * @return string 
     */
    public function getSource() {
        return $this->source;
    }
    
    /**
     * Image width
     * @return int 
     */
    public function getWidth(){
        return $this->image[0];
    }
    
    /**
     * Image height
     * @return int 
     */
    public function getHeight(){
        return $this->image[1];
    }
    
    /**
     * Image Type
     * @return int
     * @example 2 == IMG_JPG
     * 
     * Image constants:
     * IMG_GIF == 1
     * IMG_JPG == 2
     * IMG_JPEG == 2
     * IMG_PNG == 4
     * 
     */
    public function getType(){
        return $this->image[2];
    }
    
    /**
     * Image HTML
     * @param string $baseDirectory complementing the previous given source
     * @return string
     * @example
     * source = "img/example.jpg";
     * getHTML("../"); return: <img src="../img/example.jpg" width="100" height="100" /> 
     */
    public function getHTML($baseDirectory = null, $replaceDirectory = false){
        
        if($replaceDirectory){
            return '<img src="' . $baseDirectory . '" ' . $this->image[3] . ' />';
        }else{
            return '<img src="' . $baseDirectory . $this->getSource() . '" ' . $this->image[3] . ' />';
        }
        
    }
    
    /**
     * Image MIME
     * @return string 
     */
    public function getMIME(){
        return $this->image["mime"];
    }
    
    /**
     * Image resource identifier
     * @return resource 
     */
    public function getIdentifier(){
        
        switch ($this->getType()){
            case IMG_GIF:
                return imagecreatefromgif($this->getSource());
                break;
            case IMG_JPG:
                return imagecreatefromjpeg($this->getSource());
                break;
            case IMG_PNG:
                return imagecreatefrompng($this->getSource());
                break;
                
        }
        
    }
    
    /**
     * Temporary image identifier
     * @param identifier $tempImage 
     */
    public function setTempImage($tempImage) {
        $this->tempImage = $tempImage;
    }
    
    /**
     * Temporary image identifier
     * @return identifier 
     */
    public function getTempImage() {
        return $this->tempImage;
    }
            
    /**
     * Image browser output
     * @return boolean
     * @param int $quality [optional]
     */
    public function output($quality = self::QUALITY_HIGH){
        
        if($quality == null){
            $quality = self::QUALITY_HIGH;
        }
        
        if($this->getTempImage() == null){
            $identifier = $this->getIdentifier();
        }else{
            $identifier = $this->getTempImage();
        }
        
        switch ($quality) {
            
            case self::QUALITY_HIGH:
                switch ($this->getType()) {
                    case IMG_JPG:
                        $quality = 100;
                        break;
                    case IMG_PNG:
                        $quality = 9;
                        break;
                }

                break;
            
            case self::QUALITY_MEDIUM:
                switch ($this->getType()) {
                    case IMG_JPG:
                        $quality = 75;
                        break;
                    case IMG_PNG:
                        $quality = 7;
                        break;
                }

                break;
            
            case self::QUALITY_HALF:
                switch ($this->getType()) {
                    case IMG_JPG:
                        $quality = 50;
                        break;
                    case IMG_PNG:
                        $quality = 5;
                        break;
                }

                break;
            
        }
        
        //outputting image to browser
        
        header("Content-Type: " . $this->getMIME());
        
        switch ($this->getType()) {
            case IMG_GIF:
                return imagegif($identifier);                
                break;
            case IMG_JPG:
                return imagejpeg($identifier, null, $quality);
                break;
            case IMG_PNG:
                return imagepng($identifier, null, $quality);
                break;
        }
        
        imagedestroy($identifier);
        
    }
    
    /**
     * Image save
     * @return boolean
     * @param int $quality [optional]
     * @param string $fileSource [optional]
     */
    public function save($quality = self::QUALITY_HIGH, $fileSource = null){
        
        if($quality == null){
            $quality = self::QUALITY_HIGH;
        }
        
        if($fileSource == null){
            $fileSource = $this->getSource();
        }
        
        if($this->getTempImage() == null){
            $identifier = $this->getIdentifier();
        }else{
            $identifier = $this->getTempImage();
        }
        
        switch ($quality) {
            
            case self::QUALITY_HIGH:
                switch ($this->getType()) {
                    case IMG_JPG:
                        $quality = 100;
                        break;
                    case IMG_PNG:
                        $quality = 9;
                        break;
                }

                break;
            
            case self::QUALITY_MEDIUM:
                switch ($this->getType()) {
                    case IMG_JPG:
                        $quality = 80;
                        break;
                    case IMG_PNG:
                        $quality = 7;
                        break;
                }

                break;
            
            case self::QUALITY_HALF:
                switch ($this->getType()) {
                    case IMG_JPG:
                        $quality = 50;
                        break;
                    case IMG_PNG:
                        $quality = 5;
                        break;
                }

                break;
            
        }
        
        switch ($this->getType()) {
            case IMG_GIF:
                return imagegif($identifier, $fileSource);                
                break;
            case IMG_JPG:
                return imagejpeg($identifier, $fileSource, $quality);
                break;
            case IMG_PNG:
                return imagepng($identifier, $fileSource, $quality);
                break;
        }
        
        imagedestroy($identifier);
        
    }
    
    /**
     * Resize image proportionally fitting it into a given dimension
     * @return void
     * @param int $maxWidth
     * @param int $maxHeight
     */
    public function fitIntoDimension($maxWidth, $maxHeight){

        $fit = false;
        $newWidth = $this->getWidth();
        $newHeight = $this->getHeight();
        
        $widthFactor = (100-($maxWidth*100/$newWidth));
        $heightFactor = (100-($maxHeight*100/$newHeight));

        if($widthFactor > 0 && $widthFactor > $heightFactor){

            $newWidth = floor(($newWidth - ($newWidth*($widthFactor/100))));
            $newHeight = floor(($newHeight - ($newHeight*($widthFactor/100))));

        }elseif($heightFactor > 0 && $heightFactor > $widthFactor){

            $newWidth = floor(($newWidth - ($newWidth*($heightFactor/100))));
            $newHeight = floor(($newHeight - ($newHeight*($heightFactor/100))));

        }else{
            
            $fit = true;
            
        }
        
        if(!$fit){            
            $this->resize($newWidth, $newHeight);            
        }
        
    }
    
    /**
     * Image resize
     * @return void
     * @param int $width
     * @param int $height 
     */
    public function resize($width, $height){
        
        $newImage = imagecreatetruecolor($width, $height);
        
        //if a temporary image is not set it uses the original
        if($this->getTempImage() == null){
            imagecopyresampled($newImage, $this->getIdentifier(), 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
            //setting a temporary image
            $this->setTempImage($newImage);
        }else{
            imagecopyresampled($newImage, $this->getTempImage(), 0, 0, 0, 0, $width, $height, imagesx($this->getTempImage()), imagesy($this->getTempImage()));
        }
        
    }
    
    /**
     * Image text
     * @return void
     * @param int $x coordinate
     * @param int $y coordinate
     * @param int $size from 1 to 5
     * @param string $text
     * @param int $red from 0 to 255
     * @param int $green from 0 to 255
     * @param int $blue from 0 to 255
     */
    public function setText($x, $y, $size, $text, $red = 0, $green = 0, $blue = 0){
        
        if($this->getTempImage() == null){
            $newImage = $this->getIdentifier();
            $color = imagecolorallocate($newImage, $red, $green, $blue);
            imagestring($newImage, $size, $x, $y, $text, $color);            
            $this->setTempImage($newImage);
        }else{            
            $color = imagecolorallocate($this->getTempImage(), $red, $green, $blue);
            imagestring($this->getTempImage(), $size, $x, $y, $text, $color);            
        }
        
    }
    
    /**
     * Image true type text
     * @todo finish implementation
     * @param int $x
     * @param int $y
     * @param int $size
     * @param int $angle
     * @param string $fontfile
     * @param string $text
     * @param int $red from 0 to 255
     * @param int $green from 0 to 255
     * @param int $blue from 0 to 255
     */
    public function setTrueTypeText($x, $y, $size, $angle, $fontfile, $text, $red = 0, $green = 0, $blue = 0){
        
        if($this->getTempImage() == null){
            $newImage = $this->getIdentifier();
            $color = imagecolorallocate($newImage, $red, $green, $blue);
            imagettftext($newImage, $size, $angle, $x, $y, $color, $fontfile, $text);
            $this->setTempImage($newImage);
        }else{
            $color = imagecolorallocate($this->getTempImage(), $red, $green, $blue);
            imagettftext($this->getTempImage(), $size, $angle, $x, $y, $color, $fontfile, $text);
        }
        
    }
    
    /**
     * Image grayscale
     * @return void
     */
    public function setGrayscale(){
        
        if($this->getTempImage() == null){
            $newImage = $this->getIdentifier();
            imagefilter($newImage, IMG_FILTER_GRAYSCALE);
            $this->setTempImage($newImage);
        }else{
            imagefilter($this->getTempImage(), IMG_FILTER_GRAYSCALE);            
        }
        
    }
    
    /**
     * Image brightness
     * @return void
     * @param int $level [Optional] value from -255 to 255
     */
    public function setBrightness($level = 0){
        
        if($this->getTempImage() == null){
            $newImage = $this->getIdentifier();
            imagefilter($newImage, IMG_FILTER_BRIGHTNESS, $level);
            $this->setTempImage($newImage);            
        }else{            
            imagefilter($this->getTempImage(), IMG_FILTER_BRIGHTNESS, $level);            
        }
        
    }
    
    /**
     * Image contrast
     * @return void
     * @param int $level [Optional]
     */
    public function setConstrast($level = 0){
        
        if($this->getTempImage() == null){
            $newImage = $this->getIdentifier();
            imagefilter($newImage, IMG_FILTER_CONTRAST, $level);
            $this->setTempImage($newImage);            
        }else{            
            imagefilter($this->getTempImage(), IMG_FILTER_CONTRAST, $level);
            
        }
        
    }
    
    /**
     * Image emboss
     * @return void
     */
    public function setEmboss(){
        
        if($this->getTempImage() == null){
            $newImage = $this->getIdentifier();
            imagefilter($newImage, IMG_FILTER_EMBOSS);
            $this->setTempImage($newImage);            
        }else{            
            imagefilter($this->getTempImage(), IMG_FILTER_EMBOSS);
            
        }
        
    }
    
    /**
     * Image negative
     * @return void
     */
    public function setNegative(){
        
        if($this->getTempImage() == null){
            $newImage = $this->getIdentifier();
            imagefilter($newImage, IMG_FILTER_NEGATE);
            $this->setTempImage($newImage);            
        }else{            
            imagefilter($this->getTempImage(), IMG_FILTER_NEGATE);
            
        }
        
    }
    
}