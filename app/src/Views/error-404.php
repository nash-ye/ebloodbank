<?php
/**
 * Error 404 Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

http_response_code(404); // Set the HTTP response status.

View::display('header', array( 'title' => __('Error: Not Found') ));
?>

	<div class="error-msg error-404-msg">
		<p><?php _e('Sorry, Not Found') ?></p>
	</div>

<?php
View::display('footer');
