<?php

namespace codeminus\util;

/**
 * E-mail sender
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 0.9.1b
 */
class Email {

  /**
   * Specifies additional headers, like From, Cc, and Bcc.
   * The additional headers should be separated with a CRLF (\r\n)
   * @var string 
   */
  private $header;

  /**
   * Sender's name and e-mail address
   * @var array 
   */
  private $from = array();

  /**
   * Carbon-copy
   * @var array cc name and e-mail addresses
   */
  private $cc = array();

  /**
   * Blind carbon-copy
   * @var array bcc name and e-mail addresses
   */
  private $bcc = array();

  /**
   * Specifies the receiver / receivers of the email
   * @var array main receivers name and e-mail addresses
   */
  private $to = array();

  /**
   * Specifies the subject of the email
   * @var string 
   */
  private $subject;

  /**
   * Defines the message to be sent. Each line should be separated with a LF (\n).
   * Lines should not exceed 70 characters
   * @var string 
   */
  private $message;

  /**
   * E-mail message format TEXT or HTML
   * @var int 
   */
  private $format;

  const TEXT_FORMAT = 0;
  const HTML_FORMAT = 1;
  const SENDER = "from";
  const CC = "cc";
  const BCC = "bcc";
  const TO = "to";

  /**
   * E-mail
   * @param int $format[optional]
   * @return object Email
   */
  public function __construct($format = self::TEXT_FORMAT) {

    $this->setFormat($format);

    #$this->setFrom();
    #ini_set("SMTP","smtp.example.com" ); 
    #ini_set('sendmail_from', 'user@example.com');
  }

  /**
   * Sanitize and validate e-mail
   * @param string $email
   * @return string or boolean Returns the e-mail if it is valid or false if it is not.
   * @example filterEmail("some exam/ple@ example. c o m") returns someexample@example.com
   */
  protected function filterEmail($email) {

    //Clears out whitespaces and invalid characters.
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    //Validates e-mail
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return $email;
    } else {
      return false;
    }
  }

  /**
   * Format e-mail
   * @param string $email
   * @param string $name
   * @return string
   * @example formatEmail("someexample@example.com", "Some Example") returns Some Example <someexample@example.com> or only
   * someexample@example.com if name is not set.
   * 
   */
  protected function formatEmail($email, $name) {
    if (isset($name)) {
      return $name . " <" . $email . "> ";
    } else {
      return $email;
    }
  }

  /**
   * E-mail headers
   * @return string 
   */
  public function getHeader() {
    if (!isset($this->header)) {

      $cc = "";
      $bcc = "";

      if (count($this->cc) > 0) {
        $cc = "Cc: " . $this->getEmail(self::CC) . "\r\n";
      }

      if (count($this->bcc) > 0) {
        $bcc = "BCc: " . $this->getEmail(self::BCC) . "\r\n";
      }

      $header = $this->getContentType();

      $header .= "From: " . $this->getFrom() . "\r\n" .
              "Reply-To: " . $this->getFrom() . "\r\n" .
              $cc .
              $bcc .
              "X-Mailer: PHP/" . phpversion();

      return $header;
    } else {
      return $this->header;
    }
  }

  /**
   * E-mail headers
   * @param string $header
   * @return void
   */
  public function setHeader($header) {
    $this->header = $header;
  }

  /**
   * E-mail from address and name
   * @param string $email
   * @param string $name[optional]
   * @return void
   */
  public function setFrom($email, $name = null) {

    if ($this->filterEmail($email)) {
      $this->from["email"] = $this->filterEmail($email);
    } else {
      throw new Exception("Invalid e-mail address: " . $email);
    }

    $this->from["name"] = $name;
  }

  /**
   * E-mail from address and name
   * @param boolean $formatted
   * @return string if $formatted  is set to true and array if not
   */
  public function getFrom($formatted = true) {

    if ($formatted) {
      return $this->formatEmail($this->from["email"], $this->from["name"]);
    } else {
      return $this->from;
    }
  }

  /**
   * To, Cc or Bcc e-mail addresses
   * @param string $emailType Email::TO, Email::CC, Email::BCC
   * @param boolean $formatted[optional]
   * @return string if $formatted  is set to true and array if not
   */
  public function getEmail($emailType, $formatted = true) {

    if ($emailType == Email::TO || $emailType == Email::CC || $emailType == Email::BCC) {

      if ($formatted) {

        $addresses = $this->$emailType;

        $formattedEmails = "";

        for ($i = 0; $i < count($this->$emailType); $i++) {

          $formattedEmails .= $this->formatEmail($addresses[$i]["email"], $addresses[$i]["name"]);

          if ($i < (count($addresses) - 1)) {
            $formattedEmails .= ", ";
          }
        }

        return $formattedEmails;
      } else {
        return $this->$emailType;
      }
    } else {
      throw new Exception("Invalid e-mail type: " . $emailType);
    }
  }

  /**
   * To, Cc or Bcc e-mail addresses
   * @param string $emailType Email::TO, Email::CC, Email::BCC
   * @param string $email address
   * @param string $name[optional] owner
   * @param boolean $replaceAll[optional] if set to true, it replaces all previous added emails
   * @return void
   */
  public function addEmail($emailType, $email, $name = null, $replaceAll = false) {

    if ($emailType == Email::TO || $emailType == Email::CC || $emailType == Email::BCC) {

      if ($this->filterEmail($email)) {
        if ($replaceAll) {
          $this->to = array();
        }

        $emailArray = array(
            "name" => $name,
            "email" => $this->filterEmail($email)
        );

        array_push($this->$emailType, $emailArray);
      } else {
        throw new Exception("Invalid e-mail address: " . $email);
      }
    } else {
      throw new Exception("Invalid e-mail type: " . $emailType);
    }
  }

  /**
   * E-mail subject
   * @return string 
   */
  public function getSubject() {
    return $this->subject;
  }

  /**
   * E-mail subject
   * @param string $subject 
   * @return void
   */
  public function setSubject($subject) {
    $this->subject = $subject;
  }

  /**
   * E-mail message
   * @return string 
   */
  public function getMessage() {

    if ($this->format == self::TEXT_FORMAT) {
      return wordwrap($this->message, 70);
    } else {
      return $this->message;
    }
  }

  /**
   * E-mail message
   * @param string $message 
   * @return void
   */
  public function setMessage($message) {
    $this->message = $message;
  }

  /**
   * E-mail format
   * @return int 
   */
  public function getFormat() {
    return $this->format;
  }

  /**
   * E-mail format
   * @param int $format
   * @return void
   */
  protected function setFormat($format) {

    if ($format == self::TEXT_FORMAT || $format == self::HTML_FORMAT) {
      $this->format = $format;
    } else {
      $this->format = self::TEXT_FORMAT;
    }
  }

  /**
   * E-mail content type
   * @return string 
   */
  protected function getContentType() {

    switch ($this->format) {
      case self::TEXT_FORMAT:

        $header = "Content-type: text/plain\r\n";
        return $header;
        break;

      case self::HTML_FORMAT:

        $header = "MIME-Version: 1.0" . "\r\n";
        $header .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
        return $header;
        break;
    }
  }

  /**
   * Send e-mail
   * @return boolean 
   */
  public function send() {
    return mail($this->getEmail(Email::TO), $this->getSubject(), $this->getMessage(), $this->getHeader());
  }

}