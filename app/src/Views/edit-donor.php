<?php
/**
 * Edit Donor
 *
 * @package    eBloodBank
 * @subpackage Views
 */
use eBloodBank\Kernal\View;

$header = new View('header');
$header(array( 'title' => __('Edit Donor') ));

$form = new View('form-donor');
$form(array( 'donorID' => $data['id'] ));

$footer = new View('footer');
$footer();
