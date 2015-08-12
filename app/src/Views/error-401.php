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

View::display('header', array( 'title' => __('Error: Unauthorized') ));
?>

	<div class="error-msg error-404-msg">
		<p><?php _e('Sorry, You are not allowed to enter this page') ?></p>
	</div>

<?php
View::display('footer');
