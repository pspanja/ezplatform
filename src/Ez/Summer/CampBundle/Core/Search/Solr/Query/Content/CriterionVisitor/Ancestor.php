<?php

namespace Ez\Summer\CampBundle\Core\Search\Solr\Query\Content\CriterionVisitor;

use eZ\Publish\Core\Search\Solr\Query\CriterionVisitor;
use Ez\Summer\CampBundle\API\Repository\Values\Content\Query\Criterion\Ancestor as AncestorCriterion;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Operator;

/**
 * Visits the Ancestor criterion.
 */
class Ancestor extends CriterionVisitor
{
    /**
     * Check if visitor is applicable to current criterion.
     *
     * @param Criterion $criterion
     *
     * @return bool
     */
    public function canVisit(Criterion $criterion)
    {
        return $criterion instanceof AncestorCriterion && $criterion->operator === Operator::EQ;
    }

    /**
     * Map field value to a proper Solr representation.
     *
     * @param Criterion $criterion
     * @param CriterionVisitor $subVisitor
     *
     * @return string
     */
    public function visit(Criterion $criterion, CriterionVisitor $subVisitor = null)
    {
        return '(' .
            implode(
                ' OR ',
                array_map(
                    function ($value) {
                        return 'location_id_mid:"' . $value . '"';
                    },
                    explode( '/', $criterion->value[0] )
                )
            ) .
            ')';
    }
}
