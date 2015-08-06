<?php

/**
 * @return bool
 * @since 1.0
 */
function isCurrentUserCan($caps, $opt = 'AND')
{
    if (empty($caps)) {
        return false;
    }

    $current_user = getCurrentUser();

    if (empty($current_user)) {
        return false;
    }

    return $current_user->hasCaps((array) $caps, $opt);
}
