<?php
use eBloodBank\Kernal\View;

$header = new View('header');
$header(array( 'title' => __('Home') ));
?>

<?php
$footer = new View('footer');
$footer();
