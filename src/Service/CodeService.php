<?php

declare(strict_types=1);

namespace App\Service;


use App\Entity\Code;
use App\Repository\CodeRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * CodeService constructor.
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->codeRepository = $entityManager->getRepository(Code::class);
        $this->validator = $validator;
    }

    /**
     * @return string
     */
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

    /**
     * @param string $code
     * @return bool
     */
    public function writeCode(string $code): bool
    {
        $codeObject = new Code();
        $codeObject->setCode($code);
        $codeObject->setDate((new \DateTime("now", new \DateTimeZone("Europe/Minsk"))));

        if (count($this->validator->validate($codeObject)) == 0) {
            $this->entityManager->persist($codeObject);
            $this->entityManager->flush();

            return true;
        }
        return false;
    }

    /**
     * @param int $count
     */
    public function generateAndWriteCodes(int $count = 1)
    {
        for ($i = 0; $i < $count; $i++) {
            $code = $this->generateCode();
            if (!$this->writeCode($code)) {
                $i--;
            }
        }
    }

    /**
     * @param string $code
     * @return Code|null|object
     */
    public function getCodeInfo(string $code)
    {
        return $this
            ->codeRepository
            ->findOneBy(['code' => $code]);
    }
}