<?php

namespace App\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;

class CheckAvailabilityData extends Data
{
    public function __construct(
        #[Required, Exists('vehicles', 'id')]
        public int $vehicle_id,
        
        #[Required]
        public Carbon $start_date,
        
        #[Required]
        public Carbon $end_date,
    ) {}
}
