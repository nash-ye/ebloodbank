<?php
/**
 * Edit District
 *
 * @package    EBloodBank
 * @subpackage Views
 */
use EBloodBank\Kernal\View;

$header = new View('header', array( 'title' => __('Edit District') ));
$header();

$footer = new View('form-district', array( 'district' => $this->get('district') ));
$footer();

$footer = new View('footer');
$footer();
