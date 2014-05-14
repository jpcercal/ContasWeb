<?php

namespace Cekurte\Home\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Conta
 *
 * @ORM\Table(name="conta")
 * @ORM\Entity(repositoryClass="\Cekurte\Home\AdminBundle\Entity\Repository\ContaRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Conta
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo_despesa", type="string", length=255, nullable=false)
     */
    private $tipoDespesa;

    /**
     * @var string
     *
     * @ORM\Column(name="forma_pagamento", type="string", length=255, nullable=false)
     */
    private $formaPagamento;

    /**
     * @var string
     *
     * @ORM\Column(name="conta", type="string", length=255, nullable=true)
     */
    private $conta;

    /**
     * @var string
     *
     * @ORM\Column(name="valor", type="string", length=255, nullable=false)
     */
    private $valor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data", type="datetime", length=255, nullable=false)
     */
    private $data;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->setCreatedAt(new \DateTime('NOW'));
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tipoDespesa
     *
     * @param string $tipoDespesa
     * @return Conta
     */
    public function setTipoDespesa($tipoDespesa)
    {
        $this->tipoDespesa = $tipoDespesa;

        return $this;
    }

    /**
     * Get tipoDespesa
     *
     * @return string
     */
    public function getTipoDespesa()
    {
        return $this->tipoDespesa;
    }

    /**
     * Set formaPagamento
     *
     * @param string $formaPagamento
     * @return Conta
     */
    public function setFormaPagamento($formaPagamento)
    {
        $this->formaPagamento = $formaPagamento;

        return $this;
    }

    /**
     * Get formaPagamento
     *
     * @return string
     */
    public function getFormaPagamento()
    {
        return $this->formaPagamento;
    }

    /**
     * Set conta
     *
     * @param string $conta
     * @return Conta
     */
    public function setConta($conta)
    {
        $this->conta = $conta;

        return $this;
    }

    /**
     * Get conta
     *
     * @return string
     */
    public function getConta()
    {
        return $this->conta;
    }

    /**
     * Set valor
     *
     * @param string $valor
     * @return Conta
     */
    public function setValor($valor)
    {
        // TODO: formatar valor
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return string
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set data
     *
     * @param \DateTime $data
     * @return Conta
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return \DateTime
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Conta
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
