<?php

namespace App\Service\Transporter;

interface TransporterInterface
{
    public function export(string $to);

    public function import(string $from);
}
