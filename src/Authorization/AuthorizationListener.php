<?php

namespace ApiSkeletons\OAuth2\Doctrine\Permissions\Acl\Authorization;

use Laminas\ApiTools\MvcAuth\MvcAuthEvent;
use Doctrine\ORM\EntityRepository;
use ApiSkeletons\OAuth2\Doctrine\Permissions\Acl\Role\ObjectRepositoryProvider;
use ApiSkeletons\OAuth2\Doctrine\Permissions\Acl\Identity\AuthenticatedIdentity as DoctrineAuthenticatedIdentity;
use GianArb\Angry\Unclonable;
use GianArb\Angry\Unserializable;

class AuthorizationListener
{
    use Unclonable;
    use Unserializable;

    protected $roleProvider;

    public function __construct(
        ObjectRepositoryProvider $roleProvider
    ) {
        $this->roleProvider = $roleProvider;
    }

    public function __invoke(MvcAuthEvent $mvcAuthEvent)
    {
        $authorization = $mvcAuthEvent->getAuthorizationService();

        // Add all roles
        foreach ($this->roleProvider->getRoles() as $role) {
            if (! $authorization->hasRole($role)) {
                $authorization->addRole($role, $role->getParent());
            }
        }
    }
}
