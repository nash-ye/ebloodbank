<?php
/**
 * Notices
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank\Notices;

if (Notices::hasNotices()) { ?>
    <div class="alerts">
        <?php foreach (Notices::getNotices() as $notice) : ?>
        <div class="alert alert-<?php echo $notice->type ?> alert-code-<?php echo $notice->code ?>" role="alert">
            <p><?php echo escHTML($notice->msg) ?></p>
        </div>
        <?php endforeach; ?>
    </div><?php
}