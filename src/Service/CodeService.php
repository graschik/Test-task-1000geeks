<?php

declare(strict_types=1);

namespace App\Service;


use App\Entity\Code;
use App\Repository\CodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;

class CodeService
{
    public $digits = [2, 3, 4, 6, 7, 8];

    public $characters = ['A', 'B', 'C', 'D', 'E', 'F'];

    const COUNT_DIGITS = 4;

    const COUNT_CHARACTERS = 6;
    /**
     * @var CodeRepository
     */
    private $codeRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->codeRepository = $entityManager->getRepository(Code::class);
    }

    public function generateCode(): string
    {
        $code = [];

        for ($j = 0; $j < CodeService::COUNT_CHARACTERS; $j++) {
            $randCharacter = $this->characters[rand(0, count($this->characters) - 1)];
            array_push($code, $randCharacter);
        }
        for ($j = 0; $j < CodeService::COUNT_DIGITS; $j++) {
            $randCharacter = $this->digits[rand(0, count($this->digits) - 1)];
            array_push($code, $randCharacter);
        }
        shuffle($code);

        return implode($code);

    }

    public function writeCode(string $code): bool
    {
        try{
            $codeObject = new Code();
            $codeObject->setCode($code);
            $codeObject->setDate((new \DateTime("now", new \DateTimeZone('Europe/Belarus')))->format('Y-m-d H:i:s e'));

            $this->entityManager->persist($codeObject);
            $this->entityManager->flush();
            return true;
        }catch (ORMException $exception){
            return false;
        }
    }

    public function generateAndWriteCodes(int $count = 1)
    {
        for ($i = 0; $i < $count; $i++) {
            $code = $this->generateCode();
            $this->writeCode($code);
        }
    }

}