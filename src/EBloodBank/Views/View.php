<?php
/**
 * View class file
 *
 * @package    EBloodBank
 * @subpackage Views
 * @since      1.0
 */
namespace EBloodBank\Views;

use EBloodBank as EBB;
use EBloodBank\Main;
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
     * @return View
     * @since 1.0
     * @static
     */
    public static function forge($name, array $data = [])
    {
        $data += [
            'currentUser' => EBB\getCurrentUser(),
        ];

        $view = new static($name, $data);
        $view->setAcl(Main::getInstance()->getContainer()->get('acl'));

        return $view;
    }

    /**
     * @return void
     * @since 1.0
     * @static
     */
    public static function display($name, array $data = [])
    {
        $view = static::forge($name, $data);
        $view();
    }

    /**
     * @return void
     * @since 1.0
     */
    protected function __construct($name, array $data = [])
    {
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * @return View
     * @since 1.0
     */
    public function forgeView($name, array $data = [])
    {
        return self::forge($name, $data);
    }

    /**
     * @return void
     * @since 1.0
     */
    public function displayView($name, array $data = [])
    {
        return self::display($name, $data);
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
