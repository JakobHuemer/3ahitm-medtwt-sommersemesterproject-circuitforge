<?php

namespace App\Enums;

enum EntityType: string {
    case POST = "post";
    case COMMENT = "comment";
    case DISCUSSION = "discussion";
}

