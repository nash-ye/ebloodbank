<?php
namespace EBloodBank\Kernal;

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

                    if (empty($this->caps[$cap])) {
                        return false;
                    }

                    break;

                case 'OR':

                    if (! empty($this->caps[$cap])) {
                        return true;
                    }

                    break;

                case 'NOT':

                    if (! empty($this->caps[$cap])) {
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
