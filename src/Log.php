<?php

/*
 * This file is part of the Notify package.
 *
 * (c) Prince Dorcis <princedorcis@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Prinx\Notify;

/**
 * Simple Log.
 */
class Log
{
    protected $file = '';
    protected $cache = '';
    protected $levels = [
        'debug',
        'info',
        'notice',
        'warning',
        'error',
        'critical',
        'alert',
        'emergency',
    ];

    public function __construct($file = '', $cache = '')
    {
        if ($file) {
            $this->setFile($file, $cache);
        }

        if ($cache) {
            $this->setCache($cache);
        }
    }

    public function debug($message, $flag = FILE_APPEND)
    {
        $this->log('debug', $message, $flag);
    }

    public function info($message, $flag = FILE_APPEND)
    {
        $this->log('info', $message, $flag);
    }

    public function notice($message, $flag = FILE_APPEND)
    {
        $this->log('notice', $message, $flag);
    }

    public function warning($message, $flag = FILE_APPEND)
    {
        $this->log('warning', $message, $flag);
    }

    public function error($message, $flag = FILE_APPEND)
    {
        $this->log('error', $message, $flag);
    }

    public function critical($message, $flag = FILE_APPEND)
    {
        $this->log('critical', $message, $flag);
    }

    public function alert($message, $flag = FILE_APPEND)
    {
        $this->log('alert', $message, $flag);
    }

    public function emergency($message, $flag = FILE_APPEND)
    {
        $this->log('emergency', $message, $flag);
    }

    /**
     * Log method for any level.
     *
     * If $message is string, it will be logged as-is.
     * If message is an array, it will be converted to json
     * If message is an object AND implements the `__toString` method
     * it will be converted to string
     * Else, the message will be print with print_r.
     *
     * @param  string              $level
     * @param  string|array|object $message
     * @param  const               $flag
     * @throws \Exception
     * @return void
     */
    public function log(string $level, $message, $flag = FILE_APPEND)
    {
        if (!method_exists($this, $level)) {
            throw new \Exception('Log level `'.$level.'` not supported');
        }

        if (is_string($message)) {
            $toLog = trim($message);
        } elseif (is_array($message)) {
            $toLog = json_encode($message, JSON_PRETTY_PRINT);
        } elseif (is_object($message) && method_exists($message, '__toString')) {
            $toLog = strval($message);
        } else {
            $toLog = print_r($message, true);
        }

        $num = $this->count();

        $toLog = '#'.$num.' ['.strtoupper($level).'] ['.date('D, d m Y, H:i:s')."]\n".$toLog."\n\n";

        file_put_contents($this->file, $toLog, $flag);
        file_put_contents($this->cache, $num);
    }

    public function clear()
    {
        file_put_contents($this->file, '');
        file_put_contents($this->cache, 0);
    }

    public function remove()
    {
        unlink($this->file);
        unlink($this->cache);

        @rmdir(dirname($this->cache));
        @rmdir(dirname($this->file));
    }

    public function setFile($file, $cache = '')
    {
        if (empty($file)) {
            throw new \RuntimeException('Invalid path for log.');
        }

        $this->file = $file ? $file : $this->file;

        $this->ensureFileExists($this->file);

        $this->setCache($cache, $this->file);

        return $this;
    }

    public function setCache($cache, $logFile = '')
    {
        if (empty($cache) && empty($logFile)) {
            throw new \RuntimeException('Invalid path for cache.');
        }

        $this->cache = ($cache ? $cache : dirname($logFile).'/cache/.'.basename($logFile).'.count') ?: $this->cache;

        if ($this->cache) {
            $this->ensureFileExists($this->cache, 0);
        }

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getCache()
    {
        return $this->cache;
    }

    public function ensureFileExists($file, $defaultValue = '')
    {
        if (!file_exists($file)) {
            if (!file_exists(dirname($file))) {
                mkdir(dirname($file), 0755, true);
            }

            file_put_contents($file, $defaultValue);
        }
    }

    public function count()
    {
        return intval(file_get_contents($this->cache)) + 1;
    }
}
