<?php
/**
 * Signup
 *
 * @package    EBloodBank
 * @subpackage Views
 */
use EBloodBank\Kernal\View;

$header = new View('header', array( 'title' => __('Sign up') ));
$header();

$form = new View('form-signup');
$form();

$footer = new View('footer');
$footer();
