<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Coming Soon - HTR ENGINEERING PTE LTD</title>
    <meta name="description" content="We're getting ready to launch! Professional roller shutters and security solutions in Singapore.">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-32x32.png') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .animate-fade-in {
            animation: fadeIn 1s ease-out;
        }
        
        .animate-pulse-slow {
            animation: pulse 3s ease-in-out infinite;
        }
        
        .countdown-box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .logo-glow {
            filter: drop-shadow(0 0 20px rgba(59, 130, 246, 0.5));
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 min-h-screen flex items-center justify-center overflow-hidden">
    
    {{-- Background Pattern --}}
    <div class="absolute inset-0 bg-black opacity-20"></div>
    <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center animate-fade-in">
            
            {{-- Logo --}}
            <div class="mb-8 flex justify-center">
                <img src="{{ asset('images/logo.png') }}" 
                     alt="HTR ENGINEERING PTE LTD" 
                     class="h-24 w-auto object-contain bg-white rounded-lg p-2 logo-glow">
            </div>
            
            {{-- Company Name --}}
            <div class="mb-6">
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-2">
                    HTR ENGINEERING
                </h1>
                <p class="text-xl md:text-2xl text-blue-200 font-medium">PTE LTD</p>
            </div>
            
            {{-- Main Message --}}
            <div class="mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    We Are Getting Ready to Launch! 🚀
                </h2>
                <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                    Our new and improved website is coming soon. We're working hard to bring you the best experience for all your roller shutter and security solution needs in Singapore.
                </p>
            </div>
            
            {{-- Countdown Timer --}}
            <div class="mb-12">
                <p class="text-blue-200 text-lg mb-6 font-medium">Launching In</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-3xl mx-auto">
                    {{-- Days --}}
                    <div class="countdown-box rounded-2xl p-6 animate-pulse-slow">
                        <div id="days" class="text-5xl md:text-6xl font-bold text-white mb-2">00</div>
                        <div class="text-blue-200 text-sm md:text-base uppercase tracking-wider">Days</div>
                    </div>
                    
                    {{-- Hours --}}
                    <div class="countdown-box rounded-2xl p-6 animate-pulse-slow" style="animation-delay: 0.2s;">
                        <div id="hours" class="text-5xl md:text-6xl font-bold text-white mb-2">00</div>
                        <div class="text-blue-200 text-sm md:text-base uppercase tracking-wider">Hours</div>
                    </div>
                    
                    {{-- Minutes --}}
                    <div class="countdown-box rounded-2xl p-6 animate-pulse-slow" style="animation-delay: 0.4s;">
                        <div id="minutes" class="text-5xl md:text-6xl font-bold text-white mb-2">00</div>
                        <div class="text-blue-200 text-sm md:text-base uppercase tracking-wider">Minutes</div>
                    </div>
                    
                    {{-- Seconds --}}
                    <div class="countdown-box rounded-2xl p-6 animate-pulse-slow" style="animation-delay: 0.6s;">
                        <div id="seconds" class="text-5xl md:text-6xl font-bold text-white mb-2">00</div>
                        <div class="text-blue-200 text-sm md:text-base uppercase tracking-wider">Seconds</div>
                    </div>
                </div>
            </div>
            
            {{-- Contact Information --}}
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 max-w-2xl mx-auto border border-white/20">
                <h3 class="text-2xl font-bold text-white mb-6">Need Immediate Assistance?</h3>
                <div class="grid md:grid-cols-2 gap-6 text-left">
                    <div class="flex items-center gap-4">
                        <div class="bg-white text-blue-900 p-3 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-blue-200 text-sm">Call Us</div>
                            <a href="tel:+6586973181" class="text-white font-bold text-lg hover:text-blue-300 transition-colors">+65 8697 3181</a>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="bg-white text-blue-900 p-3 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-blue-200 text-sm">Email Us</div>
                            <a href="mailto:rollershutter14@gmail.com" class="text-white font-bold text-lg hover:text-blue-300 transition-colors break-all">rollershutter14@gmail.com</a>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Footer --}}
            <div class="mt-12 text-blue-200 text-sm">
                <p>&copy; {{ date('Y') }} HTR ENGINEERING PTE LTD. All rights reserved.</p>
            </div>
        </div>
    </div>
    
    {{-- Countdown JavaScript --}}
    <script>
        // Set launch date to 10 days from now
        const launchDate = new Date();
        launchDate.setDate(launchDate.getDate() + 10);
        
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = launchDate.getTime() - now;
            
            // Calculate time units
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            // Update DOM
            document.getElementById('days').textContent = String(days).padStart(2, '0');
            document.getElementById('hours').textContent = String(hours).padStart(2, '0');
            document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
            document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
            
            // If countdown is finished
            if (distance < 0) {
                document.getElementById('days').textContent = '00';
                document.getElementById('hours').textContent = '00';
                document.getElementById('minutes').textContent = '00';
                document.getElementById('seconds').textContent = '00';
            }
        }
        
        // Update every second
        updateCountdown();
        setInterval(updateCountdown, 1000);
    </script>
</body>
</html>
