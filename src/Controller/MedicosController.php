<?php

namespace App\Controller;

use App\Entity\Medico;
use App\Helper\MedicoFactory;
use App\Repository\MedicoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicosController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManger;

    /** @var MedicoFactory */
    private $medicoFactory;

    /** @var MedicosRepository */
    private $medicosRepository;

    public function __construct(
        EntityManagerInterface $entityManagerInterface,
        MedicoFactory $medicoFactory,
        MedicoRepository $medicosRepository
    ) {
        $this->entityManger = $entityManagerInterface;
        $this->medicoFactory = $medicoFactory;
        $this->medicosRepository = $medicosRepository;
    }

    /** @Route("/medicos", methods={"POST"}) */
    public function novoMedico(Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $medico = $this->medicoFactory->criarMedico($corpoRequisicao);

        $this->entityManger->persist($medico);
        $this->entityManger->flush();

        return new JsonResponse($medico);
    }

    /** @Route("/medicos", methods={"GET"}) */
    public function buscarTodosMedicos(): Response
    {
        $medicoList = $this->medicosRepository->findAll();
        return new JsonResponse($medicoList);
    }

    /** @Route("/medicos/{id}", methods={"GET"}) */
    public function buscarMedicoPorId(int $id): Response
    {
        $medico = $this->buscaMedico($id);
        $codigoRetorno = 200;
        if(is_null($medico)){
            $codigoRetorno = Response::HTTP_NO_CONTENT;
        }

        return new JsonResponse($medico, $codigoRetorno);
    }

    /** @Route("/medicos/{id}", methods={"PUT"}) */
    public function atualiza(int $id, Request $request): Response 
    {
        $corpoRequisicao = $request->getContent();

        $medicoEnviado = $this->medicoFactory->criarMedico($corpoRequisicao);

        $medicoExistente = $this->buscaMedico($id);

        if (is_null($medicoExistente)){
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        $medicoExistente
            ->setCrm($medicoEnviado->getCrm())
            ->setNome($medicoEnviado->getNome());
            
        $this->entityManger->flush();

        return new JsonResponse($medicoExistente);
    }

    /** @Route("/medicos/{id}", methods={"DELETE"}) */
    public function remove(int $id)
    {
        $medico = $this->buscaMedico($id);
        $this->entityManger->remove($medico);
        $this->entityManger->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }
    
    /** @Route("/especialidades/{especialidadeId}/medicos", methods={"GET"}) */
    public function buscaMedicoPorEspecialidade(
        int $especialidadeId
    ): Response {

        $medicos = $this->medicosRepository->findBy([
            'especialidade' => $especialidadeId
        ]);

        return new JsonResponse($medicos);
    }

    private function buscaMedico(int $id)
    {
        $medico = $this->medicosRepository->find($id);
        return $medico;
    }
}