<?php
/**
 * HTTP Functions
 *
 * @package EBloodBank
 * @since 1.0
 */

/**
 * @return void
 * @since 1.0
 */
function redirect($url, $status = 302)
{
    if (isValidURL($url)) {
        header("Location: $url", true, (int) $status);
        die(0);
    }
}
