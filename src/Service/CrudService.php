<?php
namespace App\Service;

use App\Contract\EntidadeComVinculos;
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
        if ($entidade instanceof EntidadeComVinculos && !$entidade->getLivros()->isEmpty()) {
            throw new RegistroEmUsoException('Existem livros vinculados a este registro.');
        }

        try {
            $this->em->remove($entidade);
            $this->em->flush();
        } catch (ForeignKeyConstraintViolationException $e) {
            throw new RegistroEmUsoException('Registro vinculado a outros.', previous: $e);
        }
    }
}
