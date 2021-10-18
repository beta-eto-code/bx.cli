<?php

namespace Bx\Cli\Interfaces;

use Symfony\Component\Console\Command\Command;

interface BitrixCliAppInterface
{
    /**
     * @param Command $command
     * @return mixed
     */
    public function addCommand(Command $command);

    /**
     * @return BitrixCliAppInterface
     */
    public static function instance(): BitrixCliAppInterface;
}