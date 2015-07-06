<?php
/**
 * Signin
 *
 * @package eBloodBank
 * @subpackage Views
 */
use eBloodBank\Kernal\View;

$header = new View('header');
$header(array( 'title' => __('Signin') ));

$form = new View('form-signin');
$form();

$footer = new View('footer');
$footer();
