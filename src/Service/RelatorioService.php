<?php

namespace App\Service;

use Doctrine\DBAL\Connection;
use Dompdf\Dompdf;

class RelatorioService
{
    public function __construct(private Connection $connection)
    {
    }

    /** @return array<string, list<array<string, mixed>>> */
    public function livrosAgrupadosPorAutor(?string $autor = null): array
    {
        $sql = 'SELECT * FROM vw_livros_por_autor';
        $params = [];

        if ($autor) {
            $sql .= ' WHERE autor LIKE :autor';
            $params['autor'] = '%' . $autor . '%';
        }

        $linhas = $this->connection->fetchAllAssociative($sql, $params);

        $porAutor = [];
        foreach ($linhas as $linha) {
            $porAutor[$linha['autor']][] = $linha;
        }

        return $porAutor;
    }

    public function gerarPdf(string $html): string
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }
}
