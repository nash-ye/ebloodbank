<?php
/**
 * Base View
 *
 * @package EBloodBank
 * @subpackage Views
 * @since 1.0
 */
namespace EBloodBank\Views;

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
    protected $data;

    /**
     * @return void
     * @since 1.0
     */
    public function __construct($name, array $data = array())
    {
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * @return void
     * @since 1.0
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @return bool
     * @since 1.0
     */
    public function isExists($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * @return mixed
     * @since 1.0
     */
    public function get($key)
    {
        if ($this->isExists($key)) {
            return $this->data[$key];
        }
    }

    /**
     * @return string
     * @since 1.0
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     * @since 1.0
     */
    public function getPath()
    {
        return __DIR__ . '/' . $this->getName() . '.php';
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (is_readable($this->getPath())) {
            extract($this->data, EXTR_REFS);
            include $this->getPath();
        }
    }
}
