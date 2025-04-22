<?php

namespace App\Enums;

enum ApiResponseType: string {
    case AUTH_ADD = "auth_add";
    case AUTH_LOGIN = "auth_login";
}
