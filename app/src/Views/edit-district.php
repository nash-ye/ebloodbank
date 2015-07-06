<?php
/**
 * Edit District
 *
 * @package eBloodBank
 * @subpackage Views
 */
use eBloodBank\Kernal\View;

$header = new View('header');
$header(array( 'title' => __('Edit District') ));

$footer = new View('form-district');
$footer(array( 'id' => $data['id'] ));

$footer = new View('footer');
$footer();
