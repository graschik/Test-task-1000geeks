<?php

declare(strict_types=1);

namespace App\Controller\Rest;


use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CodeController extends FOSRestController
{
    /**
     * @param Request $request
     * @Rest\Get("/")
     * @return View
     */
    public function getCode(Request $request): View
    {
        return View::create("OK", Response::HTTP_OK);
    }
}