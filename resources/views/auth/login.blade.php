@extends('layouts.auth')

@section('content')
<div class="flex flex-col lg:flex-row min-h-[600px]">

    <!-- Left Side - Branding & Image (Hidden on mobile) -->
    <div class="hidden lg:flex lg:w-3/5 bg-gradient-to-br from-blue-600 via-blue-700 to-cyan-600 justify-center relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-20">
            <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="bubbles" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                        <circle cx="10" cy="10" r="2" fill="white" opacity="0.3" />
                        <circle cx="5" cy="5" r="1" fill="white" opacity="0.2" />
                        <circle cx="15" cy="15" r="1.5" fill="white" opacity="0.25" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#bubbles)" />
            </svg>
        </div>

        <!-- Content Overlay -->
        <div class="relative z-10 h-full flex flex-col justify-center items-center p-8 lg:p-12">
            <div class="flex flex-col items-center text-white text-center">
                <div class="rounded-xl flex items-center justify-center mb-6 backdrop-blur-sm border-2 border-white border-rounded-xl">
                    <img src="{{ asset('images/icon-logo.svg') }}" alt="NOVELENTITY" class="w-48 h-auto">
                </div>
                
                <div class="space-y-1">
                    <h1 class="text-4xl font-bold tracking-tight">NOVEL ENTITY</h1>
                    <h1 class="text-3xl font-bold opacity-90">VISUALIZER</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="lg:w-2/5 p-8 lg:p-12 flex items-center">
        <div class="w-full max-w-sm mx-auto">

            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-2xl mb-4">
                    <x-icons.user width="36" height="36" class="text-blue-600" />
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Login to Your Account</h2>
                <p class="text-gray-600">Manage your data easily</p>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Email Address
                    </label>
                    <div class="relative">
                        <div class="absolute top-1/2 left-0 pl-3 flex items-center pointer-events-none transform -translate-y-1/2">
                            <x-icons.mail width="20" height="20" class="text-gray-400" />
                        </div>
                        <input 
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            class="block w-full pl-10 pr-3 py-3 border-2 border-gray-200 @error('email') border-red-500 @enderror rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                            placeholder="email@example.com"
                        />
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div x-data="{ showPassword: false }">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute top-1/2 left-0 pl-3 flex items-center pointer-events-none transform -translate-y-1/2">
                            <x-icons.lock width="20" height="20" class="text-gray-400" />
                        </div>
                        <input 
                            :type="showPassword ? 'text' : 'password'"
                            name="password"
                            id="password"
                            class="block w-full pl-10 pr-10 py-3 border-2 border-gray-200 @error('password') border-red-500 @enderror rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                            placeholder="Enter your password"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" @click="showPassword = !showPassword"
                                class="text-gray-400 hover:text-gray-600">
                                <x-icons.eye width="20" height="20" class="text-gray-400" />
                            </button>
                        </div>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Login Button -->
                <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-cyan-600 cursor-pointer text-white py-3 px-4 rounded-xl font-semibold hover:from-blue-700 hover:to-cyan-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all duration-200 transform hover:scale-[1.02]">
                    <div class="flex items-center justify-center">
                        <x-icons.lock-open width="20" height="20" class="mr-2" />
                        Login
                    </div>
                </button>

                <!-- Contact Info -->
                <div class="text-center mt-6 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500 mb-2">Need help? Ask Me!</p>
                    <div class="flex items-center justify-center space-x-4 text-sm">
                        {{-- <a href="tel:+6285369807844" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <x-icons.phone width="20" height="20" class="mr-1" />
                            Call Center
                        </a>
                        <span class="text-gray-300">|</span>
                        <a href="https://wa.me/085369807844?text=Halo%20Admin%20SIPO" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <x-icons.brand-whatsapp width="20" height="20" class="mr-1" />
                            WhatsApp
                        </a> --}}
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
