<?php

namespace App\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class VehicleFilterData extends Data
{
    public function __construct(
        #[Required]
        public Carbon $start_date,
        
        #[Required]
        public Carbon $end_date,
        
        public ?string $type = null,
        
        public ?float $min_price = null,
        
        public ?float $max_price = null,
    ) {}
}
