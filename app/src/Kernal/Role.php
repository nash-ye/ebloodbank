<?php
namespace eBloodBank\Kernal;

/**
 * @since 1.0
 */
class Role
{
    /**
     * @var string
     * @since 1.0
     */
    public $slug;

    /**
     * @var string
     * @since 1.0
     */
    public $title;

    /**
     * @var array
     * @since 1.0
     */
    public $caps = array();

    /**
     * @since 1.0
     */
    public function __construct($slug, $title, array $caps)
    {
        $this->slug    = $slug;
        $this->title   = $title;
        $this->caps    = $caps;
    }

    /**
     * @return bool
     * @since 1.0
     */
    public function hasCaps(array $caps, $opt = 'AND')
    {
        if (empty($opt)) {
            $opt = 'AND';
        }

        $opt = strtoupper($opt);

        foreach ($caps as $cap) {
            switch ($opt) {

            case 'AND':

                if (! $this->hasCap($cap)) {
                    return false;
                }

                break;

            case 'OR':

                if ($this->hasCap($cap)) {
                    return true;
                }

                break;

            case 'NOT':

                if ($this->hasCap($cap)) {
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

    /**
     * @return bool
     * @since 1.0
     */
    public function hasCap($cap)
    {
        return (! empty($this->caps[ $cap ]));
    }
}
