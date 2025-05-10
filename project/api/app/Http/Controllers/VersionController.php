<?php

namespace App\Http\Controllers;


use App\Http\Requests\VersionSearchRequest;
use App\Models\Version;

class VersionController extends Controller {


    public function __invoke(VersionSearchRequest $request) {


        $data = $request->validated();

        $versionTypesArray = $data["types"];
        $versionQuery = $data["query"];
        $limit = $data["limit"] ?? 20;

        $versions = Version::where("version", "LIKE", "%${versionQuery}%")
            ->whereIn("type", $versionTypesArray)
            ->take($limit)
            ->get();

        return $versions;
    }
}
