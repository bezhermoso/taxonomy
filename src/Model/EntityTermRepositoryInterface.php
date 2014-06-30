<?php
/**
 * Created by PhpStorm.
 * User: bezalelhermoso
 * Date: 6/30/14
 * Time: 9:09 AM
 */

namespace ActiveLAMP\Taxonomy\Model;

use ActiveLAMP\Taxonomy\Entity\EntityTermInterface;
use ActiveLAMP\Taxonomy\Entity\Vocabulary;


/**
 * Interface EntityTermRepositoryInterface
 *
 * @package ActiveLAMP\Taxonomy\Model
 * @author Bez Hermoso <bez@activelamp.com>
 */
interface EntityTermRepositoryInterface
{
    /**
     * @param string|Vocabulary $vocabulary
     * @param string $entityType
     * @param string $entityIdentifier
     * @return EntityTermInterface[]
     */
    public function findEntities($vocabulary, $entityType, $entityIdentifier);

    /**
     * @param string|Vocabulary $vocabulary
     * @param string $entityType
     * @param string $entityIdentifier
     * @return EntityTermInterface
     */
    public function findEntity($vocabulary, $entityType, $entityIdentifier);

    /**
     * @return string
     */
    public function getClassName();
}