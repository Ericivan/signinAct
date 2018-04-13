<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/13
 * Time: 15:58
 */

namespace App\Libraries\Scheduler;


class Task
{
    protected $taskId;

    protected $coroutine;

    protected $sendValue = null;

    protected $beforeFirstYield = true;

    public function __construct($taskId,\Generator $coroutine)
    {
        $this->taskId = $taskId;
        $this->coroutine = $coroutine;
    }

    public function getTaskId()
    {
        return $this->taskId;
    }

    public function run()
    {
        if ($this->beforeFirstYield) {
            $this->beforeFirstYield = false;

            return $this->coroutine->current();
        }else{
            $retval = $this->coroutine->send($this->sendValue);
            $this->sendValue = null;
            return $retval;
        }
    }

    public function isFinished()
    {
        return !$this->coroutine->valid();
    }
}