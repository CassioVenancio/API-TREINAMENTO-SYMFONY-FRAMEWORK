<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Helper\EspecialidadeFactory;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspecialidadesController extends BaseController
{
    public function __construct(
        EspecialidadeRepository $repository,
        EntityManagerInterface $entityManager,
        EspecialidadeFactory $factory
    ) {
        parent::__construct(
            $repository,
            $entityManager,
            $factory
        );
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @param Especialidade $entidadeExistente
     * @param Especialidade $entidadeEnviada
     */
    public function atualizarEntidadeExistente($entidadeExistente, $entidadeEnviada)
    {
        $entidadeExistente
            ->setDescricao($entidadeEnviada->getDescricao());
    }
}
