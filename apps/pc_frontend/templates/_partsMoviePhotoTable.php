<?php use_helper('opMovieUtil') ?>
<?php if (count($options->list)): ?><? // if 1 ?>

<?php
$options->setDefault('col', 4);
$options->setDefault('type', 'full');

$options->addRequiredOption('link_to');
?>
<table>
<?php $row = ceil(count($options->list) / $options->col) ?>
<?php for ($i = 1; $row >= $i; $i++): ?><? // for 1 ?>

<?php if ($options->type === 'full' || $options->type === 'only_image'): ?><? // if 2 ?>
<tr class="photo">
<?php for ($j = ($i * $options->col) - $options->col; ($i * $options->col) > $j; $j++): ?><? // for 2 ?>
<td>
<?php if (!empty($options->list[$j])): ?><? // if 3 ?>
<?php if (0 === $j): ?><? // if 4 ?>
<p class="crown"><?php echo op_image_tag('icon_crown.gif', array('alt' => 'No.1')) ?></p>
<?php endif ?><? // endif 4 ?>
<?php echo link_to(op_movie_gen_img_tag($options->list[$j], 180), '@movie_show?id='.$options->list[$j]->id) ?>
<?php endif ?><? // endif 3 ?>
</td>
<?php endfor ?><? // endfor 2 ?>
</tr>
<?php endif ?><? // endif 2 ?>

<?php if ($options->type === 'full' || $options->type === 'only_name'): ?><? // if 2 ?>
<tr class="text">
<?php for ($j = ($i * $options->col) - $options->col; ($i * $options->col) > $j; $j++): ?><? // for 2 ?>
<td>
<?php if (!empty($options->list[$j])): ?><? // if 3 ?>
<?php echo link_to(op_movie_title_count($options->list[$j], MovieTable::PLAY_TOTAL), '@movie_show?id='.$options->list[$j]->id) ?>
<?php endif ?><? // endif 3 ?>
</td>
<?php endfor ?><? // endfor 2 ?>
</tr>
<?php endif ?><? // endif 2 ?>
<?php endfor ?><? // endfor 1 ?>
</table>

<?php endif ?><? // endif 1 ?>

<?php if ($options->offset > 0): ?><? // if 3 ?>
<?php echo link_to(__('Prev'), $options->getRaw('link_to_pager').($options->offset - 1)) ?>
<?php endif ?><? // if 3 ?>
<?php if (count($options->list) == $options->limit): ?><? // if 1 ?>
<?php echo link_to(__('Next'), $options->getRaw('link_to_pager').($options->offset + 1)) ?>
<?php endif ?><? // if 3 ?>
