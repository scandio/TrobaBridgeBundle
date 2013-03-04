<?php
namespace Scandio\TrobaBridgeBundle\Manager;

use troba\EQM\EQM;

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

    public function __construct($driver, $host, $port, $name, $user, $password)
    {
        $this->driver = $driver;
        $this->host = $host;
        $this->port = empty($port) ? 3306 : $port;
        $this->name = $name;
        $this->user = $user;
        $this->password = $password;

        $init = [
        'dsn' => "$this->driver:host=$this->host:$this->port;dbname=$this->name",
        'username' => $this->user,
        'password' => $this->password,
        EQM::RUN_MODE => EQM::DEV_MODE, // todo: config can change mode
    ];

        EQM::initialize($init);
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