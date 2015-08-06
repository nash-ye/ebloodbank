<?php
/**
 * Edit Donor
 *
 * @package    EBloodBank
 * @subpackage Views
 */
use EBloodBank\Kernal\View;

$header = new View('header', array( 'title' => __('Edit Donor') ));
$header();

$form = new View('form-donor', array( 'donor' => $this->get('donor') ));
$form();

$footer = new View('footer');
$footer();
