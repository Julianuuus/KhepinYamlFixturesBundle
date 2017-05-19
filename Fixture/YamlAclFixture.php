<?php

namespace Khepin\YamlFixturesBundle\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
use Oneup\AclBundle\Security\Acl\Manager\AclManager;

class YamlAclFixture extends AbstractFixture
{
    /**
     * @var AclManagerInterface
     */
    private $aclManager;

    /**
     * @param AclManager $aclManager
     */
    public function setAclManager(AclManager $aclManager)
    {
        $this->aclManager = $aclManager;
    }



    public function load(ObjectManager $manager, $tags = null)
    {
        if (!$this->hasTag($tags) || !isset($this->file['acl'])) {
            return;
        }

        foreach ($this->file['acl'] as $reference => $permissions) {
            foreach ($permissions as $user => $permission) {
                $this->aclManager->setObjectPermission(
                    $this->loader->getReference($reference),
                    $this->getMask($permission),
                    $this->loader->getReference($user)
                );
            }
        }
    }

    /**
     * Retrieves the constant value for the given mask name
     *
     * @param  string $permission
     *
     * @return integer
     * @see Symfony\Component\Security\Acl\Permission\MaskBuilder
     */
    public function getMask($permission)
    {
        return constant('Symfony\Component\Security\Acl\Permission\MaskBuilder::'.$permission);
    }

    public function createObject($class, $data, $metadata, $options = [])
    {
        // No implementation for ACL fixtures
    }
}
