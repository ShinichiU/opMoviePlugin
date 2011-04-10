<?php

/**
 * movie actions.
 *
 * @package    OpenPNE
 * @subpackage movie
 * @author     Your name here
 */
class opMVMovieActions extends opMVActions
{
 /**
  * Executes List action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeList(sfWebRequest $request)
  {
    return sfView::NONE;
  }

 /**
  * Executes Show action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeShow(sfWebRequest $request)
  {
    $this->forward404Unless($this->movie->isShowable($this->getUser()->getMemberId(), $this->relation));
    if (!$this->movie->hasFileCache())
    {
      return sfView::ERROR;
    }
  }

 /**
  * Executes GetImageBin action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeGetImageBin(sfWebRequest $request)
  {
    sfConfig::set('sf_web_debug', false);
    $this->getUser()->undeleteFlash();

    if ($this->hasPermission($request))
    {
      $this->movie->outputImageBin($request->getParameter('size'));
    }

    return sfView::NONE;
  }

 /**
  * Executes GetBin action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeGetBin(sfWebRequest $request)
  {
    sfConfig::set('sf_web_debug', false);
    $this->getUser()->undeleteFlash();
    set_time_limit(0);

    if ($this->hasPermission($request))
    {
      Doctrine_Core::getTable('MoviePlayRank')->updateRank($this->movie, $this->getUser()->getMember());
      header('Cache-Control: public');
      header('Expires: '.date(DATE_RFC2822, strtotime('20year')));
      header('Pragma: public');
      header('Content-Type: video/x-flv');
      header('Content-Disposition: attachment; filename="'.$movie->id.'.flv"');
      header('Content-Length: '.filesize($this->movie->getFlvFilePath()));
      $handle = fopen($this->movie->getFlvFilePath(), 'rb');
      while(!feof($handle))
      {
        echo fread($handle, 8192);
      }
      fclose($handle);
    }

    return sfView::NONE;
  }

 /**
  * Executes New action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeNew(sfWebRequest $request)
  {
    $this->form = new MovieForm();
  }

 /**
  * Executes Create action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeCreate(sfWebRequest $request)
  {
    $this->form = new MovieForm();
    $this->processForm($this->form, $request, self::CREATE);
  }

 /**
  * Executes Edit action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($this->movie->isEditable($this->getUser()->getMemberId()));
    $this->form = new MovieForm($this->movie);
    $this->setTemplate('new');
  }

 /**
  * Executes Update action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($this->movie->isEditable($this->getUser()->getMemberId()));
    $this->form = new MovieForm($this->movie);
    $this->processForm($this->form, $request, self::UPDATE);
  }

 /**
  * Executes DeleteConfirm action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeDeleteConfirm(sfWebRequest $request)
  {
    $this->forward404Unless($this->movie->isEditable($this->getUser()->getMemberId()));
    $this->form = new BaseForm();
  }

 /**
  * Executes Delete action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeDelete(sfWebRequest $request)
  {
    $this->forward404Unless($this->movie->isEditable($this->getUser()->getMemberId()));
    $this->processDelete($this->movie, $request, self::DELETE);
  }
}
