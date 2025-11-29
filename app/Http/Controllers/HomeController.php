<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Project;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Services\ServiceService;
use App\Services\ProjectService;
use App\Services\TestimonialService;

class HomeController extends Controller
{
    protected ServiceService $serviceService;
    protected ProjectService $projectService;
    protected TestimonialService $testimonialService;

    public function __construct(
        ServiceService $serviceService,
        ProjectService $projectService,
        TestimonialService $testimonialService
    ) {
        $this->serviceService = $serviceService;
        $this->projectService = $projectService;
        $this->testimonialService = $testimonialService;
    }

    /**
     * Display the home page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch all active services from database (for slider)
        $services = $this->serviceService->getActiveForHome(null); // Get all
        
        // If no services in database, use fallback
        if (empty($services)) {
            $services = $this->getFallbackServices();
        }

        // Fetch all featured projects from database (for slider)
        $projects = $this->projectService->getFeaturedForHome(null); // Get all
        
        // Fallback if no projects
        if (empty($projects)) {
            $projects = $this->getFallbackProjects();
        }

        // Fetch all testimonials from database (for slider)
        $testimonials = $this->testimonialService->getActiveForHome(null); // Get all
        
        // Fallback testimonials
        if (empty($testimonials)) {
            $testimonials = $this->getFallbackTestimonials();
        }

        return view('home', compact('services', 'projects', 'testimonials'));
    }

    /**
     * Get fallback services data.
     *
     * @return array
     */
    private function getFallbackServices(): array
    {
        return [
            [
                'title' => 'Roller Shutters',
                'description' => 'High-quality roller shutters for commercial and residential properties. Durable, secure, and customizable.',
                'icon' => 'shutters'
            ],
            [
                'title' => 'Security Grilles',
                'description' => 'Robust security grilles to protect your premises while maintaining visibility and airflow.',
                'icon' => 'grilles'
            ],
            [
                'title' => 'Automatic Gates',
                'description' => 'Automated gate systems with advanced access control for enhanced security and convenience.',
                'icon' => 'gates'
            ],
            [
                'title' => 'Automatic Doors',
                'description' => 'Modern automatic door solutions for commercial buildings, hospitals, and retail spaces.',
                'icon' => 'doors'
            ],
            [
                'title' => 'Metal Works',
                'description' => 'Custom metal fabrication and installation services for industrial and commercial projects.',
                'icon' => 'metal'
            ],
            [
                'title' => 'Maintenance Services',
                'description' => 'Comprehensive maintenance and repair services to keep your installations in optimal condition.',
                'icon' => 'maintenance'
            ],
        ];
    }

    /**
     * Get fallback projects data.
     *
     * @return array
     */
    private function getFallbackProjects(): array
    {
        return [
                [
                    'title' => 'Commercial Warehouse Security',
                    'category' => 'Commercial',
                    'image' => 'project1.jpg',
                    'description' => 'Complete roller shutter installation for a 10,000 sqft warehouse facility.'
                ],
                [
                    'title' => 'Residential Auto Gate System',
                    'category' => 'Residential',
                    'image' => 'project2.jpg',
                    'description' => 'Automated sliding gate with remote access control for luxury condominium.'
                ],
                [
                    'title' => 'Industrial Facility Upgrade',
                    'category' => 'Industrial',
                    'image' => 'project3.jpg',
                    'description' => 'Heavy-duty roller shutters and security grilles for manufacturing plant.'
                ],
                [
                    'title' => 'Shopping Mall Security Grilles',
                    'category' => 'Commercial',
                    'image' => 'project4.jpg',
                    'description' => 'Modern security grilles installation for retail outlets.'
                ],
                [
                    'title' => 'Office Building Automatic Doors',
                    'category' => 'Commercial',
                    'image' => 'project5.jpg',
                    'description' => 'Sleek automatic door systems for corporate headquarters.'
                ],
                [
                    'title' => 'Factory Roller Shutters',
                    'category' => 'Industrial',
                    'image' => 'project6.jpg',
                    'description' => 'High-performance roller shutters for industrial operations.'
                ],
            ];
    }

    /**
     * Get fallback testimonials data.
     *
     * @return array
     */
    private function getFallbackTestimonials(): array
    {
        return [
                [
                    'name' => 'David Tan',
                    'company' => 'Warehouse Solutions Pte Ltd',
                    'message' => 'Excellent workmanship and professional service. Our roller shutters have been functioning perfectly for over 3 years now.',
                    'rating' => 5
                ],
                [
                    'name' => 'Sarah Lim',
                    'company' => 'Residential Client',
                    'message' => 'Very satisfied with the automatic gate installation. The team was punctual, efficient, and cleaned up after the job.',
                    'rating' => 5
                ],
                [
                    'name' => 'Michael Chen',
                    'company' => 'Industrial Park Management',
                    'message' => 'Reliable partner for all our security shutter needs. Quick response time and competitive pricing.',
                    'rating' => 5
                ],
            ];
    }
}
