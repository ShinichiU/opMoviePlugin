<?php

class opMovieGenerateflvTask extends sfDoctrineBaseTask
{
  const ERR = 'err';
  const INFO = 'info';
  const DEBUG = 'debug';

  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('movie-id', sfCommandArgument::REQUIRED, 'Movie autoincriment Id'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'pc_frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'opMovie';
    $this->name             = 'generate-flv';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [opMovie:generate-flv|INFO] task does things.
Call it with:

  [php symfony opMovie:generate-flv|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    if (!sfContext::hasInstance($options['application']))
    {
      sfContext::createInstance($this->createConfiguration($options['application'], $options['env']), $application);
    }

    $this->_log('START', self::INFO, array('movie-id' => $arguments['movie-id']));

    $movie = Doctrine_Core::getTable('Movie')->find($arguments['movie-id']);

    if (!file_exists(sfConfig::get('app_op_ffmpeg_path')))
    {
      $message = 'ffmpeg path is not exist. Please check plugins/opMoviePlugin/config/app.yml ffmpeg path.';
      $this->_log('START', self::ERR, array('message' => $message));

      throw new sfException($message);
    }

    if (!$movie)
    {
      $message = 'The movie does not exist';
      $this->_log('START', self::ERR, array('message' => $message, 'movie-id' => $arguments['movie-id']));

      throw new sfException($message);
    }

    if ($movie->getIsConverted())
    {
      $message = 'The movie is already converted';
      $this->_log('START', self::ERR, array('message' => $message, 'movie-id' => $arguments['movie-id']));

      throw new sfException($message);
    }

    $this->_log('START_EXEC', self::INFO, array('movie-id' => $arguments['movie-id']));
    $exec_command = sprintf(
      '%s -i %s -f flv -ab 64 -s 512x384 -ac 1 %s',
      sfConfig::get('app_op_ffmpeg_path'),
      $movie->getFilePath(),
      $movie->getFlvFilePath()
    );
    $this->_log('START_EXEC', self::DEBUG, array('comand' => $exec_command));
    $result = exec($exec_command);

    if (!file_exists($movie->getFlvFilePath()))
    {
      $this->_log('START_EXEC', self::ERR, array('message' => $result, 'movie-id' => $arguments['movie-id']));

      throw new sfException($result);
    }

    $this->_log('END_EXEC', self::INFO, array('message' => $result, 'movie-id' => $arguments['movie-id']));

    if (!@unlink($movie->getFilePath()))
    {
      $message = 'Cannot delete old movie file';
      $this->_log('END', self::ERR, array('message' => $message, 'movie-id' => $arguments['movie-id']));

      throw new sfException($message);
    }

    try
    {
      $movie->setIsConverted(true);
      $movie->save();
    }
    catch (Doctrine_Exception $e)
    {
      $this->_log('END', self::ERR, array('message' => $e->getMessage(), 'code' => $e->getCode(), 'movie-id' => $arguments['movie-id']));

      throw new sfException($e->getMessage());
    }

    $this->_log('END', self::INFO, array('movie-id' => $arguments['movie-id']));
  }

  protected function _log($lavel, $level, $options = array())
  {
    $values = array();
    if (isset($options[0]))
    {
      $values[] = implode(',', $options);
    }
    else
    {
      foreach ($options as $key => $value)
      {
        $values[] = sprintf('%s: %s', $key, $value);
      }
    }

    $msg = '';
    if (count($values))
    {
      $msg .= implode(', ', $values);
    }
    $this->logSection($lavel, $msg);

    if (!sfConfig::get('sf_logging_enabled'))
    {
      return false;
    }
    $msg = sprintf('[movie-convert] %s', $lavel).' -> '.$msg;
    sfContext::getInstance()->getLogger()->$level($msg);
  }
}
