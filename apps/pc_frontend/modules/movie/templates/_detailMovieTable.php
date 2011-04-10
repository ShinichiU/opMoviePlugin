<?php use_helper('opMovieUtil') ?>
<?php if ($is_show_movie): ?>
<script type="text/javascript">
  var flashvars = { url : "<?php echo url_for('@movie_bin?id='.$movie->id) ?>" };
  var params = { allowFullScreen : true };
  var attributes = { };
  swfobject.embedSWF(
    "<?php echo public_path('/opMoviePlugin/swf/player.swf') ?>",
    "movieShow",
    "552",
    "454",
    "9.0.0",
    "<?php echo public_path('/opMoviePlugin/js/swfobject/expressInstall.swf') ?>",
    flashvars,
    params,
    attributes
  );
</script>
<?php endif ?>
<dl>
<dt><?php echo op_format_date($movie->created_at, 'XDateTimeJaBr') ?></dt>
<dd>
<div class="title">
<p class="heading"><?php echo $movie->title; ?></p>
</div>
<div class="body">
<?php echo op_url_cmd(op_decoration(nl2br($movie->body))) ?>
</div>
<div class="movie">
<?php if ($is_show_movie && $movie->is_converted): ?>
<div id="movieShow">&nbsp;</div>
<?php elseif ($is_show_movie): ?>
<div class="innerMovieMessage">
<?php echo __('Movie being converted now. Please wait or reload this page.') ?>
<br />
<br />
<input class="input_submit" type="submit" value="<?php echo __('reload') ?>" onclick="location.reload(true); return false;" />
</div>
<?php else: ?>
<?php echo op_movie_gen_img_tag($movie) ?>
<?php endif ?>
</div>
</dd>
</dl>
