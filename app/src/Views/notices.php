<?php
/**
 * Notices
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank\Kernal\Notices;

if (Notices::hasNotices()) { ?>
    <div class="notices">
        <ul>
            <?php foreach (Notices::getNotices() as $notice) : ?>
            <li class="notice notice-<?php echo $notice->code ?> notice-type-<?php echo $notice->type ?>">
                <p><?php echo $notice->msg ?></p>
            </li>
            <?php endforeach; ?>
        </ul>
    </div><?php
}