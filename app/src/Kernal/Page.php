<?php4
namespace EBloodBank\Kernal;

/**
 * @since 1.0
 */
class Page
{

    /**
     * @var string
     * @since 1.0
     */
    protected $name;

    /**
     * @var \EBloodBank\Controllers\Controller
     * @since 1.0
     */
    protected $controller;

    /**
     * @retrun void
     * @since 1.0
     */
    public function __construct($name, $controller)
    {
        $this->name = $name;
        $this->controller = $controller;
    }
}