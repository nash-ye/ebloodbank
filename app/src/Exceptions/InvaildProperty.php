<?php
namespace EBloodBank\Exceptions;

/**
 * @since 1.0
 */
class InvaildProperty extends \Exception
{
	/**
	 * @var string
	 * @since 1.0
	 */
	protected $slug;

	/**
	 * @return void
	 * @since 1.0
	 */
	public function __construct($message, $slug)
    {
		$this->slug = $slug;
		parent::__construct($message, 0);
	}

	/**
	 * @return string
	 * @since 1.0
	 */
	public function getSlug()
    {
		return $this->slug;
	}
}
