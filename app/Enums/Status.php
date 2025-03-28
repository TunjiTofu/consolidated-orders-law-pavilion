<?php

namespace App\Enums;

enum Status: string
{
    case PENDING = 'pending';

    case SHIPPED = 'shipped';

    case DELIVERED = 'delivered';

    case PROCESSING = 'processing';

    case COMPLETED = 'completed';
}
