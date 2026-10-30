<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('about_infos', function (Blueprint $table) {
            $table->string('pillar1_title')->nullable()->after('image');
            $table->text('pillar1_desc')->nullable()->after('pillar1_title');
            $table->string('pillar2_title')->nullable()->after('pillar1_desc');
            $table->text('pillar2_desc')->nullable()->after('pillar2_title');
            $table->string('pillar3_title')->nullable()->after('pillar2_desc');
            $table->text('pillar3_desc')->nullable()->after('pillar3_title');

            $table->string('expert_name')->nullable()->after('pillar3_desc');
            $table->string('expert_role')->nullable()->after('expert_name');
            $table->string('expert_badge')->nullable()->after('expert_role');
            $table->text('expert_credentials')->nullable()->after('expert_badge');
            $table->text('expert_bio')->nullable()->after('expert_credentials');
            $table->string('expert_image')->nullable()->after('expert_bio');
            $table->string('facebook_link')->nullable()->after('expert_image');
            $table->string('youtube_link')->nullable()->after('facebook_link');

            $table->string('offline_address')->nullable()->after('youtube_link');
            $table->string('offline_phone')->nullable()->after('offline_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('about_infos', function (Blueprint $table) {
            $table->dropColumn([
                'pillar1_title',
                'pillar1_desc',
                'pillar2_title',
                'pillar2_desc',
                'pillar3_title',
                'pillar3_desc',
                'expert_name',
                'expert_role',
                'expert_badge',
                'expert_credentials',
                'expert_bio',
                'expert_image',
                'facebook_link',
                'youtube_link',
                'offline_address',
                'offline_phone',
            ]);
        });
    }
};

