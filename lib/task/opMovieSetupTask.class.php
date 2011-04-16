<?php

class opMovieSetupTask extends sfBaseTask
{
  protected $hasError = null;

  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'pc_frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'opMovie';
    $this->name             = 'setup';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [opMovie:setup|INFO] task does things.
Call it with:

  [php symfony opMovie:setup|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $filesystem = $this->getFilesystem();
    $filesystem->chmod(sfConfig::get('op_movie_plugin_upload_dir'), 0777);
    $filesystem->chmod(sfFinder::type('dir')->in(sfConfig::get('op_movie_plugin_upload_dir')), 0777);

    if (!sfConfig::get('app_op_php_path') || !sfConfig::get('app_op_ffmpeg_path'))
    {
      while (true)
      {
        $php = $this->guessExecPath('php');
        $_php = $this->askAndValidate(array(
            'Set php path:',
            'or when you do not have any problem in a default value,',
            'please push the Enter button with non-input.',
            sprintf('DEFAULT [%s]', $php),
        ), new opValidatorString(array('required' => empty($php))));
        $php = $_php ? $_php : $php;
        if (file_exists($php))
        {
          break;
        }

        $this->logSection('file does\'nt exist ', 'path: '.$php);
      }

      while (true)
      {
        $ffmpeg = $this->guessExecPath('ffmpeg');
        $_ffmpeg = $this->askAndValidate(array(
            'Set ffmpeg path:',
            'or when you do not have any problem in a default value,',
            'please push the Enter button with non-input.',
            sprintf('DEFAULT [%s]', $ffmpeg),
        ), new opValidatorString(array('required' => empty($ffmpeg))));
        $ffmpeg = $_ffmpeg ? $_ffmpeg : $ffmpeg;
        if (file_exists($ffmpeg))
        {
          break;
        }

        $this->logSection('file does\'nt exist ', 'path: '.$ffmpeg);
      }

      $this->configureMovieApp($php, $ffmpeg);
    }

    $cc = new sfCacheClearTask($this->dispatcher, $this->formatter);
    $cc->run();
    if ($this->hasError)
    {
      $this->logSection('Install is faled!!');
    }
    else
    {
      $this->logSection('Install is successed!! Let\'s enjoy movie!');
    }
  }

  protected function configureMovieApp($php, $ffmpeg)
  {
    $file = implode(DIRECTORY_SEPARATOR, array(sfConfig::get('op_movie_plugin_root_dir'), 'config', 'app.yml'));

    $config = array();

    if (!file_exists($file))
    {
      $config['all'] = array(
        'op_php_path' => realpath($php),
        'op_ffmpeg_path' => realpath($ffmpeg),
      );
      if (!file_put_contents($file, sfYaml::dump($config)))
      {
        $this->hasError = true;
      }
    }
  }

  private function guessExecPath($command)
  {
    $r = exec('which '.(string)$command);
    if (!$r)
    {
      $r = exec('whereis '.(string)$command);
    }

    return $r;
  }
}
