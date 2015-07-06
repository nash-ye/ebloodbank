<?php

/**
 * @return bool
 * @since 1.0
 */
function isVaildID($id)
{
    $id = (int) $id;
    return ($id > 0);
}

/**
 * @return void
 * @since 1.0
 */
function redirect($location)
{
    header("Location: $location");
    die();
}
