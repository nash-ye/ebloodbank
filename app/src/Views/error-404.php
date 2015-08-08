<?php
/**
 * Error 404 Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

header('HTTP/1.0 404 Not Found');

$header = new View('header', array( 'title' => __('Error: Not Found') ));
$header();
?>

	<div class="error-msg error-404-msg">
		<p><?php _e('Sorry, Not Found') ?></p>
	</div>

<?php
$footer = new View('footer');
$footer();
