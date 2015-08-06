<?php
namespace EBloodBank\Kernal;

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
     * @var string
     * @since 1.0
     */
    protected $path;

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

        if (! empty($this->name)) {
            $views_path = ABSPATH . '/app/src/Views';
            $this->path = $views_path . '/' . $this->name . '.php';
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function get($key)
    {
        if ($this->isExists($key)) {
            return $this->data[$key];
        }
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
     * @return void
     * @since 1.0
     */
    public function isExists($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        extract($this->data, EXTR_REFS);
        if (file_exists($this->path)) {
            include $this->path;
        }
    }
}
