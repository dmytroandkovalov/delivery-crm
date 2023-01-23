<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestCase;

abstract class BaseFeatureTest extends TestCase
{
    use CreatesApplication;
    use RefreshDatabase;
}
