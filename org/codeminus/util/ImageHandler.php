<?php

namespace org\codeminus\util;

/**
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.1
 * Supported image types: GIF, JPEG, PNG
 */
final class ImageHandler {

  private $identifier;
  private $imageInfo;
  private $source;
  private $tempImage;

  //Quality constants are needed because the quality values for JPG are different from PNG files

  const QUALITY_HIGH = 101;
  const QUALITY_MEDIUM = 102;
  const QUALITY_HALF = 103;

  /**
   * Image Handler
   * @param string $imageSource the image file path
   * @return ImageHandler
   */
  public function __construct($imageSource) {
    $this->setSource($imageSource);
    $this->setImageInfo(getimagesize($imageSource));
    switch ($this->getType()) {
      case IMAGETYPE_GIF:
        $identifier = imagecreatefromgif($this->getSource());
        break;
      case IMAGETYPE_JPEG:
        $identifier = imagecreatefromjpeg($this->getSource());
        break;
      case IMAGETYPE_PNG:
        $identifier = imagecreatefrompng($this->getSource());
        break;
    }
    $this->setIdentifier($identifier);
  }

  /**
   * Image info
   * @return array containing informations about the image
   * [0] => width
   * [1] => height
   * [2] => php image type constant
   * [3] => string with width and height that can be use directly into img tag
   * ['bits'] => numbers of bits for each color
   * ['channels'] => 3 for RGB and 4 for CMYK
   * ['mime'] =>  mime type of the image
   */
  public function getImageInfo() {
    return $this->imageInfo;
  }

  /**
   * Image Info
   * @param array $imageInfo
   * @return void
   */
  protected function setImageInfo($imageInfo) {
    $this->imageInfo = $imageInfo;
  }

  /**
   * Image source
   * @return string 
   */
  public function getSource() {
    return $this->source;
  }

  /**
   * Image source
   * @param string $source the image file path
   * @return void
   */
  protected function setSource($source) {
    $this->source = $source;
  }

  /**
   * Image width
   * @return int 
   */
  public function getWidth() {
    return imagesx($this->getIdentifier());
  }

  /**
   * Image height
   * @return int 
   */
  public function getHeight() {
    return imagesy($this->getIdentifier());
  }

  /**
   * Image Type
   * @return int
   * @example 2 == IMAGETYPE_JPEG
   * 
   * Supported PHP Image constants examples:
   * IMAGETYPE_GIF == 1
   * IMAGETYPE_JPEG == 2
   * IMG_JPEG == 2
   * IMAGETYPE_PNG == 4
   * 
   */
  public function getType() {
    return $this->imageInfo[2];
  }

  /**
   * Image HTML
   * @param string $baseDirectory complementing the previous given source
   * @return string
   * @example
   * source = "img/example.jpg";
   * getHTML("../"); return: <img src="../img/example.jpg" width="100" height="100" /> 
   */
  public function getHTML($baseDirectory = null, $replaceDirectory = false) {

    if ($replaceDirectory) {
      return '<img src="' . $baseDirectory . '" ' . $this->imageInfo[3] . ' />';
    } else {
      return '<img src="' . $baseDirectory . $this->getSource() . '" ' . $this->imageInfo[3] . ' />';
    }
  }

  /**
   * Image MIME
   * @return string 
   */
  public function getMIME() {
    return $this->imageInfo["mime"];
  }

  /**
   * Image resource identifier
   * @return resource 
   */
  public function getIdentifier() {
    return $this->identifier;
  }

  private function setIdentifier($identifier) {
    $this->identifier = $identifier;
    //preserving png transparency
    if ($this->getType() == IMAGETYPE_PNG) {
      imagealphablending($this->identifier, false);
    }
  }

  /**
   * Temporary image identifier
   * @return identifier 
   */
  public function getTempImage() {
    return $this->tempImage;
  }

  /**
   * Temporary image identifier
   * @param identifier $tempImage 
   */
  private function setTempImage($tempImage) {
    $this->tempImage = $tempImage;
  }

  private function getQuality($qualityConstant) {
    switch ($qualityConstant) {
      case self::QUALITY_HIGH:
        switch ($this->getType()) {
          case IMAGETYPE_JPEG:
            $quality = 100;
            break;
          case IMAGETYPE_PNG:
            $quality = 9;
            break;
        }
        break;
      case self::QUALITY_MEDIUM:
        switch ($this->getType()) {
          case IMAGETYPE_JPEG:
            $quality = 75;
            break;
          case IMAGETYPE_PNG:
            $quality = 7;
            break;
        }
        break;
      case self::QUALITY_HALF:
        switch ($this->getType()) {
          case IMAGETYPE_JPEG:
            $quality = 50;
            break;
          case IMAGETYPE_PNG:
            $quality = 5;
            break;
        }
        break;
    }
    return $quality;
  }

  /**
   * Image browser output
   * @param int $qualityConstant [optional]
   * @return boolean
   */
  public function output($raw = false, $qualityConstant = self::QUALITY_HIGH) {

    if ($qualityConstant == null) {
      $qualityConstant = self::QUALITY_HIGH;
    }
    $quality = $this->getQuality($qualityConstant);

    $identifier = $this->getIdentifier();
    /*
      if ($this->getTempImage() == null) {
      $identifier = $this->getIdentifier();
      } else {
      $identifier = $this->getTempImage();
      } */

    //outputting image to browser
    if (!$raw) {
      header("Content-Type: " . $this->getMIME());
    }

    ob_start();
    switch ($this->getType()) {
      case IMAGETYPE_GIF:
        return imagegif($identifier);
        break;
      case IMAGETYPE_JPEG:
        return imagejpeg($identifier, null, $quality);
        break;
      case IMAGETYPE_PNG:
        imagesavealpha($identifier, true);
        return imagepng($identifier, null, $quality);
        break;
    }
    $raw = ob_get_contents();
    ob_end_clean();
    imagedestroy($identifier);
    return $raw;
  }

  /**
   * Image save
   * @param int $qualityConstant [optional]
   * @param string $fileSource [optional]
   * @return boolean
   */
  public function save($qualityConstant = self::QUALITY_HIGH, $fileSource = null) {
    if ($qualityConstant == null) {
      $qualityConstant = self::QUALITY_HIGH;
    }
    $quality = $this->getQuality($qualityConstant);

    if ($fileSource == null) {
      $fileSource = $this->getSource();
    }
    
    $identifier = $this->getIdentifier();
    $imageSaved = false;
    
    switch ($this->getType()) {
      case IMAGETYPE_GIF:
        $imageSaved = imagegif($identifier, $fileSource);
        break;
      case IMAGETYPE_JPEG:
        $imageSaved = imagejpeg($identifier, $fileSource, $quality);
        break;
      case IMAGETYPE_PNG:
        $imageSaved = imagepng($identifier, $fileSource, $quality);
        break;
    }
    imagedestroy($identifier);
    return $imageSaved;
  }

  /**
   * Resize image proportionally fitting it into a given dimension
   * @param int $maxWidth
   * @param int $maxHeight
   * @return void
   */
  public function fitIntoDimension($maxWidth, $maxHeight) {

    $fit = false;
    $newWidth = $this->getWidth();
    $newHeight = $this->getHeight();

    $widthFactor = (100 - ($maxWidth * 100 / $newWidth));
    $heightFactor = (100 - ($maxHeight * 100 / $newHeight));

    if ($widthFactor > 0 && $widthFactor > $heightFactor) {
      $newWidth = floor(($newWidth - ($newWidth * ($widthFactor / 100))));
      $newHeight = floor(($newHeight - ($newHeight * ($widthFactor / 100))));
    } elseif ($heightFactor > 0 && $heightFactor > $widthFactor) {
      $newWidth = floor(($newWidth - ($newWidth * ($heightFactor / 100))));
      $newHeight = floor(($newHeight - ($newHeight * ($heightFactor / 100))));
    } else {
      $fit = true;
    }

    if (!$fit) {
      $this->resize($newWidth, $newHeight);
    }
  }

  /**
   * Image resize
   * @param int $width
   * @param int $height 
   * @return void
   */
  public function resize($width, $height) {
    $newImage = imagecreatetruecolor($width, $height);
    //preserving png transparency
    if ($this->getType() == IMAGETYPE_PNG) {
      imagealphablending($newImage, false);
    }
    imagecopyresampled($newImage, $this->getIdentifier(), 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
    $this->setIdentifier($newImage);
  }

  /**
   * Image text
   * @param int $x coordinate
   * @param int $y coordinate
   * @param int $size from 1 to 5
   * @param string $text
   * @param int $red from 0 to 255
   * @param int $green from 0 to 255
   * @param int $blue from 0 to 255
   * @return void
   */
  public function setText($x, $y, $size, $text, $red = 0, $green = 0, $blue = 0) {

    if ($this->getTempImage() == null) {
      $newImage = $this->getIdentifier();
      $color = imagecolorallocate($newImage, $red, $green, $blue);
      imagestring($newImage, $size, $x, $y, $text, $color);
      $this->setTempImage($newImage);
    } else {
      $color = imagecolorallocate($this->getTempImage(), $red, $green, $blue);
      imagestring($this->getTempImage(), $size, $x, $y, $text, $color);
    }
  }

  /**
   * Image true type text
   * @todo finish implementation
   * @param int $x coordinate
   * @param int $y coordinate
   * @param int $size
   * @param int $angle
   * @param string $fontfile path
   * @param string $text
   * @param int $red from 0 to 255
   * @param int $green from 0 to 255
   * @param int $blue from 0 to 255
   */
  public function setTrueTypeText($x, $y, $size, $angle, $fontfile, $text, $red = 0, $green = 0, $blue = 0) {

    if ($this->getTempImage() == null) {
      $newImage = $this->getIdentifier();
      $color = imagecolorallocate($newImage, $red, $green, $blue);
      imagettftext($newImage, $size, $angle, $x, $y, $color, $fontfile, $text);
      $this->setTempImage($newImage);
    } else {
      $color = imagecolorallocate($this->getTempImage(), $red, $green, $blue);
      imagettftext($this->getTempImage(), $size, $angle, $x, $y, $color, $fontfile, $text);
    }
  }

  /**
   * Image grayscale
   * @return void
   */
  public function setGrayscale() {
    imagefilter($this->getIdentifier(), IMG_FILTER_GRAYSCALE);
  }

  /**
   * Image brightness
   * @param int $level [Optional] value from -255 to 255
   * @return void
   */
  public function setBrightness($level = 0) {
    imagefilter($this->getIdentifier(), IMG_FILTER_BRIGHTNESS, $level);
  }

  /**
   * Image contrast
   * @param int $level [Optional]
   * @return void
   */
  public function setConstrast($level = 0) {
    imagefilter($this->getIdentifier(), IMG_FILTER_CONTRAST, $level);
  }

  /**
   * Image emboss
   * @return void
   */
  public function setEmboss() {
    imagefilter($this->getIdentifier(), IMG_FILTER_EMBOSS);
  }

  /**
   * Image negative
   * @return void
   */
  public function setNegative() {
    imagefilter($this->getIdentifier(), IMG_FILTER_NEGATE);
  }

  /**
   * Add image stamp
   * @param mixed $image This parameter accepts either an image source or
   * resource
   * @param int $x coordinate
   * @param int $y coordinate
   * @return void
   */
  public function addStamp($image, $x = 0, $y = 0) {
    if (is_resource($image)) {
      $stamp = $image;
    } else {
      $stampInfo = getimagesize($image);
      switch ($stampInfo[2]) {
        case IMAGETYPE_GIF:
          $stamp = imagecreatefromgif($image);
          break;
        case IMAGETYPE_JPEG:
          $stamp = imagecreatefromjpeg($image);
          break;
        case IMAGETYPE_PNG:
          $stamp = imagecreatefrompng($image);
          break;
      }
    }
    imagecopy($this->getIdentifier(), $stamp, $x, $y, 0, 0, imagesx($stamp), imagesy($stamp));
  }

}

$cmf = new ImageHandler('cmf.png');
$cmf->fitIntoDimension(200, 200);
$cmf->setBrightness(100);
//$cmf->output();
//exit;
$imgh = new ImageHandler('test.jpg');
//$imgh->fitIntoDimension(200, 300);

$imgh->addStamp('cmf.png', 0, 0);
$imgh->setConstrast(-10);
$imgh->output();