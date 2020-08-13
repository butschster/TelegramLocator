<?php

namespace App\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class SchemaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Blueprint::macro('primaryUuid', function (string $columnName = 'uuid') {
            return $this->uuid($columnName)->primary();
        });

        Blueprint::macro('belongsTo', function (string $table, string $columnName) {
            $column = $this->uuid($columnName)->index();

            $this->foreign($columnName)
                ->references('uuid')
                ->on($table)
                ->onDelete('cascade');

            return $column;
        });
    }
}
