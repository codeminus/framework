<?php

namespace codeminus\image;

use codeminus\main as main;

/**
 * Image Handler<br/>
 * Supported image types: GIF, JPEG, PNG
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.1
 */
class ImageHandler {

  private $source;
  private $identifier;
  private $imageInfo;

  //Quality constants are needed because the quality values for JPG are different from PNG files

  const QUALITY_HIGH = 101;
  const QUALITY_MEDIUM = 102;
  const QUALITY_HALF = 103;

  /**
   * Image Handler
   * @param string $imageSource the image file path
   * @return ImageHandler
   * @throws codeminus\main\ExtendedException
   */
  public function __construct($imageSource) {
    $this->setSource($imageSource);
    $this->setImageInfo(getimagesize($imageSource));
    if(!$this->getImageInfo()){
      throw new main\ExtendedException('Unable to handle ' . $imageSource, main\ExtendedException::E_ERROR);
      return false;
    }
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
  private function setSource($source) {
    $this->source = $source;
  }

  /**
   * Image resource identifier
   * @return resource 
   */
  public function getIdentifier() {
    return $this->identifier;
  }

  /**
   * Image resource identifier
   * @param resource $identifier
   * @return void
   */
  protected function setIdentifier($identifier) {
    $this->identifier = $identifier;
    //preserving png transparency
    if ($this->getType() == IMAGETYPE_PNG) {
      imagealphablending($this->identifier, false);
    }
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
   * @example
   * Supported PHP Image constants examples:
   * IMAGETYPE_GIF == 1
   * IMAGETYPE_JPEG == 2
   * IMAGETYPE_PNG == 4
   * 
   */
  public function getType() {
    return $this->imageInfo[2];
  }

  /**
   * Image HTML
   * @param string $baseDirectory [optional] if $replaceDirectory is not given
   * it will be prepended to the original image source path
   * @param boolean $replaceDirectory [optional] if true is given, the original
   * image source path will be replaced by $baseDirectory
   * @return string
   * @example
   * source = "img/example.jpg";
   * getHTML("../");
   * return: <img src="../img/example.jpg" width="100" height="100" /> 
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
   * Returns the apropriate quality value to be used for a specific image type
   * @param int $qualityConstant
   * @return int
   */
  protected function getQuality($qualityConstant) {
    switch ($qualityConstant) {
      case self::QUALITY_HIGH:
        switch ($this->getType()) {
          case IMAGETYPE_JPEG or IMAGETYPE_GIF:
            $quality = 100;
            break;
          case IMAGETYPE_PNG:
            $quality = 9;
            break;
        }
        break;
      case self::QUALITY_MEDIUM:
        switch ($this->getType()) {
          case IMAGETYPE_JPEG or IMAGETYPE_GIF:
            $quality = 75;
            break;
          case IMAGETYPE_PNG:
            $quality = 7;
            break;
        }
        break;
      case self::QUALITY_HALF:
        switch ($this->getType()) {
          case IMAGETYPE_JPEG or IMAGETYPE_GIF:
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
   * Outputs image to the browser
   * @param int $qualityConstant[optional]
   * @return void
   */
  public function output($qualityConstant = self::QUALITY_HIGH) {
    if ($qualityConstant == null) {
      $qualityConstant = self::QUALITY_HIGH;
    }
    $quality = $this->getQuality($qualityConstant);

    $identifier = $this->getIdentifier();

    //outputting image to browser
    header("Content-Type: " . $this->getMIME());

    switch ($this->getType()) {
      case IMAGETYPE_GIF:
        imagegif($identifier);
        break;
      case IMAGETYPE_JPEG:
        imagejpeg($identifier, null, $quality);
        break;
      case IMAGETYPE_PNG:
        imagesavealpha($identifier, true);
        imagepng($identifier, null, $quality);
        break;
    }
    imagedestroy($identifier);
  }

  /**
   * Saves image
   * @param int $qualityConstant[optional] one of the ImageHandler quality
   * constants
   * @param string $fileSource[optional] if not given the original image source
   * path will be used and the previous image will be replaced
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
   * ATTENTION imagescale() is being implemented for PHP 5.5.0
   * Checkout if your server has this version avaliable
   * @param int $maxWidth
   * @param int $maxHeight[optional] if max height is not given it will use the
   * $maxWidth value
   * @return void
   */
  public function fitIntoDimension($maxWidth, $maxHeight = null) {
    $fit = false;
    $newWidth = $this->getWidth();
    $newHeight = $this->getHeight();

    if (!isset($maxHeight)) {
      $maxHeight = $maxWidth;
    }

    $widthFactor = (100 - ($maxWidth * 100 / $newWidth));
    $heightFactor = (100 - ($maxHeight * 100 / $newHeight));

    if ($widthFactor > 0 && $widthFactor >= $heightFactor) {
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
   * Resizes the image to a given dimension
   * @param int $width
   * @param int $height[optional] if not given, it will use $width value
   * @return void
   */
  public function resize($width, $height = null) {
    if (!isset($height)) {
      $height = $width;
    }
    $newImage = imagecreatetruecolor($width, $height);
    //preserving png transparency
    if ($this->getType() == IMAGETYPE_PNG) {
      imagealphablending($newImage, false);
    }
    imagecopyresampled($newImage, $this->getIdentifier(), 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
    $this->setIdentifier($newImage);
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
   * Emboss image
   * @return void
   */
  public function setEmboss() {
    imagefilter($this->getIdentifier(), IMG_FILTER_EMBOSS);
  }

  /**
   * Reverse image colors
   * @return void
   */
  public function setNegative() {
    imagefilter($this->getIdentifier(), IMG_FILTER_NEGATE);
  }

  /**
   * Rotate image
   * @param type $angle
   * @return void
   */
  public function rotate($angle) {
    $this->setIdentifier(imagerotate($this->getIdentifier(), $angle, 0));
  }

  /**
   * Add text on image
   * @param string $text to add on image
   * @param int $size[optional] from 1 to 5
   * @param int $x[optional] coordinate
   * @param int $y[optional] coordinate
   * @param int $red[optional] from 0 to 255
   * @param int $green[optional] from 0 to 255
   * @param int $blue[optional] from 0 to 255
   * @return void
   */
  public function addText($text, $size = 5, $x = 0, $y = 0, $red = 0, $green = 0, $blue = 0) {
    $color = imagecolorallocate($this->getIdentifier(), $red, $green, $blue);
    imagestring($this->getIdentifier(), $size, $x, $y, $text, $color);
  }

  /**
   * Add true type text on image
   * @param string $text to add on image
   * @param string $fontfile file path to the font
   * @param int $size[optional] font size
   * @param int $x[optional] coordinate
   * @param int $y[optional] coordinate
   * @param int $red[optional] from 0 to 255
   * @param int $green[optional] from 0 to 255
   * @param int $blue[optional] from 0 to 255
   * @param int $angle[optional] to rotate text counting counter-clockwise
   * @return void
   */
  public function addTrueTypeText($text, $fontfile, $size = 20, $x = 0, $y = null, $red = 0, $green = 0, $blue = 0, $angle = 0) {
    $color = imagecolorallocate($this->getIdentifier(), $red, $green, $blue);
    if (!isset($y)) {
      $y = imagesy($this->getIdentifier());
    }
    imagettftext($this->getIdentifier(), $size, $angle, $x, $y, $color, $fontfile, $text);
  }

  /**
   * Add image stamp
   * @param mixed $image This parameter accepts either an image source or
   * resource
   * @param int $x[optional] coordinate
   * @param int $y[optional] coordinate
   * @param int $pct[optional] percentage of transparency. This parameter wont
   * work with png alpha channel
   * @return void
   */
  public function addStamp($image, $x = 0, $y = 0, $pct = null) {
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
    if (isset($pct)) {
      imagecopymerge($this->getIdentifier(), $stamp, $x, $y, 0, 0, imagesx($stamp), imagesy($stamp), $pct);
    } else {
      imagecopy($this->getIdentifier(), $stamp, $x, $y, 0, 0, imagesx($stamp), imagesy($stamp));
    }
  }

}
