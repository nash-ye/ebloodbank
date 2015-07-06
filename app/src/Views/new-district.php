<?php
/**
 * New District
 *
 * @package eBloodBank
 * @subpackage Views
 */
use eBloodBank\Kernal\View;

$header = new View('header');
$header(array( 'title' => __('New District') ));

$footer = new View('form-district');
$footer();

$footer = new View('footer');
$footer();
