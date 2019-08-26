<?php
/**
 * Theme class file
 *
 * @package EBloodBank
 * @since   1.3
 */
namespace EBloodBank;

use DirectoryIterator;
use InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;

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
     * @return void
     * @since 1.3
     */
    public function __construct($name, $path)
    {
        $this->name = $name;
        if (empty($this->name)) {
            throw new InvalidArgumentException(__('Invalid theme name.'));
        }
        $this->path = trimTrailingSlash($path);
        if (empty($this->path) || ! is_dir($this->path)) {
            throw new InvalidArgumentException(__('Invalid theme directory path.'));
        }
    }

    /**
     * Get the theme name.
     *
     * @return string
     * @since 1.3
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the theme path.
     *
     * @return string
     * @since 1.3
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Find a locale in the theme.
     *
     * @return Locale|null
     * @since 1.3
     */
    public function findLocale($code)
    {
        return Locales::findLocale($code, $this->getPath() . '/locales');
    }

    /**
     * Get the available locales in the theme.
     *
     * @return Locale[]
     * @since 1.3
     */
    public function getAvailableLocales()
    {
        return Locales::getAvailableLocales($this->getPath() . '/locales');
    }

    /**
     * @return mixed
     * @since 1.3
     */
    public function getData($key = '', $default = '')
    {
        static $data;

        if (is_null($data)) {
            $data = [];
            $dataFile = $this->getPath() . '/theme.yml';
            if (file_exists($dataFile)) {
                $contents = file_get_contents($dataFile);
                if ($contents !== false) {
                    $data = Yaml::parse($contents, true);
                }
            }
        }

        if (! empty($key)) {
            if (isset($data[$key])) {
                return $data[$key];
            } else {
                return $default;
            }
        }

        return $data;
    }

    /**
     * Get the available templates stacks in the theme.
     *
     * @return array
     * @since 1.3
     */
    public function getAvailableTemplateStacks()
    {
        static $stacks;

        if (is_null($stacks)) {
            $stacks = [];
            $stacksItr = new DirectoryIterator($this->getPath() . '/templates');

            foreach ($stacksItr as $stackDir) {
                if ($stackDir->isDot() || ! $stackDir->isDir()) {
                    continue;
                }
                $stacks[] = trimTrailingSlash($stackDir->getRealPath());
            }

            arsort($stacks); // Sort the stacks, high priority stack at first.
        }

        return $stacks;
    }
}
