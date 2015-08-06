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
function getSiteURL($path = '')
{
    static $url = null;
    if (is_null($url)) {
        $root = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
        $url = str_replace($root, getBaseURL(), str_replace('\\', '/', ABSPATH));
    }

    if (! empty($path)) {
        return $url . '/' . ltrim( $path, '/' );
    }

    return $url;
}

/**
 * @return string
 * @since 1.0
 */
function getPageURL($page, array $query = array())
{
    $query = array_merge(array( 'page' => $page ), $query);
    return getSiteURL('/') . '?' . http_build_query($query);
}
