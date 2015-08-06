<?php
/**
 * New District
 *
 * @package    EBloodBank
 * @subpackage Views
 */
use EBloodBank\Kernal\View;

$header = new View('header', array( 'title' => __('New District') ));
$header();

$footer = new View('form-district');
$footer();

$footer = new View('footer');
$footer();
