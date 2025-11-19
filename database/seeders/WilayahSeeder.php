<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        $currentCount = DB::table('wilayah')->count();
        if ($currentCount >= 80000) {
            echo "Data wilayah sudah lengkap ({$currentCount} records), skip seeding.\n";
            return;
        }
        
        echo "Current records: {$currentCount}\n";
        
        // Load reference data once
        $provinces = $this->loadProvinces();
        $regencies = $this->loadRegencies();
        $districts = $this->loadDistricts();
        
        $file = base_path('wilayahexel/villages.csv');
        $chunkSize = 50; // Much smaller chunk
        $lineNumber = 0;
        
        // Set memory limit and time limit
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 0);
        
        if (($handle = fopen($file, 'r')) !== false) {
            $data = [];
            
            while (($row = fgetcsv($handle)) !== false) {
                $lineNumber++;
                
                // Skip already processed lines
                if ($lineNumber <= $currentCount) {
                    continue;
                }
                
                $districtId = $row[1] ?? null;
                if (!$districtId || !isset($districts[$districtId])) {
                    continue;
                }
                
                $regencyId = $districts[$districtId]['regency_id'] ?? null;
                $provinceId = $regencies[$regencyId]['province_id'] ?? null;

                $data[] = [
                    'provinsi' => $provinces[$provinceId] ?? 'Unknown',
                    'kabupaten' => $regencies[$regencyId]['name'] ?? 'Unknown', 
                    'kecamatan' => $districts[$districtId]['name'] ?? 'Unknown',
                    'kelurahan' => $row[2] ?? 'Unknown',
                    'kodepos' => '00000',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($data) >= $chunkSize) {
                    DB::table('wilayah')->insert($data);
                    echo "Progress: {$lineNumber} lines processed (Memory: " . memory_get_usage(true) / 1024 / 1024 . "MB)\n";
                    
                    // Clear memory aggressively
                    unset($data);
                    $data = [];
                    gc_collect_cycles();
                    
                    // Small delay to prevent overwhelming
                    usleep(1000);
                }
            }
            
            // Insert remaining data
            if (!empty($data)) {
                DB::table('wilayah')->insert($data);
            }
            
            fclose($handle);
            $finalCount = DB::table('wilayah')->count();
            echo "Seeding completed. Total records: {$finalCount}\n";
        }
    }

    private function loadProvinces(): array
    {
        $provinces = [];
        $file = base_path('wilayahexel/provinces.csv');
        
        if (($handle = fopen($file, 'r')) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                $provinces[$data[0]] = $data[1];
            }
            fclose($handle);
        }
        
        return $provinces;
    }

    private function loadRegencies(): array
    {
        $regencies = [];
        $file = base_path('wilayahexel/regencies.csv');
        
        if (($handle = fopen($file, 'r')) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                $regencies[$data[0]] = [
                    'name' => $data[2],
                    'province_id' => $data[1]
                ];
            }
            fclose($handle);
        }
        
        return $regencies;
    }

    private function loadDistricts(): array
    {
        $districts = [];
        $file = base_path('wilayahexel/districts.csv');
        
        if (($handle = fopen($file, 'r')) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                $districts[$data[0]] = [
                    'name' => $data[2],
                    'regency_id' => $data[1]
                ];
            }
            fclose($handle);
        }
        
        return $districts;
    }


}