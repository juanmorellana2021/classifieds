<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class PeruProvincesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $map = [
            '01' => ['Amazonas', ['Chachapoyas', 'Bagua', 'Bongara', 'Condorcanqui', 'Luya', 'Rodriguez de Mendoza', 'Utcubamba']],
            '02' => ['Ancash', ['Huaraz', 'Aija', 'Antonio Raymondi', 'Asuncion', 'Bolognesi', 'Carhuaz', 'Carlos Fermin Fitzcarrald', 'Casma', 'Corongo', 'Huari', 'Huarmey', 'Huaylas', 'Mariscal Luzuriaga', 'Ocros', 'Pallasca', 'Pomabamba', 'Recuay', 'Santa', 'Sihuas', 'Yungay']],
            '03' => ['Apurimac', ['Abancay', 'Andahuaylas', 'Antabamba', 'Aymaraes', 'Cotabambas', 'Chincheros', 'Grau']],
            '04' => ['Arequipa', ['Arequipa', 'Camana', 'Caraveli', 'Castilla', 'Caylloma', 'Condesuyos', 'Islay', 'La Union']],
            '05' => ['Ayacucho', ['Huamanga', 'Cangallo', 'Huanca Sancos', 'Huanta', 'La Mar', 'Lucanas', 'Parinacochas', 'Paucar del Sara Sara', 'Sucre', 'Victor Fajardo', 'Vilcas Huaman']],
            '06' => ['Cajamarca', ['Cajamarca', 'Cajabamba', 'Celendin', 'Chota', 'Contumaza', 'Cutervo', 'Hualgayoc', 'Jaen', 'San Ignacio', 'San Marcos', 'San Miguel', 'San Pablo', 'Santa Cruz']],
            '07' => ['Callao', ['Callao']],
            '08' => ['Cusco', ['Cusco', 'Acomayo', 'Anta', 'Calca', 'Canas', 'Canchis', 'Chumbivilcas', 'Espinar', 'La Convencion', 'Paruro', 'Paucartambo', 'Quispicanchi', 'Urubamba']],
            '09' => ['Huancavelica', ['Huancavelica', 'Acobamba', 'Angaraes', 'Castrovirreyna', 'Churcampa', 'Huaytara', 'Tayacaja']],
            '10' => ['Huanuco', ['Huanuco', 'Ambo', 'Dos de Mayo', 'Huacaybamba', 'Huamalies', 'Leoncio Prado', 'Maranon', 'Pachitea', 'Puerto Inca', 'Lauricocha', 'Yarowilca']],
            '11' => ['Ica', ['Ica', 'Chincha', 'Nazca', 'Palpa', 'Pisco']],
            '12' => ['Junin', ['Huancayo', 'Concepcion', 'Chanchamayo', 'Jauja', 'Junin', 'Satipo', 'Tarma', 'Yauli', 'Chupaca']],
            '13' => ['La Libertad', ['Trujillo', 'Ascope', 'Bolivar', 'Chepen', 'Julcan', 'Otuzco', 'Pacasmayo', 'Pataz', 'Sanchez Carrion', 'Santiago de Chuco', 'Gran Chimu', 'Viru']],
            '14' => ['Lambayeque', ['Chiclayo', 'Ferrenafe', 'Lambayeque']],
            '15' => ['Lima', ['Lima', 'Barranca', 'Cajatambo', 'Canta', 'Canete', 'Huaral', 'Huarochiri', 'Huaura', 'Oyon', 'Yauyos']],
            '16' => ['Loreto', ['Maynas', 'Alto Amazonas', 'Loreto', 'Mariscal Ramon Castilla', 'Putumayo', 'Requena', 'Ucayali', 'Datem del Maranon']],
            '17' => ['Madre de Dios', ['Tambopata', 'Manu', 'Tahuamanu']],
            '18' => ['Moquegua', ['Mariscal Nieto', 'General Sanchez Cerro', 'Ilo']],
            '19' => ['Pasco', ['Pasco', 'Daniel Alcides Carrion', 'Oxapampa']],
            '20' => ['Piura', ['Piura', 'Ayabaca', 'Huancabamba', 'Morropon', 'Paita', 'Sullana', 'Talara', 'Sechura']],
            '21' => ['Puno', ['Puno', 'Azangaro', 'Carabaya', 'Chucuito', 'El Collao', 'Huancane', 'Lampa', 'Melgar', 'Moho', 'San Antonio de Putina', 'San Roman', 'Sandia', 'Yunguyo']],
            '22' => ['San Martin', ['Moyobamba', 'Bellavista', 'El Dorado', 'Huallaga', 'Lamas', 'Mariscal Caceres', 'Picota', 'Rioja', 'San Martin', 'Tocache']],
            '23' => ['Tacna', ['Tacna', 'Candarave', 'Jorge Basadre', 'Tarata']],
            '24' => ['Tumbes', ['Tumbes', 'Contralmirante Villar', 'Zarumilla']],
            '25' => ['Ucayali', ['Coronel Portillo', 'Atalaya', 'Padre Abad', 'Purus']],
        ];

        $rows = [];

        foreach ($map as $departmentCode => [$department, $provinces]) {
            foreach ($provinces as $i => $province) {
                $rows[] = [
                    'ubigeo' => $departmentCode.sprintf('%02d', $i + 1),
                    'department' => $department,
                    'province' => $province,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('peru_provinces')->upsert(
            $rows,
            ['ubigeo'],
            ['department', 'province', 'updated_at']
        );
    }
}
