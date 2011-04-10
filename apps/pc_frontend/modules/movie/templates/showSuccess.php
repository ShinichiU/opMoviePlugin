<?php decorate_with('layoutC') ?>

<div class="dparts movieDetailBox"><div class="parts">
<div class="partsHeading"><h3><?php echo __('Movie of %1%', array('%1%' => $movie->Member->name)) ?></h3></div>

<?php include_partial('detailMovieTable', array('movie' => $movie, 'is_show_movie' => true)) ?>

<?php if ($movie->isEditable($sf_user->getMemberId())): ?>
<div class="operation">

<ul class="moreInfo button">
<li><form action="<?php echo url_for('movie_edit', $movie) ?>"><input type="submit" class="input_submit" value="<?php echo __('Edit this movie') ?>" /></form></li>
<li><form action="<?php echo url_for('movie_delete_confirm', $movie) ?>"><input type="submit" class="input_submit" value="<?php echo __('Delete this movie (confirm)') ?>" /></form></li>
</ul>
</div>
<?php endif; ?>
</div></div>
