<?php

namespace codeminus\mailing;

use codeminus\main as main;

/**
 * E-mail sender
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.1
 */
class Email {

  private $smtp;
  private $header;
  private $from = array();
  private $cc = array();
  private $bcc = array();
  private $to = array();
  private $format;
  private $boundary;
  private $subject;
  private $textMessage;
  private $htmlMessage;
  private $attachments = array();

  /**
   * Email
   * @param \codeminus\mailing\SmtpConnection $smtp If not null, the send()
   * method will use the given SMTP instead of the php mail function to send
   * e-mails
   * @return Email
   */
  public function __construct(SmtpConnection $smtp = null) {
    if (isset($smtp)) {
      $this->setSmtp($smtp);
    }
    $this->boundary = md5(uniqid(time()));
  }

  /**
   * SMTP connection
   * @return \codeminus\mailing\SmtpConnection
   */
  public function getSmtp() {
    return $this->smtp;
  }

  /**
   * SMTP connection
   * @param \codeminus\mailing\SmtpConnection $smtp
   * @return void
   */
  public function setSmtp($smtp) {
    $this->smtp = $smtp;
  }

  /**
   * E-mail headers
   * @return string 
   */
  public function getHeader() {
    if (!isset($this->header)) {
      $header = "";
      $cc = "";
      $bcc = "";

      if (count($this->cc) > 0) {
        $cc = "Cc: " . $this->getCc() . "\r\n";
      }

      if (count($this->bcc) > 0) {
        $bcc = "BCc: " . $this->getBcc() . "\r\n";
      }

      $header .= "From: " . $this->getFrom() . "\r\n" .
        "Reply-To: " . $this->getFrom() . "\r\n" .
        $cc .
        $bcc;
      $header .= $this->getContentType();
      $header .= "X-Mailer: PHP/" . phpversion() . "\r\n";
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
    $this->format = $format;
  }

  /**
   * E-mail content type
   * @return string 
   */
  protected function getContentType() {
    $header = "MIME-Version: 1.0" . "\r\n";
    $header .= "Content-type: multipart/mixed; boundary=\"{$this->boundary}\"\r\n";
    return $header;
  }

  /**
   * Format e-mail
   * @param string $email
   * @param string $name
   * @return string
   */
  protected function formatEmail($email, $name) {
    if (isset($name)) {
      return $name . " <" . $email . "> ";
    } else {
      return $email;
    }
  }

  /**
   * E-mail sender
   * @param string $email Sender's e-mail address
   * @param string $name[optional] Sender's name
   * @return void
   */
  public function setFrom($email, $name = null) {
    $this->from["email"] = $email;
    $this->from["name"] = $name;
  }

  /**
   * E-mail sender
   * @param bool $formatted[optional] If TRUE, it will return a string like:
   * Some Name <somename@example.com>
   * @return string|array If $formatted is FALSE, it will return an associative
   * array containing 'email' and 'name'
   */
  public function getFrom($formatted = true) {
    if ($formatted) {
      return $this->formatEmail($this->from['email'], $this->from['name']);
    } else {
      return $this->from;
    }
  }

  /**
   * Carbon copy addresses
   * @param bool $formatted[optional] If TRUE, it will return a string
   * containing all e-mails separeted by ,(comma) like:
   * Some Name <somename@example.com>, Some Other Name <someothername@example.com>
   * @return string|array
   */
  public function getCc($formatted = true) {
    if ($formatted) {
      $formattedEmails = array();
      foreach ($this->cc as $account) {
        $formattedEmails[] = $this->formatEmail($account['email'], $account['name']);
      }
      return implode(', ', $formattedEmails);
    } else {
      return $this->cc;
    }
  }

  /**
   * Carbon copy address
   * @param string $email The e-mail address
   * @param string $name The e-mail's holder
   * @param bool $replace[optional] If TRUE it will delete all previous e-mail
   * addresses
   * @return void
   */
  public function addCc($email, $name, $replace = false) {
    if ($replace) {
      $this->cc = array();
    }
    $this->cc[] = array(
      "email" => $email,
      "name" => $name
    );
  }

  /**
   * Blind carbon copy addresses
   * @param bool $formatted[optional] If TRUE, it will return a string
   * containing all e-mails separeted by ,(comma) like:
   * Some Name <somename@example.com>, Some Other Name <someothername@example.com>
   * @return string|array
   */
  public function getBcc($formatted = true) {
    if ($formatted) {
      $formattedEmails = array();
      foreach ($this->bcc as $account) {
        $formattedEmails[] = $this->formatEmail($account['email'], $account['name']);
      }
      return implode(', ', $formattedEmails);
    } else {
      return $this->bcc;
    }
  }

  /**
   * Blind carbon copy address
   * @param string $email The e-mail address
   * @param string $name The e-mail's holder
   * @param bool $replace[optional] If TRUE it will delete all previous e-mail
   * addresses
   * @return void
   */
  public function addBcc($email, $name, $replace = false) {
    if ($replace) {
      $this->bcc = array();
    }
    $this->bcc[] = array(
      "email" => $email,
      "name" => $name
    );
  }

  /**
   * Recipient addresses
   * @param bool $formatted[optional] If TRUE, it will return a string
   * containing all e-mails separeted by ,(comma) like:
   * Some Name <somename@example.com>, Some Other Name <someothername@example.com>
   * @return string|array
   */
  public function getTo($formatted = true) {
    if ($formatted) {
      $formattedEmails = array();
      foreach ($this->to as $account) {
        $formattedEmails[] = $this->formatEmail($account['email'], $account['name']);
      }
      return implode(', ', $formattedEmails);
    } else {
      return $this->to;
    }
  }

  /**
   * Recipient address
   * @param string $email The e-mail address
   * @param string $name The e-mail's holder
   * @param bool $replace[optional] If TRUE it will delete all previous e-mail
   * addresses
   * @return void
   */
  public function addTo($email, $name, $replace = false) {
    if ($replace) {
      $this->to = array();
    }
    $this->to[] = array(
      "email" => $email,
      "name" => $name
    );
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
   * The message in text format only
   * @param bool $formatted[optional] If TRUE, it will format the message by
   * adding headers and line size limitation to it
   * @return string
   */
  public function getTextMessage($formatted = true) {
    if ($formatted) {
      if (isset($this->htmlMessage)) {
        $alt = "alt-";
      } else {
        $alt = '';
      }
      $msg = '';
      $msg .= "--{$alt}{$this->boundary}\r\n";
      $msg .= "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
      $msg .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
      $msg .= wordwrap($this->textMessage, 70) . "\r\n\r\n";
      return $msg;
    } else {
      return $this->textMessage;
    }
  }

  /**
   * The message in text format only
   * @param string $textMessage The text message
   * @return void
   */
  public function setTextMessage($textMessage) {
    $this->textMessage = $textMessage;
  }

  /**
   * The message in HTML format
   * @param bool $formatted If TRUE, it will format the message by adding
   * headers to it
   * @return string
   */
  public function getHtmlMessage($formatted = true) {
    if ($formatted) {
      if (isset($this->textMessage)) {
        $alt = "alt-";
      } else {
        $alt = '';
      }
      $msg = '';
      $msg .= "--{$alt}{$this->boundary}\r\n";
      $msg .= "Content-Type: text/html; charset=\"iso-8859-1\"\r\n";
      $msg .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
      $msg .= "{$this->htmlMessage}\r\n\r\n";
      return $msg;
    } else {
      return $this->htmlMessage;
    }
  }

  /**
   * The message in HTML format
   * @param string $htmlMessage The HTML message
   * @return void
   */
  public function setHtmlMessage($htmlMessage) {
    $this->htmlMessage = $htmlMessage;
  }

  /**
   * The e-mail attached files
   * @param bool $formatted If TRUE, it will format all attachments by adding
   * headers to them and putting all into a single string
   * @return string|array
   */
  public function getAttachments($formatted = true) {
    if ($formatted) {
      $attach = "";
      foreach ($this->attachments as $attachment) {
        $attach .= "--{$this->boundary}\r\n";
        $attach .= "Content-Type: application/octet-stream; name=\"{$attachment['filename']}\"\r\n";
        $attach .= "Content-Transfer-Encoding: base64\r\n";
        $attach .= "Content-Disposition: attachment; filename=\"{$attachment['filename']}\"\r\n\r\n";
        $attach .= "{$attachment['content']}\r\n\r\n";
        $attach .= "--{$this->boundary}\r\n";
      }
      return $attach;
    } else {
      return $this->attachments;
    }
  }

  /**
   * Attach a file to the e-mail
   * @param string $filePath The path to the file
   * @param bool $replace If TRUE, it will delete all previous attachments
   * @throws main\ExtendedException If the file is not found
   */
  public function addAttachment($filePath, $replace = false) {
    if (file_exists($filePath)) {
      if ($replace) {
        $this->attachments = array();
      }
      $this->attachments[] = array(
        'filename' => basename($filePath),
        'content' => chunk_split(base64_encode(file_get_contents($filePath)))
      );
    } else {
      throw new main\ExtendedException("{$filePath} not found", E_ERROR);
    }
  }

  /**
   * The complete message body containing the text message, the html message
   * and all the attachments along with their headers
   * @return string
   */
  public function getBody() {
    $body = '';
    if (isset($this->textMessage) && isset($this->htmlMessage)) {
      $body .= "--{$this->boundary}\r\n";
      $body .= "Content-type: multipart/alternative; boundary=\"alt-{$this->boundary}\"\r\n";
    }
    if (isset($this->textMessage)) {
      $body .= $this->getTextMessage();
    }
    if (isset($this->htmlMessage)) {
      $body .= $this->getHtmlMessage();
    }
    if (!empty($this->attachments)) {
      $body .= $this->getAttachments();
    }

    return $body;
  }

  /**
   * Send the e-mail
   * @return bool TRUE if the php mail function was executed with success or 
   * FALSE otherwise. If using SMTP to send e-mail, this method will return
   * TRUE if the response code from the server is equal to 250 after sending
   * .(end of message) request.
   */
  public function send() {
    if (isset($this->smtp)) {
      $from = $this->getFrom(false);
      $fromMail = $from['email'];
      $this->smtp->writeln("MAIL FROM: <{$fromMail}>");
      foreach ($this->to as $recipient) {
        $this->smtp->writeln("RCPT TO: <{$recipient['email']}>");
      }
      $this->smtp->writeln('DATA');
      $this->smtp->writeln('Subject: ' . $this->getSubject() . "\r\n" . 'To: ' . $this->getTo() . "\r\n" . $this->getHeader() . "\r\n\r\n" . $this->getBody() . "\r\n");
      $this->smtp->writeln('.');
      $logs = $this->smtp->getLogs();
      $lastMessage = $logs[count($this->smtp->getLogs()) - 1]['response'];
      $this->smtp->close();
      return substr($lastMessage, 0, 3) == 250;
    } else {
      return mail($this->getTo(), $this->getSubject(), $this->getBody(), $this->getHeader());
    }
  }

}