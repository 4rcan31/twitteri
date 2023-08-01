<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('followers_count')->default(0);
            $table->string('name')->nullable(false);
            $table->string('lastname')->nullable(false);
            $table->string('username', 50)->nullable(false)->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();


            DB::unprepared('
                    CREATE TRIGGER increase_follower_count AFTER INSERT ON followers
                    FOR EACH ROW
                    BEGIN
                        UPDATE users SET followers_count = followers_count + 1 
                        WHERE id = NEW.following_id
                    END
            ');

            DB::unprepared('
                    CREATE TRIGGER decrease_follower_count 
                    AFTER DELETE ON followers
                    FOR EACH ROW
                    BEGIN
                        UPDATE users SET followers_count = followers_count - 1 
                        WHERE id = NEW.following_id
                    END
            ');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS `increase_follower_count`');
        DB::unprepared('DROP TRIGGER IF EXISTS `decrease_follower_count`');
        Schema::dropIfExists('users');
    }
};
