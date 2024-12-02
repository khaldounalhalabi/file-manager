<?php

namespace App\Enums;

enum FileLogTypeEnum: string
{
    case STARTED_EDITING = "started_editing";
    case FINISHED_EDITING = "finished editing";
    case CREATED = "created";
}
