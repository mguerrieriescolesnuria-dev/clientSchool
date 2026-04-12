#!/usr/bin/env php
<?php
/**
 * Complete verification script for clientSchool API
 * Run: php verify-all.php
 * 
 * This script tests all API endpoints and shows a summary
 */

echo "\n" . str_repeat("=", 70) . "\n";
echo "✅ VERIFICACIÓN COMPLETA - clientSchool API v1.0\n";
echo str_repeat("=", 70) . "\n\n";

// Define test cases
$tests = [
    'Basic Endpoints' => [
        ['name' => 'Root', 'uri' => '/', 'method' => 'GET', 'expect' => 200],
        ['name' => 'Health Check', 'uri' => '/api/health', 'method' => 'GET', 'expect' => 200],
    ],
    'Teacher Endpoints' => [
        ['name' => 'List Teachers', 'uri' => '/api/teachers', 'method' => 'GET', 'expect' => 200],
        ['name' => 'Get Teacher (ID: 1)', 'uri' => '/api/teachers/1', 'method' => 'GET', 'expect' => 200],
        ['name' => 'Get Teacher (Invalid ID)', 'uri' => '/api/teachers/999', 'method' => 'GET', 'expect' => 404],
    ],
    'Student Endpoints' => [
        ['name' => 'List Students', 'uri' => '/api/students', 'method' => 'GET', 'expect' => 200],
        ['name' => 'Get Student (ID: 1)', 'uri' => '/api/students/1', 'method' => 'GET', 'expect' => 200],
        ['name' => 'Get Student (Invalid ID)', 'uri' => '/api/students/999', 'method' => 'GET', 'expect' => 404],
    ],
    'Subject Endpoints' => [
        ['name' => 'List Subjects', 'uri' => '/api/subjects', 'method' => 'GET', 'expect' => 200],
        ['name' => 'Get Subject (ID: 1)', 'uri' => '/api/subjects/1', 'method' => 'GET', 'expect' => 200],
        ['name' => 'Get Subject (Invalid ID)', 'uri' => '/api/subjects/999', 'method' => 'GET', 'expect' => 404],
    ],
    'Error Handling' => [
        ['name' => 'Invalid Route', 'uri' => '/api/invalid', 'method' => 'GET', 'expect' => 404],
    ],
];

$passed = 0;
$failed = 0;
$total = 0;
$results = [];

// Run each test
foreach ($tests as $category => $categoryTests) {
    echo "📁 $category\n";
    echo str_repeat("-", 70) . "\n";
    
    foreach ($categoryTests as $test) {
        $total++;
        
        // Build command
        $cmd = sprintf(
            'REQUEST_METHOD=%s REQUEST_URI=%s php index.php 2>&1',
            escapeshellarg($test['method']),
            escapeshellarg($test['uri'])
        );
        
        // Execute test
        $output = shell_exec($cmd);
        $data = json_decode($output, true);
        $status = $data['status'] ?? 500;
        
        // Check result
        $pass = ($status === $test['expect']);
        
        if ($pass) {
            echo "  ✅ PASS  | {$test['name']}\n";
            $passed++;
        } else {
            echo "  ❌ FAIL  | {$test['name']} (Expected: {$test['expect']}, Got: {$status})\n";
            $failed++;
        }
        
        $results[] = [
            'category' => $category,
            'name' => $test['name'],
            'uri' => $test['uri'],
            'expected' => $test['expect'],
            'actual' => $status,
            'pass' => $pass
        ];
    }
    echo "\n";
}

// Summary
echo str_repeat("=", 70) . "\n";
echo "📊 RESUMEN\n";
echo str_repeat("=", 70) . "\n";
printf("Total Pruebas:  %d\n", $total);
printf("✅ Pasadas:     %d\n", $passed);
printf("❌ Fallidas:    %d\n", $failed);
printf("📈 Porcentaje:  %.1f%%\n", ($passed / $total) * 100);
echo str_repeat("=", 70) . "\n";

if ($failed === 0) {
    echo "\n🎉 ¡TODAS LAS PRUEBAS PASARON!\n";
    echo "✅ El proyecto estructura está correctamente\n";
    echo "✅ Todos los endpoints responden correctamente\n";
    echo "✅ El manejo de errores funciona\n\n";
    exit(0);
} else {
    echo "\n⚠️  Algunas pruebas fallaron.\n";
    echo "   Revisa los errores arriba y consulta TROUBLESHOOTING.md\n\n";
    exit(1);
}
