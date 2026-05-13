<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokemon API</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .pokemon-card {
            background: linear-gradient(145deg, #ffffff 0%, #f3f4f6 100%);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .type-badge {
            transition: transform 0.2s;
        }
        .type-badge:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 min-h-screen py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">🔍 Pokemon Search</h1>
            <p class="text-white/80">Discover your favorite Pokemon!</p>
        </div>

        <!-- Search Form -->
        <div class="pokemon-card rounded-2xl p-6 mb-6">
            <form action="{{ route('pokemon.index') }}" method="GET" class="flex gap-3">
                <input type="text" 
                       name="name" 
                       placeholder="Enter Pokemon name (e.g., pikachu)" 
                       class="flex-1 px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-purple-500 focus:outline-none transition-colors"
                       required>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all transform hover:scale-105">
                    Search
                </button>
            </form>
            
            <!-- Random Button -->
            <div class="mt-4 text-center">
                <a href="{{ route('pokemon.random') }}" 
                   class="inline-flex items-center gap-2 px-6 py-2 bg-gradient-to-r from-yellow-400 to-orange-500 text-white font-semibold rounded-full hover:from-yellow-500 hover:to-orange-600 transition-all transform hover:scale-105 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd" />
                    </svg>
                    Random Pokemon
                </a>
            </div>
        </div>

        @if(isset($pokemon))
            <!-- Pokemon Card -->
            <div class="pokemon-card rounded-2xl p-8 text-center relative overflow-hidden">
                <!-- Background Circle -->
                <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-64 h-64 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full -z-10"></div>
                
                <!-- Pokemon Image -->
                <img src="{{ $pokemon['sprites']['other']['official-artwork']['front_default'] ?? $pokemon['sprites']['front_default'] }}" 
                     alt="{{ $pokemon['name'] }}" 
                     class="w-48 h-48 mx-auto mb-4 drop-shadow-2xl"
                     style="image-rendering: pixelated;">
                
                <!-- Pokemon Name -->
                <h2 class="text-3xl font-bold text-gray-800 capitalize mb-2">{{ $pokemon['name'] }}</h2>
                
                <!-- Pokemon ID -->
                <span class="inline-block bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-sm font-medium mb-4">
                    #{{ str_pad($pokemon['id'], 3, '0', STR_PAD_LEFT) }}
                </span>

                <!-- Types -->
                <div class="flex justify-center gap-2 mb-6">
                    @foreach($pokemon['types'] as $type)
                        <span class="type-badge px-4 py-1 rounded-full text-white text-sm font-medium
                            @switch($type['type']['name'])
                                @case('fire') bg-orange-500 @break
                                @case('water') bg-blue-500 @break
                                @case('grass') bg-green-500 @break
                                @case('electric') bg-yellow-500 @break
                                @case('ice') bg-cyan-400 @break
                                @case('fighting') bg-red-600 @break
                                @case('poison') bg-purple-600 @break
                                @case('ground') bg-amber-600 @break
                                @case('flying') bg-sky-400 @break
                                @case('psychic') bg-pink-500 @break
                                @case('bug') bg-lime-500 @break
                                @case('ghost') bg-indigo-700 @break
                                @case('dragon') bg-indigo-600 @break
                                @case('steel') bg-slate-500 @break
                                @case('fairy') bg-pink-400 @break
                                @case('normal') bg-gray-400 @break
                                @case('rock') bg-amber-700 @break
                                @default bg-gray-500
                            @endswitch
                        ">
                            {{ ucfirst($type['type']['name']) }}
                        </span>
                    @endforeach
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl">
                        <p class="text-sm text-gray-500">Height</p>
                        <p class="text-xl font-bold text-gray-800">{{ $pokemon['height'] / 10 }} m</p>
                    </div>
                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl">
                        <p class="text-sm text-gray-500">Weight</p>
                        <p class="text-xl font-bold text-gray-800">{{ $pokemon['weight'] / 10 }} kg</p>
                    </div>
                </div>

                <!-- Base Stats -->
                <div class="mt-6 text-left">
                    <h3 class="font-semibold text-gray-700 mb-3">Base Stats</h3>
                    @foreach($pokemon['stats'] as $stat)
                        <div class="mb-2">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600 capitalize">{{ str_replace('-', ' ', $stat['stat']['name']) }}</span>
                                <span class="font-medium text-gray-800">{{ $stat['base_stat'] }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full" 
                                     style="width: {{ min($stat['base_stat'], 100) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Abilities -->
                <div class="mt-6 text-left">
                    <h3 class="font-semibold text-gray-700 mb-3">Abilities</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($pokemon['abilities'] as $ability)
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                                {{ ucfirst($ability['ability']['name']) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>
</html>