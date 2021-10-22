<?php

namespace Bx\Cli;

use Bitrix\Main\ModuleManager;
use Bx\Cli\Interfaces\BitrixCliAppInterface;
use Exception;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BitrixCliApplication implements BitrixCliAppInterface
{
    /**
     * @var BitrixCliAppInterface|null
     */
    private static $instance;
    /**
     * @var Application
     */
    private $cliApplication;

    private function __construct()
    {
        $this->cliApplication = new Application('Bitrix CLI', '0.1.0');
    }

    private function __clone(){}

    /**
     * @param Command $command
     * @return void
     */
    public function addCommand(Command $command)
    {
        $this->cliApplication->add($command);
    }

    /**
     * @return BitrixCliAppInterface
     */
    public static function instance(): BitrixCliAppInterface
    {
        if (static::$instance instanceof BitrixCliAppInterface) {
            return static::$instance;
        }

        return static::$instance = new static();
    }

    /**
     * @param string $path
     * @return string
     */
    private function getModuleNameByPath(string $path): string
    {
        $path = str_replace(
            [
                $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/',
                $_SERVER['DOCUMENT_ROOT'].'/local/modules/',
            ],
            '',
            $path
        );

        return current(explode('/', $path));
    }

    private function initModuleCommands()
    {
        $cliApp = $this;
        foreach (glob($_SERVER['DOCUMENT_ROOT'].'/local/modules/*/initcli.php') as $path) {
            $moduleName = $this->getModuleNameByPath($path);
            if (ModuleManager::isModuleInstalled($moduleName)) {
                require_once $path;
            }
        }

        foreach (glob($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/*/initcli.php') as $path) {
            $moduleName = $this->getModuleNameByPath($path);
            if (ModuleManager::isModuleInstalled($moduleName)) {
                require_once $path;
            }
        }
    }

    /**
     * @param InputInterface|null $input
     * @param OutputInterface|null $output
     * @return int
     * @throws Exception
     */
    public function run(InputInterface $input = null, OutputInterface $output = null): int
    {
        $this->initModuleCommands();
        return (int)$this->cliApplication->run($input, $output);
    }
}