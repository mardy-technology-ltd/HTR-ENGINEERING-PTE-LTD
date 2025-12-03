<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Project;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    /**
     * Generate dynamic sitemap.xml
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
        $sitemap .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $sitemap .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 ';
        $sitemap .= 'http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
        
        // Home Page (highest priority, updates daily)
        $sitemap .= $this->addUrl(url('/'), now(), 'daily', '1.0');
        
        // About Page
        $sitemap .= $this->addUrl(route('about'), now(), 'monthly', '0.8');
        
        // Services Listing Page
        $sitemap .= $this->addUrl(route('services'), now(), 'weekly', '0.9');
        
        // Individual Service Pages (using slugs)
        $services = Service::where('is_active', true)->get();
        foreach ($services as $service) {
            $sitemap .= $this->addUrl(
                route('service.details', $service->slug),
                $service->updated_at,
                'weekly',
                '0.9'
            );
        }
        
        // Gallery Page
        $sitemap .= $this->addUrl(route('gallery'), now(), 'weekly', '0.8');
        
        // Individual Project Pages
        $projects = Project::all();
        foreach ($projects as $project) {
            $sitemap .= $this->addUrl(
                route('project.details', $project->id),
                $project->updated_at,
                'monthly',
                '0.7'
            );
        }
        
        // Contact Page
        $sitemap .= $this->addUrl(route('contact'), now(), 'monthly', '0.7');
        
        // Privacy Policy
        $sitemap .= $this->addUrl(route('privacy-policy'), now(), 'yearly', '0.3');
        
        // Terms of Service
        $sitemap .= $this->addUrl(route('terms-of-service'), now(), 'yearly', '0.3');
        
        $sitemap .= '</urlset>';
        
        return response($sitemap, 200)
            ->header('Content-Type', 'text/xml; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600'); // Cache for 1 hour
    }
    
    /**
     * Generate a sitemap URL entry
     *
     * @param string $loc URL location
     * @param string|\Carbon\Carbon $lastmod Last modified date
     * @param string $changefreq Change frequency
     * @param string $priority Priority (0.0 to 1.0)
     * @return string
     */
    private function addUrl($loc, $lastmod, $changefreq, $priority)
    {
        $url = '<url>';
        $url .= '<loc>' . htmlspecialchars($loc, ENT_XML1, 'UTF-8') . '</loc>';
        $url .= '<lastmod>' . date('c', strtotime($lastmod)) . '</lastmod>'; // ISO 8601 format
        $url .= '<changefreq>' . $changefreq . '</changefreq>';
        $url .= '<priority>' . $priority . '</priority>';
        $url .= '</url>';
        
        return $url;
    }
}
