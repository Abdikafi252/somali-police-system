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
        Schema::create('investigation_statements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investigation_id')->constrained()->onDelete('cascade');
            $table->string('person_name');
            $table->enum('person_type', ['Eedeysane', 'Markhaati', 'Dhibane']); // Suspect, Witness, Victim
            $table->text('statement');
            $table->date('statement_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investigation_statements');
    }
};
