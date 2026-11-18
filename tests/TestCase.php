<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    /**
     * Refuse to run against anything but the in-memory test database.
     *
     * RefreshDatabase runs migrate:fresh, which drops every table of whatever
     * connection the app is configured with. phpunit.xml points the tests at
     * sqlite :memory:, but a phpunit.xml without that override (a stash, a
     * checkout of an older commit, a cached config from `config:cache`) sends
     * them to database/database.sqlite instead, and the site's pages, menu
     * and accounts are wiped on the next run. This check sits before the
     * traits are set up, so it fires before anything is dropped.
     */
    protected function setUpTraits()
    {
        $connection = config('database.default');
        $database = config("database.connections.{$connection}.database");

        if ($database !== ':memory:') {
            throw new RuntimeException(
                "Tests refused to run: they would migrate:fresh the [{$connection}] database "
                . "[{$database}]. Point them at sqlite :memory: (phpunit.xml) and run "
                . '`php artisan config:clear` if the config is cached.'
            );
        }

        return parent::setUpTraits();
    }
}
