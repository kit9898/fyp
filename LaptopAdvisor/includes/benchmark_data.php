<?php
// benchmark_data.php - CPU and GPU Benchmark Database

/**
 * Get CPU benchmark score based on PassMark CPU Benchmark
 * @param string $cpu_name The CPU model name
 * @return int Benchmark score
 */
function getCPUBenchmarkScore($cpu_name) {
    $cpu = strtolower($cpu_name);
    
    // PassMark CPU Benchmark Scores (approximate)
    $cpu_benchmarks = [
        // Intel Core Ultra (Series 1 & 2)
        'ultra 9 185h' => 48000,
        'ultra 7 165h' => 36000,
        'ultra 7 155h' => 32000,
        'ultra 5 135h' => 24000,
        'ultra 5 125h' => 22000,

        // Intel Core i9 (13th/14th Gen)
        'i9-14900k' => 56000,
        'i9-14900hx' => 48000,
        'i9-13900k' => 54000,
        'i9-13900hx' => 46000,
        'i9-13980hx' => 47000,
        'i9-12900k' => 41000,
        'i9-12900hx' => 38000,
        'i9-12900h' => 35000,
        
        // Intel Core i7 (13th/14th Gen)
        'i7-14700k' => 48000,
        'i7-14700hx' => 42000,
        'i7-13700k' => 45000,
        'i7-13700hx' => 40000,
        'i7-13700h' => 38000,
        'i7-12700h' => 28000,
        'i7-11800h' => 22000,
        'i7-1260p' => 24000,
        
        // Intel Core i5 (13th/14th Gen)
        'i5-14600k' => 38000,
        'i5-13600k' => 36000,
        'i5-13500h' => 26000,
        'i5-12500h' => 22000,
        'i5-12450h' => 18000,
        'i5-1240p' => 20000,
        
        // AMD Ryzen 9
        'ryzen 9 8945hs' => 40000,
        'ryzen 9 7950x' => 60000,
        'ryzen 9 7945hx' => 52000,
        'ryzen 9 7940hs' => 38000,
        'ryzen 9 6900hx' => 31000,
        'ryzen 9 5900hx' => 28000,
        'ryzen 9 5900x' => 39000,
        
        // AMD Ryzen 7
        'ryzen 7 8845hs' => 34000,
        'ryzen 7 7800x3d' => 42000,
        'ryzen 7 7840hs' => 32000,
        'ryzen 7 7735hs' => 30000,
        'ryzen 7 6800h' => 26000,
        'ryzen 7 5800h' => 24000,
        'ryzen 7 5800x' => 28000,
        
        // AMD Ryzen 5
        'ryzen 5 7600x' => 35000,
        'ryzen 5 7640hs' => 26000,
        'ryzen 5 5600h' => 19000,
        'ryzen 5 5600x' => 22000,
        'ryzen 5 5500u' => 12000,
        
        // Apple Silicon
        'm3 max' => 42000,
        'm3 pro' => 35000,
        'm3' => 28000,
        'm2 max' => 38000,
        'm2 pro' => 32000,
        'm2' => 26000,
        'm1 max' => 34000,
        'm1 pro' => 28000,
        'm1' => 23000,
        
        // Budget CPUs
        'i3-13100' => 14000,
        'i3-12100' => 13000,
        'ryzen 3 5300u' => 9000,
        'ryzen 3 3250u' => 5000,
        'celeron n5100' => 4000,
        'celeron n4020' => 2000,
        'pentium gold' => 5000,
        'pentium silver' => 3000,
    ];
    
    // Try to match the CPU name with benchmark data
    foreach ($cpu_benchmarks as $model => $score) {
        if (stripos($cpu, $model) !== false) {
            return $score;
        }
    }
    
    // Fallback to heuristic if not found
    return estimateCPUScore($cpu);
}

/**
 * Get GPU benchmark score based on 3DMark Time Spy Graphics Score
 * @param string $gpu_name The GPU model name
 * @return int Benchmark score
 */
function getGPUBenchmarkScore($gpu_name) {
    $gpu = strtolower($gpu_name);
    
    // 3DMark Time Spy Graphics Scores (approximate)
    $gpu_benchmarks = [
        // NVIDIA RTX 40-series
        'rtx 4090' => 35000,
        'rtx 4080 super' => 30000,
        'rtx 4080' => 28000,
        'rtx 4070 ti super' => 24000,
        'rtx 4070 ti' => 22000,
        'rtx 4070 super' => 20000,
        'rtx 4070' => 18000,
        'rtx 4060 ti' => 14000,
        'rtx 4060' => 11000,
        'rtx 4050' => 8000,
        
        // NVIDIA RTX 30-series (Desktop)
        'rtx 3090 ti' => 24000,
        'rtx 3090' => 22000,
        'rtx 3080 ti' => 19000,
        'rtx 3080' => 17000,
        'rtx 3070 ti' => 14000,
        'rtx 3070' => 13000,
        'rtx 3060 ti' => 11000,
        'rtx 3060' => 8500,
        'rtx 3050 ti' => 6000,
        'rtx 3050' => 5000,
        
        // NVIDIA GTX Series
        'gtx 1660 ti' => 6000,
        'gtx 1660' => 5500,
        'gtx 1650 ti' => 4000,
        'gtx 1650' => 3500,
        'gtx 1630' => 2500,
        
        // AMD Radeon RX 7000
        'rx 7900 xtx' => 32000,
        'rx 7900 xt' => 28000,
        'rx 7800 xt' => 20000,
        'rx 7700 xt' => 16000,
        'rx 7600' => 12000,
        
        // AMD Radeon RX 6000
        'rx 6900 xt' => 21000,
        'rx 6800 xt' => 19000,
        'rx 6800' => 17000,
        'rx 6700 xt' => 13000,
        'rx 6600 xt' => 10000,
        'rx 6600' => 8500,
        'rx 6500 xt' => 4000,
        
        // Integrated Graphics
        'iris xe' => 1800,
        'iris plus' => 1200,
        'radeon 780m' => 2500,
        'radeon 680m' => 2000,
        'radeon 660m' => 1500,
        'vega 8' => 1200,
        'vega 7' => 1000,
        'uhd 770' => 900,
        'uhd 730' => 700,
        'uhd 620' => 600,
        'intel hd 620' => 600,
        'intel hd 520' => 450,
        'intel graphics' => 500,
    ];
    
    // Try to match the GPU name with benchmark data
    foreach ($gpu_benchmarks as $model => $score) {
        if (stripos($gpu, $model) !== false) {
            return $score;
        }
    }
    
    // Fallback to heuristic if not found
    return estimateGPUScore($gpu);
}

/**
 * Fallback CPU estimation when exact model not found
 * @param string $cpu Lowercase CPU name
 * @return int Estimated score
 */
function estimateCPUScore($cpu) {
    if (strpos($cpu, 'ultra 9') !== false) return 45000;
    if (strpos($cpu, 'ultra 7') !== false) return 32000;
    if (strpos($cpu, 'ultra 5') !== false) return 22000;
    
    if (strpos($cpu, 'i9') !== false || strpos($cpu, 'ryzen 9') !== false) {
        if (strpos($cpu, '14') !== false || strpos($cpu, '13') !== false) return 45000;
        return 40000;
    }
    if (strpos($cpu, 'i7') !== false || strpos($cpu, 'ryzen 7') !== false) {
        if (strpos($cpu, '14') !== false || strpos($cpu, '13') !== false) return 38000;
        return 28000;
    }
    if (strpos($cpu, 'i5') !== false || strpos($cpu, 'ryzen 5') !== false) {
        if (strpos($cpu, '14') !== false || strpos($cpu, '13') !== false) return 30000;
        return 20000;
    }
    if (strpos($cpu, 'i3') !== false || strpos($cpu, 'ryzen 3') !== false) return 12000;
    if (strpos($cpu, 'm3') !== false) return 35000;
    if (strpos($cpu, 'm2') !== false) return 30000;
    if (strpos($cpu, 'm1') !== false) return 26000;
    if (strpos($cpu, 'celeron') !== false) return 3000;
    if (strpos($cpu, 'pentium') !== false) return 5000;
    return 15000; // Default
}

/**
 * Fallback GPU estimation when exact model not found
 * @param string $gpu Lowercase GPU name
 * @return int Estimated score
 */
function estimateGPUScore($gpu) {
    if (strpos($gpu, 'rtx 40') !== false) return 15000;
    if (strpos($gpu, 'rtx 30') !== false) return 10000;
    if (strpos($gpu, 'gtx 16') !== false) return 5000;
    if (strpos($gpu, 'gtx') !== false) return 4000;
    if (strpos($gpu, 'rx 7') !== false) return 18000;
    if (strpos($gpu, 'rx 6') !== false) return 12000;
    if (strpos($gpu, 'iris') !== false) return 1500;
    if (strpos($gpu, 'radeon') !== false && strpos($gpu, 'integrated') !== false) return 1200;
    if (strpos($gpu, 'vega') !== false) return 1000;
    if (strpos($gpu, 'uhd') !== false) return 700;
    return 1000; // Default integrated
}

/**
 * Normalize CPU benchmark score to 1-10 scale
 * @param int $benchmark_score Raw benchmark score
 * @return int Score from 1-10
 */
function normalizeCPUScore($benchmark_score) {
    // Scale: 0-60000+ to 1-10
    if ($benchmark_score >= 50000) return 10;
    if ($benchmark_score >= 40000) return 9;
    if ($benchmark_score >= 30000) return 8;
    if ($benchmark_score >= 22000) return 7;
    if ($benchmark_score >= 16000) return 6;
    if ($benchmark_score >= 12000) return 5;
    if ($benchmark_score >= 8000) return 4;
    if ($benchmark_score >= 5000) return 3;
    if ($benchmark_score >= 3000) return 2;
    return 1;
}

/**
 * Normalize GPU benchmark score to 1-10 scale
 * @param int $benchmark_score Raw benchmark score
 * @return int Score from 1-10
 */
function normalizeGPUScore($benchmark_score) {
    // Scale: 0-35000+ to 1-10
    if ($benchmark_score >= 25000) return 10;
    if ($benchmark_score >= 18000) return 9;
    if ($benchmark_score >= 13000) return 8;
    if ($benchmark_score >= 9000) return 7;
    if ($benchmark_score >= 6000) return 6;
    if ($benchmark_score >= 4000) return 5;
    if ($benchmark_score >= 2000) return 4;
    if ($benchmark_score >= 1000) return 3;
    if ($benchmark_score >= 500) return 2;
    return 1;
}
?>
