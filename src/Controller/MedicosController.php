<?php

namespace App\Controller;

use App\Entity\Medico;
use App\Helper\ExtratorDadosRequest;
use App\Helper\MedicoFactory;
use App\Repository\MedicoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicosController extends BaseController
{
    public function __construct(
        EntityManagerInterface $entityManager,
        MedicoFactory $medicoFactory,
        MedicoRepository $medicosRepository,
        ExtratorDadosRequest $extratorDadosRequest
    ) {
        parent::__construct(
            $medicosRepository,
            $entityManager,
            $medicoFactory,
            $extratorDadosRequest
        );
        $this->entityManger = $entityManager;
        $this->medicoFactory = $medicoFactory;
        $this->repository = $medicosRepository;
    }

    /**
     * @param Medico $entidadeExistente
     * @param Medico $entidadeEnviada
     */
    public function atualizarEntidadeExistente($entidadeExistente, $entidadeEnviada)
    {
        $entidadeExistente
            ->setCrm($entidadeEnviada->getCrm())
            ->setNome($entidadeEnviada->getNome())
            ->setEspecialidade($entidadeEnviada->getEspecialidade());   
    }

    /** @Route("/especialidades/{especialidadeId}/medicos", methods={"GET"}) */
    public function buscaMedicoPorEspecialidade(
        int $especialidadeId
    ): Response {

        $medicos = $this->repository->findBy([
            'especialidade' => $especialidadeId
        ]);

        return new JsonResponse($medicos);
    }

}