<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Controller\Api\Language;

use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("languages/autocomplete", methods={"GET"})
 */
class LanguageAutocompleteAction
{
    private LanguageQueryInterface $query;

    public function __construct(LanguageQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @SWG\Tag(name="Language")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="search",
     *     in="query",
     *     required=false,
     *     type="string",
     *     description="searched value"
     * )
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     required=false,
     *     type="integer",
     *     description="searched value count"
     * )
     * @SWG\Parameter(
     *     name="field",
     *     in="query",
     *     required=false,
     *     type="string",
     *     description="Order field",
     * )
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"ASC","DESC"},
     *     description="Order",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns language",
     * )
     *
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     */
    public function __invoke(Request $request): Response
    {
        $search = $request->query->get('search');
        $limit = $request->query->getInt('limit', null);
        $field = $request->query->get('field');
        $order = $request->query->get('order');

        $data = $this->query->autocomplete($search, $limit, $field, $order);

        return new SuccessResponse($data);
    }
}
