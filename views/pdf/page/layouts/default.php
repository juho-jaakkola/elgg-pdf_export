<?php
/**
 * Elgg default PDF layout 
 *
 * @uses $vars['content'] Content string
 * @uses $vars['class']   Additional class to apply to layout
 */

$class = '';
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}
?>
<div class="<?php echo $class; ?>">
	<div class="elgg-body elgg-main">
		<div class="elgg-head clearfix">
			<?php echo elgg_view_title($vars['title']); ?>
		</div>
		<div class="elgg-content">
			<div class="elgg-output">
				<?php echo $vars['content']; ?>
			</div>
		</div>
	</div>
</div>