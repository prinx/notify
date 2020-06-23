<?php
namespace Prinx\Notify;

class Log
{
    protected $file = '';
    protected $cache = '';

    public function __construct($file = '', $cache = '')
    {
        $this->file = $file ?: realpath(__DIR__ . '../../') . '/app.log';
        $this->cache = $cache ?: '.' . $file . '.cache';

        if (!file_exists($this->cache)) {
            file_put_contents($this->cache, 0);
        }
    }

    public function info($message, $flag = FILE_APPEND)
    {
        $num = intval(file_get_contents($this->cache)) + 1;

        $to_log = is_string($message) ? trim($message) : print_r($message, true);
        $to_log = '#' . $num . ' [' . date("D, d m Y, H:i:s") . "]\n" . $to_log . "\n\n";

        file_put_contents($this->file, $to_log, $flag);
        file_put_contents($this->cache, $num);
    }

    public function warn($message, $flag = FILE_APPEND)
    {
        $this->info($message, $flag);
    }

    public function error($message, $flag = FILE_APPEND)
    {
        $this->info($message, $flag);
    }

    public function alert($message, $flag = FILE_APPEND)
    {
        $this->info($message, $flag);
    }

    public function debug($message, $flag = FILE_APPEND)
    {
        $this->info($message, $flag);
    }

    public function notice($message, $flag = FILE_APPEND)
    {
        $this->info($message, $flag);
    }

    public function clear()
    {
        file_put_contents($this->file, '');
        file_put_contents($this->cache, 0);
    }
}
