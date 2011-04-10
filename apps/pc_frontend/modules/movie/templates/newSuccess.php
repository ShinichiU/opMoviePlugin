<?php
$form->getWidget('title')->setAttribute('size', 40);
$form->getWidget('body')->setAttribute('rows', 10);
$form->getWidget('body')->setAttribute('cols', 50);

$options = array(
  'button' => __('Save'),
);

if ($form->isNew())
{
  $options['title'] = __('Post a movie');
  $options['url'] = url_for('movie_create');
  $options['isMultipart'] = true;
}
else
{
  $options['title'] = __('Edit the movie');
  $options['url'] = url_for('movie_update', $form->getObject());
  $options['isMultipart'] = false;
}

op_include_form('movieForm', $form, $options);
?>
