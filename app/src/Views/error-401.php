<?php
/**
 * Error 401
 *
 * @package eBloodBank
 * @subpackage Views
 */
use eBloodBank\Kernal\View;

header('HTTP/1.1 401 Unauthorized');

$header = new View('header');
$header(array( 'title' => __('Error: Unauthorized') ));
?>

	<div class="error-msg error-404-msg">
		<p><?php _e('Sorry, You are not allowed to enter this page') ?></p>
	</div>

<?php
$footer = new View('footer');
$footer();
