<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Prinx\Notify\Log;

class LogTest extends TestCase
{
    public function test_must_properly_create_logs()
    {
        $file = $this->newTestingLoFile();

        $logger = new Log;

        $logger->setFile($file);

        $this->assertEquals($file, $logger->getFile());
        $this->assertFileExists($file);

        return $logger;
    }

    /**
     * @depends test_must_properly_create_logs
     */
    public function test_must_properly_log_message_to_specify_log_file($logger)
    {
        $file = $logger->getFile();

        file_put_contents($file, '');
        $this->assertStringEqualsFile($file, '');

        $message = 'Simple log.';
        $logger->info($message, false);
        $this->assertStringContainsString($message, file_get_contents($file));

        return $logger;
    }

    /**
     * @depends test_must_properly_log_message_to_specify_log_file
     */
    public function test_must_properly_remove_log_file_and_count_cache($logger)
    {
        $logger->remove();

        $this->assertFileDoesNotExist($logger->getFile());
        $this->assertFileDoesNotExist($logger->getCache());
        $this->assertDirectoryDoesNotExist(dirname($logger->getFile()));
    }

    public function newTestingLoFile()
    {
        $dir = $this->randomString(10);
        $file = $this->randomString(10).'.'.$this->randomString(3);
        $file = realpath(__DIR__.'/../../')."/logs/{$dir}/{$file}";

        return $file;
    }

    public function randomString($length)
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyz';
        $alphabetLength = strlen($alphabet) - 1;
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= $alphabet[random_int(0, $alphabetLength)];
        }

        return $string;
    }
}
