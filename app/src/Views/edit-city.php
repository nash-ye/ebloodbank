<?php
/**
 * Edit City
 *
 * @package eBloodBank
 * @subpackage Views
 */
use eBloodBank\Kernal\View;

$header = new View('header');
$header(array( 'title' => __('Edit City') ));

$form = new View('form-city');
$form(array( 'id' => $data['id'] ));

$footer = new View('footer');
$footer();
