<?php
/**
 * Delete the temporary directory for pdf exports
 */

$file_dir = elgg_get_data_path() . 'pdf_export';

if (is_dir($file_dir)) {
	$files = scandir($file_dir);

	// Remove the directory contents
	foreach ($files as $filename) {
		if ($filename == '.' || $filename == '..') {
			continue;
		}

		unlink("{$file_dir}/{$filename}");
	}

	// Remove the directory
	rmdir($file_dir);
}