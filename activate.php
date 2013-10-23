<?php
/**
 * Create a temporary directory for pdf exports
 * 
 * @todo Check server requirements and add an admin notice if necessary
 */

$file_dir = elgg_get_data_path() . 'pdf_export';

if (!is_dir($file_dir)) {
	// Create empty directory for the userexport
	if (!mkdir($file_dir, 0755)) {
		// Creating the directory failed
		elgg_add_admin_notice(elgg_echo('pdf_export:admin_notice:dir_creation_failed', array($file_dir)));
		return false;
	}
}