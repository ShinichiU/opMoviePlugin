<?php

/**
 * movie actions.
 *
 * @package    OpenPNE
 * @subpackage movie
 * @author     Your name here
 */
class opMVActions extends sfActions
{
  const CREATE = 'create';
  const UPDATE = 'update';
  const DELETE = 'delete';

  protected $messages = array(
    self::CREATE => array(
      'error' => '動画の登録に失敗しました',
      'notice' => '動画を登録しました',
    ),
    self::UPDATE => array(
      'error' => '動画情報の更新に失敗しました',
      'notice' => '動画情報を更新しました',
    ),
    self::DELETE => array(
      'error' => '動画の削除に失敗しました',
      'notice' => '動画を削除しました',
    ),
  );

  public function preExecute()
  {
    if (is_callable(array($this->getRoute(), 'getObject')))
    {
      $object = $this->getRoute()->getObject();
      if ($object instanceof Movie)
      {
        $this->movie = $object;
        $this->member = $object->Member;
      }
      if ($object instanceof Member)
      {
        $this->member = $object;
      }
    }

    if ($this->member && $this->member->id !== $this->getUser()->getMemberId())
    {
      $this->relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($this->member->id, $this->getUser()->getMemberId());
      $this->forwardIf($this->relation && $this->relation->is_access_block, 'default', 'error');
    }
  }

  public function postExecute()
  {
    if ($this->member && $this->getUser()->isAuthenticated())
    {
      $this->setNavigation($this->member);
    }
  }

  protected function setNavigation(Member $member)
  {
    if ($member->id !== $this->getUser()->getMemberId())
    {
      sfConfig::set('sf_nav_type', 'friend');
      sfConfig::set('sf_nav_id', $member->id);
    }
  }

  protected function processForm(BaseForm $form, sfWebRequest $request, $type = null)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if (!$this->setFlashMessageByType($form->isValid(), $type))
    {
      $this->setTemplate('new');

      return false;
    }

    $obj = $form->save();
    $this->redirect('@movie_show?id='.$obj->id);
  }

  protected function processDelete(Doctrine_Record $obj, sfWebRequest $request, $type = null)
  {
    $request->checkCSRFProtection();
    $this->setFlashMessageByType($obj->delete(), $type);

    $this->redirect('@movie');
  }

  protected function setFlashMessageByType($condition, $type = null)
  {
    $flash = $condition ? 'notice' : 'error';
    if (null !== $type && isset($this->messages[$type][$flash]))
    {
      $this->getUser()->setFlash($flash, $this->messages[$type][$flash], $condition);
    }

    return $condition;
  }

  protected function hasPermission(sfWebRequest $request)
  {
    if (!$this->getUser()->isAuthenticated())
    {
      // HTTP 401 Unauthorized
      $this->setStatusCode(401);

      return false;
    }

    if (!$this->getUser()->hasCredential('SNSMember'))
    {
      // HTTP 403 Forbidden
      $this->setStatusCode(403);

      return false;
    }

    $this->movie = Doctrine_Core::getTable('Movie')->find($request->getParameter('id'));

    if (
      !$this->movie
      || !$this->movie->isShowable($this->getUser()->getMemberId())
    )
    {
      // HTTP 404 Not Found
      $this->getResponse()->setStatusCode(404);

      return false;
    }

    return true;
  }

  protected function setStatusCode($statusCode)
  {
    $this->getResponse()->setStatusCode($statusCode);
  }
}
