<?php

namespace Ez\Summer\CampBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('EzSummerCampBundle:Default:index.html.twig', array('name' => $name));
    }

    public function languageFallbackDemoAction()
    {
        $languageSettings = array(
            'languages' => $this->getConfigResolver()->getParameter( 'languages' ),
        );
        $searchService = $this->get( 'ezpublish.api.service.search' );

        $query = new Query();
        $query->filter = new Criterion\LogicalAnd(
            array(
                new Criterion\ContentTypeIdentifier( 'test_product' ),
            )
        );
        $query->sortClauses = array(
            new SortClause\ContentId( Query::SORT_ASC ),
        );

        $searchResult = $searchService->findContent( $query, $languageSettings );

        return $this->render(
            'EzSummerCampBundle::language_fallback.html.twig',
            array(
                'keyboards' => $searchResult,
                'languageSettings' => $languageSettings
            )
        );
    }
}
