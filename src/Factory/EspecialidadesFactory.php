<?php

namespace App\Factory;

use App\Entity\Especialidade;

class EspecialidadesFactory
{
    public function create(string $json):Especialidade
    {
        $dadosEmJson = json_decode($json);

        $especialidade = new Especialidade();
        $especialidade->setDescricao($dadosEmJson->descricao);

        return $especialidade;
    }
}