<?php
/**
 * PDF object view
 *
 * @package Elgg
 * @subpackage Core
 */

$entity = elgg_extract('entity', $vars);
$content = elgg_extract('content', $vars);

$pubdate = elgg_view_friendly_time($entity->getTimeCreated());
$creator = $entity->getOwnerEntity()->name;

$item = <<<HTML
	<div class="elgg-subtext mbl">$creator $pubdate</div>
	<div class="elgg-content">
		<div class="elgg-output">
			$content
		</div>
	</div>
HTML;

echo $item;