<?php
/**
 * Created by PhpStorm.
 * User: siklol
 * Date: 2/22/15
 * Time: 7:39 PM
 */

namespace Scandio\TrobaBridgeBundle\DataCollector;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class SqlDataCollector extends DataCollector
{
    const NAME = "troba_sql_data_collector";

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Collects data for the given Request and Response.
     *
     * @param Request $request A Request instance
     * @param Response $response A Response instance
     * @param \Exception $exception An Exception instance
     *
     * @api
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = $this->logger->getData();
    }

    /**
     * Returns the name of the collector.
     *
     * @return string The collector name
     *
     * @api
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}