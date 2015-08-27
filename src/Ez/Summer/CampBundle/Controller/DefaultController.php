<?php

namespace Ez\Summer\CampBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause;
use Ez\Summer\CampBundle\API\Repository\Values\Content\Query\Criterion\Ancestor;
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

    public function urlFieldSearchAction()
    {
        $languageSettings = array(
            'languages' => array(),
        );
        $searchService = $this->get( 'ezpublish.api.service.search' );

        $query = new Query();
        $query->filter = new Criterion\LogicalAnd(
            array(
                new Criterion\ContentTypeIdentifier( 'test_product' ),
                new Criterion\Field( 'link', Criterion\Operator::EQ, 'https://keyboards.keytronic.com' )
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

    public function urlCustomFieldSearchAction()
    {
        $languageSettings = array(
            'languages' => array(),
        );
        $searchService = $this->get( 'ezpublish.api.service.search' );

        $query = new Query();
        $query->filter = new Criterion\LogicalAnd(
            array(
                new Criterion\ContentTypeIdentifier( 'test_product' ),
                new Criterion\CustomField( 'test_product_link_value_id_s', Criterion\Operator::EQ, '69' )
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

    public function ancestorSearchAction()
    {
        $languageSettings = array(
            'languages' => array(),
        );
        /** @var \eZ\Publish\API\Repository\SearchService $searchService */
        $searchService = $this->get( 'ezpublish.api.service.search' );
        /** @var \eZ\Publish\API\Repository\LocationService $locationService */
        $locationService = $this->get( 'ezpublish.api.service.location' );

        $location = $locationService->loadLocation( 72 );

        $query = new LocationQuery();
        $query->filter = new Ancestor( $location->pathString );
        $query->sortClauses = array(
            new SortClause\Location\Depth( Query::SORT_ASC ),
        );

        $searchResult = $searchService->findLocations( $query, $languageSettings );

        return $this->render(
            'EzSummerCampBundle::list.html.twig',
            array(
                'searchResult' => $searchResult,
            )
        );
    }
}
