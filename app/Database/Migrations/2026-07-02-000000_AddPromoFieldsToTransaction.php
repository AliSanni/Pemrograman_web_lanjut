<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPromoFieldsToTransaction extends Migration
{
    public function up()
    {
        $fields = [
            'service_fee' => [
                'type' => 'DOUBLE',
                'null' => true,
                'default' => 0,
                'after' => 'diskon',
            ],
            'voucher_code' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'service_fee',
            ],
            'voucher_discount' => [
                'type' => 'DOUBLE',
                'null' => true,
                'default' => 0,
                'after' => 'voucher_code',
            ],
            'free_mouse_value' => [
                'type' => 'DOUBLE',
                'null' => true,
                'default' => 0,
                'after' => 'voucher_discount',
            ],
        ];

        $this->forge->addColumn('transaction', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('transaction', ['service_fee', 'voucher_code', 'voucher_discount', 'free_mouse_value']);
    }
}
