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
     * @var string
     * @since 1.0
     */
    protected $path;

    /**
     * @return void
     * @since 1.0
     */
    public function __construct($name)
    {
        $this->name = $name;
        if (! empty($this->name)) {
            $views_path = ABSPATH . '/app/src/Views';
            $this->path = $views_path . '/' . $this->name . '.php';
        }
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke(array $data = array())
    {
        extract($data, EXTR_PREFIX_ALL | EXTR_REFS, '_');
        if (file_exists($this->path)) {
            include $this->path;
        }
    }
}
