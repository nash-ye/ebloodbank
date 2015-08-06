<?php
/**
 * Login Page
 *
 * @package    EBloodBank
 * @subpackage Views
 */
use EBloodBank\Kernal\View;

$header = new View('header', array( 'title' => __('Login') ));
$header();

$form = new View('form-login');
$form();

$footer = new View('footer');
$footer();
