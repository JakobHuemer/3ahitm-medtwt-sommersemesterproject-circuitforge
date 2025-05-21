<?php

namespace App\Enums;

enum Rating: string {
    case LIKE = "1";
    case DISLIKE = "-1";
}
