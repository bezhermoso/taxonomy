<?php

namespace ActiveLAMP\Taxonomy\Model;

use ActiveLAMP\Taxonomy\Entity\PluralVocabularyField;
use ActiveLAMP\Taxonomy\Entity\SingularVocabularyField;
use ActiveLAMP\Taxonomy\Entity\Term;
use ActiveLAMP\Taxonomy\Entity\Vocabulary;
use ActiveLAMP\Taxonomy\Entity\VocabularyFieldInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

/**
 * @author Bez Hermoso <bez@activelamp.com>
 */
class VocabularyFieldFactory
{
    protected $om;

    protected $entityTerms;

    public function __construct(ObjectManager $em, EntityTermRepositoryInterface $entityTerms)
    {
        $this->om = $em;
        $this->entityTerms = $entityTerms;
    }

    /**
     * @param \ActiveLAMP\Taxonomy\Entity\Vocabulary $vocabulary
     * @param $type
     * @param $identifier
     * @param null $previousValue
     * @param bool $singular
     * @return PluralVocabularyField|\ActiveLAMP\Taxonomy\Entity\SingularVocabularyField|VocabularyFieldInterface
     */
    public function createVocabularyField(Vocabulary $vocabulary, $type, $identifier, $previousValue = null, $singular = false)
    {
        if ($singular === true) {
            return new SingularVocabularyField(
                $this->om,
                $this->entityTerms,
                $vocabulary,
                $type,
                $identifier,
                $previousValue instanceof Term ? $previousValue : null
            );
        } else {
            return new PluralVocabularyField(
                $this->om,
                $this->entityTerms,
                $vocabulary,
                $type,
                $identifier,
                $previousValue instanceof Collection ? $previousValue : null
            );
        }
    }
} 