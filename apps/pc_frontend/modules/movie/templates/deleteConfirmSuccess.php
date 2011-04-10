<div id="formMovie" class="dparts form"><div class="parts">
<div class="partsHeading"><h3><?php echo __('Movie delete confirm') ?></h3></div>
<?php include_partial('detailMovieTable', array('movie' => $movie, 'is_show_movie' => false)) ?>

<div class="operation">
<ul class="moreInfo button">
<li>
<?php echo $form->renderFormTag(url_for('movie_delete', $movie), array('method' => 'post')) ?>
<?php echo $form->renderHiddenFields(), "\n" ?>
<input type="submit" class="input_submit" value="<?php echo __('Delete') ?>" />
</form>
</li>
<li>
<?php echo $form->renderFormTag(url_for('movie_show', $movie), array('method' => 'get')) ?>
<input type="submit" class="input_submit" value="<?php echo __('Cancel') ?>" />
</form>
</li>
</ul>
</div>
</div>

</div>
