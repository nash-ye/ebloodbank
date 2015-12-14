<?php
/**
 * View donor page template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.1
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => $donor->get('name')]); ?>

<?php $view->displayView('notices') ?>

<div id="view-donor-<?= EBB\escAttr($donor->get('id')) ?>" class="view-donor">

    <dl class="dl-horizontal">

        <dt><?= EBB\escHTML(__('Name')) ?></dt>
        <dd><?= EBB\escHTML($donor->get('name')) ?></dd>

        <dt><?= EBB\escHTML(__('Gender')) ?></dt>
        <dd><?= EBB\escHTML($donor->getGenderTitle()) ?></dd>

        <dt><?= EBB\escHTML(__('Birthdate')) ?></dt>
        <dd><?= EBB\escHTML($donor->get('birthdate')) ?></dd>

        <dt><?= EBB\escHTML(__('Blood Group')) ?></dt>
        <dd><?= EBB\escHTML($donor->get('blood_group')) ?></dd>

        <dt><?= EBB\escHTML(__('Weight (kg)')) ?></dt>
        <dd><?= EBB\escHTML($donor->getMeta('weight')) ?></dd>

		<?php $donorEmailVisibility = $donor->getMeta('email_visibility') ?>

		<?php if (! $donorEmailVisibility || 'everyone' === $donorEmailVisibility) : ?>
        <dt><?= EBB\escHTML(__('E-mail')) ?></dt>
        <dd><?= EBB\escHTML($donor->getMeta('email')) ?></dd>
		<?php elseif ('members' === $donorEmailVisibility) : ?>
        <dt><?= EBB\escHTML(__('E-mail')) ?></dt>
		<?php if (EBB\isUserLoggedIn() && EBB\getCurrentUser()->canViewDonor($donor)) : ?>
        <dd><?= EBB\escHTML($donor->getMeta('email')) ?></dd>
		<?php else: ?>
		<dd><span class="text-warning"><?= EBB\escHTML(__('Only site members can view this donor e-mail address.')) ?></span></dd>
		<?php endif; ?>
		<?php elseif ('staff' === $donorEmailVisibility) : ?>
        <dt><?= EBB\escHTML(__('E-mail')) ?></dt>
		<?php if (EBB\isUserLoggedIn() && EBB\getCurrentUser()->canEditDonor($donor)) : ?>
        <dd><?= EBB\escHTML($donor->getMeta('email')) ?></dd>
		<?php else: ?>
		<dd><span class="text-warning"><?= EBB\escHTML(__('Only site staff can view this donor e-mail address.')) ?></span></dd>
		<?php endif; ?>
		<?php endif; ?>

		<?php $donorPhoneVisibility = $donor->getMeta('phone_visibility') ?>

		<?php if (! $donorPhoneVisibility || 'everyone' === $donorPhoneVisibility) : ?>
        <dt><?= EBB\escHTML(__('Phone')) ?></dt>
        <dd><?= EBB\escHTML($donor->getMeta('phone')) ?></dd>
		<?php elseif ('members' === $donorPhoneVisibility) : ?>
        <dt><?= EBB\escHTML(__('Phone')) ?></dt>
		<?php if (EBB\isUserLoggedIn() && EBB\getCurrentUser()->canViewDonor($donor)) : ?>
        <dd><?= EBB\escHTML($donor->getMeta('phone')) ?></dd>
		<?php else: ?>
		<dd><span class="text-warning"><?= EBB\escHTML(__('Only site members can view this donor phone number.')) ?></span></dd>
		<?php endif; ?>
		<?php elseif ('staff' === $donorPhoneVisibility) : ?>
        <dt><?= EBB\escHTML(__('Phone')) ?></dt>
		<?php if (EBB\isUserLoggedIn() && EBB\getCurrentUser()->canEditDonor($donor)) : ?>
        <dd><?= EBB\escHTML($donor->getMeta('phone')) ?></dd>
		<?php else: ?>
		<dd><span class="text-warning"><?= EBB\escHTML(__('Only site staff can view this donor phone number.')) ?></span></dd>
		<?php endif; ?>
		<?php endif; ?>

        <dt><?= EBB\escHTML(__('City\District')) ?></dt>
        <dd><?= EBB\escHTML(sprintf(__('%1$s\%2$s'), $donor->get('district')->get('city')->get('name'), $donor->get('district')->get('name'))) ?></dd>

        <dt><?= EBB\escHTML(__('Address')) ?></dt>
        <dd><?= EBB\escHTML($donor->getMeta('address')) ?></dd>

    </dl>

</div>

<?php
$view->displayView('footer');
