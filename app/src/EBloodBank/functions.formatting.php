<?php
/**
 * Formatting Functions
 *
 * @package EBloodBank
 * @since 1.0
 */
namespace EBloodBank;

/**
 * @return string
 * @since 1.0
 */
function trimTrailingSlash($string)
{
	return rtrim($string, '/\\');
}
