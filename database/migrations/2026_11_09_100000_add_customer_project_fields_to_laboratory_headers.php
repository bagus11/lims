<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerProjectFieldsToLaboratoryHeaders extends Migration
{
    /**
     * Permintaan QC: report LHU harus mencantumkan untuk siapa pengujian
     * dilakukan. Ketiganya opsional karena uji rutin internal tidak punya
     * customer/proyek.
     */
    public function up()
    {
        Schema::table('laboratory_headers', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->after('product_type');
            $table->string('project_name')->nullable()->after('customer_name');
            $table->string('made_for')->nullable()->after('project_name');
        });
    }

    public function down()
    {
        Schema::table('laboratory_headers', function (Blueprint $table) {
            $table->dropColumn(['customer_name', 'project_name', 'made_for']);
        });
    }
}
