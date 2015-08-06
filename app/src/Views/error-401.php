<?php
/**
 * Error 401
 *
 * @package    EBloodBank
 * @subpackage Views
 */
use EBloodBank\Kernal\View;

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
