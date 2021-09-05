<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->enum('category', [
                'Fundamentals', 'String', 'Algorithms', 'Mathematic',
                'Performance', 'Booleans', 'Functions'
            ]);
            $table->string('title');
            $table->text('body');
            $table->tinyInteger('solved')->default(0)->comment('0=not, 1=yes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
