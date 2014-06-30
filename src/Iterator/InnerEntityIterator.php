<?php
/**
 * Created by PhpStorm.
 * User: bezalelhermoso
 * Date: 5/23/14
 * Time: 2:08 PM
 */

namespace ActiveLAMP\Taxonomy\Iterator;

use ActiveLAMP\Taxonomy\Entity\EntityTerm;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 */
class InnerEntityIterator extends AbstractInnerMemberIterator
{

    public function extractCurrent($current, $key)
    {
        if ($current instanceof EntityTerm) {
            return $current->getEntity();
        } else {
            throw new \RuntimeException('Collection must only contain instances of ActiveLAMP\Taxonomy\Entity\EntityTerm.');
        }
    }
}