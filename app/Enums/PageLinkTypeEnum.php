<?php

namespace App\Enums;

enum PageLinkTypeEnum: string {
    case INTERNAL = 'internal';
    case EXTERNAL = 'external';
    case ALL = 'all';
}
