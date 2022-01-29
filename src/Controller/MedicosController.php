<?php

namespace App\Controller;

use App\Entity\Medico;
use App\Helper\MedicoFactory;
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

    public function __construct(EntityManagerInterface $entityManagerInterface,
        MedicoFactory $medicoFactory
    )
    {
        $this->entityManger = $entityManagerInterface;
        $this->medicoFactory = $medicoFactory;
    }

    /** @Route("/medicos", methods={"POST"}) */
    public function novo(Request $request): Response
    {
        $corpoRequisicao = $request->getContent();
        $medico = $this->medicoFactory->criarMedico($corpoRequisicao);

        $this->entityManger->persist($medico);
        $this->entityManger->flush();

        return new JsonResponse($medico);
    }

    /** @Route("/medicos", methods={"GET"}) */
    public function buscarTodos(): Response
    {
        $repositorioDeMedicos = $this
            ->getDoctrine()
            ->getRepository(Medico::class);

        $medicoList = $repositorioDeMedicos->findAll();
        return new JsonResponse($medicoList);
    }

    /** @Route("/medicos/{id}", methods={"GET"}) */
    public function buscarUm(int $id): Response
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

    private function buscaMedico(int $id)
    {
        $repositorioDeMedicos = $this
            ->getDoctrine()
            ->getRepository(Medico::class);
        $medico = $repositorioDeMedicos->find($id);
        return $medico;
    }
}