
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();

            // Member Information
            $table->string('member_id')->unique();
            $table->string('name');
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('nid')->nullable()->index();

            // Contact Information
            $table->string('phone', 20)->index();
            $table->string('email')->nullable();
            $table->string('division')->nullable();
            $table->string('district')->nullable();
            $table->string('upazila')->nullable();
            $table->text('address')->nullable();

            // Additional Information
            $table->string('occupation')->nullable();
            $table->date('join_date')->nullable();
            $table->string('photo')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('remarks')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['status', 'join_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
