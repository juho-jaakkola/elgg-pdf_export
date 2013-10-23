<?php
/**
 * Elgg PDF pageshell
 *
 * @uses $vars['title']       The page title
 * @uses $vars['body']        The main content of the page
 */

header("Content-type: text/html; charset=UTF-8");

$lang = get_current_language();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $lang; ?>" lang="<?php echo $lang; ?>">
<head>
<?php echo elgg_view('page/elements/head', $vars); ?>
</head>
	<body>
		<div class="elgg-page elgg-page-default">
			<div class="elgg-page-body">
				<div class="elgg-inner">
					<?php echo $vars['body']; ?>
				</div>
			</div>
		</div>
	</body>
</html>