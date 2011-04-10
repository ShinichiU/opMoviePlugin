<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opMoviePluginException
 *
 * @package    OpenPNE
 * @subpackage exception
 * @author     Shinichi Urabe <urabe@tejimaya.com>
 */
class opMoviePluginException extends sfException
{
  /**
   * cannot open movie cache dir
   */
  const cannot_open_movie_cache_dir = '100';

  /**
   * cannot put movie file
   */
  const cannot_put_movie_file = '101';
}
