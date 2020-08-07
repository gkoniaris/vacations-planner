<?php

namespace App\Core\Commands;

use App\Core\Commands\BaseCommandInterface;
use App\Core\Services\DIContainer;

class CommandsInvoker
{
    protected $commands = [];

    public function addCommand($command)
    {
        $this->commands[] = $command;

        return $this;
    }

    public function run()
    {
        foreach($this->commands as $command) {
            $commandClass = get_class($command);
            $arguments = DIContainer::resolveFunctionArgs($commandClass, 'execute');
            $command->execute(...$arguments);
        }
    }
}