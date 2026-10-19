<?php

namespace App\Contract;

use Doctrine\Common\Collections\Collection;

interface EntidadeComVinculos
{
    public function getLivros(): Collection;
}
