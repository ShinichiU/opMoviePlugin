<?php
$options = array(
  'title' => __('Movie Play Total List'),
  'list' => $sf_data->getRaw('totalList'),
  'moreInfo' => array(link_to(__('Show all'), '@movie'), link_to(__('Post your movie'), '@movie_new')),
  'type' => $sf_data->getRaw('gadget')->getConfig('type'),
  'row' => $row,
  'col' => $col,
);

op_include_parts('movieNineTable', 'totalList_'.$gadget->getId(), $options);
