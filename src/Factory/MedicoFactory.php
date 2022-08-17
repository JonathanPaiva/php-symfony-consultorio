<?php

namespace App\Factory;

use App\Entity\Medico;
use App\Repository\EspecialidadeRepository;

class MedicoFactory
{
    /**
     * @var EspecialidadeRepository
     */
    private $especialidadeRepository;

    public function __construct(EspecialidadeRepository $especialidadeRepository)
    {
        $this->especialidadeRepository = $especialidadeRepository;
    }

    public function create(string $json):Medico
    {
        //Formata os dados passado no parametro da função para poder serem utilizados
        $dadosEmJson = json_decode($json);

        $especialidadeId = $dadosEmJson->especialidadeId;

        $especialidade = $this->especialidadeRepository->find($especialidadeId);

        //a partir dos dados coletados na requisição, mapei as informações na entidade do tipo médico e lança os seus dados nos determinados campos
        $medico = new Medico();
        $medico
            ->setCrm($dadosEmJson->crm)
            ->setNomeMedico($dadosEmJson->nome)
            ->setEspecialidade($especialidade);

        return $medico;
    }
}