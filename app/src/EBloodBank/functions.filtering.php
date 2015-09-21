<?php
/**
 * Filtering Functions
 *
 * @package EBloodBank
 * @since 1.0
 */
namespace EBloodBank;

/**
 * @return bool
 * @since 1.0
 */
function isValidIP($value)
{
    return filter_var($value, FILTER_VALIDATE_IP);
}

/**
 * @return bool
 * @since 1.0
 */
function isValidMAC($value)
{
    return filter_var($value, FILTER_VALIDATE_MAC);
}

/**
 * @return bool
 * @since 1.0
 */
function isValidURL($value)
{
    return filter_var($value, FILTER_VALIDATE_URL);
}

/**
 * @return bool
 * @since 1.0
 */
function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * @return bool
 * @since 1.0
 */
function isValidInteger($value)
{
    return filter_var($value, FILTER_VALIDATE_INT);
}

/**
 * @return bool
 * @since 1.0
 */
function isValidFloat($value)
{
    return filter_var($value, FILTER_VALIDATE_FLOAT);
}

/**
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
