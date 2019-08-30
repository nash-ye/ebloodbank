<?php
/**
 * View donor page template
 *
 * @package    WinryTheme
 * @subpackage Templates
 * @since      1.0
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => $donor->get('name')]); ?>

<?php $view->displayView('notices') ?>

<div id="view-donor-<?= EBB\escAttr($donor->get('id')) ?>" class="view-donor">

    <dl class="dl-horizontal">

        <dt><?= EBB\escHTML(d__('winry', 'Name')) ?></dt>
        <dd><?= EBB\escHTML($donor->get('name')) ?></dd>

        <dt><?= EBB\escHTML(d__('winry', 'Gender')) ?></dt>
        <dd><?= EBB\escHTML($donor->getGenderTitle()) ?></dd>

        <dt><?= EBB\escHTML(d__('winry', 'Birthdate')) ?></dt>
        <dd><?= EBB\escHTML($donor->get('birthdate')) ?></dd>

        <dt><?= EBB\escHTML(d__('winry', 'Blood Group')) ?></dt>
        <dd><?= EBB\escHTML($donor->get('blood_group')) ?></dd>

        <dt><?= EBB\escHTML(d__('winry', 'Weight (kg)')) ?></dt>
        <dd><?= EBB\escHTML($donor->getMeta('weight')) ?></dd>

		<?php $donorEmailVisibility = $donor->getMeta('email_visibility') ?>

		<?php if (! $donorEmailVisibility || 'everyone' === $donorEmailVisibility) : ?>
        <dt><?= EBB\escHTML(d__('winry', 'E-mail')) ?></dt>
        <dd><?= EBB\escHTML($donor->getMeta('email')) ?></dd>
		<?php elseif ('members' === $donorEmailVisibility) : ?>
        <dt><?= EBB\escHTML(d__('winry', 'E-mail')) ?></dt>
		<?php if (EBB\isUserLoggedIn() && $acl->canReadEntity(EBB\getCurrentUser(), $donor)) : ?>
        <dd><?= EBB\escHTML($donor->getMeta('email')) ?></dd>
		<?php else : ?>
		<dd><span class="text-warning"><?= EBB\escHTML(d__('winry', 'Only site members can view this donor e-mail address.')) ?></span></dd>
		<?php endif; ?>
		<?php elseif ('staff' === $donorEmailVisibility) : ?>
        <dt><?= EBB\escHTML(d__('winry', 'E-mail')) ?></dt>
		<?php if (EBB\isUserLoggedIn() && $acl->canEditEntity(EBB\getCurrentUser(), $donor)) : ?>
        <dd><?= EBB\escHTML($donor->getMeta('email')) ?></dd>
		<?php else : ?>
		<dd><span class="text-warning"><?= EBB\escHTML(d__('winry', 'Only site staff can view this donor e-mail address.')) ?></span></dd>
		<?php endif; ?>
		<?php endif; ?>

		<?php $donorPhoneVisibility = $donor->getMeta('phone_visibility') ?>

		<?php if (! $donorPhoneVisibility || 'everyone' === $donorPhoneVisibility) : ?>
        <dt><?= EBB\escHTML(d__('winry', 'Phone')) ?></dt>
        <dd><?= EBB\escHTML($donor->getMeta('phone')) ?></dd>
		<?php elseif ('members' === $donorPhoneVisibility) : ?>
        <dt><?= EBB\escHTML(d__('winry', 'Phone')) ?></dt>
		<?php if (EBB\isUserLoggedIn() && $acl->canReadEntity(EBB\getCurrentUser(), $donor)) : ?>
        <dd><?= EBB\escHTML($donor->getMeta('phone')) ?></dd>
		<?php else : ?>
		<dd><span class="text-warning"><?= EBB\escHTML(d__('winry', 'Only site members can view this donor phone number.')) ?></span></dd>
		<?php endif; ?>
		<?php elseif ('staff' === $donorPhoneVisibility) : ?>
        <dt><?= EBB\escHTML(d__('winry', 'Phone')) ?></dt>
		<?php if (EBB\isUserLoggedIn() && $acl->canEditEntity(EBB\getCurrentUser(), $donor)) : ?>
        <dd><?= EBB\escHTML($donor->getMeta('phone')) ?></dd>
		<?php else : ?>
		<dd><span class="text-warning"><?= EBB\escHTML(d__('winry', 'Only site staff can view this donor phone number.')) ?></span></dd>
		<?php endif; ?>
		<?php endif; ?>

        <dt><?= EBB\escHTML(d__('winry', 'City\District')) ?></dt>
        <dd><?= EBB\escHTML(sprintf(d__('winry', '%1$s\%2$s'), $donor->get('district')->get('city')->get('name'), $donor->get('district')->get('name'))) ?></dd>

        <dt><?= EBB\escHTML(d__('winry', 'Address')) ?></dt>
        <dd><?= EBB\escHTML($donor->getMeta('address')) ?></dd>

    </dl>

</div>

<?php
$view->displayView('footer');
