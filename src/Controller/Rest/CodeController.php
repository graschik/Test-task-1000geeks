<?php

declare(strict_types=1);

namespace App\Controller\Rest;


use App\Service\CodeService;
use App\Service\FileWriter;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
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
     * @Rest\Get("/{code}", name="get.code",
     *     requirements={"code" = "[\w+]{10}"}
     *     )
     * @param Request $request
     * @param string $code
     * @return View
     */
    public function getCode(Request $request, string $code)
    {
        $codeInfo = $this->codeService->getCodeInfo($code);
        if (!$codeInfo) {
            return View::create("Code not found!", Response::HTTP_NOT_FOUND);
        }
        return View::create($codeInfo, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param int $nb
     * @param string $export
     * @return View
     * @Rest\Post("/generate/{nb}/{export}", name="generate.code",
     *     defaults={"nb" = 1, "export" = null},
     *     requirements={"nb" = "\d+", "export" = "xls"})
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function generateCodes(Request $request, int $nb, string $export = null): View
    {
        $codes = $this->codeService->generateAndWriteCodes($nb);
        if ($export) {
            $writer = new FileWriter($this->get('kernel'));
            $writer->write($codes);
        }
        return View::create("OK", Response::HTTP_OK);
    }
}
