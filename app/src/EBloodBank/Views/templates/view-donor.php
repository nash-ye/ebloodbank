<?php
/**
 * View donor page template
 *
 * @package    EBloodBank\Views
 * @subpackage Templates
 * @since      1.1
 */

use EBloodBank as EBB;

$view->displayView('header', ['title' => $donor->get('name')]);
?>
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
    <dd><?= EBB\escHTML($donor->get('weight')) ?></dd>

    <dt><?= EBB\escHTML(__('E-mail')) ?></dt>
    <dd><?= EBB\escHTML($donor->get('email')) ?></dd>

    <dt><?= EBB\escHTML(__('Phone')) ?></dt>
    <dd><?= EBB\escHTML($donor->get('phone')) ?></dd>

    <dt><?= EBB\escHTML(__('City\District')) ?></dt>
    <dd><?= EBB\escHTML(sprintf(__('%1$s\%2$s'), $donor->get('district')->get('city')->get('name'), $donor->get('district')->get('name'))) ?></dd>

    <dt><?= EBB\escHTML(__('Address')) ?></dt>
    <dd><?= EBB\escHTML($donor->get('phone')) ?></dd>

</dl>
<?php
$view->displayView('footer');