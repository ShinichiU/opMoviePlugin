<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

function op_movie_gen_img_tag($movie, $size = 500)
{
  return tag('img', array(
    'src' => url_for('@movie_image_bin?size='.$size.'&id='.$movie->id),
    'title' => $movie->title,
    'alt' => $movie->title
  ));
}
