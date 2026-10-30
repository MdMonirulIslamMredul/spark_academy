<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupportChannelsToCompanySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('company_settings', 'whatsapp_number')) {
                $table->string('whatsapp_number')->nullable()->after('phone_2');
            }
            if (!Schema::hasColumn('company_settings', 'messenger_link')) {
                $table->string('messenger_link')->nullable()->after('facebook');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_settings', function (Blueprint $table) {
            if (Schema::hasColumn('company_settings', 'whatsapp_number')) {
                $table->dropColumn('whatsapp_number');
            }
            if (Schema::hasColumn('company_settings', 'messenger_link')) {
                $table->dropColumn('messenger_link');
            }
        });
    }
}

