<?php

namespace ActiveLAMP\Taxonomy\Tests\Fixtures\ORM;

use ActiveLAMP\Taxonomy\Annotations as Tax;
use ActiveLAMP\Taxonomy\Entity\SingularVocabularyField;
use ActiveLAMP\Taxonomy\Entity\TermInterface;

/**
 * @author Bez Hermoso <bez@activelamp.com>
 * @Tax\Entity
 */
class Post
{
    protected $id;

    /**
     * @Tax\Vocabulary(name="category", singular=true)
     */
    protected $category;

    protected $body;

    public function getId()
    {
        return $this->id;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @param mixed $category
     */
    public function setCategory(TermInterface $category = null)
    {
        if ($this->category instanceof SingularVocabularyField) {
            $this->category->setTerm($category);
        } else {
            $this->category = $category;
        }
    }
} 