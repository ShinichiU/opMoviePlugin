<?php

/**
 * PluginMovieTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginMovieTable extends Doctrine_Table
{
  const PUBLIC_FLAG_SNS     = 1;
  const PUBLIC_FLAG_FRIEND  = 2;

  const PLAY_TOTAL = 'getPlayTotal';
  const FAVO_TOTAL = 'getFavoTotal';

  protected static $publicFlags = array(
    self::PUBLIC_FLAG_SNS     => 'All Members',
    self::PUBLIC_FLAG_FRIEND  => 'Only %my_friend%',
  );

  public function getPublicFlags()
  {
    $publicFlags = array();

    foreach (self::$publicFlags as $key => $publicFlag)
    {
      $publicFlags[$key] = sfContext::getInstance()->getI18N()->__($publicFlag, array('%my_friend%' => Doctrine::getTable('SnsTerm')->get('my_friend')->pluralize()->titleize()), 'publicFlags');
    }

    return $publicFlags;
  }

  public function getTotalList($limit = 9, $offset = 0)
  {
    return $this->createQuery()
      ->orderBy('play_total DESC')
      ->addOrderBy('created_at DESC')
      ->limit($limit)
      ->offset($offset)
      ->execute();
  }

  /**
   * Returns an instance of this class.
   *
   * @return object PluginMovieTable
   */
  public static function getInstance()
  {
    return Doctrine_Core::getTable('PluginMovie');
  }
}
