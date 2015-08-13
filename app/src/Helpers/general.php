<?php

/**
 * @return void
 * @since 1.0
 */
function redirect($location)
{
    header("Location: $location");
    die();
}

/**
 * @return string
 * @since 1.0
 */
function trimTrailingSlash($string)
{
	return rtrim($string, '/\\');
}

/**
 * @return bool
 * @since 1.0
 */
function isVaildID($id)
{
    return (filter_var($id, FILTER_VALIDATE_INT) && $id > 0);
}
