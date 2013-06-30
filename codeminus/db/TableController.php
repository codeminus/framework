<?php
namespace codeminus\db;
/**
 * TableController Interface
 * Defines methods for database table controllers
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.0
 */
interface TableController {
  //Views
  public function add();
  public function edit($id);
  //Actions
  public function insertAction();
  public function updateAction($id);
  public function deleteAction($id);
}