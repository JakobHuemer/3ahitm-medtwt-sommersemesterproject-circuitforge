<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;


class VersionSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {

        try {
            $data = $this->getJsonVersions();

            /*
            latest
                release	"1.21.5"
                snapshot	"25w17a"
            versions
                0
                    id	"25w17a"
                    type	"snapshot"
                    url	"https://piston-meta.mojang.com/v1/packages/b0b639d40ca10ef21f714eb801222e033680e17d/25w17a.json"
                    time	"2025-04-22T13:01:57+00:00"
                    releaseTime	"2025-04-22T12:51:30+00:00"
             *
             * */

            $versionArr = $data->versions;

            foreach ($versionArr as $value) {
                $this->command->info(json_encode($versionArr));
            }

        } catch (\Exception $exception) {
            $this->command->error("No version_manifest.json file available! Not seeding!");
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
            return json_decode(Storage::get($localFilePath), true);
        } else {
            throw new \Exception("versions_manifest.json does not exists! cannot proceed further!");
        }

    }
}
