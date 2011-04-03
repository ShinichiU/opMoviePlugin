<?php

/**
 * movie actions.
 *
 * @package    OpenPNE
 * @subpackage movie
 * @author     Your name here
 */
class movieActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }
}
