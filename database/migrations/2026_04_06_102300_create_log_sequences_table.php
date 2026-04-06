<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_sequences', function (Blueprint $table) {
            $table->uuid('application_id')->primary();
            $table->unsignedBigInteger('last_seq')->default(0);

            $table->foreign('application_id')
                ->references('id')
                ->on('applications')
                ->cascadeOnDelete();
        });

        // Populate dari data existing:
        // Ambil seq terakhir per application_id dari unified_logs
        DB::statement("
            INSERT INTO log_sequences (application_id, last_seq)
            SELECT application_id, MAX(seq) AS last_seq
            FROM unified_logs
            GROUP BY application_id
        ");

        // Tambahkan index DESC untuk optimasi query ORDER BY seq DESC
        DB::statement("
            ALTER TABLE unified_logs
            ADD INDEX idx_app_seq_desc (application_id, seq DESC)
        ");
    }

    public function down(): void
    {
        // Hapus index dulu
        try {
            DB::statement("ALTER TABLE unified_logs DROP INDEX idx_app_seq_desc");
        } catch (\Throwable $e) {
            // Index mungkin tidak ada, abaikan
        }

        Schema::dropIfExists('log_sequences');
    }
};
