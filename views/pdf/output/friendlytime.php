<?php
/**
 * Friendly time
 * Translates an epoch time into a human-readable time.
 * 
 * @uses string $vars['time'] Unix-style epoch timestamp
 */

echo htmlspecialchars(date(elgg_echo('pdf_export:date_format'), $vars['time']));