<?php
/**
 * New City
 *
 * @package    EBloodBank
 * @subpackage Views
 */
use EBloodBank\Kernal\View;

$header = new View('header', array( 'title' => __('New City') ));
$header();

$form = new View('form-city');
$form();

$footer = new View('footer');
$footer();
