<?php
/**
 * Edit City
 *
 * @package    EBloodBank
 * @subpackage Views
 */
use EBloodBank\Kernal\View;

$header = new View('header', array( 'title' => __('Edit User') ));
$header();

$form = new View('form-user', array( 'user' => $this->get('user') ));
$form();

$footer = new View('footer');
$footer();
