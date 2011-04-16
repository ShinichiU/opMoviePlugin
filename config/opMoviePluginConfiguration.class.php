<?php

/**
 * The opMoviePluginConfiguration class.
 *
 * @package    opMoviePluginConfiguration
 * @subpackage config
 * @author     Shinichi Urabe <urabe@tejimaya.com>
 */
class opMoviePluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
    sfConfig::set('op_movie_plugin_root_dir', realpath(implode(DIRECTORY_SEPARATOR, array(dirname(__FILE__), '..'))));
    sfConfig::set('op_movie_plugin_upload_dir', realpath(implode(DIRECTORY_SEPARATOR, array(dirname(__FILE__), '..', 'cache'))));
  }
}
