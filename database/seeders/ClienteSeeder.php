<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ClienteSeeder extends Seeder
{
    public function run()
    {
        $clientes = [
            // Add clients data from Excel here
            [
                'nombre_fiscal' => 'MARTINEZ FRESNO, MARIA SOLEDAD',
                'nif' => '02205026Q',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 0.0,
                'codigo_sage' => 620
            ],

            [
                'nombre_fiscal' => 'GALLUD FERNANDEZ-MUÑIZ, MARIA ANGELES',
                'nif' => '05282855P',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 12
            ],

            [
                'nombre_fiscal' => 'PIEDRAFITA TREMOSA, RITA',
                'nif' => '05910266W',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 995
            ],

            [
                'nombre_fiscal' => 'GATA ALVAREZ, FELIPE',
                'nif' => '08759513D',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 0.0,
                'codigo_sage' => 46
            ],

            [
                'nombre_fiscal' => 'TORNATTI RODRIGUEZ, PABLO',
                'nif' => '09050116F',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.O.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 10
            ],

            [
                'nombre_fiscal' => 'PUERTOLAS SIMON, NURIA',
                'nif' => '43695682Y',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 58
            ],

            [
                'nombre_fiscal' => 'ARNEDILLO PARDO, JUAN MARIA',
                'nif' => '15792170W',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E,D,S,',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 273
            ],

            [
                'nombre_fiscal' => 'ROJO POLO, MARIA PALOMA',
                'nif' => '17158085Q',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'RESUMEN ANUAL',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 960
            ],

            [
                'nombre_fiscal' => 'ANSON SOLER, JOSE ANGEL',
                'nif' => '17184107W',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 232
            ],

            [
                'nombre_fiscal' => 'GRACIA PEÑAFIEL, SERGIO',
                'nif' => '17189652G',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 29
            ],

            [
                'nombre_fiscal' => 'ESCARTIN OLIVAN, ANTONIO',
                'nif' => '17711963P',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 870
            ],

            [
                'nombre_fiscal' => 'LOPEZ GARCIA, CANDELARIA',
                'nif' => '17875005A',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 24
            ],

            [
                'nombre_fiscal' => 'OPERE PEIRE, CONSUELO',
                'nif' => '17898397G',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 65
            ],

            [
                'nombre_fiscal' => 'ARILLA BERNAD, JOSE',
                'nif' => '17898402D',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 90
            ],

            [
                'nombre_fiscal' => 'GIMENEZ BUERA, ANTONIO',
                'nif' => '17952011M',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 103
            ],

            [
                'nombre_fiscal' => 'GUILLEN SERVETO, JOSEFINA',
                'nif' => '17962865A',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 218
            ],

            [
                'nombre_fiscal' => 'COSCULLUELA CLEMENTE, ROSA',
                'nif' => '17963487G',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 64
            ],

            [
                'nombre_fiscal' => 'BORRUEL CLADELLES, LUIS',
                'nif' => '17975855K',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 208
            ],

            [
                'nombre_fiscal' => 'ZANUY VISA, BLAS',
                'nif' => '17983731P',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'IVA SIMPLIFICADO',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 31
            ],

            [
                'nombre_fiscal' => 'GAMIZ ABUYE, MARIA JOSE',
                'nif' => '17984890V',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 640
            ],

            [
                'nombre_fiscal' => 'COSCULLUELA CLEMENTE, BLAS',
                'nif' => '17985965B',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 27
            ],

            [
                'nombre_fiscal' => 'ALPIN GRASA, CARLOS',
                'nif' => '17986042L',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 87
            ],

            [
                'nombre_fiscal' => 'LAITA SIN, JOSE',
                'nif' => '17986769X',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 112
            ],

            [
                'nombre_fiscal' => 'TRILLO SUELVES, ROSARIO',
                'nif' => '17993511J',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 667
            ],

            [
                'nombre_fiscal' => 'ABIZANDA SANROMAN, JESUS',
                'nif' => '17994034F',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 0.0,
                'codigo_sage' => 294
            ],

            [
                'nombre_fiscal' => 'CARPI ARNAL, BIENVENIDO',
                'nif' => '17995445S',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 633
            ],

            [
                'nombre_fiscal' => 'BUERA ALQUEZAR, AMADO JOSE',
                'nif' => '17996799N',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 234
            ],

            [
                'nombre_fiscal' => 'MUR CAMPO, INES VICTORIA',
                'nif' => '17996810T',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 418
            ],

            [
                'nombre_fiscal' => 'BLECUA PERIZ, ANTONIO',
                'nif' => '17997150H',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 164
            ],

            [
                'nombre_fiscal' => 'CASTRO MUR, JOAQUIN',
                'nif' => '17999396X',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 165
            ],

            [
                'nombre_fiscal' => 'NAYA GRACIA, YOLANDA',
                'nif' => '18003971P',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 42
            ],

            [
                'nombre_fiscal' => 'FENES FACERIAS, SANTIAGO',
                'nif' => '18004308T',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 624
            ],

            [
                'nombre_fiscal' => 'GARCES LASUS, NEMESIO JOSE',
                'nif' => '18005285B',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 89
            ],

            [
                'nombre_fiscal' => 'TORNES PALLARUELO, JUAN JOSE',
                'nif' => '18006592F',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 0.0,
                'codigo_sage' => 81
            ],

            [
                'nombre_fiscal' => 'JORDAN COSCULLUELA, MARIA JESUS',
                'nif' => '18006885R',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 992
            ],

            [
                'nombre_fiscal' => 'BESCOS, S.C.',
                'nif' => 'J22383012',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 380
            ],

            [
                'nombre_fiscal' => 'TORRES GARCES, JOSE ANTONIO',
                'nif' => '18007006F',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'IVA SIMPLIFICADO',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 597
            ],

            [
                'nombre_fiscal' => 'RAMOS JAIME, MIGUEL',
                'nif' => '18007020K',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'IVA SIMPLIFICADO',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 71
            ],

            [
                'nombre_fiscal' => 'CERA RASO, MARIA JOSE',
                'nif' => '18007434K',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 989
            ],

            [
                'nombre_fiscal' => 'MIRANDA OLIVAR MARIA PILAR',
                'nif' => '18007476V',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 262
            ],

            [
                'nombre_fiscal' => 'SANCHEZ CABRERO, MARIANO JOSE',
                'nif' => '18008166V',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 973
            ],

            [
                'nombre_fiscal' => 'PUERTOLAS OPERE, MARIA PILAR',
                'nif' => '18008425T',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 985
            ],

            [
                'nombre_fiscal' => 'JORDAN COSCULLUELA, JOSE JULIAN',
                'nif' => '18010285C',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 990
            ],

            [
                'nombre_fiscal' => 'VILLACAMPA AYERBE, MARIA JOSE',
                'nif' => '18010928L',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 217
            ],

            [
                'nombre_fiscal' => 'ALMAZOR LLENA, MIGUEL A.',
                'nif' => '18012301N',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'IVA SIMPLIFICADO',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 106
            ],

            [
                'nombre_fiscal' => 'BONED FUERTES, GERMAN TOMAS',
                'nif' => '18018805F',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'IVA SIMPLIFICADO',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 956
            ],

            [
                'nombre_fiscal' => 'LANAU PALACIN, RICARDO',
                'nif' => '18021962J',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 119
            ],

            [
                'nombre_fiscal' => 'OLIVERA FUMANAL, JOSE',
                'nif' => '18022337C',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'IVA SIMPLIFICADO',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 154
            ],

            [
                'nombre_fiscal' => 'SIERRA PLANA, JOSE MANUEL',
                'nif' => '18024051D',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.O.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 147
            ],

            [
                'nombre_fiscal' => 'PUEYO BOIX, JOSE RAMON',
                'nif' => '18024093M',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 391
            ],

            [
                'nombre_fiscal' => 'GONZALEZ ANIDO, LUIS',
                'nif' => '18024243V',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 465
            ],

            [
                'nombre_fiscal' => 'VIGIL FERNÁNDEZ, FRANCISCO ',
                'nif' => '18024470Z',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 412
            ],

            [
                'nombre_fiscal' => 'MARTIN ATENCIA, ANTONIO',
                'nif' => '18025540A',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 2
            ],

            [
                'nombre_fiscal' => 'ALAS  GUILLEN, JESUS IGNA',
                'nif' => '18025572N',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 435
            ],

            [
                'nombre_fiscal' => 'RIU JOVE, RAMON JOSE ANTONIO',
                'nif' => '18026605X',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 0.0,
                'codigo_sage' => 967
            ],

            [
                'nombre_fiscal' => 'OLIVERA PUYUELO, RAMON',
                'nif' => '18027212L',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'IVA SIMPLIFICADO',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 68
            ],

            [
                'nombre_fiscal' => 'VIDAL MARCO, MARIA ISABEL',
                'nif' => '18028208A',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 151
            ],

            [
                'nombre_fiscal' => 'CANUTO PUYUELO, FRANCISCO J.',
                'nif' => '18028226K',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'IVA SIMPLIFICADO',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 258
            ],

            [
                'nombre_fiscal' => 'VIÑUALES SANZ, JUAN LUIS',
                'nif' => '18028412T',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'IVA SIMPLIFICADO',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 56
            ],

            [
                'nombre_fiscal' => 'VIÑUALES SANZ, FERNANDO',
                'nif' => '18029311W',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 530
            ],

            [
                'nombre_fiscal' => 'ORTIZ SANTALIESTRA,JESUS',
                'nif' => '18032126B',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 0.0,
                'codigo_sage' => 126
            ],

            [
                'nombre_fiscal' => 'ZANUY LLEVOT, JOSE ANTONIO',
                'nif' => '18033421H',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'IVA SIMPLIFICADO',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 30
            ],

            [
                'nombre_fiscal' => 'IZQUIERDO CONTE, SERGIO',
                'nif' => '18034869V',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 188
            ],

            [
                'nombre_fiscal' => 'GIRON CEBOLLERO, SERGIO',
                'nif' => '18036280W',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 272
            ],

            [
                'nombre_fiscal' => 'CLEMENTE ARANCON, MIRIAN',
                'nif' => '18037469H',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 80
            ],

            [
                'nombre_fiscal' => 'CAÑELLAS NAYA, LOURDES',
                'nif' => '18037920D',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 182
            ],

            [
                'nombre_fiscal' => 'PARDINA GRANADO, JOSE MARIA',
                'nif' => '18038249Q',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 593
            ],

            [
                'nombre_fiscal' => 'LISA FIESTAS, PABLO',
                'nif' => '18038646E',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 974
            ],

            [
                'nombre_fiscal' => 'MORLANS FERNANDEZ, CARLOS',
                'nif' => '18038788A',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 11
            ],

            [
                'nombre_fiscal' => 'ALCARAZ VIGO, ALFREDO',
                'nif' => '18041982T',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 74
            ],

            [
                'nombre_fiscal' => 'HUERTAS MORENO, ANGEL IVAN',
                'nif' => '18042239G',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'IVA SIMPLIFICADO',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 233
            ],

            [
                'nombre_fiscal' => 'FIGUERA MUR, JORGE',
                'nif' => '18043219H',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 410
            ],

            [
                'nombre_fiscal' => 'PUEO VISPE, JOSE LUIS',
                'nif' => '18043484F',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'IVA SIMPLIFICADO',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 48
            ],

            [
                'nombre_fiscal' => 'ANSON TORTOSA, JESUS ALBERTO',
                'nif' => '18043723Q',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 542
            ],

            [
                'nombre_fiscal' => 'PEREZ TRELL, DAVID',
                'nif' => '18044585G',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 95
            ],

            [
                'nombre_fiscal' => 'SALAMERO PUEYO, LUCIA',
                'nif' => '18044661B',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 944
            ],

            [
                'nombre_fiscal' => 'GRASA RODRIGO, LUCIA',
                'nif' => '18044822B',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 219
            ],

            [
                'nombre_fiscal' => 'OLIVERA GUILLAMON, JOSE',
                'nif' => '18046825J',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 996
            ],

            [
                'nombre_fiscal' => 'CUELLO ALONSO, JOSE JAVIER',
                'nif' => '18047559B',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 22
            ],

            [
                'nombre_fiscal' => 'ESCARTIN COSCOJUELA, ALBERTO',
                'nif' => '18047947P',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'IVA SIMPLIFICADO',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 69
            ],

            [
                'nombre_fiscal' => 'BALLABRIGA SOLANO, CRISTIAN',
                'nif' => '18049570K',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA ',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 167
            ],

            [
                'nombre_fiscal' => 'CARPI MOMBIOLA, DANIEL',
                'nif' => '18052804N',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 39
            ],

            [
                'nombre_fiscal' => 'PERELLA ZANUY, BASILIO',
                'nif' => '18053133L',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 266
            ],

            [
                'nombre_fiscal' => 'PALACIO SOPENA, JORGE',
                'nif' => '18056433F',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 13
            ],

            [
                'nombre_fiscal' => 'RAMOS JAIME, MIGUEL',
                'nif' => '18059975F',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'IVA SIMPLIFICADO',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 101
            ],

            [
                'nombre_fiscal' => 'OLIVAN LOPEZ, NATALIA',
                'nif' => '18060183P',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 136
            ],

            [
                'nombre_fiscal' => 'NAVAL LOPEZ, ANA MARIA',
                'nif' => '18060269W',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 0.0,
                'codigo_sage' => 159
            ],

            [
                'nombre_fiscal' => 'GRASA SESMA, RUBEN',
                'nif' => '18060754G',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 40
            ],

            [
                'nombre_fiscal' => 'BOSQUE LERIS, IVAN MARTIN',
                'nif' => '18061425P',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 428
            ],

            [
                'nombre_fiscal' => 'PARDO CIPRÉS, JAVIER',
                'nif' => '18065550Q',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 976
            ],

            [
                'nombre_fiscal' => 'PARDO CIPRES, CRISTINA',
                'nif' => '18065551V',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 0.0,
                'codigo_sage' => 187
            ],

            [
                'nombre_fiscal' => 'LAITA MOMBIOLA, ALEJANDRO',
                'nif' => '18068639T',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 963
            ],

            [
                'nombre_fiscal' => 'ROMANO TOVO, MARTA ALICIA',
                'nif' => '18101889S',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 393
            ],

            [
                'nombre_fiscal' => 'MCHIOUER, YASSINE',
                'nif' => '18102980W',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 983
            ],

            [
                'nombre_fiscal' => 'BERNAD SOLANS, LEONOR',
                'nif' => '18103166G',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 471
            ],

            [
                'nombre_fiscal' => 'GARUZ ESPOT, JOSE',
                'nif' => '18122037S',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 275
            ],

            [
                'nombre_fiscal' => 'COMES GARCIA LOLA',
                'nif' => '20829367S',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 99
            ],

            [
                'nombre_fiscal' => 'BRIONES TUDELA, JUAN FERMIN',
                'nif' => '24337203Y',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 415
            ],

            [
                'nombre_fiscal' => 'PELET LAGUNA, ANTONIO',
                'nif' => '25457173Z',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 50
            ],

            [
                'nombre_fiscal' => 'ORTIZ ARACIL, JUAN FRANCISCO',
                'nif' => '25899909T',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 631
            ],

            [
                'nombre_fiscal' => 'GRACIA DOMINGUEZ, LORENA',
                'nif' => '29132691V',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 292
            ],

            [
                'nombre_fiscal' => 'FUENTES LEAL, SOLEDAD',
                'nif' => '29168534A',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 443
            ],

            [
                'nombre_fiscal' => 'NASARRE AZARA, BEGOÑA',
                'nif' => '33891184V',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 28
            ],

            [
                'nombre_fiscal' => 'FERNANDEZ VALUGO, MARIA JOSE',
                'nif' => '35115274R',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 20
            ],

            [
                'nombre_fiscal' => 'PLANES ARIÑO, ANTONIO',
                'nif' => '35152602T',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 401
            ],

            [
                'nombre_fiscal' => 'PARDO ALVAREZ, MANUEL',
                'nif' => '37791676P',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 468
            ],

            [
                'nombre_fiscal' => 'FERNANDEZ CABALLERO, MARIA JOAQUINA',
                'nif' => '38791906Z',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 21
            ],

            [
                'nombre_fiscal' => 'MOLINA MEDINA, FRANCISCA',
                'nif' => '38815198F',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 664
            ],

            [
                'nombre_fiscal' => 'ESCARTIN VERDAGUER, ANA MARIA',
                'nif' => '39165726S',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 315
            ],

            [
                'nombre_fiscal' => 'ALLUE LATRE, JULIA',
                'nif' => '40613086P',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 948
            ],

            [
                'nombre_fiscal' => 'MACIA BONET, JAIME',
                'nif' => '40848575T',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 968
            ],

            [
                'nombre_fiscal' => 'DOMENECH RATES, RAMON',
                'nif' => '40881046H',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 451
            ],

            [
                'nombre_fiscal' => 'ALQUEZAR MAGALLON, MARGARITA',
                'nif' => '41080961V',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 298
            ],

            [
                'nombre_fiscal' => 'PUERTOLAS SIMON, LAURA',
                'nif' => '14270287Y',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 221
            ],

            [
                'nombre_fiscal' => 'ALEGRE CANUDAS, RUDESINDO',
                'nif' => '43717839Z',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 969
            ],

            [
                'nombre_fiscal' => 'CIPRES ORTEGA, MAITE TERESA',
                'nif' => '46542474L',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 431
            ],

            [
                'nombre_fiscal' => 'CARRASCO PLAZA, MARIA DEL MAR',
                'nif' => '46594173Z',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 120
            ],

            [
                'nombre_fiscal' => 'PLAZAS GARCIA, DANIEL',
                'nif' => '46603222R',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 96
            ],

            [
                'nombre_fiscal' => 'FERNANDEZ OLIVERA, SILVIA',
                'nif' => '46964005Y',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 160
            ],

            [
                'nombre_fiscal' => 'ZAMORA FERNANDEZ, DAVID',
                'nif' => '47103853Z',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 4
            ],

            [
                'nombre_fiscal' => 'RODRIGUEZ CAMPOVERDE, DAVID ANTONIO',
                'nif' => '49757792Y',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 971
            ],

            [
                'nombre_fiscal' => 'MOYA MARTINEZ, JOAN CARLES',
                'nif' => '52308859M',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 60
            ],

            [
                'nombre_fiscal' => 'DOMINGUEZ PRIETO, CARLOS',
                'nif' => '71499168B',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 15
            ],

            [
                'nombre_fiscal' => 'SALVA PUYUELO, ANTONIO ALEXANDER',
                'nif' => '72971005D',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'IVA SIMPLIFICADO',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 196
            ],

            [
                'nombre_fiscal' => 'LISA CUELLO, PABLO',
                'nif' => '73169532T',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 63
            ],

            [
                'nombre_fiscal' => 'BARDAJI SESE, MARIA ISABEL',
                'nif' => '73170840C',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'IVA SIMPLIFICADO',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 9
            ],

            [
                'nombre_fiscal' => 'BRAVO LIS, JOSE MARIA',
                'nif' => '73177493A',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 17
            ],

            [
                'nombre_fiscal' => 'BROTO BALLARIN, JUAN ANTONIO',
                'nif' => '73179603C',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 73
            ],

            [
                'nombre_fiscal' => 'RUBIELLA DOMPER, RAQUEL',
                'nif' => '73184494N',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 466
            ],

            [
                'nombre_fiscal' => 'LECINA LANAU, ISMAEL',
                'nif' => '73185670S',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 19
            ],

            [
                'nombre_fiscal' => 'SABES SOLANO, ENRIQUE CARMELO',
                'nif' => '73186001R',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 0.0,
                'codigo_sage' => 594
            ],

            [
                'nombre_fiscal' => 'BALLARIN CALVO, M. CONSUELO',
                'nif' => '73187078C',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 0.0,
                'codigo_sage' => 174
            ],

            [
                'nombre_fiscal' => 'HUERTAS PIESDEHIERRO, FRANCISCO',
                'nif' => '73187523M',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'IVA SIMPLIFICADO',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 259
            ],

            [
                'nombre_fiscal' => 'LECINA LANAU, JOSE ANTONIO',
                'nif' => '73187988X',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 7
            ],

            [
                'nombre_fiscal' => 'CLAVERO TORRES, MARI CARMEN',
                'nif' => '73189522A',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 176
            ],

            [
                'nombre_fiscal' => 'ALOS PUEYO, LUIS ANTONIO',
                'nif' => '73189850D',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 85
            ],

            [
                'nombre_fiscal' => 'PUERTOLAS OPERE CONSUELO IRENE',
                'nif' => '73190088V',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 984
            ],

            [
                'nombre_fiscal' => 'TORNIL MATA, ANTONIA',
                'nif' => '73190197B',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.O.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 82
            ],

            [
                'nombre_fiscal' => 'GUETAS AGUILAR, ALFREDO',
                'nif' => '73190275C',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'REGIMEN SIMPLIFICADO',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 152
            ],

            [
                'nombre_fiscal' => 'MOMBIOLA ARNAL, MARIA ROSARIO',
                'nif' => '73191097Z',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 124
            ],

            [
                'nombre_fiscal' => 'SUBIRA CALVERA, FRANCISCO JAVIER',
                'nif' => '73191721V',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 462
            ],

            [
                'nombre_fiscal' => 'JORDAN COSCULLUELA, JOSE LUIS',
                'nif' => '73192092C',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 991
            ],

            [
                'nombre_fiscal' => 'ARROYO AVILA, ANGELA',
                'nif' => '73193599D',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 77
            ],

            [
                'nombre_fiscal' => 'PLANES MUR LUIS',
                'nif' => '73194099A',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 33
            ],

            [
                'nombre_fiscal' => 'CEBOLLERO SALINAS, BEGOÑA',
                'nif' => '73194760C',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 91
            ],

            [
                'nombre_fiscal' => 'GARUZ LIMINIANA, JOSE RAMON',
                'nif' => '73194780V',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 454
            ],

            [
                'nombre_fiscal' => 'NOVELLON CASAS, ANA ISABEL',
                'nif' => '73195207F',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 0.0,
                'codigo_sage' => 163
            ],

            [
                'nombre_fiscal' => 'MARTINEZ COSCOJUELA, JUAN CARLOS',
                'nif' => '73195890T',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'IVA SIMPLIFICADO',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 198
            ],

            [
                'nombre_fiscal' => 'LLORET GOMEZ, ANA MARIA',
                'nif' => '73196886F',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 615
            ],

            [
                'nombre_fiscal' => 'HERRERO GIL, LOURDES',
                'nif' => '73197679H',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 632
            ],

            [
                'nombre_fiscal' => 'PLANA ESPAÑOL, JOSE GABRIEL',
                'nif' => '73198768A',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 524
            ],

            [
                'nombre_fiscal' => 'RUFAS OLIVAN, JOSE MARIA',
                'nif' => '73199054J',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 172
            ],

            [
                'nombre_fiscal' => 'JIMENEZ CANO, PAMELA',
                'nif' => '73199267L',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 98
            ],

            [
                'nombre_fiscal' => 'DOMINGUEZ GARCIA, M.CARMEN',
                'nif' => '73199984T',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'IVA SIMPLIFICADO',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 523
            ],

            [
                'nombre_fiscal' => 'BARBANOJ SALINAS, MARIA JOSE',
                'nif' => '73201710R',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 79
            ],

            [
                'nombre_fiscal' => 'GATA CASTRO, DAVID',
                'nif' => '73204099K',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 75
            ],

            [
                'nombre_fiscal' => 'JARA PADIAL, PATRICIA',
                'nif' => '73204221M',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 57
            ],

            [
                'nombre_fiscal' => 'JARA PADIAL, VICTOR',
                'nif' => '73204222Y',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.O.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 452
            ],

            [
                'nombre_fiscal' => 'PUEYO COSIALLS, ADRIAN',
                'nif' => '73204424R',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'IVA SIMPLIFICADO',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 970
            ],

            [
                'nombre_fiscal' => 'FERNANDEZ RODRIGUEZ, ADRIAN',
                'nif' => '73205918T',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 651
            ],

            [
                'nombre_fiscal' => 'COBOS PERA, ANA BELEN',
                'nif' => '73206123K',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 1085
            ],

            [
                'nombre_fiscal' => 'SIMONI NAYA, MARIA',
                'nif' => '73207553W',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 108
            ],

            [
                'nombre_fiscal' => 'ARNAL CALVO, DAVID ALEJANDRO',
                'nif' => '73207982V',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 246
            ],

            [
                'nombre_fiscal' => 'SEGURA SANTOLARIA, DANIEL',
                'nif' => '73208460N',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 965
            ],

            [
                'nombre_fiscal' => 'CAMBRA PERALLON, FRANCISCO JOSE',
                'nif' => '73208759N',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'RENTA',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 951
            ],

            [
                'nombre_fiscal' => 'GARCES BARON, GUILLERMO',
                'nif' => '73209437T',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 993
            ],

            [
                'nombre_fiscal' => 'BARRIERAS FERNÁNDEZ, MARTA',
                'nif' => '73209502L',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 994
            ],

            [
                'nombre_fiscal' => 'CARRERAS CERA, CRISTINA',
                'nif' => '73209570H',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 666
            ],

            [
                'nombre_fiscal' => 'GARCES CABESTRE, SERGIO',
                'nif' => '73210081T',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 975
            ],

            [
                'nombre_fiscal' => 'PUYUELO VARGAS, JAVIER',
                'nif' => '73210840T',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 982
            ],

            [
                'nombre_fiscal' => 'SANCHEZ PELEGRIN, JAVIER',
                'nif' => '73211216P',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 953
            ],

            [
                'nombre_fiscal' => 'SATUE ARMENGOL, ALFONSO',
                'nif' => '73211527C',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 168
            ],

            [
                'nombre_fiscal' => 'TORNES TORNIL, ADRIAN',
                'nif' => '73211755H',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 981
            ],

            [
                'nombre_fiscal' => 'DOMPER BUIL, VICTOR',
                'nif' => '73212244R',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 456
            ],

            [
                'nombre_fiscal' => 'BAILO CUCALON, LUNA BELEN',
                'nif' => '73212386M',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 988
            ],

            [
                'nombre_fiscal' => 'MOLINA PUERTOLAS, SILVIA',
                'nif' => '73212758D',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 61
            ],

            [
                'nombre_fiscal' => 'BLAZQUEZ GRACIA, MARIA',
                'nif' => '73212984M',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 23
            ],

            [
                'nombre_fiscal' => 'MARTIN CARRERAS, ANDRES',
                'nif' => '73213058X',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'IVA SIMPLIFICADO',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 117
            ],

            [
                'nombre_fiscal' => 'PUEYO BLAN, ANA',
                'nif' => '73219531C',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 115
            ],

            [
                'nombre_fiscal' => 'MUÑOZ ROMERO, ROCIO',
                'nif' => '74882618T',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 34
            ],

            [
                'nombre_fiscal' => 'GONZALEZ LOPEZ, ANA DOLORES',
                'nif' => '77103369D',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 432
            ],

            [
                'nombre_fiscal' => 'FONTARNAU MARTINEZ, JAIME',
                'nif' => '77270352N',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 76
            ],

            [
                'nombre_fiscal' => 'RIBALECTRICA, S.A.',
                'nif' => 'A22042089',
                'tipo_cliente' => 'SOCIDAD ANONIMA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'SIN ACTIVIDAD',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 537
            ],

            [
                'nombre_fiscal' => 'PROCESSUS ACTIVA S.L.U.',
                'nif' => 'B06983696',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 107
            ],

            [
                'nombre_fiscal' => 'MADERAS PLANES, S.L.',
                'nif' => 'B22128235',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD/CLIENTE',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 374
            ],

            [
                'nombre_fiscal' => 'ERNESTO CANCER, S.L.',
                'nif' => 'B22137988',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD/CLIENTE',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 180
            ],

            [
                'nombre_fiscal' => 'HOSTELERIA OLIVERA FUMANAL',
                'nif' => 'B22153050',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'SIN ACTIVIDAD',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 142
            ],

            [
                'nombre_fiscal' => 'BLAS COSCULLUELA, S.L.',
                'nif' => 'B22154850',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD/CLIENTE',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 25
            ],

            [
                'nombre_fiscal' => 'FRUTOS SECOS JORDAN S.L.',
                'nif' => 'B22154868',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD/CLIENTE',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 625
            ],

            [
                'nombre_fiscal' => 'JUAN OLIVAN, S.L.',
                'nif' => 'B22162226',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 0.0,
                'codigo_sage' => 83
            ],

            [
                'nombre_fiscal' => 'FRUTAS JOSE CALVO, S.L. UNIP.',
                'nif' => 'B22165567',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 4.0,
                'codigo_sage' => 177
            ],

            [
                'nombre_fiscal' => 'CARPINTERIA BROTO-SERRATE,S.L.',
                'nif' => 'B22178347',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 4.0,
                'codigo_sage' => 78
            ],

            [
                'nombre_fiscal' => 'LAMBARENE S.L.',
                'nif' => 'B22187306',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD/CLIENTE',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 458
            ],

            [
                'nombre_fiscal' => 'CONSTRUCCIONES Y OBRAS ALCARAZ, S.L',
                'nif' => 'B22204317',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 173
            ],

            [
                'nombre_fiscal' => 'TAGRI-FONZ, S.L.',
                'nif' => 'B22205157',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 0.0,
                'codigo_sage' => 102
            ],

            [
                'nombre_fiscal' => 'VITISERCON S.L.',
                'nif' => 'B22205405',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 4.0,
                'codigo_sage' => 261
            ],

            [
                'nombre_fiscal' => 'CODORNICES NASARRE, S.L., EN LIQUIDACION',
                'nif' => 'B22206254',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 459
            ],

            [
                'nombre_fiscal' => 'LOGISTICA Y ESTRATEGIAS, S.L.U.',
                'nif' => 'B22208037',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 181
            ],

            [
                'nombre_fiscal' => 'ALAS Y VIGIL ASESORES, S.L.',
                'nif' => 'B22211114',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 4.0,
                'codigo_sage' => 1
            ],

            [
                'nombre_fiscal' => 'EXPLOTACIONES HERMANOS PUEO BUIL, S.L.',
                'nif' => 'B22211890',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 44
            ],

            [
                'nombre_fiscal' => 'LORDAN ENCUENTRA, S.L.L.',
                'nif' => 'B22216774',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'SIN ACTIVIDAD',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 86
            ],

            [
                'nombre_fiscal' => 'CONSTRUCCIONES GIMENEZ-',
                'nif' => 'B22230452',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'SIN ACTIVIDAD',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 104
            ],

            [
                'nombre_fiscal' => 'SALLAN HIDRAULICA, S.L.',
                'nif' => 'B22231229',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 105
            ],

            [
                'nombre_fiscal' => 'EL REGAL ESTADA, S.L.',
                'nif' => 'B22235691',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 110
            ],

            [
                'nombre_fiscal' => 'PLANES ASESORIA Y SOLUC.INFORM',
                'nif' => 'B22236541',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD/CLIENTE',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 118
            ],

            [
                'nombre_fiscal' => 'CARNES DE ALGAYON, S.L.',
                'nif' => 'B22241145',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'SIN ACTIVIDAD',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 123
            ],

            [
                'nombre_fiscal' => 'EL TILO VERDE, S.L.',
                'nif' => 'B22244305',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 4.0,
                'codigo_sage' => 129
            ],

            [
                'nombre_fiscal' => 'EXCAVACIONES PUEYO-BOIX, S.L.',
                'nif' => 'B22244313',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 128
            ],

            [
                'nombre_fiscal' => 'BARBASTRO COURIER, S.L.',
                'nif' => 'B22252530',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 193
            ],

            [
                'nombre_fiscal' => 'DOALDA, S.L. UNIPERSONAL',
                'nif' => 'B22258412',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'SIN ACTIVIDAD',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 207
            ],

            [
                'nombre_fiscal' => 'GUNITAS DEL PIRINEO, SL ',
                'nif' => 'B22258529',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 4.0,
                'codigo_sage' => 210
            ],

            [
                'nombre_fiscal' => 'INVERSIONES DIXALU, S.L.UNIP.',
                'nif' => 'B22258735',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 0.0,
                'codigo_sage' => 293
            ],

            [
                'nombre_fiscal' => 'SERVIHOGAR GAVARI, S.L.',
                'nif' => 'B22259154',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 4.0,
                'codigo_sage' => 206
            ],

            [
                'nombre_fiscal' => 'CONSTRUCCIONES CONS-NERIN S.L. UNIP.',
                'nif' => 'B22259162',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 205
            ],

            [
                'nombre_fiscal' => 'AGROMARS INGENIA, S.L.UNIP.',
                'nif' => 'B22269666',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 467
            ],

            [
                'nombre_fiscal' => 'SERVICIOS AGRICOLAS CASTRO-TORNIL, S.L.',
                'nif' => 'B22271084',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 228
            ],

            [
                'nombre_fiscal' => 'BESCO ARTESANOS, S.L.',
                'nif' => 'B22275333',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 4.0,
                'codigo_sage' => 93
            ],

            [
                'nombre_fiscal' => 'EXPLOTACIONES OPTICAS BARBASTRENSES,S.L.P.',
                'nif' => 'B22275614',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 4.0,
                'codigo_sage' => 326
            ],

            [
                'nombre_fiscal' => 'MARLAS INVERDECO S.L.',
                'nif' => 'B22279566',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'SIN ACTIVIDAD',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 250
            ],

            [
                'nombre_fiscal' => 'FERMADES REDES TELEFONICAS, S.L.',
                'nif' => 'B22280572',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 0.0,
                'codigo_sage' => 245
            ],

            [
                'nombre_fiscal' => 'BODEGAS IMPAR S.L.',
                'nif' => 'B22280838',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'SIN ACTIVIDAD',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 251
            ],

            [
                'nombre_fiscal' => 'ENERGIAS LIMPIAS DEL PIRINEO S.L.',
                'nif' => 'B22297063',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 279
            ],

            [
                'nombre_fiscal' => 'MORLANS FER, S.L.UNIP',
                'nif' => 'B22297790',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD/CLIENTE',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 977
            ],

            [
                'nombre_fiscal' => 'KALIGRAFIA SERV.DISEÑO Y ROTULACION',
                'nif' => 'B22297949',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 4.0,
                'codigo_sage' => 284
            ],

            [
                'nombre_fiscal' => 'INVER ESMACO S.L.',
                'nif' => 'B22308738',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 289
            ],

            [
                'nombre_fiscal' => 'OSCATRANS LOGISTICA S.L.U',
                'nif' => 'B22308951',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 290
            ],

            [
                'nombre_fiscal' => 'INTERMEDIACION E INVERSIONES PIRINEOS, S.L.U.',
                'nif' => 'B22310767',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 6
            ],

            [
                'nombre_fiscal' => 'EOLICA SOLAR PIRINEOS, S.L.',
                'nif' => 'B22316236',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 16
            ],

            [
                'nombre_fiscal' => 'INGENIA ELECTRICIDAD, S.L.',
                'nif' => 'B22322861',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 49
            ],

            [
                'nombre_fiscal' => 'LIMPIEZAS INDUSTRIALES DEL SOMONTANO, S.L.',
                'nif' => 'B22322879',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 4.0,
                'codigo_sage' => 137
            ],

            [
                'nombre_fiscal' => 'SALMO CINCA, S.L.U.',
                'nif' => 'B22323661',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'SIN ACTIVIDAD',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 84
            ],

            [
                'nombre_fiscal' => 'INVERSA ENERGIAS, S.L.',
                'nif' => 'B22326631',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'SIN ACTIVIDAD',
                'clasificacion' => NULL,
                'puntaje' => 0.0,
                'codigo_sage' => 127
            ],

            [
                'nombre_fiscal' => 'INGENIA PROYECTOS AVANZADOS, S.L.UNIP.',
                'nif' => 'B22326649',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 134
            ],

            [
                'nombre_fiscal' => 'PROYECTO ABIEGO, S.L.',
                'nif' => 'B22333595',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 216
            ],

            [
                'nombre_fiscal' => 'SOMONSERVI, S.L.',
                'nif' => 'B22344485',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 94
            ],

            [
                'nombre_fiscal' => 'DAPREXA SERVICIOS A DOMICILIO 2009,',
                'nif' => 'B22349559',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 604
            ],

            [
                'nombre_fiscal' => 'ADOBE CONSTRUCCION INTEGRAL, S.L.',
                'nif' => 'B22351092',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 613
            ],

            [
                'nombre_fiscal' => 'FRUTAS VILLA PEPITA, S.L.',
                'nif' => 'B22352181',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD/CLIENTE',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 619
            ],

            [
                'nombre_fiscal' => 'COMPAÑIA DEL VERO CDA 2010,',
                'nif' => 'B22354922',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'SIN ACTIVIDAD',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 629
            ],

            [
                'nombre_fiscal' => 'ESPACIO PURPURA S.L. UNIP.',
                'nif' => 'B22359954',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 622
            ],

            [
                'nombre_fiscal' => 'VIÑEDOS DE HOZ, S.L. EN LIQIDACION',
                'nif' => 'B22360168',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 656
            ],

            [
                'nombre_fiscal' => 'COMERCIAL IBER SOMONTANO, S.L.',
                'nif' => 'B22363501',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 350
            ],

            [
                'nombre_fiscal' => 'MPIRINEOS MOTOC.RECAMB.Y ACCES',
                'nif' => 'B22369383',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 660
            ],

            [
                'nombre_fiscal' => 'BODEGAS Y VIÑEDOS ALMAZOR S.L.',
                'nif' => 'B22369904',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 662
            ],

            [
                'nombre_fiscal' => 'EUROHAIL,S.L.',
                'nif' => 'B22386122',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 395
            ],

            [
                'nombre_fiscal' => 'CARLAU Y FELYMA, SL',
                'nif' => 'B22387732',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'SIN ACTIVIDAD',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 398
            ],

            [
                'nombre_fiscal' => 'GUARA AVENTURA S.L.',
                'nif' => 'B22388227',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 4.0,
                'codigo_sage' => 411
            ],

            [
                'nombre_fiscal' => 'INVERSIONES INVIJOMA S.L.',
                'nif' => 'B22389183',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'SIN ACTIVIDAD',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 408
            ],

            [
                'nombre_fiscal' => 'TIERRAS Y LIFARAS, S.L.',
                'nif' => 'B22389464',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 409
            ],

            [
                'nombre_fiscal' => 'SOLDECRUZ RESTAURACION, S.L.',
                'nif' => 'B22393110',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 448
            ],

            [
                'nombre_fiscal' => 'TURISMO RURAL AL BARRANCO, SLU',
                'nif' => 'B22405104',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'SIN ACTIVIDAD',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 1000
            ],

            [
                'nombre_fiscal' => 'ALAZEZ INFORMES Y PERITACIONES S.L.',
                'nif' => 'B22407639',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 945
            ],

            [
                'nombre_fiscal' => 'LYBO REDES TELECOM, S.L.U.',
                'nif' => 'B22410161',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 952
            ],

            [
                'nombre_fiscal' => 'INVERSIONES PLANES MUR, S.L.',
                'nif' => 'B22410971',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 955
            ],

            [
                'nombre_fiscal' => 'INSTALACIONES E INVERSIONES DEL ALTO ARAGON,.',
                'nif' => 'B22414619',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 986
            ],

            [
                'nombre_fiscal' => 'HOSTPIRINEO, S.L.',
                'nif' => 'B22418511',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 37
            ],

            [
                'nombre_fiscal' => 'GANADERA VERDE, S.C.',
                'nif' => 'J22423909',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 54
            ],

            [
                'nombre_fiscal' => '2018 INSTALACIONES BCL, S.L.',
                'nif' => 'B22423420',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 4.0,
                'codigo_sage' => 41
            ],

            [
                'nombre_fiscal' => 'CLUB FITNESS BARBASTRO, S.L.',
                'nif' => 'B22424568',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 4.0,
                'codigo_sage' => 53
            ],

            [
                'nombre_fiscal' => 'LA NUEVA DEL MONTE GANADERA S.L.',
                'nif' => 'B22424832',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 55
            ],

            [
                'nombre_fiscal' => 'MHS2015 MENSAJERIA S.L.U.',
                'nif' => 'B22428759',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 70
            ],

            [
                'nombre_fiscal' => 'DREAM SMILE DENTAL SERVICE SLU',
                'nif' => 'B22430144',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 113
            ],

            [
                'nombre_fiscal' => 'OBRAS Y REFORMAS SOMONTANO 2000 S.L',
                'nif' => 'B22431258',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 66
            ],

            [
                'nombre_fiscal' => 'BENASQUE STAY, S.L.',
                'nif' => 'B22432199',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 92
            ],

            [
                'nombre_fiscal' => 'INSTITUTO TABUCHI S.L.',
                'nif' => 'B50742485',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 460
            ],

            [
                'nombre_fiscal' => 'DEPURACION, MANTENIMIENTO Y SERV. AUXIL. SL',
                'nif' => 'B62315320',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 4.0,
                'codigo_sage' => 280
            ],

            [
                'nombre_fiscal' => 'VERTIGO LAMPISTES S.L.',
                'nif' => 'B62624010',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'SIN ACTIVIDAD',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 254
            ],

            [
                'nombre_fiscal' => 'INICIATIVAS SOLARES EL POZO SL',
                'nif' => 'B85189736',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 595
            ],

            [
                'nombre_fiscal' => 'ESTILISTAS SF, S.L.',
                'nif' => 'B96247093',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'SIN ACTIVIDAD',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 190
            ],

            [
                'nombre_fiscal' => 'CASA LANAU, C.B.',
                'nif' => 'E05441977',
                'tipo_cliente' => 'COMUNIDAD DE BIENES',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 175
            ],

            [
                'nombre_fiscal' => 'CARLOS Y JAIME MORLANS, C.B.',
                'nif' => 'E22297808',
                'tipo_cliente' => 'COMUNIDAD DE BIENES',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 978
            ],

            [
                'nombre_fiscal' => 'LARRES ENERGIA SOLAR C.B',
                'nif' => 'E22339287',
                'tipo_cliente' => 'COMUNIDAD DE BIENES',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 987
            ],

            [
                'nombre_fiscal' => 'ALAS-LES-VIGIL-CONS, C.B.',
                'nif' => 'E22351944',
                'tipo_cliente' => 'COMUNIDAD DE BIENES',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 617
            ],

            [
                'nombre_fiscal' => 'PLANES MUR SOLAR, C.B.',
                'nif' => 'E22354898',
                'tipo_cliente' => 'COMUNIDAD DE BIENES',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 639
            ],

            [
                'nombre_fiscal' => 'FOTOVOLTAICA LA PUEBLA, C.B.',
                'nif' => 'E22365548',
                'tipo_cliente' => 'COMUNIDAD DE BIENES',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 441
            ],

            [
                'nombre_fiscal' => 'SALVATIERRA BALLARIN, C.B',
                'nif' => 'E22387047',
                'tipo_cliente' => 'COMUNIDAD DE BIENES',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 399
            ],

            [
                'nombre_fiscal' => 'AISA Y LOPEZ, C.B.',
                'nif' => 'E22388953',
                'tipo_cliente' => 'COMUNIDAD DE BIENES',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'SIN ACTIVIDAD',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 964
            ],

            [
                'nombre_fiscal' => 'BLAZQUEZ-DOMINGUEZ, CB',
                'nif' => 'E22394696',
                'tipo_cliente' => 'COMUNIDAD DE BIENES',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'SIN ACTIVIDAD',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 433
            ],

            [
                'nombre_fiscal' => 'HEREDEROS MARIA TERESA ACIN SOPENA',
                'nif' => 'E22397129',
                'tipo_cliente' => 'COMUNIDAD DE BIENES',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 436
            ],

            [
                'nombre_fiscal' => 'BARON PERELLA, CB',
                'nif' => 'E22399455',
                'tipo_cliente' => 'COMUNIDAD DE BIENES',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 447
            ],

            [
                'nombre_fiscal' => 'GRASA PARDINA C.B.',
                'nif' => 'E22407613',
                'tipo_cliente' => 'COMUNIDAD DE BIENES',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 947
            ],

            [
                'nombre_fiscal' => 'BALLABRIGA AGRAZ, CB',
                'nif' => 'E22407621',
                'tipo_cliente' => 'COMUNIDAD DE BIENES',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 469
            ],

            [
                'nombre_fiscal' => 'MURO SAMBIA, C.B',
                'nif' => 'E22408264',
                'tipo_cliente' => 'COMUNIDAD DE BIENES',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 949
            ],

            [
                'nombre_fiscal' => 'PACO TORRES, C.B.',
                'nif' => 'E22409668',
                'tipo_cliente' => 'COMUNIDAD DE BIENES',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 946
            ],

            [
                'nombre_fiscal' => 'EL VEDADO, C.B.',
                'nif' => 'E22430557',
                'tipo_cliente' => 'COMUNIDAD DE BIENES',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 88
            ],

            [
                'nombre_fiscal' => 'CAMBRA MARCO, C.B.',
                'nif' => 'E50582287',
                'tipo_cliente' => 'COMUNIDAD DE BIENES',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 184
            ],

            [
                'nombre_fiscal' => 'COOP.CAMPO S.LORENZO ESTADILLA',
                'nif' => 'F22001820',
                'tipo_cliente' => 'COOPERATIVA',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 139
            ],

            [
                'nombre_fiscal' => 'S.A.T.8808 BODEGAS OSCA',
                'nif' => 'F22110969',
                'tipo_cliente' => 'COOPERATIVA',
                'tributacion' => 'CONTABILIDAD/CLIENTE',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 166
            ],

            [
                'nombre_fiscal' => 'BUILRIC SDAD. COOP. LTDA.',
                'nif' => 'F22236590',
                'tipo_cliente' => 'COOPERATIVA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 121
            ],

            [
                'nombre_fiscal' => 'ALAS NEVOT Y VIGIL INVERSIONES SDAD. COOP',
                'nif' => 'F22249577',
                'tipo_cliente' => 'COOPERATIVA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'SIN ACTIVIDAD',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 8
            ],

            [
                'nombre_fiscal' => 'HAZAVIT, S.C.L.',
                'nif' => 'F22338289',
                'tipo_cliente' => 'COOPERATIVA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 4.0,
                'codigo_sage' => 507
            ],

            [
                'nombre_fiscal' => 'ASOCIACION POLIGONO IND.VALLE CINCA',
                'nif' => 'G22036065',
                'tipo_cliente' => 'ASOCIACION',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => 'EMPESARIO',
                'puntaje' => 4.0,
                'codigo_sage' => 162
            ],

            [
                'nombre_fiscal' => 'CDAD. DE REGANTES RIPOLL VIÑEDO',
                'nif' => 'G22045975',
                'tipo_cliente' => 'ASOCIACION',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'BAJA',
                'clasificacion' => 'REGANTES',
                'puntaje' => NULL,
                'codigo_sage' => 957
            ],

            [
                'nombre_fiscal' => 'CDAD REGANTES NTRA SRA DE LA CARRODILLA',
                'nif' => 'G22104640',
                'tipo_cliente' => 'ASOCIACION',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => 'DEPORTES',
                'puntaje' => 3.0,
                'codigo_sage' => 950
            ],

            [
                'nombre_fiscal' => 'CLUB ATLETISMO BARBASTRO',
                'nif' => 'G22149033',
                'tipo_cliente' => 'ASOCIACION',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => 'DEPORTES',
                'puntaje' => 3.0,
                'codigo_sage' => 143
            ],

            [
                'nombre_fiscal' => 'CLUB DEPORTIVO FITNESS BARBASTRO',
                'nif' => 'G22393714',
                'tipo_cliente' => 'ASOCIACION',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'SIN ACTIVIDAD',
                'clasificacion' => 'DEPORTES',
                'puntaje' => NULL,
                'codigo_sage' => 429
            ],

            [
                'nombre_fiscal' => 'ASOCIACIÓN ARAGONESA DE AUTORES DE CÓMIC',
                'nif' => 'G22408140',
                'tipo_cliente' => 'ASOCIACION',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => 'CULTURAL',
                'puntaje' => 3.0,
                'codigo_sage' => 109
            ],

            [
                'nombre_fiscal' => 'WEKUPMEDIA S.C.',
                'nif' => 'J01803568',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 0.0,
                'codigo_sage' => 158
            ],

            [
                'nombre_fiscal' => 'GANADERA LUMACER S.C.',
                'nif' => 'J22421267',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 35
            ],

            [
                'nombre_fiscal' => 'MESON LA CADIERA DEL SOMONTANO, SC',
                'nif' => 'J22183404',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 4.0,
                'codigo_sage' => 47
            ],

            [
                'nombre_fiscal' => 'EL TORRULLON S.C.',
                'nif' => 'J22197339',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 248
            ],

            [
                'nombre_fiscal' => 'GRASA PARDINA, S.C.',
                'nif' => 'J22215206',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 51
            ],

            [
                'nombre_fiscal' => 'CORONA HOMBRE-MUJER, S.C.',
                'nif' => 'J22227698',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 4.0,
                'codigo_sage' => 100
            ],

            [
                'nombre_fiscal' => 'GABINETE TECNICO PERICIAL ALAZEZ, S.C.',
                'nif' => 'J22233845',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 131
            ],

            [
                'nombre_fiscal' => 'EXPLOTACIONES HERMANOS ALMAZOR, S.C',
                'nif' => 'J22237473',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 122
            ],

            [
                'nombre_fiscal' => 'EXPLOTACIONES BUIL VILLACAMPA, S.C.',
                'nif' => 'J22240873',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 125
            ],

            [
                'nombre_fiscal' => 'HOSTELERIA DEL BARRANQUE, S.C.',
                'nif' => 'J22244057',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 130
            ],

            [
                'nombre_fiscal' => 'EXPLOTACIONES AGRICOLAS J.OLIVERA',
                'nif' => 'J22246607',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 4.0,
                'codigo_sage' => 133
            ],

            [
                'nombre_fiscal' => 'EXPLOTACIONES AGRICOLAS DOMPER BUIL',
                'nif' => 'J22252241',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 192
            ],

            [
                'nombre_fiscal' => 'ALAS, NEVOT Y VIGIL INVERSIONES, S.C.',
                'nif' => 'J22253397',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 194
            ],

            [
                'nombre_fiscal' => 'BIOVACUNO S.C.',
                'nif' => 'J22261168',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 211
            ],

            [
                'nombre_fiscal' => 'OPTICA LUMEN BARBASTRO 55, S.C.',
                'nif' => 'J22263321',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 220
            ],

            [
                'nombre_fiscal' => 'HUERTAS DE CASTILLAZUELO S.C.',
                'nif' => 'J22266860',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 943
            ],

            [
                'nombre_fiscal' => 'VIÑAS ALMAZOR, S.C.',
                'nif' => 'J22274427',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 548
            ],

            [
                'nombre_fiscal' => 'HORTOFRUTIC. HERMANOS PUEO BUIL SC',
                'nif' => 'J22281984',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 962
            ],

            [
                'nombre_fiscal' => 'DAMATOL, S.C.',
                'nif' => 'J22289318',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 268
            ],

            [
                'nombre_fiscal' => 'RAMOS JAIME, S.C.',
                'nif' => 'J22305072',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 287
            ],

            [
                'nombre_fiscal' => 'FONTANERIA Y CALEFACCION RAMOS, SC',
                'nif' => 'J22318208',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 26
            ],

            [
                'nombre_fiscal' => 'EXPLOTACIONES AGROPECUARIAS EL SASO',
                'nif' => 'J22327506',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 185
            ],

            [
                'nombre_fiscal' => 'LARRES ENERGIA SOLAR, S.C.',
                'nif' => 'J22328553',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 376
            ],

            [
                'nombre_fiscal' => 'HOSTAL SAN JOSE, S.C.',
                'nif' => 'J22329585',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 4.0,
                'codigo_sage' => 504
            ],

            [
                'nombre_fiscal' => 'SERNA, S.C.',
                'nif' => 'J22335707',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 522
            ],

            [
                'nombre_fiscal' => 'EDAD 3, S.C.',
                'nif' => 'J22341663',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 116
            ],

            [
                'nombre_fiscal' => 'EL SABOR DE SHAWARMA S.C.',
                'nif' => 'J22343073',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'SIN ACTIVIDAD',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 559
            ],

            [
                'nombre_fiscal' => 'CONSTRUCCIONES ARTASONA, S.C.',
                'nif' => 'J22348734',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 611
            ],

            [
                'nombre_fiscal' => 'AGROPECUARIA LUMACER S.C.',
                'nif' => 'J22349328',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 958
            ],

            [
                'nombre_fiscal' => 'MURO SAMBIA S.C.',
                'nif' => 'J22350086',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 396
            ],

            [
                'nombre_fiscal' => 'REPARAC.ELECT.Y MANT.PARDINILLA, S.C.',
                'nif' => 'J22351696',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 621
            ],

            [
                'nombre_fiscal' => 'JIMENEZ SAMANIEGO, S.C.',
                'nif' => 'J22355127',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 665
            ],

            [
                'nombre_fiscal' => 'EXPLOTACIONES LISA-FIESTAS, S.C.',
                'nif' => 'J22356364',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 961
            ],

            [
                'nombre_fiscal' => 'SAMPERIZ AGRICOLA, S.C.',
                'nif' => 'J22379028',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 357
            ],

            [
                'nombre_fiscal' => 'GRUPO JIMSA, S.C.',
                'nif' => 'J22381230',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 375
            ],

            [
                'nombre_fiscal' => 'BESCOS, S.C.',
                'nif' => 'J22383012',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 380
            ],

            [
                'nombre_fiscal' => 'GRANJAS E.C.J.   S.C.',
                'nif' => 'J22384077',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 387
            ],

            [
                'nombre_fiscal' => 'IRC MOTOR, S.C.',
                'nif' => 'J22388391',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 4.0,
                'codigo_sage' => 406
            ],

            [
                'nombre_fiscal' => 'TRABAJOS AGRICOLAS ARNAL - BOSQUE, ',
                'nif' => 'J22391486',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 421
            ],

            [
                'nombre_fiscal' => 'BAR RESTAURANTE O´CADO, S.C.',
                'nif' => 'J22403612',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 4.0,
                'codigo_sage' => 464
            ],

            [
                'nombre_fiscal' => 'SOLCAL MONZÓN SOLDADUR. Y CALDERERI',
                'nif' => 'J22412340',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 972
            ],

            [
                'nombre_fiscal' => 'LORDAN SESE GANADERA S.C.',
                'nif' => 'J22416333',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 998
            ],

            [
                'nombre_fiscal' => 'EXPLOTACION GANADERA BUPAR, S.C.',
                'nif' => 'J02952877',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 200
            ],

            [
                'nombre_fiscal' => 'AGROPECUARIA RUFAS, S.C.',
                'nif' => 'J22423305',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 43
            ],

            [
                'nombre_fiscal' => 'AGRÍCOLA GANADERA PLUS BINÉFAR, S.L.',
                'nif' => 'B22421911',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 38
            ],

            [
                'nombre_fiscal' => 'MANUEL PARDO, S.C.P.',
                'nif' => 'J62179296',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 634
            ],

            [
                'nombre_fiscal' => 'BLASCO VALLS, OLGA',
                'nif' => 'X1825617S',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 135
            ],

            [
                'nombre_fiscal' => 'DROTLEFF , MARIA',
                'nif' => 'X3716410R',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 45
            ],

            [
                'nombre_fiscal' => 'MCHIOUER, MOHAMMED',
                'nif' => 'X3947412Z',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 0.0,
                'codigo_sage' => 72
            ],

            [
                'nombre_fiscal' => 'LASCAU, ELENA',
                'nif' => 'X4038721J',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 470
            ],

            [
                'nombre_fiscal' => 'CHEN, GUOBIN',
                'nif' => 'X4423912T',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 97
            ],

            [
                'nombre_fiscal' => 'EL MOUTAOUAKIL , ZOHAIR',
                'nif' => 'X4559688F',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 828
            ],

            [
                'nombre_fiscal' => 'GIRARDINI, NOEMI AIDA',
                'nif' => 'X4607198E',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 657
            ],

            [
                'nombre_fiscal' => 'COJOCARU, ARMAND',
                'nif' => 'X4638792Z',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 0.0,
                'codigo_sage' => 517
            ],

            [
                'nombre_fiscal' => 'LAZAR, ALEXANDRINA MARIA',
                'nif' => 'X5497665K',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 62
            ],

            [
                'nombre_fiscal' => 'ROMANESCU, CLAUDIA MELANIA',
                'nif' => 'X5783879T',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 367
            ],

            [
                'nombre_fiscal' => 'EL MOUTAOUAKIL, EL HASSAN',
                'nif' => 'X5883461S',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 954
            ],

            [
                'nombre_fiscal' => 'LAZAR , IOAN ALEX.',
                'nif' => 'X6286112M',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 453
            ],

            [
                'nombre_fiscal' => 'MITKOVA RADEVA, RENA',
                'nif' => 'X6633724H',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 178
            ],

            [
                'nombre_fiscal' => 'MARCU, CONSTANTIN MATINCA',
                'nif' => 'X6869856D',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 518
            ],

            [
                'nombre_fiscal' => 'RADEV, VESELIN ATANASOV',
                'nif' => 'X7605865H',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 59
            ],

            [
                'nombre_fiscal' => 'FINESZ, ANGELA',
                'nif' => 'X7841031D',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'nan',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 980
            ],

            [
                'nombre_fiscal' => 'MARCU, MARIUS COSTEL',
                'nif' => 'X8668576Z',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 150
            ],

            [
                'nombre_fiscal' => 'LAITON PACHECO, HUGO LIBARDO',
                'nif' => 'Y0100457F',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 997
            ],

            [
                'nombre_fiscal' => 'LAHSAINI, MOHAMED',
                'nif' => 'Y0350074M',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.O.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 132
            ],

            [
                'nombre_fiscal' => 'MUNTEAN , DUMITRU',
                'nif' => 'Y1187802G',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 359
            ],

            [
                'nombre_fiscal' => 'HUSSAIN, JAHANGIR ',
                'nif' => 'Y3711215H',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 14
            ],

            [
                'nombre_fiscal' => 'ZIMMERHACKEL, HANS CHRISTIAN',
                'nif' => 'Y4235678N',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.O.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 52
            ],

            [
                'nombre_fiscal' => 'SZEL, HELGA EVELYN',
                'nif' => 'Y4748438X',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 36
            ],

            [
                'nombre_fiscal' => 'WILSON, WILLIAM JAMES',
                'nif' => 'Y4799339N',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 111
            ],

            [
                'nombre_fiscal' => 'LATIA BALDA, ESTHER',
                'nif' => '06275291V',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 161
            ],

            [
                'nombre_fiscal' => 'NOVELLON CASAS, ANA ISABEL',
                'nif' => '73195207F',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => '-',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 163
            ],

            [
                'nombre_fiscal' => 'CORREA BETANCOURT, CRISTINA',
                'nif' => 'Y8684322L',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'EDS',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 0.0,
                'codigo_sage' => 138
            ],

            [
                'nombre_fiscal' => 'DEL RIO JUANGO, ALEJANDRO',
                'nif' => '73003257S',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'EDS',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 0.0,
                'codigo_sage' => 140
            ],

            [
                'nombre_fiscal' => 'MANAU FERRAZ, MARIA JOSE',
                'nif' => '17962809Q',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 141
            ],

            [
                'nombre_fiscal' => 'HERMANAS BRUALLA, C.B.',
                'nif' => 'E22369888',
                'tipo_cliente' => 'COMUNIDAD DE BIENES',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 144
            ],

            [
                'nombre_fiscal' => 'MITKOVA RADEVA, RENA',
                'nif' => 'X6633724H',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 178
            ],

            [
                'nombre_fiscal' => 'MITKOVA RADEVA, RENA',
                'nif' => 'X6633724H',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 178
            ],

            [
                'nombre_fiscal' => 'UNION BASKET BARBASTRO 2018',
                'nif' => 'G22421838',
                'tipo_cliente' => 'ASOCIACION',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => 'DEPORTES',
                'puntaje' => 3.0,
                'codigo_sage' => 1007
            ],

            [
                'nombre_fiscal' => 'BALDELLOU LLORET, JOSE IGNACIO',
                'nif' => '18062130T',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 170
            ],

            [
                'nombre_fiscal' => 'LES GRAN, JESUS',
                'nif' => '29114718F',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 171
            ],

            [
                'nombre_fiscal' => 'EL MIRADOR DE ALQUEZAR, S.L.',
                'nif' => 'B16916231',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 4.0,
                'codigo_sage' => 169
            ],

            [
                'nombre_fiscal' => 'PORTOLES DUERTO, FRANCISCO',
                'nif' => '18007020K',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.O.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 0.0,
                'codigo_sage' => 71
            ],

            [
                'nombre_fiscal' => 'VIDALLER BORRAR, JUAN MANUEL',
                'nif' => '18004777D',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 146
            ],

            [
                'nombre_fiscal' => 'AFIFA, NOUINI',
                'nif' => '18095404Q',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 148
            ],

            [
                'nombre_fiscal' => 'LORDAN SESE AGRICOLA, S.L.',
                'nif' => 'B72469190',
                'tipo_cliente' => 'SOCIEDAD LIMITADA',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 4.0,
                'codigo_sage' => 149
            ],

            [
                'nombre_fiscal' => 'ISMAEL GANADERA, S.C.',
                'nif' => 'J10534162',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'IVA SIMPLIFICADO',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 153
            ],

            [
                'nombre_fiscal' => 'CASTRO QUESADA, JOSEFA',
                'nif' => '33875400B',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 156
            ],

            [
                'nombre_fiscal' => 'MARZOUK, AHARROU',
                'nif' => 'X3255942Q',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.O.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 157
            ],

            [
                'nombre_fiscal' => 'POVEDA VALCEDO, LEONARDO',
                'nif' => 'X3746542A',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 189
            ],

            [
                'nombre_fiscal' => 'PARDINA SESE, ROSALIA',
                'nif' => '73188148D',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 195
            ],

            [
                'nombre_fiscal' => 'BUIL VILLACAMPA, ALBERTO',
                'nif' => '18041615R',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 186
            ],

            [
                'nombre_fiscal' => 'ALAS -LES-VIGIL-CONS, S.C,',
                'nif' => 'E22369888',
                'tipo_cliente' => 'COMUNIDAD DE BIENES',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => 0.0,
                'codigo_sage' => 617
            ],

            [
                'nombre_fiscal' => 'PARDO CIPRES, JAVIER',
                'nif' => '18065550Q',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 976
            ],

            [
                'nombre_fiscal' => 'CLARIMON PERGUA, ANTONIO',
                'nif' => '17976936K',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 215
            ],

            [
                'nombre_fiscal' => 'PADIAL MARTIN, ENCARNACION',
                'nif' => '18013574C',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 1.0,
                'codigo_sage' => 212
            ],

            [
                'nombre_fiscal' => 'MEDINA, JOSELINE LIZZETTE ',
                'nif' => 'Y3092062W',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 3.0,
                'codigo_sage' => 197
            ],

            [
                'nombre_fiscal' => 'BLANCO OLIVARES, RODOLFO ALBERTO',
                'nif' => 'Y8798499R',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 201
            ],

            [
                'nombre_fiscal' => 'RAMOS RUIZ, ANTONIO',
                'nif' => '73204807Q',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 202
            ],

            [
                'nombre_fiscal' => 'ISAMONI, S.C.',
                'nif' => 'J44967826',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 862
            ],

            [
                'nombre_fiscal' => 'MOSTOSSOMONTANO, C.B.',
                'nif' => 'E13874201',
                'tipo_cliente' => 'COMUNIDAD DE BIENES',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 1626
            ],

            [
                'nombre_fiscal' => 'GRANJA ODINA, S.C.',
                'nif' => 'J44603470',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 18
            ],

            [
                'nombre_fiscal' => 'CAMBRA PERALLON GANADERA, S.C.',
                'nif' => 'J44603827',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'ATRIBUCION RENTAS',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 199
            ],

            [
                'nombre_fiscal' => 'RUBIELLA COSCULLUELA, ROCIO',
                'nif' => '74882618T',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 34
            ],

            [
                'nombre_fiscal' => 'VILLEGAS JUBERO, LUCIA',
                'nif' => '73212760B',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.O.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 2073
            ],

            [
                'nombre_fiscal' => 'SANCHEZ ALBAJAR, ALICIA',
                'nif' => '73214589T',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'BAJA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 2038
            ],

            [
                'nombre_fiscal' => 'VILLACAMPA AYERBE, MARIA JOSE',
                'nif' => '18010298L',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 217
            ],

            [
                'nombre_fiscal' => 'BARRIO MONTES, ANTONIO',
                'nif' => '73195889E',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 2075
            ],

            [
                'nombre_fiscal' => 'PORQUET BADIA, NURIA',
                'nif' => '40854728N',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 2087
            ],

            [
                'nombre_fiscal' => 'MARCEN SAMPIETRO, CRISTINA',
                'nif' => '73204416Q',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 1850
            ],

            [
                'nombre_fiscal' => 'JORDAN CONSCULLUELA, JOSE JULIAN',
                'nif' => '18010285C',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'ALQUILER',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 990
            ],

            [
                'nombre_fiscal' => 'VALLE HUERVA, MARINA',
                'nif' => '73208114B',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.O.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 612
            ],

            [
                'nombre_fiscal' => 'CINCA RIDERS, S.C.',
                'nif' => 'J70800339',
                'tipo_cliente' => 'SOCIEDAD CIVIL ',
                'tributacion' => 'CONTABILIDAD',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => NULL,
                'codigo_sage' => 223
            ],

            [
                'nombre_fiscal' => 'UTRILLAS GRACIA, ANA',
                'nif' => '47896654M',
                'tipo_cliente' => 'PERSONA FISICA',
                'tributacion' => 'E.D.S.',
                'situacion' => 'ALTA',
                'clasificacion' => NULL,
                'puntaje' => 2.0,
                'codigo_sage' => 231
            ]
        ];

        foreach ($clientes as $cliente) {
            // Find related enum IDs (for tributaciones, situaciones, tipo_cliente, clasificacion)
            $tipo_cliente_id = DB::table('tipo_clientes')->where('nombre', $cliente['tipo_cliente'])->value('id');
            $tributacion_id = DB::table('tributaciones')->where('nombre', $cliente['tributacion'])->value('id');
            $situacion_id = DB::table('situaciones')->where('nombre', $cliente['situacion'])->value('id');
            $clasificacion_id = DB::table('clasificaciones')->where('nombre', $cliente['clasificacion'])->value('id');

            // Insert the client data
            DB::table('clientes')->insert([
                'nombre_fiscal' => $cliente['nombre_fiscal'],
                'nif' => $cliente['nif'],
                'tipo_cliente_id' => $tipo_cliente_id,
                'tributacion_id' => $tributacion_id,
                'situacion_id' => $situacion_id,
                'clasificacion_id' => $clasificacion_id,
                'puntaje' => $cliente['puntaje'],
                'codigo_sage' => $cliente['codigo_sage'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
