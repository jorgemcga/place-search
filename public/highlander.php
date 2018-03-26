<?php
/*
 * Autor: edilycesar@gmail.com
 * Exemplo de execução: php highlander.php -so linux -exec 'index.php execute consoleStart'
 */

$argv = isset($argv) ? $argv : '';
define('ARGV', $argv);
define('NL', "\n");

class Config
{

    private $argv;

    public function __construct()
    {
        $this->argv = ARGV;
    }

    public function getParam($name)
    {
        if (empty($this->argv)) {
            throw new Exception("Falta parâmetros");
        }

        foreach ($this->argv as $key => $value) {
            if (trim($value) == $name) {
                return $this->argv[$key + 1];
            }
        }

        return false;
    }

}

class FindProcess
{

    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function find()
    {
        if ($this->detectSO() === 'linux') {
            return $this->findLinux();
        }

        if ($this->detectSO() === 'windows') {
            return $this->findWindows();
        }

        throw new Exception("S.O. Não selecionado");
    }

    private function detectSO()
    {
        $so = $this->config->getParam('-so');

        if ($so !== 'linux' && $so !== 'windows') {
            throw new Exception("Parâmetro -so não encontrado: -so linux/windows");
        }

        return $so;
    }

    private function findLinux()
    {
        $exec = "ps aux | grep -v grep | grep highlander.php";
        //echo NL . $exec . NL;
        exec($exec, $output);
        return count($output) >= 2 ? true : false;
    }

    private function findWindows()
    {
        $exec = 'tasklist /v | find "highlander.php"';
        //echo NL . $exec . NL;
        exec($exec, $output);
        return count($output) >= 1 ? true : false;
    }

}

class ExecuteOnlyOne
{

    private $findProcess;
    private $config;
    private $execute;

    public function __construct(FindProcess $findProcess, Config $config)
    {
        $this->findProcess = $findProcess;
        $this->config = $config;
        $this->execute = $this->config->getParam('-exec');
    }

    public function start()
    {
        if ($this->findProcess->find() === true) {
            throw new Exception("Existe outro processo executando, só pode haver um.");
        }
        return $this->execute();
    }

    private function execute()
    {
        $execute = "php " . $this->execute;
        echo NL . "EXECUTANDO: " . $execute;
        system($execute);
    }

}

class ConfigFactory
{

    public static function create()
    {
        return new Config();
    }

}

class FindProcessFactory
{

    public static function create()
    {
        return new FindProcess(ConfigFactory::create());
    }

}

class ExecuteOnlyOneFactory
{

    public static function create()
    {
        $findProcess = FindProcessFactory::create();
        $config = ConfigFactory::create();
        return new ExecuteOnlyOne($findProcess, $config);
    }

}

$headerTx  = NL . '****************************************';
$headerTx .= NL . '************* Highlander V1.0 **********';
$headerTx .= NL . '****************************************';

echo $headerTx;

$executeOnlyOne = ExecuteOnlyOneFactory::create();

try {
    $executeOnlyOne->start();
} catch (Exception $exc) {
    echo "\n ERRO: " . $exc->getMessage();
}