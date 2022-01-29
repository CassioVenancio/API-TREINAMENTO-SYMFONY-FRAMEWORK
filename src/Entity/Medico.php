<?php 

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity()
 */
class Medico implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="integer")
     */
    private $crm;
    /**
     * @ORM\Column(type="string")
     */
    private $nome;

    /**
     * @ORM\ManyToOne(targetEntity=Especialidade::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $especialidade;

    public function getEspecialidade(): ?Especialidade
    {
        return $this->especialidade;
    }

    public function setEspecialidade(?Especialidade $especialidade): self
    {
        $this->especialidade = $especialidade;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCrm()
    {
        return $this->crm;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setCrm(int $crm)
    {
        $this->crm = $crm;
        return $this;
    }

    public function setNome(string $nome)
    {
        $this->nome = $nome;
        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' =>$this->getId(),
            'crm' => $this->getCrm(),
            'nome' => $this->getNome(),
            'especialidadeId' => $this->getEspecialidade()->getId()
        ];
    }
}