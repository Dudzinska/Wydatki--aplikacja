<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-100">
            {{ __('Panel główny') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900 sm:p-8 lg:p-10">
                <div class="grid gap-8 lg:grid-cols-[1fr_0.85fr] lg:items-center">
                    <div>
                        <p class="text-sm font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-300">
                            Rozliczanie wydatków
                        </p>
                        <h1 class="mt-4 text-3xl font-black leading-tight text-gray-950 dark:text-white sm:text-4xl">
                            Zarządzaj grupami i wspólnymi rachunkami.
                        </h1>
                        <p class="mt-5 text-base leading-7 text-gray-600 dark:text-gray-300">
                            Dodawaj wydatki, przypisuj je do osób i sprawdzaj salda w grupach.
                        </p>

                        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                            @auth
                                <a href="{{ route('groups.index') }}" class="inline-flex justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-black text-white hover:bg-indigo-700 dark:bg-indigo-500 dark:text-gray-950 dark:hover:bg-indigo-400">
                                    Moje grupy
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-black text-white hover:bg-indigo-700 dark:bg-indigo-500 dark:text-gray-950 dark:hover:bg-indigo-400">
                                    Zaloguj się
                                </a>
                                @if(Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-flex justify-center rounded-xl border border-gray-300 px-5 py-3 text-sm font-black text-gray-800 hover:border-indigo-400 hover:text-indigo-700 dark:border-gray-700 dark:text-gray-200 dark:hover:border-indigo-500 dark:hover:text-indigo-300">
                                        Utwórz konto
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>

                    <div class="rounded-2xl bg-gray-50 p-5 dark:bg-gray-950">
                        <h2 class="text-lg font-black text-gray-950 dark:text-white">Szybki start</h2>
                        <div class="mt-4 space-y-3 text-sm leading-6 text-gray-700 dark:text-gray-300">
                            <p>1. Utwórz grupę rozliczeniową.</p>
                            <p>2. Dodaj uczestników.</p>
                            <p>3. Zapisz wydatki i pozycje z rachunków.</p>
                            <p>4. Sprawdź, kto komu powinien oddać pieniądze.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-sm font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">Grupy</p>
                    <p class="mt-2 text-3xl font-black text-gray-900 dark:text-gray-100">{{ $groupsCount ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-sm font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">Użytkownicy</p>
                    <p class="mt-2 text-3xl font-black text-gray-900 dark:text-gray-100">{{ $usersCount ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-sm font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">Wydatki</p>
                    <p class="mt-2 text-3xl font-black text-gray-900 dark:text-gray-100">{{ $billsCount ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
