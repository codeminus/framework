<?php

namespace codeminus\util;

/**
 * Default system messages for database table operation
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.0
 */
class TableMessage extends SystemMessage {
  const REC_ADDED = -1;
  const REC_UPDATED = -2;
  const REC_DELETED = -3;
  const NUll_FIELD = -4;
  
  public function __construct($code = self::NONE, $additionalInfo = "") {
    parent::__construct($code, $additionalInfo);
    $this->addMessage(self::REC_ADDED, 'Record added with success.');
    $this->addMessage(self::REC_UPDATED, 'Record updated with success.');
    $this->addMessage(self::REC_DELETED, 'Record deleted with success.');
    $this->addMessage(self::NUll_FIELD, 'Required field not given.', SystemMessage::ERROR_MESSAGE);
  }
}
