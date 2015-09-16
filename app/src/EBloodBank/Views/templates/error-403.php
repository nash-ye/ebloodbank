<?php
/**
 * Error 403 Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

use EBloodBank as EBB;

http_response_code(403); // Set the HTTP response status.

View::display('header', ['title' => __('Error: Access denied')]);
?>

	<div class="error-msg error-403-msg">
		<p><?= EBB\escHTML(__('Sorry, you are not authorized to access this page.')) ?></p>
	</div>

<?php
View::display('footer');
