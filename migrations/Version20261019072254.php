<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261019072254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Criação da view para o relatório de  livros';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            CREATE OR REPLACE VIEW vw_livros_por_autor AS
            SELECT
                au.CodAu codigo_autor,
                au.Nome autor,
                l.Codl codigo_livro,
                l.Titulo titulo,
                l.Editora editora,
                l.Edicao edicao,
                l.AnoPublicacao ano_publicacao,
                l.Valor valor,
                GROUP_CONCAT(
                    DISTINCT a.Descricao
                    ORDER BY a.Descricao
                    SEPARATOR ', '
                ) AS assuntos
            FROM Autor au
            INNER JOIN Livro_Autor lau ON lau.Autor_CodAu = au.CodAu
            INNER JOIN Livro l         ON l.Codl = lau.Livro_Codl
            LEFT JOIN Livro_Assunto la ON la.Livro_Codl = l.Codl
            LEFT JOIN Assunto a        ON a.codAs = la.Assunto_codAs
            GROUP BY au.CodAu, au.Nome, l.Codl, l.Titulo, l.Editora, l.Edicao, l.AnoPublicacao, l.Valor
            ORDER BY au.Nome, l.Titulo;
        ");

    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP VIEW IF EXISTS vw_livros_por_autor');
    }
}
