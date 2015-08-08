<?php
/**
 * Error 401 Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

header('HTTP/1.1 401 Unauthorized');

$header = new View('header', array( 'title' => __('Error: Unauthorized') ));
$header();
?>

	<div class="error-msg error-404-msg">
		<p><?php _e('Sorry, You are not allowed to enter this page') ?></p>
	</div>

<?php
$footer = new View('footer');
$footer();
