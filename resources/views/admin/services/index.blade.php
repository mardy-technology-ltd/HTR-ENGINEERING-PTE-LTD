@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Services Management</h1>
        <a href="{{ route('admin.services.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition duration-200 ease-in-out transform hover:scale-105">
            <i class="fas fa-plus mr-2"></i>Add New Service
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-3"></i>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        @if($services->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                    @foreach($services as $service)
                        <div class="relative bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                            <!-- Background Image with Overlay -->
                            @if($service->image)
                                <div class="absolute inset-0 z-0">
                                    <img src="{{ asset('storage/' . $service->image) }}" 
                                         alt="{{ $service->title }}" 
                                         class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/70 to-black/40"></div>
                                </div>
                            @else
                                <div class="absolute inset-0 z-0 bg-gradient-to-br from-blue-600 to-blue-800"></div>
                            @endif

                            <!-- Content -->
                            <div class="relative z-10 p-6 flex flex-col h-full min-h-[320px]">
                                <!-- Order Badge -->
                                <div class="absolute top-4 right-4">
                                    <span class="bg-white/20 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-bold">
                                        #{{ $service->order }}
                                    </span>
                                </div>

                                <!-- Icon -->
                                <div class="mb-4">
                                    @if($service->icon)
                                        <div class="w-16 h-16 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                            <i class="{{ $service->icon }} text-3xl text-white"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Title & Description -->
                                <div class="flex-grow">
                                    <h3 class="text-2xl font-bold text-white mb-3">{{ $service->title }}</h3>
                                    <p class="text-gray-200 text-sm leading-relaxed mb-4">
                                        {{ Str::limit($service->description, 120) }}
                                    </p>
                                </div>

                                <!-- Status Badge -->
                                <div class="mb-4">
                                    @if($service->is_active)
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-500/90 text-white">
                                            <i class="fas fa-check-circle mr-1"></i> Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-red-500/90 text-white">
                                            <i class="fas fa-times-circle mr-1"></i> Inactive
                                        </span>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-2 pt-4 border-t border-white/20">
                                    <a href="{{ route('admin.services.edit', $service->id) }}" 
                                       class="flex-1 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 text-center">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                    <form action="{{ route('admin.services.destroy', $service->id) }}" 
                                          method="POST" 
                                          class="flex-1"
                                          onsubmit="return confirm('Are you sure you want to delete this service?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-full bg-red-500/80 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                                            <i class="fas fa-trash mr-1"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg mb-4">No services found.</p>
                <a href="{{ route('admin.services.create') }}" 
                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200">
                    Create Your First Service
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
