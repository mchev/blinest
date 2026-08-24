<?php

namespace App\Enums;

enum FacebookDataDeletionAction: string
{
    case Pending = 'pending';
    case Deleted = 'deleted';
    case Unlinked = 'unlinked';
    case NotFound = 'not_found';
    case Failed = 'failed';
}
