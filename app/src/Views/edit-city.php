<?php
/**
 * Edit City
 *
 * @package    EBloodBank
 * @subpackage Views
 */
use EBloodBank\Kernal\View;

$header = new View('header', array( 'title' => __('Edit City') ));
$header();

$form = new View('form-city', array( 'city' => $this->get('city') ));
$form();

$footer = new View('footer');
$footer();
