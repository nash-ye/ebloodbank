<?php
/**
 * Main Formatting API
 *
 * @package EBloodBank
 * @since 1.0
 */

/**
 * @return string
 * @since 1.0
 */
function trimTrailingSlash($string)
{
	return rtrim($string, '/\\');
}
