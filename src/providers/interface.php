<?php

namespace pskuza\dyndns\providers;

interface dns {

    public function create_record(): bool;

    public function update_record(): bool;
}