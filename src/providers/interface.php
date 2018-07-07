<?php

namespace pskuza\dyndns\providers;

interface dns {

    public function create_record();

    public function update_record();
}