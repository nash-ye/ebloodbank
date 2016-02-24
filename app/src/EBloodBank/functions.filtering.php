<?php
/**
 * Filtering functions file
 *
 * @package eBloodBank
 * @since   1.0
 */
namespace EBloodBank;

/**
 * Whether a specific value is a valid IP address.
 *
 * @return bool
 * @since 1.0
 */
function isValidIP($value)
{
    return filter_var($value, FILTER_VALIDATE_IP);
}

/**
 * Whether a specific value is a valid MAC address.
 *
 * @return bool
 * @since 1.0
 */
function isValidMAC($value)
{
    return filter_var($value, FILTER_VALIDATE_MAC);
}

/**
 * Whether a specific value is a valid URL address.
 *
 * @return bool
 * @since 1.0
 */
function isValidURL($value)
{
    return filter_var($value, FILTER_VALIDATE_URL);
}

/**
 * Whether a specific value is a valid E-mail address.
 *
 * @return bool
 * @since 1.0
 */
function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Whether a specific value is a valid integer number.
 *
 * @return bool
 * @since 1.0
 */
function isValidInteger($value)
{
    return filter_var($value, FILTER_VALIDATE_INT);
}

/**
 * Whether a specific value is a valid float number.
 *
 * @return bool
 * @since 1.0
 */
function isValidFloat($value)
{
    return filter_var($value, FILTER_VALIDATE_FLOAT);
}

/**
 * Whether a specific value is a valid numeric identifier.
 *
 * In other words, It checks whether the value
 * is a valid positive integer.
 *
 * @return bool
 * @since 1.0
 */
function isValidID($value)
{
    return (isValidInteger($value) && $value > 0);
}

/**
 * @return string
 * @since 1.0
 */
function sanitizeURL($value)
{
    return filter_var($value, FILTER_SANITIZE_URL);
}

/**
 * @return string
 * @since 1.0
 */
function sanitizeEmail($value)
{
    return filter_var($value, FILTER_SANITIZE_EMAIL);
}

/**
 * @return string
 * @since 1.0
 */
function sanitizeInteger($value)
{
    return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
}

/**
 * @return string
 * @since 1.0
 */
function sanitizeFloat($value)
{
    return filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}

/**
 * @return string
 * @since 1.0
 */
function sanitizeTitle($value)
{
    return trim(filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_NO_ENCODE_QUOTES));
}

/**
 * @return string
 * @since 1.0
 */
function sanitizeSlug($value)
{
    return filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_NO_ENCODE_QUOTES);
}
