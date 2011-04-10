<?php

/**
 * PluginMovie form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Shinichi Urabe <urabe@tejimaya.com>
 */
abstract class PluginMovieForm extends BaseMovieForm
{
  public function setup()
  {
    parent::setup();

    unset($this['id']);
    $useFields = array('title', 'body', 'public_flag');

    $this->widgetSchema['title'] = new sfWidgetFormInput();
    $this->widgetSchema['body']  = new opWidgetFormRichTextareaOpenPNE();
    $options = array(
    );
    $this->widgetSchema['public_flag'] = new sfWidgetFormChoice(array(
      'choices'  => Doctrine_Core::getTable('Movie')->getPublicFlags(),
      'expanded' => true,
    ));

    $this->validatorSchema['title'] = new opValidatorString(array('trim' => true));
    $this->validatorSchema['body']  = new opValidatorString(array('rtrim' => true));
    $this->validatorSchema['public_flag'] = new sfValidatorChoice(array(
      'choices' => array_keys(Doctrine_Core::getTable('Movie')->getPublicFlags()),
    ));
    if ($this->isNew())
    {
      $this->widgetSchema['movie'] = new sfWidgetFormInputFile(array(), array('size' => 40));
      $this->validatorSchema['movie'] = new sfValidatorFile(
        array(
          'required' => true,
          'mime_types' => array(
            'video/x-ms-asf',
            'video/x-msvideo',
            'video/x-flv',
            'video/quicktime',
            'video/mp4',
            'video/mpeg',
            'video/x-ms-wmv',
          )
        ),
        array('mime_types' => 'this file is not supported.')
      );
      $useFields[] = 'movie';

      $this->setDefault('public_flag', PluginMovieTable::PUBLIC_FLAG_SNS);
    }
    $this->useFields($useFields);
  }

  public function doSave($conn)
  {
    if ($this->isNew())
    {
      if (!file_exists(sfConfig::get('op_movie_plugin_upload_dir')))
      {
        throw new opMoviePluginException('cannot open movie cache directory', opMoviePluginException::cannot_open_movie_cache_dir);
      }
      $file_name = md5(uniqid(rand(), 1));
      $file_path = sfConfig::get('op_movie_plugin_upload_dir').DIRECTORY_SEPARATOR.$file_name;
      if (!file_put_contents($file_path, file_get_contents($this->getValue('movie')->getTempName())))
      {
        throw new opMoviePluginException('cannot put movie file', opMoviePluginException::cannot_put_movie_file);
      }
      $this->getObject()->setFileName($file_name);
    }

    parent::doSave($conn);
  }
}
