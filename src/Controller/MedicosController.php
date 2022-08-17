<?php 

namespace App\Controller;

use App\Entity\Medico;
use App\Factory\MedicoFactory;
use Doctrine\ORM\EntityManagerInterface;
//use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use symfony\Component\Routing\Annotation\Route;

class MedicosController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManagerMedicoController;

    /**
     * @var MedicoFactory
     */
    private $medicoFactoryController;

    public function __construct(EntityManagerInterface $entityManager,
                                MedicoFactory $medicoFactory)
    {
        $this->entityManagerMedicoController = $entityManager;
        $this->medicoFactoryController = $medicoFactory;
    }

    /**
    * @Route("/medicos", methods={"POST"})
    */ 
    public function create(Request $request): Response
    {
        $corpoRequisicao = $request->getContent();

        $medico = $this->medicoFactoryController->create($corpoRequisicao);

        $this->entityManagerMedicoController->persist($medico);
        $this->entityManagerMedicoController->flush();

        return new JsonResponse($medico);
    }

    /**
     * @Route("/medicos", methods={"GET"})
     */
    public function getMedicosTodos(): Response
    {
        /* pode ser feito dessa forma
        public function getMedicosTodos(ManagerRegistry $doctrine): Response 
        Método getDoctrine está depreciado nessa versão, necessário utilizar a blibioteca ManagerRegistry, conforme descrito na função.
        $repositorioMedico = $doctrine->getRepository(Medico::class);
        */
        $repositorioMedicos = $this->entityManagerMedicoController->getRepository(Medico::class);

        $medicosList = $repositorioMedicos->findAll();

        return new JsonResponse($medicosList);
    }

    /**
     * @Route("/medicos/{id}", methods={"GET"})
     */
    public function getMedico(int $id ):Response
    {
        //$id = $request->get('id');
        //Pode ser pego o parametro diretamente da rota caso seja o mesmo nome do parametro da rota
        //busca o médico a partir do método criado para localizar o registro.
        $medico = $this->getMedicoID($id);

        $codigoRetorno = is_null($medico) ? Response::HTTP_NO_CONTENT:200;

        return new JsonResponse($medico, $codigoRetorno);

    }

    /**
     * @Route("/medicos/{id}", methods={"PUT"})
     */
    public function update(int $id, Request $request):Response
    {
        //Captura o 'id' da requisição passada na url
        //$id = $request->get('id');
        //Pode ser pego o parametro diretamente da rota caso seja o mesmo nome do parametro da rota

        //Captura os dados encaminhados na requisição (crm, nome do médico, etc...)
        $corpoRequisicao = $request->getContent();

        //utiliza a factory de Médico para criar a instancia de médico de forma mais simplificada 
        $medicoEnviado = $this->medicoFactoryController->create($corpoRequisicao);

        //busca o médico a partir do método criado para localizar o registro.
        $medico = $this->getMedicoID($id);

        if (is_null($medico)){
            return Response('', Response::HTTP_NOT_FOUND);
        }
        
        //Define os campos e os dados a serem atualizados
        $medico
            ->setCrm($medicoEnviado->getCrm())
            ->setNomeMedico($medicoEnviado->getNomeMedico())
            ->setEspecialidade($medicoEnviado->getEspecialidade());

        //Confirma as atualizações no banco de dados
        $this->entityManagerMedicoController->flush();

        //Retorna um Json 
        return new JsonResponse($medico);
        
    }

    /**
     * @Route("/medicos/{id}", methods={"DELETE"})
     */
    public function removeMedico(int $id): Response
    {
        //Busca o médico de acordo com o id passado usando o método criado
        $medico = $this->getMedicoID($id);

        //utiliza o entity manager para criar a ação do remover desse registro
        $this->entityManagerMedicoController->remove($medico);
        
        //efetiva essas alterações no banco de dados
        $this->entityManagerMedicoController->flush();

        //Retorna uma resposta vazia.
        return new Response('', Response::HTTP_NO_CONTENT);
    }
    
    /**
     * @param int $id
     * @return object|null
     */
    public function getMedicoID(int $id)
    {
        $repositorioMedicos =
        $this->entityManagerMedicoController->getRepository(Medico::class);
        $medico = $repositorioMedicos->find($id);

        return $medico;
    }

    /*public function updateMedico_comMetodosDiretos(int $id, Request $request):Response
    {
        //Captura o 'id' da requisição passada na url
        //$id = $request->get('id');
        //Pode ser pego o parametro diretamente da rota caso seja o mesmo nome do parametro da rota

        //Captura os dados encaminhados na requisição (crm, nome do médico, etc...)
        $corpoRequisicao = $request->getContent();

        //Formata os dados capturads na requisiação com a função abaixo para poder serem utilizados
        $dadosEmJson = json_decode($corpoRequisicao);

        //a partir dos dados coletados na requisição, mapei as informações na entidade do tipo médico e lança os seus dados nos determinados campos
        $medicoEnviado = new Medico();
        $medicoEnviado->crm = $dadosEmJson->crm;
        $medicoEnviado->nomeMedico= $dadosEmJson->nomeMedico;

        //Busca o registro na entidade desejada baseado na blibioteca EntityManagerInterface criada na construct no inicio da classe
        $repositorioMedicos =
        $this->entityManagerMedicoController->getRepository(Medico::class);
        $medico = $repositorioMedicos->find($id);

        if (is_null($medico)){
            return Response('', Response::HTTP_NOT_FOUND);
        }
        
        //Define os campos e os dados a serem atualizados
        $medico->crm = $medicoEnviado->crm;
        $medico->nomeMedico = $medicoEnviado->nomeMedico;

        //Confirma as atualizações no banco de dados
        $this->entityManagerMedicoController->flush();

        //Retorna um Json 
        return new JsonResponse($medico);
       
    }
    */
}