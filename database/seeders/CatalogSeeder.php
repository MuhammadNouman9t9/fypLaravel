<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\InventoryItem;
use App\Models\Product;
use App\Models\ProductMedia;
use App\Models\ProductSpecification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $categories = collect([
                'CCTV Cameras' => [
                    'Smart Indoor Cameras',
                    'Smart Outdoor Cameras',
                    'Wireless Cameras',
                    '4K Security Cameras',
                ],
                'Motion Detectors' => [
                    'PIR Motion Sensors',
                    'Microwave Motion Sensors',
                    'Dual Technology Sensors',
                    'Pet-Immune Sensors',
                ],
                'Smart Locks' => [
                    'Keyless Entry Locks',
                    'Biometric Locks',
                    'Remote Access Locks',
                    'Smart Deadbolts',
                ],
                'Digital Doorbells' => [
                    'Video Doorbells',
                    'Wi-Fi Doorbells',
                    'Wired Doorbells',
                    'Battery Doorbells',
                ],
                'Alarm Systems' => [
                    'Home Alarm Systems',
                    'Wireless Alarms',
                    'Monitored Alarms',
                    'DIY Alarm Kits',
                ],
                'Biometric Access Controls' => [
                    'Fingerprint Scanners',
                    'Facial Recognition',
                    'Iris Scanners',
                    'Access Control Panels',
                ],
                'Automation & Hubs' => [
                    'Security Hubs',
                    'AI Assistants',
                    'Lighting Automation',
                    'Smart Home Controllers',
                ],
            ])->map(function (array $children, string $parentName) {
                $parent = Category::query()->firstOrCreate(
                    ['slug' => Str::slug($parentName)],
                    [
                        'uuid' => (string) Str::uuid(),
                        'name' => $parentName,
                        'summary' => "Explore SafeNest {$parentName} to monitor and secure your property.",
                        'description' => "Trusted {$parentName} engineered for resilient SafeNest households.",
                    ]
                );

                $childRecords = collect($children)->map(function (string $child) use ($parent) {
                    return Category::query()->firstOrCreate(
                        ['slug' => Str::slug($child)],
                        [
                            'uuid' => (string) Str::uuid(),
                            'parent_id' => $parent->id,
                            'name' => $child,
                            'summary' => "Curated {$child} for modern SafeNest deployments.",
                            'description' => "{$child} optimized for SafeNest AI orchestration.",
                        ]
                    );
                });

                return collect([$parent])->merge($childRecords);
            })->flatten();

            // Real product data
            $realProducts = [
                [
                    'name' => 'SafeNest Pro 4K Outdoor Security Camera',
                    'brand' => 'SafeNest',
                    'summary' => 'Weather-resistant 4K camera with night vision and AI-powered motion detection for complete home security.',
                    'description' => 'The SafeNest Pro 4K Outdoor Security Camera delivers crystal-clear video footage day and night. With advanced AI motion detection, you\'ll receive instant alerts when activity is detected. IP66 weatherproof rating ensures reliable performance in all conditions.',
                    'price' => 249.99,
                    'compare_at_price' => 299.99,
                    'rating_average' => 4.7,
                    'reviews_count' => 1245,
                    'category_slug' => 'cctv-cameras',
                ],
                [
                    'name' => 'Smart Motion Detector Pro',
                    'brand' => 'SecureGuard',
                    'summary' => 'Advanced PIR motion sensor with pet immunity and 120° detection range for reliable home monitoring.',
                    'description' => 'Our Smart Motion Detector Pro uses dual PIR technology to accurately detect human movement while ignoring pets up to 50lbs. With a 120° detection range and 30ft range, it provides comprehensive coverage for any room.',
                    'price' => 79.99,
                    'compare_at_price' => 99.99,
                    'rating_average' => 4.5,
                    'reviews_count' => 892,
                    'category_slug' => 'motion-detectors',
                ],
                [
                    'name' => 'Biometric Smart Lock Elite',
                    'brand' => 'HomeShield',
                    'summary' => 'Fingerprint and PIN access smart lock with remote control via smartphone app and auto-lock feature.',
                    'description' => 'The Biometric Smart Lock Elite offers multiple access methods including fingerprint recognition, PIN code, and smartphone app. Features include auto-lock after 30 seconds, tamper alerts, and compatibility with most standard door preparations.',
                    'price' => 349.99,
                    'compare_at_price' => 399.99,
                    'rating_average' => 4.8,
                    'reviews_count' => 2156,
                    'category_slug' => 'smart-locks',
                ],
                [
                    'name' => 'Video Doorbell Pro 2K',
                    'brand' => 'GuardianEye',
                    'summary' => '2K HD video doorbell with two-way audio, night vision, and package detection alerts.',
                    'description' => 'Never miss a visitor with the Video Doorbell Pro 2K. Features include 2K HD video, two-way audio communication, infrared night vision, and AI-powered package detection. Works with existing doorbell wiring or battery-powered installation.',
                    'price' => 199.99,
                    'compare_at_price' => 249.99,
                    'rating_average' => 4.6,
                    'reviews_count' => 1876,
                    'category_slug' => 'digital-doorbells',
                ],
                [
                    'name' => 'Wireless Home Alarm System',
                    'brand' => 'TechSecure',
                    'summary' => 'Complete wireless alarm system with 8 sensors, keypad, and mobile app monitoring.',
                    'description' => 'Protect your entire home with this comprehensive wireless alarm system. Includes 8 door/window sensors, motion detector, keypad, and mobile app for remote monitoring. Professional monitoring available.',
                    'price' => 499.99,
                    'compare_at_price' => 599.99,
                    'rating_average' => 4.4,
                    'reviews_count' => 634,
                    'category_slug' => 'alarm-systems',
                ],
                [
                    'name' => 'Facial Recognition Access Control',
                    'brand' => 'SmartGuard',
                    'summary' => 'Advanced facial recognition system with 99.9% accuracy and anti-spoofing technology.',
                    'description' => 'Enterprise-grade facial recognition access control for your home. Features 99.9% recognition accuracy, anti-spoofing technology, and can store up to 1000 faces. Perfect for high-security applications.',
                    'price' => 799.99,
                    'compare_at_price' => 999.99,
                    'rating_average' => 4.9,
                    'reviews_count' => 423,
                    'category_slug' => 'biometric-access-controls',
                ],
                [
                    'name' => 'SafeNest AI Security Hub',
                    'brand' => 'SafeNest',
                    'summary' => 'Centralized smart home security hub with AI assistant and automation capabilities.',
                    'description' => 'The SafeNest AI Security Hub is the brain of your smart security system. Connects all your SafeNest devices, provides AI-powered insights, and enables advanced automation routines. Includes voice control and mobile app.',
                    'price' => 299.99,
                    'compare_at_price' => 349.99,
                    'rating_average' => 4.7,
                    'reviews_count' => 1123,
                    'category_slug' => 'automation-hubs',
                ],
                [
                    'name' => 'Indoor Security Camera 1080p',
                    'brand' => 'SecureGuard',
                    'summary' => 'Compact indoor camera with pan/tilt, two-way audio, and privacy mode.',
                    'description' => 'Monitor your home from anywhere with this feature-rich indoor camera. 1080p HD video, 360° pan and tilt, two-way audio, and privacy mode for when you\'re home. Perfect for baby monitoring or pet watching.',
                    'price' => 129.99,
                    'compare_at_price' => 159.99,
                    'rating_average' => 4.5,
                    'reviews_count' => 2341,
                    'category_slug' => 'cctv-cameras',
                ],
                [
                    'name' => 'Glass Break Sensor',
                    'brand' => 'HomeShield',
                    'summary' => 'Acoustic glass break detector with 25ft range and false alarm prevention.',
                    'description' => 'Detects the sound of breaking glass up to 25 feet away. Advanced algorithms filter out false alarms from normal household sounds. Battery-powered with 2-year battery life.',
                    'price' => 59.99,
                    'compare_at_price' => 79.99,
                    'rating_average' => 4.3,
                    'reviews_count' => 567,
                    'category_slug' => 'motion-detectors',
                ],
                [
                    'name' => 'Keyless Entry Smart Lock',
                    'brand' => 'GuardianEye',
                    'summary' => 'Wi-Fi enabled smart lock with keypad, app control, and guest access codes.',
                    'description' => 'Upgrade your door with this Wi-Fi enabled smart lock. Features include keypad entry, smartphone app control, guest access codes, and activity logs. Works with Alexa and Google Assistant.',
                    'price' => 229.99,
                    'compare_at_price' => 279.99,
                    'rating_average' => 4.6,
                    'reviews_count' => 1456,
                    'category_slug' => 'smart-locks',
                ],
                [
                    'name' => 'Battery Video Doorbell',
                    'brand' => 'TechSecure',
                    'summary' => 'Wireless video doorbell with 6-month battery life and HD video recording.',
                    'description' => 'No wiring required! This battery-powered video doorbell provides 6 months of battery life on a single charge. Features HD video, motion detection, and works with existing doorbell chime.',
                    'price' => 179.99,
                    'compare_at_price' => 219.99,
                    'rating_average' => 4.4,
                    'reviews_count' => 987,
                    'category_slug' => 'digital-doorbells',
                ],
                [
                    'name' => 'DIY Alarm Kit Starter',
                    'brand' => 'SmartGuard',
                    'summary' => 'Easy-to-install alarm kit with 5 sensors, siren, and mobile app.',
                    'description' => 'Perfect for renters or DIY enthusiasts. This starter kit includes everything you need: 5 door/window sensors, motion detector, siren, and mobile app. No professional installation required.',
                    'price' => 199.99,
                    'compare_at_price' => 249.99,
                    'rating_average' => 4.2,
                    'reviews_count' => 445,
                    'category_slug' => 'alarm-systems',
                ],
                [
                    'name' => 'Fingerprint Scanner Pro',
                    'brand' => 'SafeNest',
                    'summary' => 'Standalone fingerprint scanner with 500 user capacity and anti-tamper protection.',
                    'description' => 'High-security fingerprint scanner with 500 user capacity. Features include anti-tamper protection, weatherproof design, and optional card reader. Perfect for home offices or high-security areas.',
                    'price' => 449.99,
                    'compare_at_price' => 549.99,
                    'rating_average' => 4.7,
                    'reviews_count' => 678,
                    'category_slug' => 'biometric-access-controls',
                ],
                [
                    'name' => 'Smart Home Controller Hub',
                    'brand' => 'SecureGuard',
                    'summary' => 'Universal smart home hub supporting Zigbee, Z-Wave, and Wi-Fi devices.',
                    'description' => 'Control all your smart home devices from one central hub. Supports Zigbee, Z-Wave, and Wi-Fi protocols. Includes voice control, automation rules, and mobile app. Compatible with 1000+ devices.',
                    'price' => 179.99,
                    'compare_at_price' => 229.99,
                    'rating_average' => 4.5,
                    'reviews_count' => 1234,
                    'category_slug' => 'automation-hubs',
                ],
                [
                    'name' => 'Outdoor PTZ Security Camera',
                    'brand' => 'HomeShield',
                    'summary' => 'Pan-tilt-zoom outdoor camera with 4x zoom, night vision, and weatherproof design.',
                    'description' => 'Professional-grade PTZ camera for outdoor security. Features 4x optical zoom, 360° pan, 90° tilt, full-color night vision, and IP67 weatherproof rating. Perfect for large properties.',
                    'price' => 399.99,
                    'compare_at_price' => 499.99,
                    'rating_average' => 4.8,
                    'reviews_count' => 789,
                    'category_slug' => 'cctv-cameras',
                ],
                [
                    'name' => 'Pet-Immune Motion Sensor',
                    'brand' => 'GuardianEye',
                    'summary' => 'Motion detector that ignores pets up to 80lbs while detecting humans accurately.',
                    'description' => 'Advanced motion sensor specifically designed to ignore pets while detecting human movement. Works with pets up to 80lbs. Features adjustable sensitivity and 110° detection angle.',
                    'price' => 89.99,
                    'compare_at_price' => 109.99,
                    'rating_average' => 4.6,
                    'reviews_count' => 1123,
                    'category_slug' => 'motion-detectors',
                ],
                [
                    'name' => 'Smart Deadbolt Lock',
                    'brand' => 'TechSecure',
                    'summary' => 'Wi-Fi smart deadbolt with auto-lock, activity logs, and voice control.',
                    'description' => 'Replace your existing deadbolt with this smart Wi-Fi enabled lock. Features auto-lock after 30 seconds, activity logs, voice control via Alexa/Google, and works with most standard doors.',
                    'price' => 279.99,
                    'compare_at_price' => 329.99,
                    'rating_average' => 4.5,
                    'reviews_count' => 876,
                    'category_slug' => 'smart-locks',
                ],
                [
                    'name' => 'Wired Video Doorbell 4K',
                    'brand' => 'SmartGuard',
                    'summary' => '4K UHD wired doorbell with pre-roll recording and advanced motion zones.',
                    'description' => 'Ultra-high definition 4K video doorbell with pre-roll recording to capture events before motion is detected. Features advanced motion zones, two-way audio, and works with existing doorbell wiring.',
                    'price' => 249.99,
                    'compare_at_price' => 299.99,
                    'rating_average' => 4.7,
                    'reviews_count' => 1567,
                    'category_slug' => 'digital-doorbells',
                ],
                // New Featured Products with Custom Images
                [
                    'name' => 'Bullet Outdoor Security Camera',
                    'brand' => 'SafeNest',
                    'summary' => 'Weather-resistant bullet-style security camera with night vision and AI motion detection for complete outdoor surveillance.',
                    'description' => 'Professional-grade bullet security camera designed for outdoor installation. Features IP66 weatherproof rating, full-color night vision, AI-powered motion detection, and red indicator lights showing active status. Perfect for monitoring patios, driveways, and entry points.',
                    'price' => 189.99,
                    'compare_at_price' => 229.99,
                    'rating_average' => 4.8,
                    'reviews_count' => 2341,
                    'category_slug' => 'cctv-cameras',
                    'custom_image' => '/images/products/bullet-security-camera.jpg',
                ],
                [
                    'name' => 'Indoor Spherical Security Camera',
                    'brand' => 'SafeNest',
                    'summary' => 'Sleek spherical indoor camera with 360° coverage, two-way audio, and privacy mode for home monitoring.',
                    'description' => 'Modern spherical security camera perfect for indoor use. Features 1080p HD video, 360° coverage, two-way audio communication, and privacy mode. Red indicator lights show recording status. Compact design blends seamlessly into any home decor.',
                    'price' => 149.99,
                    'compare_at_price' => 179.99,
                    'rating_average' => 4.6,
                    'reviews_count' => 1876,
                    'category_slug' => 'cctv-cameras',
                    'custom_image' => '/images/products/spherical-security-camera.jpg',
                ],
                [
                    'name' => 'Smart Door Camera Pro',
                    'brand' => 'SafeNest',
                    'summary' => 'Advanced smart doorbell camera with 2K video, two-way audio, night vision, and package detection.',
                    'description' => 'Premium smart door camera with 2K HD video resolution, infrared night vision with LED ring illumination, two-way audio communication, and AI-powered package detection. Silver brushed finish with blue status indicator. Perfect for monitoring your front door and receiving visitors.',
                    'price' => 219.99,
                    'compare_at_price' => 269.99,
                    'rating_average' => 4.7,
                    'reviews_count' => 2156,
                    'category_slug' => 'digital-doorbells',
                    'custom_image' => '/images/products/smart-door-camera.jpg',
                ],
                [
                    'name' => 'Biometric Smart Door Lock',
                    'brand' => 'SafeNest',
                    'summary' => 'Advanced smart door lock with fingerprint recognition, PIN access, and smartphone app control.',
                    'description' => 'High-security smart door lock featuring fingerprint biometric scanner, PIN code access, and smartphone app control. Glossy black screen with blue interactive indicator, auto-lock feature, and tamper alerts. Compatible with standard door preparations. Perfect for keyless entry and enhanced home security.',
                    'price' => 329.99,
                    'compare_at_price' => 399.99,
                    'rating_average' => 4.9,
                    'reviews_count' => 3124,
                    'category_slug' => 'smart-locks',
                    'custom_image' => '/images/products/smart-door-lock.jpg',
                ],
            ];

            foreach ($realProducts as $productData) {
                $category = $categories->firstWhere('slug', $productData['category_slug']);
                if (! $category) {
                    $category = $categories->first();
                }

                $product = Product::create([
                    'uuid' => (string) Str::uuid(),
                    'sku' => strtoupper('SN-'.Str::random(6)),
                    'slug' => Str::slug($productData['name']),
                    'name' => $productData['name'],
                    'brand' => $productData['brand'],
                    'summary' => $productData['summary'],
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'compare_at_price' => $productData['compare_at_price'],
                    'currency' => 'USD',
                    'rating_average' => $productData['rating_average'],
                    'reviews_count' => $productData['reviews_count'],
                    'availability_status' => 'in_stock',
                    'is_active' => true,
                    'is_featured' => isset($productData['custom_image']) ? true : (rand(1, 10) <= 2), // Featured if has custom image
                    'warranty_period' => Arr::random(['1 year', '2 years', '3 years']),
                    'return_policy' => '30-day returns',
                    'specifications_snapshot' => [],
                    'meta' => [],
                ]);

                $product->categories()->sync([
                    $category->id => [
                        'is_primary' => true,
                        'assigned_at' => now(),
                    ],
                ]);

                $this->seedMedia($product, $productData['category_slug'], $productData['custom_image'] ?? null);
                $this->seedSpecifications($product, $productData['category_slug']);
                $this->seedInventory($product);
            }
        });
    }

    protected function seedMedia(Product $product, string $categorySlug = 'cctv-cameras', ?string $customImage = null): void
    {
        // Use custom image if provided
        if ($customImage) {
            ProductMedia::create([
                'product_id' => $product->id,
                'type' => 'image',
                'file_path' => $customImage,
                'thumbnail_path' => $customImage,
                'alt_text' => "{$product->name}",
                'position' => 0,
                'is_primary' => true,
                'meta' => ['source' => 'custom'],
            ]);

            return;
        }

        $imageMap = [
            'cctv-cameras' => [
                'https://images.unsplash.com/photo-1580894908361-967195033215?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1558002038-1055907df827?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1535905496755-26ae35d0ae54?auto=format&fit=crop&w=1200&q=80',
            ],
            'motion-detectors' => [
                'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=1200&q=80',
            ],
            'smart-locks' => [
                'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=1200&q=80',
            ],
            'digital-doorbells' => [
                'https://images.unsplash.com/photo-1558002038-1055907df827?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1580894908361-967195033215?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1558002038-1055907df827?auto=format&fit=crop&w=1200&q=80',
            ],
            'alarm-systems' => [
                'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?auto=format&fit=crop&w=1200&q=80',
            ],
            'biometric-access-controls' => [
                'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=1200&q=80',
            ],
            'automation-hubs' => [
                'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?auto=format&fit=crop&w=1200&q=80',
            ],
        ];

        $images = $imageMap[$categorySlug] ?? $imageMap['cctv-cameras'];

        foreach ($images as $index => $url) {
            ProductMedia::create([
                'product_id' => $product->id,
                'type' => 'image',
                'file_path' => $url,
                'thumbnail_path' => $url,
                'alt_text' => "{$product->name} image ".($index + 1),
                'position' => $index,
                'is_primary' => $index === 0,
                'meta' => ['source' => 'unsplash'],
            ]);
        }
    }

    protected function seedSpecifications(Product $product, string $categorySlug = 'cctv-cameras'): void
    {
        $specsMap = [
            'cctv-cameras' => [
                ['group' => 'Video', 'name' => 'Resolution', 'value' => Arr::random(['1080p HD', '2K HD', '4K UHD'])],
                ['group' => 'Video', 'name' => 'Field of View', 'value' => Arr::random(['110°', '130°', '150°', '180°'])],
                ['group' => 'Night Vision', 'name' => 'Range', 'value' => Arr::random(['30ft', '50ft', '100ft'])],
                ['group' => 'Connectivity', 'name' => 'Wireless Protocol', 'value' => Arr::random(['Wi-Fi 6', 'Wi-Fi 5', 'Ethernet'])],
                ['group' => 'Durability', 'name' => 'Weather Rating', 'value' => Arr::random(['IP65', 'IP66', 'IP67'])],
                ['group' => 'Power', 'name' => 'Power Source', 'value' => Arr::random(['Wired', 'Battery', 'Solar + Battery'])],
            ],
            'motion-detectors' => [
                ['group' => 'Detection', 'name' => 'Range', 'value' => Arr::random(['20ft', '30ft', '40ft'])],
                ['group' => 'Detection', 'name' => 'Field of View', 'value' => Arr::random(['90°', '110°', '120°'])],
                ['group' => 'Power', 'name' => 'Battery Life', 'value' => Arr::random(['1 year', '2 years', '3 years'])],
                ['group' => 'Connectivity', 'name' => 'Protocol', 'value' => Arr::random(['Zigbee', 'Z-Wave', 'Wi-Fi'])],
                ['group' => 'Features', 'name' => 'Pet Immunity', 'value' => Arr::random(['Up to 50lbs', 'Up to 80lbs', 'None'])],
            ],
            'smart-locks' => [
                ['group' => 'Access', 'name' => 'Entry Methods', 'value' => Arr::random(['Fingerprint + PIN', 'Fingerprint + App', 'PIN + App + Key'])],
                ['group' => 'Capacity', 'name' => 'User Capacity', 'value' => Arr::random(['50 users', '100 users', '250 users'])],
                ['group' => 'Power', 'name' => 'Battery Type', 'value' => Arr::random(['4x AA', 'Rechargeable', 'Hardwired'])],
                ['group' => 'Connectivity', 'name' => 'Wireless', 'value' => Arr::random(['Wi-Fi', 'Zigbee', 'Z-Wave', 'Bluetooth'])],
                ['group' => 'Compatibility', 'name' => 'Door Type', 'value' => Arr::random(['Standard Deadbolt', 'Mortise Lock', 'Both'])],
            ],
            'digital-doorbells' => [
                ['group' => 'Video', 'name' => 'Resolution', 'value' => Arr::random(['1080p HD', '2K HD', '4K UHD'])],
                ['group' => 'Video', 'name' => 'Field of View', 'value' => Arr::random(['160°', '180°', '200°'])],
                ['group' => 'Power', 'name' => 'Power Source', 'value' => Arr::random(['Wired', 'Battery', 'Both'])],
                ['group' => 'Features', 'name' => 'Two-Way Audio', 'value' => 'Yes'],
                ['group' => 'Features', 'name' => 'Package Detection', 'value' => Arr::random(['Yes', 'No'])],
            ],
            'alarm-systems' => [
                ['group' => 'Components', 'name' => 'Sensors Included', 'value' => Arr::random(['5 sensors', '8 sensors', '10 sensors'])],
                ['group' => 'Connectivity', 'name' => 'Connection', 'value' => Arr::random(['Wireless', 'Wired', 'Hybrid'])],
                ['group' => 'Monitoring', 'name' => 'Monitoring', 'value' => Arr::random(['Self-Monitored', 'Professional', 'Both'])],
                ['group' => 'Power', 'name' => 'Backup Battery', 'value' => Arr::random(['8 hours', '24 hours', '48 hours'])],
            ],
            'biometric-access-controls' => [
                ['group' => 'Biometric', 'name' => 'Recognition Type', 'value' => Arr::random(['Fingerprint', 'Facial', 'Iris', 'Multi-Modal'])],
                ['group' => 'Capacity', 'name' => 'User Capacity', 'value' => Arr::random(['100 users', '500 users', '1000 users'])],
                ['group' => 'Security', 'name' => 'Accuracy', 'value' => Arr::random(['99.5%', '99.7%', '99.9%'])],
                ['group' => 'Features', 'name' => 'Anti-Spoofing', 'value' => 'Yes'],
            ],
            'automation-hubs' => [
                ['group' => 'Connectivity', 'name' => 'Supported Protocols', 'value' => Arr::random(['Zigbee + Z-Wave', 'Zigbee + Wi-Fi', 'All Protocols'])],
                ['group' => 'Capacity', 'name' => 'Device Limit', 'value' => Arr::random(['50 devices', '100 devices', 'Unlimited'])],
                ['group' => 'Features', 'name' => 'Voice Control', 'value' => Arr::random(['Alexa', 'Google', 'Both'])],
                ['group' => 'Power', 'name' => 'Power Source', 'value' => 'AC Adapter'],
            ],
        ];

        $specs = $specsMap[$categorySlug] ?? $specsMap['cctv-cameras'];

        foreach ($specs as $index => $spec) {
            ProductSpecification::create([
                'product_id' => $product->id,
                'group' => $spec['group'],
                'name' => $spec['name'],
                'value' => $spec['value'],
                'display_order' => $index,
            ]);
        }
    }

    protected function seedInventory(Product $product): void
    {
        InventoryItem::create([
            'product_id' => $product->id,
            'sku' => $product->sku,
            'quantity_on_hand' => rand(25, 150),
            'quantity_reserved' => rand(0, 10),
            'reorder_level' => 20,
            'reorder_quantity' => 40,
            'warehouse_location' => 'SAFE-'.rand(100, 999),
            'is_trackable' => true,
            'last_restocked_at' => now()->subDays(rand(1, 30)),
        ]);
    }
}
