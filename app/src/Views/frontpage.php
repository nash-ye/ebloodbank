<?php
use EBloodBank\Kernal\View;

$header = new View('header', array( 'title' => __('Home') ));
$header();
?>

<?php
$footer = new View('footer');
$footer();
