<?php
/**
 * Error 403 Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

http_response_code(403); // Set the HTTP response status.

View::display('header', array( 'title' => __('Error: Access denied') ));
?>

	<div class="error-msg error-403-msg">
		<p><?php __e('Sorry, you are not authorized to access this page.') ?></p>
	</div>

<?php
View::display('footer');
