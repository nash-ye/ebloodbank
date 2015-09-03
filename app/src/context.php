<?php
/**
 * Main Context API
 *
 * @package EBloodBank
 * @since 1.0
 */

/**
 * @return bool
 * @since 1.0
 */
function isInstaller()
{
    return ('install.php' === basename($_SERVER['SCRIPT_NAME']));
}
