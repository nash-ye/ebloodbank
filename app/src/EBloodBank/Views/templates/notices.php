<?php
/**
 * Notices List Template
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank as EBB;
use EBloodBank\Notices;

if (Notices::hasNotices()) : ?>
    <div class="alerts">
        <?php foreach (Notices::getNotices() as $notice) : ?>
        <div class="alert alert-<?= $notice->type ?> alert-code-<?= $notice->code ?>" role="alert">
            <p><?= EBB\escHTML($notice->msg) ?></p>
        </div>
        <?php endforeach; ?>
    </div><?php
endif;