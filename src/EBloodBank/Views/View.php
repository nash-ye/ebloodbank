<?php
/**
 * View class file
 *
 * @package    EBloodBank
 * @subpackage Views
 * @since      1.0
 */
namespace EBloodBank\Views;

use EBloodBank\Themes;
use EBloodBank\Traits\AclTrait;

/**
 * View class
 *
 * @since 1.0
 */
class View
{
    use AclTrait;

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
    public function __construct($name, array $data = [])
    {
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * @return View
     * @since 1.0
     */
    public function forgeView(string $name, array $data = [])
    {
        $data = array_merge($this->data, $data);
        $view = new self($name, $data);
        $view->setAcl($this->getAcl());

        return $view;
    }

    /**
     * @return void
     * @since 1.0
     */
    public function displayView(string $name, array $data = [])
    {
        $view = $this->forgeView($name, $data);
        $view();
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
        $templatePath = '';
        $currentTheme = Themes::getCurrentTheme();

        if (empty($currentTheme)) {
            return $templatePath;
        }

        $stacks = $currentTheme->getAvailableTemplateStacks();

        foreach ($stacks as $stackPath) {
            $templatePath = $stackPath . '/' . $this->getName() . '.php';
            if (file_exists($templatePath)) {
                break;
            }
        }

        return $templatePath;
    }

    /**
     * @return void
     * @since 1.0
     */
    public function __invoke()
    {
        if (is_readable($this->getPath())) {
            $data = $this->data;
            $data += [
                'context' => $this,
                'view'    => $this,
                'acl'     => $this->getAcl(),
            ];

            extract($data, EXTR_REFS);

            include $this->getPath();
        }
    }
}
