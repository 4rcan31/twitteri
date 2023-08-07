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
    public function up(): void{
        Schema::create('tweets', function (Blueprint $table) {
            $table->id();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('tweet', 280)->nullable(false); //280 por que es la capacidad maxima de tweeter
            $table->integer('likes_count')->default(0);
            $table->integer('retweets_count')->default(0);
            $table->integer('commets_count')->default(0);
            $table->timestamps();
        });

        /* Triggers para aumentar el contador de likes, retweets y comentarios */
        DB::unprepared('
            CREATE TRIGGER increase_likes
            AFTER INSERT ON tweet_likes
            FOR EACH ROW
            BEGIN
                UPDATE tweets SET likes_count = likes_count + 1 
                WHERE id = NEW.user_id
            END
        ');

        DB::unprepared('
        CREATE TRIGGER increase_retweets
        AFTER INSERT ON retweets
        FOR EACH ROW
        BEGIN
            UPDATE tweets SET retweets_count = retweets_count + 1 
            WHERE id = NEW.user_id
        END
        ');
        
        DB::unprepared('
            CREATE TRIGGER increase_commets
            AFTER INSERT ON commets
            FOR EACH ROW
            BEGIN
                UPDATE tweets SET commets_count = commets_count + 1 
                WHERE id = NEW.user_id
            END
        ');


        /* Trigger para decrementar el contador de likes, retweets y comentarios */
        DB::unprepared('
        CREATE TRIGGER decrease_likes_count 
        AFTER DELETE ON tweet_likes
        FOR EACH ROW
        BEGIN
            UPDATE users SET likes_count = likes_count - 1 
            WHERE id = NEW.following_id
        END
        ');

        DB::unprepared('
        CREATE TRIGGER decrease_retweets_count 
        AFTER DELETE ON retweets
        FOR EACH ROW
        BEGIN
            UPDATE users SET retweets_count = retweets_count - 1 
            WHERE id = NEW.following_id
        END
        ');

        DB::unprepared('
        CREATE TRIGGER decrease_commets_count 
        AFTER DELETE ON commets
        FOR EACH ROW
        BEGIN
            UPDATE users SET commets_count = commets_count - 1 
            WHERE id = NEW.following_id
        END
        ');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tweets');
    }
};
