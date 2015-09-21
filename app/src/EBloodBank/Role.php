<?php
/**
 * Role Class
 *
 * @package EBloodBank
 * @since 1.0
 */
namespace EBloodBank;

/**
 * @since 1.0
 */
class Role
{
    /**
     * @var string
     * @since 1.0
     */
    protected $slug;

    /**
     * @var string
     * @since 1.0
     */
    protected $title;

    /**
     * @var array
     * @since 1.0
     */
    protected $caps = array();

    /**
     * @since 1.0
     */
    public function __construct($slug, $title, array $caps)
    {
        $this->setSlug($slug);
        $this->setTitle($title);
        $this->setCapabilities($caps);
    }

    /**
     * @return string
     * @since 1.0
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return void
     * @since 1.0
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     * @since 1.0
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return void
     * @since 1.0
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return array
     * @since 1.0
     */
    public function getCapabilities()
    {
        return $this->title;
    }

    /**
     * @return void
     * @since 1.0
     */
    public function setCapabilities(array $caps)
    {
        $this->caps = $caps;
    }

    /**
     * @return bool
     * @since 1.0
     */
    public function hasCapability($cap)
    {
        return ! empty($this->caps[$cap]);
    }

    /**
     * @return bool
     * @since 1.0
     */
    public function hasCapabilities(array $caps, $opt = 'AND')
    {
        if (empty($opt)) {
            $opt = 'AND';
        }

        $opt = strtoupper($opt);

        foreach ($caps as $cap) {

            switch ($opt) {

                case 'AND':

                    if (! $this->hasCapability($cap)) {
                        return false;
                    }

                    break;

                case 'OR':

                    if ($this->hasCapability($cap)) {
                        return true;
                    }

                    break;

                case 'NOT':

                    if ($this->hasCapability($cap)) {
                        return false;
                    }

                    break;

            }

        }

        switch ($opt) {

            case 'AND':
            case 'NOT':
                return true;

            case 'OR':
                return false;

        }
    }
}
