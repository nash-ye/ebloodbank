<?php
/**
 * Edit Donor Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

$header = new View('header', array( 'title' => __('Edit Donor') ));
$header();

$form = new View('form-donor', array( 'donor' => $this->get('donor') ));
$form();

$footer = new View('footer');
$footer();
