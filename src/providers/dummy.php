<?php
declare(strict_types=1);

namespace pskuza\dyndns\providers;

class dummy implements pskuza\dyndns\providers\dns
{
    public function create_record(): bool
    {
        return true;
    }

    public function update_record(): bool
    {
        return true;
    }
}