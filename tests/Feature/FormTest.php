<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

test('can access form step1', function () {
    $response = $this->get('/form/step1');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Form/Step1')
        ->where('step', 1)
        ->where('totalSteps', 4)
    );
});

test('can submit form step1 with valid data', function () {
    $formData = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'company_name' => 'Acme Corp',
        'website_url' => 'https://acme.com',
    ];

    $response = $this->post('/form/step1', $formData);

    $response->assertRedirect('/form/step2');
    expect(session('form_data.step1'))->toBe($formData);
});

test('validates required fields in step1', function () {
    $response = $this->post('/form/step1', []);

    $response->assertSessionHasErrors(['name', 'email', 'company_name']);
});

test('validates name minimum length', function () {
    $formData = [
        'name' => 'Jo', // Less than 3 characters
        'email' => 'john@example.com',
        'company_name' => 'Acme Corp',
    ];

    $response = $this->post('/form/step1', $formData);

    $response->assertSessionHasErrors(['name']);
    $response->assertSessionHasErrors(['name' => 'The name must be at least 3 characters.']);
});

test('validates company name minimum length', function () {
    $formData = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'company_name' => 'Ac', // Less than 3 characters
    ];

    $response = $this->post('/form/step1', $formData);

    $response->assertSessionHasErrors(['company_name']);
    $response->assertSessionHasErrors(['company_name' => 'The company name must be at least 3 characters.']);
});

test('validates email format in step1', function () {
    $formData = [
        'name' => 'John Doe',
        'email' => 'invalid-email',
        'company_name' => 'Acme Corp',
    ];

    $response = $this->post('/form/step1', $formData);

    $response->assertSessionHasErrors(['email']);
    $response->assertSessionHasErrors(['email' => 'Please enter a valid email address.']);
});

test('validates website url format in step1', function () {
    $formData = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'company_name' => 'Acme Corp',
        'website_url' => 'not-a-url',
    ];

    $response = $this->post('/form/step1', $formData);

    $response->assertSessionHasErrors(['website_url']);
    $response->assertSessionHasErrors(['website_url' => 'Please enter a valid URL.']);
});

test('accepts valid website url in step1', function () {
    $formData = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'company_name' => 'Acme Corp',
        'website_url' => 'https://acme.com',
    ];

    $response = $this->post('/form/step1', $formData);

    $response->assertRedirect('/form/step2');
    expect(session('form_data.step1'))->toBe($formData);
});

test('accepts optional website url in step1', function () {
    $formData = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'company_name' => 'Acme Corp',
        // website_url is optional
    ];

    $response = $this->post('/form/step1', $formData);

    $response->assertRedirect('/form/step2');
    expect(session('form_data.step1'))->toBe($formData);
});

test('validates exact minimum length for name', function () {
    $formData = [
        'name' => 'Jon', // Exactly 3 characters
        'email' => 'john@example.com',
        'company_name' => 'Acme Corp',
    ];

    $response = $this->post('/form/step1', $formData);

    $response->assertRedirect('/form/step2');
    expect(session('form_data.step1'))->toBe($formData);
});

test('validates exact minimum length for company name', function () {
    $formData = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'company_name' => 'ABC', // Exactly 3 characters
    ];

    $response = $this->post('/form/step1', $formData);

    $response->assertRedirect('/form/step2');
    expect(session('form_data.step1'))->toBe($formData);
});

// Step 2 Tests
test('can access form step2', function () {
    // Add step 1 data to session first
    $this->post('/form/step1', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'company_name' => 'Acme Corp',
    ]);

    $response = $this->get('/form/step2');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Form/Step2')
        ->where('step', 2)
        ->where('totalSteps', 4)
        ->has('websiteTypes')
    );
});

test('can submit form step2 with valid data', function () {
    $formData = [
        'website_type' => 'ecommerce',
    ];

    $response = $this->post('/form/step2', $formData);

    $response->assertRedirect('/form/step3');
    expect(session('form_data.step2'))->toBe($formData);
});

test('validates required website type in step2', function () {
    $response = $this->post('/form/step2', []);

    $response->assertSessionHasErrors(['website_type']);
    $response->assertSessionHasErrors(['website_type' => 'Please select a website type.']);
});

test('validates website type values in step2', function () {
    $formData = [
        'website_type' => 'invalid-type',
    ];

    $response = $this->post('/form/step2', $formData);

    $response->assertSessionHasErrors(['website_type']);
    $response->assertSessionHasErrors(['website_type' => 'Please select a valid website type.']);
});

test('accepts all valid website types in step2', function () {
    $validTypes = ['ecommerce', 'blog', 'corporate', 'portfolio', 'other'];

    foreach ($validTypes as $type) {
        $formData = [
            'website_type' => $type,
        ];

        $response = $this->post('/form/step2', $formData);

        $response->assertRedirect('/form/step3');
        expect(session('form_data.step2'))->toBe($formData);
    }
});

test('can access form step3', function () {
    // First, submit step1 and step2 to have the required data
    $this->post('/form/step1', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'company_name' => 'Acme Corp',
    ]);

    $this->post('/form/step2', [
        'website_type' => 'ecommerce',
    ]);

    $response = $this->get('/form/step3');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Form/Step3')
        ->where('step', 3)
        ->where('totalSteps', 4)
        ->has('websiteType')
        ->has('platforms')
    );
});

test('can access form step4', function () {
    // Add all required step data to session first
    $this->post('/form/step1', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'company_name' => 'Acme Corp',
    ]);

    $this->post('/form/step2', [
        'website_type' => 'ecommerce',
    ]);

    $this->post('/form/step3', [
        'platform' => 'shopify',
    ]);

    $response = $this->get('/form/step4');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Form/Step4')
        ->where('step', 4)
        ->where('totalSteps', 4)
        ->has('formData')
    );
});

test('redirects to step1 if no step2 data exists', function () {
    $response = $this->get('/form/step3');

    $response->assertRedirect('/form/step1');
});

test('shows ecommerce platforms when ecommerce website type is selected', function () {
    // First, submit step1 and step2 with ecommerce type
    $this->post('/form/step1', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'company_name' => 'Acme Corp',
    ]);

    $this->post('/form/step2', [
        'website_type' => 'ecommerce',
    ]);

    $response = $this->get('/form/step3');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->where('websiteType', 'ecommerce')
        ->where('platforms', [
            'shopify' => 'Shopify',
            'woocommerce' => 'WooCommerce',
            'bigcommerce' => 'BigCommerce',
            'magento' => 'Magento',
            'custom_solution' => 'Custom Solution',
            'other' => 'Other',
        ])
    );
});

test('shows general platforms when non-ecommerce website type is selected', function () {
    // First, submit step1 and step2 with blog type
    $this->post('/form/step1', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'company_name' => 'Acme Corp',
    ]);

    $this->post('/form/step2', [
        'website_type' => 'blog',
    ]);

    $response = $this->get('/form/step3');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->where('websiteType', 'blog')
        ->where('platforms', [
            'wordpress' => 'WordPress',
            'squarespace' => 'Squarespace',
            'webflow' => 'Webflow',
            'custom_developed' => 'Custom Developed',
            'other' => 'Other',
        ])
    );
});

test('can submit form step3 with valid ecommerce platform', function () {
    // First, submit step1 and step2
    $this->post('/form/step1', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'company_name' => 'Acme Corp',
    ]);

    $this->post('/form/step2', [
        'website_type' => 'ecommerce',
    ]);

    $formData = [
        'platform' => 'shopify',
    ];

    $response = $this->post('/form/step3', $formData);

    $response->assertRedirect('/form/step4');
    expect(session('form_data.step3'))->toBe($formData);
});

test('can submit form step3 with valid general platform', function () {
    // First, submit step1 and step2
    $this->post('/form/step1', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'company_name' => 'Acme Corp',
    ]);

    $this->post('/form/step2', [
        'website_type' => 'blog',
    ]);

    $formData = [
        'platform' => 'wordpress',
    ];

    $response = $this->post('/form/step3', $formData);

    $response->assertRedirect('/form/step4');
    expect(session('form_data.step3'))->toBe($formData);
});

test('validates required platform in step3', function () {
    // First, submit step1 and step2
    $this->post('/form/step1', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'company_name' => 'Acme Corp',
    ]);

    $this->post('/form/step2', [
        'website_type' => 'ecommerce',
    ]);

    $response = $this->post('/form/step3', []);

    $response->assertSessionHasErrors(['platform']);
    $response->assertSessionHasErrors(['platform' => 'Please select a platform.']);
});

test('validates platform values for ecommerce website type', function () {
    // First, submit step1 and step2
    $this->post('/form/step1', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'company_name' => 'Acme Corp',
    ]);

    $this->post('/form/step2', [
        'website_type' => 'ecommerce',
    ]);

    $formData = [
        'platform' => 'invalid-platform',
    ];

    $response = $this->post('/form/step3', $formData);

    $response->assertSessionHasErrors(['platform']);
    $response->assertSessionHasErrors(['platform' => 'Please select a valid platform.']);
});

test('validates platform values for general website type', function () {
    // First, submit step1 and step2
    $this->post('/form/step1', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'company_name' => 'Acme Corp',
    ]);

    $this->post('/form/step2', [
        'website_type' => 'blog',
    ]);

    $formData = [
        'platform' => 'shopify', // Invalid for blog type
    ];

    $response = $this->post('/form/step3', $formData);

    $response->assertSessionHasErrors(['platform']);
    $response->assertSessionHasErrors(['platform' => 'Please select a valid platform.']);
});

test('accepts all valid ecommerce platforms', function () {
    // First, submit step1 and step2
    $this->post('/form/step1', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'company_name' => 'Acme Corp',
    ]);

    $this->post('/form/step2', [
        'website_type' => 'ecommerce',
    ]);

    $validPlatforms = ['shopify', 'woocommerce', 'bigcommerce', 'magento', 'custom_solution', 'other'];

    foreach ($validPlatforms as $platform) {
        $formData = [
            'platform' => $platform,
        ];

        $response = $this->post('/form/step3', $formData);

        $response->assertRedirect('/form/step4');
        expect(session('form_data.step3'))->toBe($formData);
    }
});

test('accepts all valid general platforms', function () {
    // First, submit step1 and step2
    $this->post('/form/step1', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'company_name' => 'Acme Corp',
    ]);

    $this->post('/form/step2', [
        'website_type' => 'blog',
    ]);

    $validPlatforms = ['wordpress', 'squarespace', 'webflow', 'custom_developed', 'other'];

    foreach ($validPlatforms as $platform) {
        $formData = [
            'platform' => $platform,
        ];

        $response = $this->post('/form/step3', $formData);

        $response->assertRedirect('/form/step4');
        expect(session('form_data.step3'))->toBe($formData);
    }
});

// Step 4 Tests
test('can access form step4 with complete form data', function () {
    // Submit all previous steps
    $this->post('/form/step1', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'company_name' => 'Acme Corp',
    ]);

    $this->post('/form/step2', [
        'website_type' => 'ecommerce',
    ]);

    $this->post('/form/step3', [
        'platform' => 'shopify',
    ]);

    $response = $this->get('/form/step4');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Form/Step4')
        ->where('step', 4)
        ->where('totalSteps', 4)
        ->has('formData')
    );
});

test('can submit complete form successfully', function () {
    // Submit all previous steps
    $this->post('/form/step1', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'company_name' => 'Acme Corp',
    ]);

    $this->post('/form/step2', [
        'website_type' => 'ecommerce',
    ]);

    $this->post('/form/step3', [
        'platform' => 'shopify',
    ]);

    $response = $this->post('/form/submit');

    $response->assertRedirect('/form');
    $response->assertSessionHas('success');
    // Check that success message contains the base message (notification text may vary)
    $this->assertStringContainsString('Your information has been submitted successfully!', session('success'));
});

test('prevents email duplication on form submission', function () {
    // Create a user with the same email first
    \App\Models\User::factory()->create([
        'email' => 'john@example.com',
    ]);

    // Submit all previous steps
    $this->post('/form/step1', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'company_name' => 'Acme Corp',
    ]);

    $this->post('/form/step2', [
        'website_type' => 'ecommerce',
    ]);

    $this->post('/form/step3', [
        'platform' => 'shopify',
    ]);

    $response = $this->post('/form/submit');

    $response->assertRedirect('/form/step1');
    $response->assertSessionHasErrors(['email' => 'An account with this email address already exists. Please use a different email or contact support if you need assistance.']);
});

test('displays success message after successful submission', function () {
    // Submit all previous steps
    $this->post('/form/step1', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'company_name' => 'Acme Corp',
    ]);

    $this->post('/form/step2', [
        'website_type' => 'ecommerce',
    ]);

    $this->post('/form/step3', [
        'platform' => 'shopify',
    ]);

    $this->post('/form/submit');

    $response = $this->get('/form');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->where('success', function ($success) {
            return str_contains($success, 'Your information has been submitted successfully!');
        })
    );
});

test('displays error message when email already exists', function () {
    // Create a user with the same email first
    \App\Models\User::factory()->create([
        'email' => 'john@example.com',
    ]);

    // Submit all previous steps
    $this->post('/form/step1', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'company_name' => 'Acme Corp',
    ]);

    $this->post('/form/step2', [
        'website_type' => 'ecommerce',
    ]);

    $this->post('/form/step3', [
        'platform' => 'shopify',
    ]);

    $this->post('/form/submit');

    $response = $this->get('/form/step1');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->has('errors.email')
    );
});

test('populates form with old input when submission fails', function () {
    // Create a user with the same email first
    \App\Models\User::factory()->create([
        'email' => 'john@example.com',
    ]);

    $formData = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'company_name' => 'Acme Corp',
        'website_url' => 'https://example.com',
    ];

    // Submit all previous steps
    $this->post('/form/step1', $formData);

    $this->post('/form/step2', [
        'website_type' => 'ecommerce',
    ]);

    $this->post('/form/step3', [
        'platform' => 'shopify',
    ]);

    $this->post('/form/submit');

    $response = $this->get('/form/step1');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->where('old', $formData)
    );
});
