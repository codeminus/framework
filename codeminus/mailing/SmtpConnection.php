<?php

namespace codeminus\mailing;

use codeminus\main as main;

/**
 * SmtpConnection
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 0.9b
 */
class SmtpConnection {

  private $socket;
  private $logs;

  /**
   * SmtpConnection
   * @param string $user The email to be used as smtp user
   * @param string $pass The email password
   * @param string $host The smtp host address or nome
   * @param int $port The connection port
   * @param int $timeout The time limit attempting to connect
   * @throws main\ExtendedException
   * @return SmtpConnection
   */
  public function __construct($user, $pass, $host = 'ssl://smtp.gmail.com', $port = 465, $timeout = 15) {
    $socket = fsockopen($host, $port, $errno, $errstr, $timeout);
    if ($socket) {
      $this->setSocket($socket);
      //$this->logResponse("Open socket using: host: $host:$port,user: $user, pass: $pass");
    } else {
      throw new main\ExtendedException("Could not connect to $host. $errno $errstr", E_ERROR);
    }

    $this->writeln("EHLO $host");
    $this->writeln("AUTH LOGIN");
    $this->writeln(base64_encode($user));
    $this->writeln(base64_encode($pass));
  }

  /**
   * The connection socket
   * @return int Resource identifier
   */
  public function getSocket() {
    return $this->socket;
  }

  /**
   * The connection socket
   * @param int $socket Resource identifier
   * @return void
   */
  protected function setSocket($socket) {
    $this->socket = $socket;
  }

  /**
   * The requests and responses log
   * @return array
   */
  public function getLogs() {
    return $this->logs;
  }

  /**
   * Logs request and response from a smtp command
   * @param string $request The smtp command
   * @return void
   */
  protected function logResponse($request) {
    $response = '';
    //$responseArray = array();
    while (substr($response, 3, 1) != " ") {
      $response = fgets($this->socket, 512);
      /*$responseArray[] = array(
        'code' => substr($response, 0, 3),
        'message' => substr($response, 3)
      );*/
    }
    $this->logs[] = array(
      'request' => $request,
      'response' => $response
    );
  }

  /**
   * Write to a smtp socket
   * @param string $request The smtp command/request
   * @return void
   */
  public function write($request) {
    fwrite($this->socket, $request);
    $this->logResponse($request);
  }

  /**
   * Write to a smtp socket and adds a new line at the end
   * @param string $request The smtp command/request
   * @return void
   */
  public function writeln($request = null) {
    $this->write($request . "\r\n");
  }

  /**
   * Closes the smtp connection
   * return void
   */
  public function close() {
    $this->writeln('QUIT');
    fclose($this->socket);
  }

}