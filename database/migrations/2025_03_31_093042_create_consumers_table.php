<?php

use App\Models\Subcity;
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
        Schema::create('consumers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('password');
            $table->string('institution_name');
            $table->string('primary_phone')->unique();
            $table->string('secondary_phone')->unique();
            $table->string('license_number');
            $table->string('tin_number');
            $table->foreignIdFor(Subcity::class)->nullable()->constrained()->onDelete('set null');
            $table->string('special_place');
            $table->integer('woreda');
            $table->boolean('approved')->default(false);
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumers');
    }
};
