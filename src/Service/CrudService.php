<?php
namespace App\Service;

use App\Exception\RegistroDuplicadoException;
use App\Exception\RegistroEmUsoException;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;

class CrudService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function salvar(object $entidade): void
    {
        try {
            $this->em->persist($entidade);
            $this->em->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new RegistroDuplicadoException('Registro duplicado.', previous: $e);
        } catch (ForeignKeyConstraintViolationException $e) {
            throw new RegistroEmUsoException('Registro vinculado a outros.', previous: $e);
        }
    }

    public function remover(object $entidade): void
    {
        try {
            $this->em->remove($entidade);
            $this->em->flush();
        } catch (ForeignKeyConstraintViolationException $e) {
            throw new RegistroEmUsoException('Registro vinculado a outros.', previous: $e);
        }
    }
}
