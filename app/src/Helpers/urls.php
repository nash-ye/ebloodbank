<?php

/**
 * @return bool
 * @since 1.0
 */
function isHTTPS()
{
    return ! empty($_SERVER['HTTPS']);
}

/**
 * @return string
 * @since 1.0
 */
function getBaseURL()
{
    if (isHTTPS()) {
        $url = 'https://';
    } else {
        $url = 'http://';
    }

    $url .= $_SERVER['SERVER_NAME'];

    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getCurrentURL()
{
    return getBaseURL() . $_SERVER['REQUEST_URI'];
}

/**
 * @return string
 * @since 1.0
 */
function getSiteURL(array $query = array())
{
    static $url = null;

    if (is_null($url)) {
        $root = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
        $url = str_replace($root, getBaseURL(), str_replace('\\', '/', eBloodBank\ABSPATH));
    }

    if (! empty($query)) {
        return "{$url}/index.php?" . http_build_query($query);
    }

    return $url;
}
