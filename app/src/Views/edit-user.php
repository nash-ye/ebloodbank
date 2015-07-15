<?php
/**
 * Edit City
 *
 * @package    eBloodBank
 * @subpackage Views
 */
use eBloodBank\Kernal\View;

$header = new View('header');
$header(array( 'title' => __('Edit User') ));

$form = new View('form-user');
$form(array( 'userID' => $data['id'] ));

$footer = new View('footer');
$footer();
