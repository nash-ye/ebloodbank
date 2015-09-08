<?php
/**
 * Context Functions
 *
 * @package EBloodBank
 * @since 1.0
 */
namespace EBloodBank;

/**
 * @return bool
 * @since 1.0
 */
function isInstaller()
{
    return ('install.php' === basename($_SERVER['SCRIPT_NAME']));
}
