<?php
/**
 * Home Page Template
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
use EBloodBank as EBB;

$view->displayView('header', ['title' => __('Home'), 'page_header.hide' => true]); ?>

    <div class="jumbotron">
        <h1><?= EBB\escHTML(__('Hello, life savers!')) ?></h1>
        <p><?= EBB\escHTML(__('Transfusion of blood helps save millions of lives every year, optimistically we will help to save more.')) ?></p>
        <div class="btn-group" role="group">
            <?php if (EBB\isUserLoggedIn()) : ?>
            <?= EBB\getAddDonorLink(['content' => EBB\escHTML(__('Add Donor')), 'atts' => ['class' => 'btn btn-lg btn-primary']]) ?>
            <?= EBB\getAddCityLink(['content' => EBB\escHTML(__('Add City')), 'atts' => ['class' => 'btn btn-lg btn-default']]) ?>
            <?= EBB\getAddDistrictLink(['content' => EBB\escHTML(__('Add District')), 'atts' => ['class' => 'btn btn-lg btn-default']]) ?>
            <?= EBB\getAddUserLink(['content' => EBB\escHTML(__('Add User')), 'atts' => ['class' => 'btn btn-lg btn-default']]) ?>
            <?php else : ?>
            <?= EBB\getSignupLink(['atts' => ['class' => 'btn btn-lg btn-primary']]) ?>
            <?= EBB\getLoginLink(['atts' => ['class' => 'btn btn-lg btn-default']]) ?>
            <?php endif; ?>
        </div>
    </div>

<?php
$view->displayView('footer');
