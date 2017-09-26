<?php

use yii\db\Schema;
use yii\db\Migration;

class m160208_091732_change_app_data_data_key_column_type extends Migration
{
    private $makes = [
        1 => 'Acura',
        2 => 'Alfa Romeo',
        3 => 'AMC',
        4 => 'Aston Martin',
        5 => 'Audi',
        6 => 'Avanti',
        7 => 'Bentley',
        8 => 'BMW',
        9 => 'Buick',
        10 => 'Cadillac',
        11 => 'Chevrolet',
        12 => 'Chrysler',
        13 => 'Daewoo',
        14 => 'Daihatsu',
        15 => 'Datsun',
        16 => 'DeLorean',
        17 => 'Dodge',
        18 => 'Eagle',
        19 => 'Ferrari',
        20 => 'FIAT',
        21 => 'Fisker',
        22 => 'Ford',
        23 => 'Freightliner',
        24 => 'Geo',
        25 => 'GMC',
        26 => 'Honda',
        27 => 'HUMMER',
        28 => 'Hyundai',
        29 => 'Infiniti',
        30 => 'Isuzu',
        31 => 'Jaguar',
        32 => 'Jeep',
        33 => 'Kia',
        34 => 'Lamborghini',
        35 => 'Lancia',
        36 => 'Land Rover',
        37 => 'Lexus',
        38 => 'Lincoln',
        39 => 'Lotus',
        40 => 'Maserati',
        41 => 'Maybach',
        42 => 'Mazda',
        43 => 'McLaren',
        44 => 'Mercedes-Benz',
        45 => 'Mercury',
        46 => 'Merkur',
        47 => 'MINI',
        48 => 'Mitsubishi',
        49 => 'Nissan',
        50 => 'Oldsmobile',
        51 => 'Peugeot',
        52 => 'Plymouth',
        53 => 'Pontiac',
        54 => 'Porsche',
        55 => 'RAM',
        56 => 'Renault',
        57 => 'Rolls-Royce',
        58 => 'Saab',
        59 => 'Saturn',
        60 => 'Scion',
        61 => 'smart',
        62 => 'SRT',
        63 => 'Sterling',
        64 => 'Subaru',
        65 => 'Suzuki',
        66 => 'Tesla',
        67 => 'Toyota',
        68 => 'Triumph',
        69 => 'Volkswagen',
        70 => 'Volvo',
        71 => 'Yugo'];



    public function up()
    {
        $this->alterColumn('app_data', 'data_key', $this->string()->notNull());
    }

    public function down()
    {
        echo "m160208_091732_change_app_data_data_key_column_type cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
