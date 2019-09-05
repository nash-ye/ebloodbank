<?php
/**
 * @package    EBloodBank
 * @subpackage Views
 * @since      1.6
 */
namespace EBloodBank\Views;

use EBloodBank\Traits\AclTrait;
use Psr\Container\ContainerInterface;

/**
 * @since 1.6
 */
class ViewFactory
{
    use AclTrait;

    /**
     * @var   array
     * @since 1.6
     */
    protected $data = [];

    /**
     * @since 1.6
     */
    public function __construct(ContainerInterface $container)
    {
        $this->setAcl($container->get('acl'));
    }

    /**
     * @return \EBloodBank\Views\View
     * @since  1.6
     */
    public function forgeView(string $name, array $data = [])
    {
        $data = array_merge($this->getAllData(), $data);
        $view = new View($name, $data);
        $view->setAcl($this->getAcl());

        return $view;
    }

    /**
     * @return void
     * @since  1.6
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
    public function setData(string $key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @return mixed
     * @since 1.6
     */
    public function getData(string $key)
    {
        return $this->data[$key];
    }

    /**
     * @return array
     * @since 1.6
     */
    public function getAllData()
    {
        return $this->data;
    }
}
