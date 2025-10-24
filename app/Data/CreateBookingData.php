<?php

namespace App\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Attributes\Validation\After;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Accepted;
use Spatie\LaravelData\Data;

class CreateBookingData extends Data
{
    public function __construct(
        #[Required, Exists('vehicles', 'id')]
        public int $vehicle_id,
        
        #[Required, AfterOrEqual('today')]
        public Carbon $start_date,
        
        #[Required, After('start_date')]
        public Carbon $end_date,
        
        #[Required]
        public int $user_id,
        
        #[Accepted]
        public bool $terms_accepted = false,
        
        public ?string $notes = null,
    ) {}
}
