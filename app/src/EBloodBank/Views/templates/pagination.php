<?php
/**
 * Pagination Template
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
use EBloodBank as EBB;

$urls = EBB\getPaginationURLs([
    'total'    => $view->get('total'),
    'base_url' => $view->get('base_url'),
    'page_url' => $view->get('page_url'),
]);

if (! empty($urls)) : ?>
    <nav>
        <ul class="pagination">
            <?php if ($view->get('current') > 1) : ?>
            <li>
                <a href="<?= EBB\escURL($urls[$view->get('current') - 1]) ?>">
                    <span aria-hidden="true"><?= EBB\escHTML(__('&laquo;')) ?></span>
                </a>
            </li>
            <?php endif; ?>

            <?php foreach($urls as $number => $url) : ?>

            <?php if ($number == $view->get('current')) : ?>
            <li class="active"><span><?= EBB\escHTML(number_format($number)) ?></span></li>
            <?php else : ?>
            <li><a href="<?= EBB\escURL($url) ?>"><?= EBB\escHTML(number_format($number)) ?></a></li>
            <?php endif; ?>

            <?php endforeach; ?>

            <?php if ($view->get('current') < $view->get('total')) : ?>
                <li>
                    <a href="<?= EBB\escURL($urls[$view->get('current') + 1]) ?>">
                        <span aria-hidden="true"><?= EBB\escHTML(__('&raquo;')) ?></span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav><?php
endif;
