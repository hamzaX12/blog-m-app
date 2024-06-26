<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenamePostTagToTaggablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('post_tag', function (Blueprint $table) {

            $table->dropForeign(['post_id']);
            $table->dropColumn('post_id');
        });

        // to rename a table we use 
        Schema::rename('post_tag', 'taggables');

        // we do this syntax because the forgein id use the name of the table 
        // post_tag_post_id_foreign
        Schema::table('taggables', function (Blueprint $table) {

            $table->morphs('taggable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taggables', function (Blueprint $table) {
            $table->dropMorphs('tagable');
        });

        Schema::rename('taggables', 'post_tag');
        
        Schema::disableForeignKeyConstraints();

        Schema::table('post_tag', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::enableForeignKeyConstraints();


    }
}