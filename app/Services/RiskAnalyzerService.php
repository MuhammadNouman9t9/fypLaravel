<?php

namespace App\Services;

use App\Models\Product;
use App\Models\RiskAssessment;

class RiskAnalyzerService
{
    public function analyze(array $input): RiskAssessment
    {
        // Check if Python API is configured
        $pythonApiUrl = config('services.safenest.python_api.url');
        if ($pythonApiUrl) {
            return $this->analyzeWithPython($input);
        }

        // Use PHP-based implementation
        return $this->analyzeWithPHP($input);
    }

    protected function analyzeWithPython(array $input): RiskAssessment
    {
        $apiUrl = config('services.safenest.python_api.url');
        $apiKey = config('services.safenest.python_api.key');

        if (! $apiUrl || ! $apiKey) {
            \Illuminate\Support\Facades\Log::error('Python API URL or key not configured');

            throw new \Exception('Python API not configured. Please set SAFENEST_PYTHON_API_URL and SAFENEST_PYTHON_API_KEY in .env');
        }

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
            ])->timeout(10)->post("{$apiUrl}/risk-analyzer", [
                'input' => $input,
                'user_id' => auth()->id(),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (! isset($data['score']) || ! isset($data['risk_level'])) {
                    throw new \Exception('Invalid response from Python API: missing score or risk_level');
                }

                return RiskAssessment::create([
                    'user_id' => auth()->id(),
                    'session_id' => session()->getId(),
                    'property_type' => $input['property_type'] ?? null,
                    'property_size' => (int) ($input['property_size'] ?? 0),
                    'occupancy_pattern' => $input['occupancy_pattern'] ?? 'unknown',
                    'neighborhood_profile' => $input['neighborhood_profile'] ?? 'unknown',
                    'score' => $data['score'],
                    'risk_level' => $data['risk_level'],
                    'recommendations' => $data['recommendations'] ?? [],
                    'analysis' => $data['analysis'] ?? [],
                    'input_payload' => $input,
                    'analyzed_at' => now(),
                ]);
            }

            throw new \Exception('Python API returned error: '.$response->body());
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Python risk analyzer API error: '.$e->getMessage());

            throw $e;
        }
    }

    protected function analyzeWithPHP(array $input): RiskAssessment
    {
        $propertyType = $input['property_type'] ?? null;
        $propertySize = (int) ($input['property_size'] ?? 0);
        $occupancyPattern = $input['occupancy_pattern'] ?? 'unknown';
        $neighborhoodProfile = $input['neighborhood_profile'] ?? 'unknown';
        $entryPoints = (int) ($input['entry_points'] ?? 0);
        $exitPoints = (int) ($input['exit_points'] ?? 0);
        $hasSecuritySystem = (bool) ($input['has_security_system'] ?? false);
        $previousIncidents = (bool) ($input['previous_incidents'] ?? false);

        // Calculate base score (0-100, higher = more secure)
        $score = 50; // Start with neutral score

        // Property Type Factors
        $score += $this->getPropertyTypeScore($propertyType);

        // Property Size Factors
        $score += $this->getPropertySizeScore($propertySize);

        // Occupancy Pattern
        $score += $this->getOccupancyScore($occupancyPattern);

        // Neighborhood Profile
        $score += $this->getNeighborhoodScore($neighborhoodProfile);

        // Entry/Exit Points
        $score -= $this->getEntryExitPenalty($entryPoints, $exitPoints);

        // Security System Bonus
        if ($hasSecuritySystem) {
            $score += 15;
        }

        // Previous Incidents Penalty
        if ($previousIncidents) {
            $score -= 20;
        }

        // Normalize score to 0-100
        $score = max(0, min(100, $score));

        // Determine risk level
        $riskLevel = $this->getRiskLevel($score);

        // Generate recommendations
        $recommendations = $this->generateRecommendations($score, $riskLevel, $input);

        // Generate analysis details
        $analysis = $this->generateAnalysis($score, $input);

        $assessment = RiskAssessment::create([
            'user_id' => auth()->id(),
            'session_id' => session()->getId(),
            'property_type' => $propertyType,
            'property_size' => $propertySize,
            'occupancy_pattern' => $occupancyPattern,
            'neighborhood_profile' => $neighborhoodProfile,
            'score' => $score,
            'risk_level' => $riskLevel,
            'recommendations' => $recommendations,
            'analysis' => $analysis,
            'input_payload' => $input,
            'analyzed_at' => now(),
        ]);

        return $assessment;
    }

    protected function getPropertyTypeScore(?string $propertyType): int
    {
        return match ($propertyType) {
            'apartment', 'condo' => 10, // More secure due to building security
            'house', 'villa' => 5,
            'townhouse' => 8,
            'commercial' => 3,
            default => 0,
        };
    }

    protected function getPropertySizeScore(int $size): int
    {
        if ($size < 500) {
            return 5; // Smaller = easier to secure
        } elseif ($size < 1500) {
            return 0;
        } elseif ($size < 3000) {
            return -5;
        } else {
            return -10; // Larger = more vulnerable
        }
    }

    protected function getOccupancyScore(string $pattern): int
    {
        return match ($pattern) {
            'always_occupied' => 15,
            'mostly_occupied' => 10,
            'partially_occupied' => 5,
            'rarely_occupied' => -10,
            'vacant' => -20,
            default => 0,
        };
    }

    protected function getNeighborhoodScore(string $profile): int
    {
        return match ($profile) {
            'very_safe' => 15,
            'safe' => 10,
            'moderate' => 0,
            'risky' => -15,
            'high_crime' => -25,
            default => 0,
        };
    }

    protected function getEntryExitPenalty(int $entryPoints, int $exitPoints): int
    {
        $total = $entryPoints + $exitPoints;
        if ($total <= 2) {
            return 0;
        } elseif ($total <= 4) {
            return 5;
        } elseif ($total <= 6) {
            return 10;
        } else {
            return 15;
        }
    }

    protected function getRiskLevel(float $score): string
    {
        if ($score >= 80) {
            return 'low';
        } elseif ($score >= 60) {
            return 'moderate';
        } elseif ($score >= 40) {
            return 'high';
        } else {
            return 'critical';
        }
    }

    protected function generateRecommendations(float $score, string $riskLevel, array $input): array
    {
        $recommendations = [];

        if ($score < 40) {
            $recommendations[] = [
                'priority' => 'critical',
                'title' => 'Immediate Security Measures Required',
                'description' => 'Your property has critical security vulnerabilities. Consider installing a comprehensive security system immediately.',
                'products' => $this->getRecommendedProducts('critical'),
            ];
        }

        if ($input['entry_points'] > 2 || $input['exit_points'] > 2) {
            $recommendations[] = [
                'priority' => 'high',
                'title' => 'Multiple Entry Points Detected',
                'description' => "With {$input['entry_points']} entry points, consider installing cameras and sensors at each entry point.",
                'products' => $this->getRecommendedProducts('entry_points'),
            ];
        }

        if (($input['property_size'] ?? 0) > 2000) {
            $recommendations[] = [
                'priority' => 'medium',
                'title' => 'Large Property Coverage',
                'description' => 'For large properties, consider PTZ cameras and multiple sensor systems for comprehensive coverage.',
                'products' => $this->getRecommendedProducts('large_property'),
            ];
        }

        if (! ($input['has_security_system'] ?? false)) {
            $recommendations[] = [
                'priority' => 'high',
                'title' => 'No Security System Detected',
                'description' => 'Consider installing a basic security system including cameras, alarms, and smart locks.',
                'products' => $this->getRecommendedProducts('basic_system'),
            ];
        }

        if (($input['neighborhood_profile'] ?? '') === 'high_crime' || ($input['neighborhood_profile'] ?? '') === 'risky') {
            $recommendations[] = [
                'priority' => 'high',
                'title' => 'High-Risk Neighborhood',
                'description' => 'Due to neighborhood risk factors, consider advanced security measures including 24/7 monitoring systems.',
                'products' => $this->getRecommendedProducts('advanced'),
            ];
        }

        return $recommendations;
    }

    protected function getRecommendedProducts(string $category): array
    {
        $query = Product::query()->active();

        return match ($category) {
            'critical' => $query->where(function ($q) {
                $q->where('name', 'like', '%System%')
                    ->orWhere('name', 'like', '%Hub%')
                    ->orWhere('name', 'like', '%Alarm%');
            })->limit(5)->pluck('id')->toArray(),
            'entry_points' => $query->where(function ($q) {
                $q->where('name', 'like', '%Camera%')
                    ->orWhere('name', 'like', '%Sensor%')
                    ->orWhere('name', 'like', '%Lock%');
            })->limit(5)->pluck('id')->toArray(),
            'large_property' => $query->where(function ($q) {
                $q->where('name', 'like', '%PTZ%')
                    ->orWhere('name', 'like', '%Hub%')
                    ->orWhere('name', 'like', '%System%');
            })->limit(5)->pluck('id')->toArray(),
            'basic_system' => $query->where(function ($q) {
                $q->where('name', 'like', '%Camera%')
                    ->orWhere('name', 'like', '%Alarm%')
                    ->orWhere('name', 'like', '%Lock%');
            })->limit(5)->pluck('id')->toArray(),
            'advanced' => $query->where(function ($q) {
                $q->where('name', 'like', '%System%')
                    ->orWhere('name', 'like', '%Monitoring%')
                    ->orWhere('name', 'like', '%Advanced%');
            })->limit(5)->pluck('id')->toArray(),
            default => [],
        };
    }

    protected function generateAnalysis(float $score, array $input): array
    {
        return [
            'score_breakdown' => [
                'base_score' => 50,
                'property_type_adjustment' => $this->getPropertyTypeScore($input['property_type'] ?? null),
                'property_size_adjustment' => $this->getPropertySizeScore((int) ($input['property_size'] ?? 0)),
                'occupancy_adjustment' => $this->getOccupancyScore($input['occupancy_pattern'] ?? 'unknown'),
                'neighborhood_adjustment' => $this->getNeighborhoodScore($input['neighborhood_profile'] ?? 'unknown'),
                'entry_exit_penalty' => -$this->getEntryExitPenalty(
                    (int) ($input['entry_points'] ?? 0),
                    (int) ($input['exit_points'] ?? 0)
                ),
                'security_system_bonus' => ($input['has_security_system'] ?? false) ? 15 : 0,
                'incidents_penalty' => ($input['previous_incidents'] ?? false) ? -20 : 0,
            ],
            'risk_factors' => $this->identifyRiskFactors($input),
            'strengths' => $this->identifyStrengths($input),
        ];
    }

    protected function identifyRiskFactors(array $input): array
    {
        $factors = [];

        if (($input['entry_points'] ?? 0) > 4) {
            $factors[] = 'Multiple entry points increase vulnerability';
        }

        if (($input['property_size'] ?? 0) > 2000) {
            $factors[] = 'Large property size requires more comprehensive coverage';
        }

        if (($input['neighborhood_profile'] ?? '') === 'high_crime' || ($input['neighborhood_profile'] ?? '') === 'risky') {
            $factors[] = 'High-risk neighborhood requires enhanced security';
        }

        if (! ($input['has_security_system'] ?? false)) {
            $factors[] = 'No existing security system detected';
        }

        if (($input['previous_incidents'] ?? false)) {
            $factors[] = 'Previous security incidents indicate vulnerability';
        }

        if (($input['occupancy_pattern'] ?? '') === 'rarely_occupied' || ($input['occupancy_pattern'] ?? '') === 'vacant') {
            $factors[] = 'Low occupancy increases risk of break-ins';
        }

        return $factors;
    }

    protected function identifyStrengths(array $input): array
    {
        $strengths = [];

        if (($input['has_security_system'] ?? false)) {
            $strengths[] = 'Existing security system provides baseline protection';
        }

        if (($input['occupancy_pattern'] ?? '') === 'always_occupied') {
            $strengths[] = 'Constant occupancy acts as natural deterrent';
        }

        if (($input['neighborhood_profile'] ?? '') === 'very_safe' || ($input['neighborhood_profile'] ?? '') === 'safe') {
            $strengths[] = 'Safe neighborhood reduces overall risk';
        }

        if (($input['property_type'] ?? '') === 'apartment' || ($input['property_type'] ?? '') === 'condo') {
            $strengths[] = 'Building security provides additional protection';
        }

        return $strengths;
    }
}
