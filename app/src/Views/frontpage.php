<?php
/**
 * Front-Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

$header = new View('header', array( 'title' => __('Home') ));
$header();
?>

<?php
$footer = new View('footer');
$footer();
