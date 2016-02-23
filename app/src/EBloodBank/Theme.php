<?php
/**
 * Theme class file
 *
 * @package EBloodBank
 * @since   1.3
 */
namespace EBloodBank;

/**
 * Theme class
 *
 * @since 1.3
 */
class Theme
{
    /**
     * @var string
     * @since 1.3
     */
    protected $name;

    /**
     * @var string
     * @since 1.3
     */
    protected $path;

    /**
     * @var array
     * @since 1.3
     */
    protected $data = array();

    /**
     * @return void
     * @since 1.3
     */
    public function __construct($name, $path)
    {
        $this->name = $name;
        if (empty($this->name)) {
            // @TODO: Throw an exception.
        }
        $this->path = trimTrailingSlash($path);
        if (empty($this->path) || ! is_dir($this->path)) {
            // @TODO: Throw an exception.
        }
    }

    /**
     * @return string
     * @since 1.3
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     * @since 1.3
     */
    public function getPath()
    {
        return $this->path;
    }
}
