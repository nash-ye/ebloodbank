<?php
/**
 * Error 404 Page
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
use EBloodBank as EBB;

http_response_code(404); // Set the HTTP response status.

$view->displayView('header', ['title' => __('Error: Not Found')]);
?>

	<div class="error-msg error-404-msg">
		<p><?= EBB\escHTML(__('Sorry, the page you requested was not found.')) ?></p>
	</div>

<?php
$view->displayView('footer');
