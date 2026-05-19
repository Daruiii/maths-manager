<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Généralise correction_requests pour couvrir DS et DM :
     * - ds_id devient nullable (l'une ou l'autre FK est remplie)
     * - dm_id ajouté (FK nullable vers dm)
     * - grade rendu nullable (absent avant correction)
     * - correction_pdf supprimé (redondant avec latex_solution)
     */
    public function up(): void
    {
        Schema::table('correction_requests', function (Blueprint $table) {
            $table->dropForeign(['ds_id']);
            $table->unsignedBigInteger('ds_id')->nullable()->change();
            $table->foreign('ds_id')->references('id')->on('ds')->onDelete('cascade');

            $table->foreignId('dm_id')
                ->nullable()
                ->after('ds_id')
                ->constrained('dm')
                ->onDelete('cascade');

            $table->integer('grade')->nullable()->change();
        });

        if (Schema::hasColumn('correction_requests', 'correction_pdf')) {
            Schema::table('correction_requests', function (Blueprint $table) {
                $table->dropColumn('correction_pdf');
            });
        }
    }

    public function down(): void
    {
        Schema::table('correction_requests', function (Blueprint $table) {
            $table->dropForeign(['dm_id']);
            $table->dropColumn('dm_id');

            $table->dropForeign(['ds_id']);
            $table->unsignedBigInteger('ds_id')->nullable(false)->change();
            $table->foreign('ds_id')->references('id')->on('ds')->onDelete('cascade');

            $table->integer('grade')->nullable(false)->change();

            $table->string('correction_pdf')->nullable();
        });
    }
};
