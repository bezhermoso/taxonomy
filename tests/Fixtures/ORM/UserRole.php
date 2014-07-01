<?php

namespace ActiveLAMP\Taxonomy\Tests\Fixtures\ORM;

use ActiveLAMP\Taxonomy\Annotations as Tax;
use ActiveLAMP\Taxonomy\Entity\TermInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Bez Hermoso <bez@activelamp.com>
 * @Tax\Entity
 */
class UserRole
{
    protected $id;

    protected $name;

    protected $label;

    /**
     * @Tax\Vocabulary(name="role")
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function addRole(TermInterface $term)
    {
        $this->roles->add($term);
    }

    public function removeRole(TermInterface $term)
    {
        $this->roles->removeElement($term);
    }

    public function getRoles()
    {
        return $this->roles;
    }
}