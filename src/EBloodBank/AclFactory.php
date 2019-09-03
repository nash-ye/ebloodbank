<?php
/**
 * @package EBloodBank
 * @since   1.5
 */
namespace EBloodBank;

use Psr\Container\ContainerInterface;

/**
 * @since 1.5
 */
class AclFactory
{
    /**
     * @param ContainerInterface $container
     * @return \EBloodBank\Acl
     * @since 1.5
     */
    public function __invoke(ContainerInterface $container)
    {
        $acl = new Acl();

        /* Resources */
        $acl->addResource('User');
        $acl->addResource('Donor');
        $acl->addResource('City');
        $acl->addResource('District');
        $acl->addResource('Setting');

        /* Roles */
        $acl->addRole('subscriber');
        $acl->allow('subscriber', 'Donor', ['read']);
        $acl->allow('subscriber', 'City', ['read']);
        $acl->allow('subscriber', 'District', ['read']);

        $acl->addRole('contributor');
        $acl->allow('contributor', 'Donor', ['read', 'add', 'edit', 'delete']);
        $acl->allow('contributor', 'City', ['read']);
        $acl->allow('contributor', 'District', ['read']);

        $acl->addRole('editor');
        $acl->allow('editor', 'User', ['read']);
        $acl->allow('editor', 'Donor', ['read', 'approve', 'add', 'edit', 'edit_others', 'delete', 'delete_others']);
        $acl->allow('editor', 'City', ['read', 'add', 'edit', 'delete']);
        $acl->allow('editor', 'District', ['read', 'add', 'edit', 'delete']);

        $acl->addRole('administrator');
        $acl->allow('administrator', 'User', ['read', 'activate', 'add', 'edit', 'delete']);
        $acl->allow('administrator', 'Donor', ['read', 'approve', 'add', 'edit', 'edit_others', 'delete', 'delete_others']);
        $acl->allow('administrator', 'City', ['read', 'add', 'edit', 'delete']);
        $acl->allow('administrator', 'District', ['read', 'add', 'edit', 'delete']);
        $acl->allow('administrator', 'Setting', ['edit']);

        return $acl;
    }
}
