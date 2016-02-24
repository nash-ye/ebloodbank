<?php
/**
 * Formatting functions file
 *
 * @package eBloodBank
 * @since   1.0
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
