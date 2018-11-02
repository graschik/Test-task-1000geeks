<?php

declare(strict_types=1);

namespace App\Controller\Rest;


use App\Entity\Code;
use App\Service\CodeService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CodeController extends FOSRestController
{
    private $codeService;

    public function __construct(CodeService $codeService)
    {
        $this->codeService = $codeService;
    }

    /**
     * @param Request $request
     * @param int $nb
     * @param string $export
     * @return View
     * @Rest\Post("/generate/{nb}/{export}", name="generate.code",
     *     defaults={"nb" = 1, "export" = null},
     *     requirements={"nb" = "\d+", "export" = "xls"})
     */
    public function generateCodes(Request $request, int $nb, string $export = null): View
    {
        $this->codeService->generateAndWriteCodes($nb);
        return View::create("OK", Response::HTTP_OK);
    }
}
