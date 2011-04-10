<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */
class opMVMovieComponents extends sfComponents
{
  public function executeTotalListBox()
  {
    $this->member = $this->getUser()->getMember();
    $this->row = $this->gadget->getConfig('row');
    $this->col = $this->gadget->getConfig('col');

    $this->totalList = Doctrine::getTable('Movie')->getTotalList($this->row * $this->col);
  }
}
