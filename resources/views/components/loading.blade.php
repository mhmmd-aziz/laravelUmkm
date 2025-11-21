<div 
    x-show="loading"
    x-cloak
    class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-white/80 backdrop-blur-sm">

    <img src="{{ asset('images/maskot2.png') }}" 
         alt="Loading..."
         class="w-40 h-40 animate-bounce-slow select-none" />

    <p class="mt-4 text-gray-700 font-medium text-lg animate-pulse">
        Memuat...
    </p>
</div>

<style>
@keyframes bounceSlow {
    0%, 100%   { transform: translateY(0); }
    50%        { transform: translateY(-12px); }
}
.animate-bounce-slow {
    animation: bounceSlow 1.6s ease-in-out infinite;
}
</style>
