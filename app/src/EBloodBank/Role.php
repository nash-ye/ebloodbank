<?php
/**
 * Role class file
 *
 * @package eBloodBank
 * @since   1.0
 */
namespace EBloodBank;

/**
 * Role class
 *
 * @since 1.0
 */
class Role
{
    /**
     * The role slug.
     *
     * @var string
     * @since 1.0
     */
    protected $slug;

    /**
     * The role title.
     *
     * @var string
     * @since 1.0
     */
    protected $title;

    /**
     * The role capabilities.
     *
     * @var array
     * @since 1.0
     */
    protected $caps = array();

    /**
     * @return void
     * @since 1.0
     */
    public function __construct($slug, $title, array $caps)
    {
        $this->setSlug($slug);
        $this->setTitle($title);
        $this->setCapabilities($caps);
    }

    /**
     * Get the role slug.
     *
     * @return string
     * @since 1.0
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set the role slug.
     *
     * @return void
     * @since 1.0
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get the role title.
     *
     * @return string
     * @since 1.0
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the role title.
     *
     * @return void
     * @since 1.0
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get the role capabilities.
     *
     * @return array
     * @since 1.0
     */
    public function getCapabilities()
    {
        return $this->title;
    }

    /**
     * Whether the role has a capability.
     *
     * @return bool
     * @since 1.0
     */
    public function hasCapability($cap)
    {
        return ! empty($this->caps[$cap]);
    }

    /**
     * Set the role capabilities.
     *
     * @return void
     * @since 1.0
     */
    public function setCapabilities(array $caps)
    {
        $this->caps = $caps;
    }
}
