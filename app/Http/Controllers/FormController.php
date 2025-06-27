<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WebsiteDetail;
use App\Services\OneSignalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Stringable;

class FormController extends Controller
{
    private OneSignalService $oneSignalService;

    public function __construct(OneSignalService $oneSignalService)
    {
        $this->oneSignalService = $oneSignalService;
    }

    /**
     * Display the form index page
     */
    public function index()
    {
        return Inertia::render('Form/Index', [
            'success' => session('success'),
        ]);
    }

    /**
     * Display the form step 1 - Basic Information
     */
    public function step1()
    {
        return Inertia::render('Form/Step1', [
            'step' => 1,
            'totalSteps' => 4, // Assuming 4 steps total
            'errors' => session('errors') ? session('errors')->getBag('default')->getMessages() : [],
            'success' => session('success'),
            'old' => session('_old_input', []),
        ]);
    }

    /**
     * Store step 1 data
     */
    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|max:255',
            'company_name' => 'required|min:3',
            'website_url' => 'nullable|url|max:255',
        ], [
            'name.min' => 'The name must be at least 3 characters.',
            'email.email' => 'Please enter a valid email address.',
            'company_name.min' => 'The company name must be at least 3 characters.',
            'website_url.url' => 'Please enter a valid URL.',
        ]);

        $request->session()->put('form_data.step1', $validated);

        return redirect()->route('form.step2');
    }

    /**
     * Display the form step 2 - Website Details
     */
    public function step2()
    {
        if (!session('form_data.step1')) {
            return redirect()->route('form.step1');
        }

        return Inertia::render('Form/Step2', [
            'step' => 2,
            'totalSteps' => 4,
            'errors' => session('errors') ? session('errors')->getBag('default')->getMessages() : [],
            'websiteTypes' => [
                'ecommerce' => 'E-commerce',
                'blog' => 'Blog/Content Site',
                'corporate' => 'Corporate/Business Site',
                'portfolio' => 'Portfolio',
                'other' => 'Other',
            ],
        ]);
    }

    /**
     * Store step 2 data
     */
    public function storeStep2(Request $request)
    {
        $validated = $request->validate([
            'website_type' => 'required|in:ecommerce,blog,corporate,portfolio,other',
        ], [
            'website_type.required' => 'Please select a website type.',
            'website_type.in' => 'Please select a valid website type.',
        ]);

        $request->session()->put('form_data.step2', $validated);

        return redirect()->route('form.step3');
    }

    /**
     * Display the form step 3 - Platform Selection
     */
    public function step3()
    {
        if (!session('form_data.step1') || !session('form_data.step2')) {
            return redirect()->route('form.step1');
        }

        $websiteType = session('form_data.step2.website_type');
        $platforms = $this->getPlatformsForWebsiteType($websiteType);

        return Inertia::render('Form/Step3', [
            'step' => 3,
            'totalSteps' => 4,
            'errors' => session('errors') ? session('errors')->getBag('default')->getMessages() : [],
            'platforms' => $platforms,
            'websiteType' => $websiteType,
        ]);
    }

    /**
     * Store step 3 data
     */
    public function storeStep3(Request $request)
    {
        $websiteType = session('form_data.step2.website_type');
        $validPlatforms = array_keys($this->getPlatformsForWebsiteType($websiteType));

        $validated = $request->validate([
            'platform' => 'required|in:' . implode(',', $validPlatforms),
        ], [
            'platform.required' => 'Please select a platform.',
            'platform.in' => 'Please select a valid platform.',
        ]);

        $request->session()->put('form_data.step3', $validated);

        return redirect()->route('form.step4');
    }

    /**
     * Display the form step 4 - Review & Submit
     */
    public function step4()
    {
        if (!session('form_data.step1') || !session('form_data.step2') || !session('form_data.step3')) {
            return redirect()->route('form.step1');
        }

        $formData = [
            'step1' => session('form_data.step1'),
            'step2' => session('form_data.step2'),
            'step3' => session('form_data.step3'),
        ];

        return Inertia::render('Form/Step4', [
            'step' => 4,
            'totalSteps' => 4,
            'formData' => $formData,
        ]);
    }

    /**
     * Handle the final submission of the form
     */
    public function submit(Request $request)
    {
        $session = session()->get('form_data');

        // Check if email already exists
        $existingUser = User::where('email', $session['step1']['email'])->first();

        if ($existingUser) {
            return redirect()->route('form.step1')->withErrors([
                'email' => 'An account with this email address already exists. Please use a different email or contact support if you need assistance.'
            ])->withInput($session['step1']);
        }

        try {
            $websiteDetail = WebsiteDetail::query()
                ->where('type', $session['step2']['website_type'])
                ->where('name', $session['step3']['platform'])
                ->first();

            if (!$websiteDetail) {
                // Create website detail if it doesn't exist
                $websiteDetail = WebsiteDetail::create([
                    'type' => $session['step2']['website_type'],
                    'name' => $session['step3']['platform'],
                    'slug' => Str::slug($session['step3']['platform']),
                ]);
            }

            $user = $websiteDetail->users()->create([
                'name' => $session['step1']['name'],
                'email' => $session['step1']['email'],
                'company_name' => $session['step1']['company_name'],
                'website_url' => $session['step1']['website_url'] ?? null,
                'password' => bcrypt('12345678'),
            ]);

            // Send OneSignal notification
            $notificationResult = $this->sendLeadNotification($user, $session);

            $request->session()->forget('form_data');

            $successMessage = 'Your information has been submitted successfully! We will contact you soon.';

            if ($notificationResult['success']) {
                $successMessage .= ' Notification sent to team.';
            } else {
                // Log notification failure but don't fail the form submission
                Log::warning('OneSignal notification failed', $notificationResult);
            }

            return redirect()->route('form.index')->with('success', $successMessage);

        } catch (\Exception $e) {
            return redirect()->route('form.step1')->withErrors([
                'general' => 'Something went wrong while processing your submission. Please try again or contact support if the problem persists.'
            ])->withInput($session['step1']);
        }
    }

    /**
     * Send OneSignal notification for new lead
     */
    private function sendLeadNotification(User $user, array $formData): array
    {
        $message = "A new user has submitted the registration form";

        $data = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'company_name' => $user->company_name,
            'website_type' => $formData['step2']['website_type'],
            'platform' => $formData['step3']['platform'],
            'submitted_at' => now()->toISOString(),
        ];

        return $this->oneSignalService->sendNotificationToAll($message, $data);
    }

    /**
     * Get platform options based on website type
     */
    private function getPlatformsForWebsiteType(string $websiteType): array
    {
        if ($websiteType === 'ecommerce') {
            return [
                'shopify' => 'Shopify',
                'woocommerce' => 'WooCommerce',
                'bigcommerce' => 'BigCommerce',
                'magento' => 'Magento',
                'custom_solution' => 'Custom Solution',
                'other' => 'Other',
            ];
        }

        return [
            'wordpress' => 'WordPress',
            'squarespace' => 'Squarespace',
            'webflow' => 'Webflow',
            'custom_developed' => 'Custom Developed',
            'other' => 'Other',
        ];
    }
}
