<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;


class VersionSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {


        try {
            $data = $this->getJsonVersions();

            $versionArr = $data->versions;

            // release: {latest release}
            // snapshot: {latest snapshot}
            $latestVersions = array_values((array)$data->latest);
            DB::table('versions')->truncate();

            foreach ($versionArr as $value) {
                DB::table('versions')->insert([
                    "version" => $value->id,
                    "released" => $value->releaseTime,
                    "type" => $value->type,
                    ...(in_array($value->id, $latestVersions) ? ["is_latest" => true] : []),
                ]);
            }

            $this->command->info("Done seeding versions!");

        } catch (\Exception $exception) {
            $this->command->error("Something went horribly wrong! not seeding: "
                . $exception->getMessage());
        }

    }

    protected function getJsonVersions(): mixed {

        // download version_manifest.json

        $manifestUrl = "https://launchermeta.mojang.com/mc/game/version_manifest.json";
        $localFilePath = "minecraft/version_manifest.json";

        try {
            $response = Http::get($manifestUrl);

            if ($response->successful()) {
                Storage::put($localFilePath, $response->body());
                $this->command->info("Successfully downloaded version_manifest.json from mojang.com!");
            } else {
                $this->command->warn("Failed to fetch version_manifest.json: " . $response->status());
            }
        } catch (\Exception $exception) {
            $this->command->warn("Error fetching version_manifest.json: " . $exception->getMessage());
        }

        if (Storage::exists($localFilePath)) {
            return json_decode(Storage::get($localFilePath));
        } else {
            throw new \Exception("versions_manifest.json does not exists! cannot proceed further!");
        }

    }
}
