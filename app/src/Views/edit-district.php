<?php
/**
 * Edit District Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

$header = new View('header', array( 'title' => __('Edit District') ));
$header();

$footer = new View('form-district', array( 'district' => $this->get('district') ));
$footer();

$footer = new View('footer');
$footer();
