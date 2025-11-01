<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Service;
use App\Models\Contact;

class HomeController extends Controller
{
    /**
     * Get all data for frontend
     */
    public function index()
    {
        return response()->json([
            'projects' => Project::active()->ordered()->get(),
            'services' => Service::active()->ordered()->get(),
        ]);
    }

    /**
     * Get projects data
     */
    public function projects()
    {
        $projects = Project::active()->ordered()->get()->map(function ($project) {
            return [
                'id' => $project->id,
                'name' => $project->name,
                'description' => $project->description,
                'image' => $project->thumbnail_url,
                'technologies' => $project->technologies,
                'live_url' => $project->live_url,
                'github_url' => $project->github_url,
                'demo_url' => $project->demo_url,
                'color' => $project->color ?? '#00ffff',
                'maxHealth' => 3,
                'featured' => $project->featured,
            ];
        });

        return response()->json($projects);
    }

    /**
     * Get services data
     */
    public function services()
    {
        $services = Service::active()->ordered()->get()->map(function ($service) {
            return [
                'id' => $service->id,
                'title' => $service->title,
                'description' => $service->description,
                'icon' => $service->icon,
                'color' => $service->color,
                'features' => $service->features,
            ];
        });

        return response()->json($services);
    }

    /**
     * Get services data formatted as planets for space portfolio
     */
    public function planets()
    {
        $services = Service::active()->ordered()->get()->map(function ($service, $index) {
            return [
                'id' => $service->id,
                'name' => $service->title,
                'subtitle' => 'Service Expertise',
                'icon' => $service->icon,
                'color' => $service->color,
                'position' => [
                    'x' => 8 + ($index * 4),
                    'y' => 0,
                    'z' => 0
                ],
                'size' => 2 + (mt_rand() / mt_getrandmax()) * 1.5, // Random size between 2-3.5
                'experience' => mt_rand(1, 5), // Random experience years
                'description' => $service->description,
                'technologies' => $service->features,
                'projects' => [
                    [
                        'id' => $service->id,
                        'name' => $service->title . ' Projects',
                        'description' => 'Explore various ' . strtolower($service->title) . ' projects and implementations',
                        'link' => '#services/' . $service->id,
                        'technologies' => $service->features
                    ]
                ]
            ];
        });

        return response()->json($services);
    }

    /**
     * Store a new contact message
     */
    public function storeContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'phone' => 'nullable|string|max:20',
        ]);

        $contact = Contact::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Contact message sent successfully!',
            'contact' => $contact
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return response()->json(['message' => 'Method not implemented'], 501);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(['message' => 'Method not implemented'], 501);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return response()->json(['message' => 'Method not implemented'], 501);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response()->json(['message' => 'Method not implemented'], 501);
    }
}
