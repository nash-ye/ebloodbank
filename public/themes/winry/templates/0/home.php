<?php
/**
 * Home page template
 *
 * @package    WinryTheme
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => d__('winry', 'Home'), 'page_header.hide' => true]); ?>

    <div class="jumbotron">
        <h1><?= EBB\escHTML(d__('winry', 'Hello, life savers!')) ?></h1>
        <p><?= EBB\escHTML(d__('winry', 'Blood transfusion helps save millions of lives every year. Optimistically, we will help to save more.')) ?></p>
        <div class="btn-group" role="group">
            <?php if ($currentUser) : ?>
            <?= EBB\getAddDonorLink(['content' => EBB\escHTML(d__('winry', 'Add Donor')), 'atts' => ['class' => 'btn btn-lg btn-primary']], $context) ?>
            <?= EBB\getAddCityLink(['content' => EBB\escHTML(d__('winry', 'Add City')), 'atts' => ['class' => 'btn btn-lg btn-default']], $context) ?>
            <?= EBB\getAddDistrictLink(['content' => EBB\escHTML(d__('winry', 'Add District')), 'atts' => ['class' => 'btn btn-lg btn-default']], $context) ?>
            <?= EBB\getAddUserLink(['content' => EBB\escHTML(d__('winry', 'Add User')), 'atts' => ['class' => 'btn btn-lg btn-default']], $context) ?>
            <?php else : ?>
            <?= EBB\getSignupLink(['atts' => ['class' => 'btn btn-lg btn-primary']]) ?>
            <?= EBB\getLoginLink(['atts' => ['class' => 'btn btn-lg btn-default']]) ?>
            <?php endif; ?>
        </div>
    </div>

<?php
$view->displayView('footer');
