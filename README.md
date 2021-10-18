# Bitrix CLI

Предоставляет единый CLI интерфейс, функционал расширяется за счет доп. модулей. Для добавления нового функционала в корне 
нового модуля должен быть расположен файл **initcli.php**, пример содержимого файла:

```php
use Bitrix\Main\Loader;
use Bx\Cli\Interfaces\BitrixCliAppInterface;

/**
 * @var BitrixCliAppInterface $cliApp
 */

if (empty($cliApp) || !($cliApp instanceof BitrixCliAppInterface)) {
    return;
}

Loader::includeModule('current.module');

$cliApp->addCommand(new MyLocalCommand());  // класс должен наследоваться от \Symfony\Component\Console\Command\Command
```

После установки модуля в корне проекта появиться исполяемый файл **bxcli**