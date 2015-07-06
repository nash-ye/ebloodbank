<?php

/**
 * @return string
 * @since 1.0
 */
function __($text) {
	return $text;
}

/**
 * @return void
 * @since 1.0
 */
function _e($text) {
	echo __($text);
}
