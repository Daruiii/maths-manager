<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $connection = (string) config('database.default');
        $database = (string) config("database.connections.{$connection}.database");

        if ($connection === 'sqlite') {
            return;
        }

        if ($database === '' || !str_contains(strtolower($database), 'test')) {
            throw new RuntimeException(
                "Unsafe test database detected: '{$database}'. Expected a dedicated test database. " .
                "If this is local, clear cached config first: `php artisan config:clear`."
            );
        }
    }
}
