<?php
$options = array(
  'title' => __('Movie List'),
  'list' => $totalList,
  'link_to' => '@movie_show?id=',
  'link_to_pager' => '@movie_offset?offset=',
  'offset' => $offset,
  'limit' => $limit,
);
op_include_parts('moviePhotoTable', 'movieList', $options)
?>
