<?php

namespace App\Controller;

use App\Factory\EspecialidadesFactory;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspecialidadesController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    /**
     * @var EspecialidadesFactory
     */
    private $especialidadeFactoryController;

    /**
     * @var EspecialidadeRepository
     */
    private $especialidadeRespository;

    public function __construct(EntityManagerInterface $entityManagerInterface, 
                                EspecialidadesFactory $especialidadeFactory, 
                                EspecialidadeRepository $repository)
    {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->especialidadeFactoryController = $especialidadeFactory;
        $this->especialidadeRespository = $repository;
    }

    //Criar Especialidade
    #[Route('/especialidades', methods:'POST')]
    public function create(Request $request): Response
    {
        $dadosRequest = $request->getContent();

        $especialidade = $this->especialidadeFactoryController->create($dadosRequest);

        $this->entityManagerInterface->persist($especialidade);
        $this->entityManagerInterface->flush();

        return New JsonResponse($especialidade);
    }
    
    // Atualizar Especialidade
    #[Route('/especialidades/{id}', methods:'PUT')]
    public function update(int $id, Request $request): Response
    {
        $corpoRequisicao = $request->getContent();

        $especialidadeEnviada = $this->especialidadeFactoryController->create($corpoRequisicao);

        $especialidade = $this->especialidadeRespository->find($id);

        $especialidade->setDescricao($especialidadeEnviada->getDescricao());

        $this->entityManagerInterface->flush();

        return new JsonResponse($especialidade);
    }

    // Deletar Especialidade
    #[Route('/especialidades/{id}', methods:'DELETE')]
    public function remove(int $id): Response
    {
        $especialidade = $this->especialidadeRespository->find($id);
        
        $this->entityManagerInterface->remove($especialidade);
        $this->entityManagerInterface->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    //Recupera Todas Especialidades
    #[Route('/especialidades', methods:'GET')]
    public function getAll(): Response
    {
        $especialidadeList = $this->especialidadeRespository->findAll();

        return new JsonResponse($especialidadeList);
    }

    //Recupera Uma Especialidade Específica
    #[Route('/especialidades/{id}', methods:'GET')]
    public function getEspecialidade(int $id): Response
    {
        $especialidade = $this->especialidadeRespository->find($id);

        return new JsonResponse($especialidade);
    }

}
