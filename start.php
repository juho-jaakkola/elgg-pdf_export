<?php

elgg_register_event_handler('init', 'system', 'pdf_init');

/**
 * Initialize the plugin
 */
function pdf_init () {
	elgg_set_config('pdf_export_supported_page_handlers', array('blog', 'pages'));
	elgg_set_config('pdf_export_supported_subtypes', array('blog', 'page', 'page_top'));

	elgg_register_page_handler('pdf', 'pdf_page_handler');

	elgg_register_plugin_hook_handler('route', 'all', 'pdf_export_add_extras_menu_item');
}

/**
 * Handle requests to www.example.com/pdf/*
 *
 * URLs take the form of
 *  Generate a PDF file: pdf/generate
 *  Download a PDF file: pdf/download
 *
 * @param array $page
 * @return boolean|void
 */
function pdf_page_handler($page) {
	$guid = get_input('guid');
	$entity = get_entity($guid);

	if (!$entity) {
		register_error(elgg_echo('noaccess'));
		forward(REFERER);
	}

	$friendly_title = elgg_get_friendly_title($entity->title);
	$pdf_file_name =  "{$guid}-{$friendly_title}.pdf";
	$pdf_file_path = elgg_get_data_path() . "pdf_export/$pdf_file_name";

	if ($page[0] == 'generate') {
		$description = get_input('content');

		elgg_set_viewtype('pdf');
		$content = elgg_view('object/default', array(
			'entity' => $entity,
			'content' => $description,
		));

		$params = array(
			'title' => $entity->title,
			'content' => $content,
		);

		$body = elgg_view_layout('default', $params);
		$html = elgg_view_page($entity->title, $body);

		$html_file_path = elgg_get_data_path() . "pdf_export/{$guid}-{$friendly_title}.html";
		file_put_contents($html_file_path, $html);
		$command = "xvfb-run --server-args=\"-screen 0, 1024x768x24\" wkhtmltopdf $html_file_path $pdf_file_path";
		shell_exec($command);
		unlink($html_file_path);

		return true;
	}

	if ($page[0] == 'download') {
		header("Pragma: public");
		header('Content-type: application/pdf');
		header("Content-Disposition: inline; filename=\"$pdf_file_name\"");
		ob_clean();
		flush();
		readfile($pdf_file_path);
		unlink($pdf_file_path);
		exit;
	}
}

/**
 * Add pdf generation link to the pages that support it
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param array  $return Array containing handler name and page segment
 * @param null
 * @return array
 */
function pdf_export_add_extras_menu_item($hook, $type, $return, $params) {
	if (!elgg_is_logged_in()) {
		return $return;
	}

	if (!isset($return['handler'])) {
		return $return;
	}

	if (!in_array($return['handler'], elgg_get_config('pdf_export_supported_page_handlers'))) {
		return $return;
	}

	if (empty($return['segments'][0]) || $return['segments'][0] != 'view') {
		return $return;
	}

	if (isset($return['segments'][1])) {
		$guid = $return['segments'][1];
		elgg_require_js('pdf_export/export');

		elgg_register_menu_item('extras', array(
			'name' => 'pdf',
			'href' => "pdf/generate?guid={$guid}",
			'text' => elgg_view_icon('download'),
		));
	}

	return $return;
}