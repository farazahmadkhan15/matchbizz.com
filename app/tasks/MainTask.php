<?php

use Phalcon\Cli\Task;

class MainTask extends Task
{
    protected $log;
    
    public function initialize()
    {
        $this->log = $this->getDI()->getLogger();
    }

    public function mainAction()
    {
        echo "This is the default task and the default action" . PHP_EOL;
        $this->log->info("This is the default task and the default action");
        $this->log->error("This is the default task and the default action");
    }

    /**
     * @param array $params
     */
    public function testAction(array $params)
    {
        echo sprintf(
           "hello %s",
           $params[0]
        );
 
        echo PHP_EOL;
 
        echo sprintf(
           "best regards, %s",
           $params[1]
        );
 
        echo PHP_EOL;
    }
}
