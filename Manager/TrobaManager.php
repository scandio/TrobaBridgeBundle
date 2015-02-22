<?php
namespace Scandio\TrobaBridgeBundle\Manager;

use Psr\Log\LoggerInterface;
use troba\EQM\EQM;
use troba\EQM\PDOWrapper;

class TrobaManager
{
    /**
     * @var string
     */
    private $driver;

    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct($driver, $host, $port, $name, $user, $password, LoggerInterface $logger = null)
    {
        $this->driver = $driver;
        $this->host = $host;
        $this->port = empty($port) ? 3306 : $port;
        $this->name = $name;
        $this->user = $user;
        $this->password = $password;
        $this->logger = $logger;

        $this->init();
    }

    /**
     * @param array $config
     */
    public function init()
    {
        $config = [];
        $this->logger instanceof LoggerInterface && $config[PDOWrapper::LOGGER] = $this->logger;
        EQM::initialize(
            new \PDO(
                "mysql:host={$this->host};port={$this->port};dbname={$this->name}", $this->user, $this->password,
                [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"]
            ),
            $config
        );
    }

    /**
     * @param $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, array $args)
    {
        return forward_static_call_array(array('troba\EQM\EQM', $method), $args);
    }

    /**
     * @param $name
     * @param array $init
     */
    public function addEntityManager($name, array $init)
    {
        EQM::initialize($init, $name);
    }
}