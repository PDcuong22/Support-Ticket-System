<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attachments', function (Blueprint $table) {
            $table->string('file_name')->before('file_path');
            $table->string('mime_type')->after('ticket_id');
            $table->unsignedBigInteger('file_size')->after('mime_type');
            $table->foreignId('uploaded_by')->nullable()->after('file_size')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attachments', function (Blueprint $table) {
            $table->dropForeign(['uploaded_by']);
            $table->dropColumn(['file_name', 'mime_type', 'file_size', 'uploaded_by']);
        });
    }
};
