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

View::display('header', array( 'title' => __('Error: Not Found') ));
?>

	<div class="error-msg error-404-msg">
		<p><?php _e('Sorry, Not Found') ?></p>
	</div>

<?php
View::display('footer');
