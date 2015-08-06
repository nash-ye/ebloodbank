<?php
/**
 * New City
 *
 * @package    EBloodBank
 * @subpackage Views
 */
use EBloodBank\Kernal\View;

$header = new View('header', array( 'title' => __('New User') ));
$header();

$form = new View('form-user');
$form();

$footer = new View('footer');
$footer();
