<?php
namespace eBloodBank\Kernal;

/**
 * @since 1.0
 */
class View
{
	/**
	 * @var string
	 * @since 1.0
	 */
	protected $name;

	/**
	 * @var array
	 * @since 1.0
	 */
	protected $data = array();

	/**
	 * @return void
	 * @since 1.0
	 */
	public function __construct($name)
	{
		$this->name = $name;
	}

	/**
	 * @return void
	 * @since 1.0
	 */
	public function __invoke(array $data = array())
	{
		$view_path = \eBloodBank\ABSPATH . '/app/src/Views/' . $this->name . '.php';
		if (file_exists($view_path)) {
			require $view_path;
		}
	}
}
