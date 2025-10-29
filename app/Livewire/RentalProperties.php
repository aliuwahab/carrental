<?php

namespace App\Livewire;

use Livewire\Component;

class RentalProperties extends Component
{
    public $properties = [
        [
            'id' => 1,
            'name' => '2 Bedrooms House With Private Pool - The Minimalist House',
            'description' => 'Experience ultimate luxury living in this stunning 2-bedroom minimalist house featuring an exclusive private swimming pool in Spintex, Accra. Just 15 minutes from Kotoka International Airport, this modern self-contained compound combines contemporary design with exceptional comfort and privacy. The property features spacious ensuite bedrooms, a fully equipped modern kitchen, and premium amenities throughout. Enjoy your own private oasis with exclusive pool access, perfect for relaxation after exploring the vibrant city. With secure parking, modern appliances, and a serene environment, this property offers the ideal balance of luxury and tranquility for both short getaways and extended stays.',
            'features' => [
                'Private Swimming Pool',
                '2 Spacious Ensuite Bedrooms',
                'Modern Minimalist Design',
                'Self-Contained Compound',
                'Fully Equipped Kitchen',
                'Secure Parking',
                'Modern Appliances',
                'Central Air Conditioning',
                'Free WiFi',
                'Private Entrance',
                '24/7 Security',
                'Peaceful Location'
            ],
            'location' => 'Spintex, Accra (15 min from Airport)',
            'airbnb_url' => 'https://www.airbnb.co.uk/rooms/1426062913311569946',
            'image' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&h=600&fit=crop'
        ],
        [
            'id' => 2,
            'name' => '2-Bedrooms Self Compound - The Minimalist House',
            'description' => 'Experience elegant minimalist living inspired by modern Japanese design at this stunning 2-bedroom self-compound property in Spintex, Accra. Just 15 minutes from Kotoka International Airport, this spacious home features two ensuite bedrooms with smart TVs, ultra-fast Starlink internet, and a fully equipped modern kitchen with high-end appliances. The property boasts premium amenities including a washing machine, dryer, standby generator, and professional 24/7 security with CCTV surveillance and electrified perimeter fencing. Perfect for families and business travelers seeking a 5-star living experience.',
            'features' => [
                'Starlink Ultra-Fast WiFi',
                '2 Ensuite Bedrooms with Smart TVs',
                'Modern Kitchen with Built-in Appliances',
                'Washing Machine & Dryer',
                'CCTV Security & Electric Fence',
                'Standby Generator',
                'Free Parking',
                'Airport Transfer Available',
                'Car Rental Services',
                'Baby Cot Available',
                'Central Air Conditioning',
                'Netflix & Amazon Prime'
            ],
            'location' => 'Spintex, Accra (15 min from Airport)',
            'airbnb_url' => 'https://www.airbnb.co.uk/rooms/1327661956927938458',
            'image' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=800&h=600&fit=crop'
        ]
    ];

    public function render()
    {
        return view('livewire.rental-properties')
            ->layout('components.layouts.public');
    }
}
