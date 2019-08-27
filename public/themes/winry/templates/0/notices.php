<?php
/**
 * Page notices template
 *
 * @package    WinryTheme
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;
use EBloodBank\Notices;

$notices = Notices::getNotices();

if (! empty($notices) && is_array($notices)) : ?>
    <div class="alerts">
        <?php foreach ($notices as $notice) : ?>
        <div class="alert alert-<?= $notice->type ?> alert-code-<?= $notice->code ?>" role="alert">
            <p><?= EBB\escHTML($notice->msg) ?></p>
        </div>
        <?php endforeach; ?>
    </div><?php
endif;
