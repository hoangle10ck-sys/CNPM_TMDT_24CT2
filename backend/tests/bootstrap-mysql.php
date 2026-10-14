<?php

use Illuminate\Foundation\Testing\RefreshDatabaseState;

require dirname(__DIR__, 2).'/vendor/autoload.php';

if (
    getenv('DB_CONNECTION') !== 'mysql'
    || getenv('DB_DATABASE') !== 'techstore_codex_test'
) {
    throw new RuntimeException(
        'MySQL tests are restricted to the dedicated techstore_codex_test database.'
    );
}

// The dedicated database is migrated with the additive `migrate` command before
// PHPUnit starts. Skip RefreshDatabase's default `migrate:fresh` behavior.
RefreshDatabaseState::$migrated = true;
