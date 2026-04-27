<?php

namespace Tests\Unit;

use App\Support\SchoolApi;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function test_subject_fields_are_defined(): void
    {
        $this->assertSame(['name', 'course', 'teacher'], SchoolApi::fieldsFor('subjects'));
    }
}
