<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'System rozliczania wydatków') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        @include('layouts.theme-script')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 text-gray-900 dark:bg-gray-950 dark:text-gray-100">
        <div class="min-h-screen">
            <header class="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                <div class="mx-auto flex max-w-6xl flex-col gap-4 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                    <a href="{{ route('home') }}" class="text-xl font-black tracking-tight text-indigo-700 dark:text-indigo-300">
                        System rozliczania wydatków
                    </a>

                    <div class="flex flex-wrap items-center gap-3">
                        <x-theme-toggle />
                        @auth
                            <a href="{{ route('dashboard') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-bold text-white hover:bg-indigo-700 dark:bg-indigo-500 dark:text-gray-950 dark:hover:bg-indigo-400">
                                Panel użytkownika
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-indigo-700 dark:text-gray-200 dark:hover:text-indigo-300">
                                Logowanie
                            </a>
                            @if(Route::has('register'))
                                <a href="{{ route('register') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-bold text-white hover:bg-indigo-700 dark:bg-indigo-500 dark:text-gray-950 dark:hover:bg-indigo-400">
                                    Rejestracja
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </header>

            <main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
                <section class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900 sm:p-8 lg:p-10">
                    <p class="text-sm font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-300">
                        Problem 4
                    </p>

                    <div class="mt-4 grid gap-8 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
                        <div>
                            <h1 class="text-3xl font-black leading-tight text-gray-950 dark:text-white sm:text-4xl">
                                System do sprawiedliwego rozliczania wspólnych wydatków
                            </h1>
                            <p class="mt-5 text-base leading-7 text-gray-600 dark:text-gray-300">
                                Aplikacja umożliwia tworzenie grup, dodawanie uczestników, zapisywanie wydatków, przypisywanie pozycji z paragonu do konkretnych osób oraz automatyczne obliczanie należności.
                            </p>

                            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                                @auth
                                    <a href="{{ route('groups.index') }}" class="inline-flex justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-black text-white hover:bg-indigo-700 dark:bg-indigo-500 dark:text-gray-950 dark:hover:bg-indigo-400">
                                        Przejdź do grup
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="inline-flex justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-black text-white hover:bg-indigo-700 dark:bg-indigo-500 dark:text-gray-950 dark:hover:bg-indigo-400">
                                        Zaloguj się
                                    </a>
                                    <a href="{{ route('dashboard') }}" class="inline-flex justify-center rounded-xl border border-gray-300 px-5 py-3 text-sm font-black text-gray-800 hover:border-indigo-400 hover:text-indigo-700 dark:border-gray-700 dark:text-gray-200 dark:hover:border-indigo-500 dark:hover:text-indigo-300">
                                        Zobacz opis projektu
                                    </a>
                                @endauth
                            </div>
                        </div>

                        <div class="rounded-2xl bg-gray-50 p-5 dark:bg-gray-950">
                            <h2 class="text-xl font-black text-gray-950 dark:text-white">Opis problemu</h2>
                            <div class="mt-4 space-y-4 text-sm leading-7 text-gray-700 dark:text-gray-300">
                                <p>
                                    W wielu sytuacjach życia codziennego — takich jak wspólne wycieczki, imprezy czy mieszkanie razem — pojawia się potrzeba sprawiedliwego rozliczania wspólnych wydatków. W prostych przypadkach wystarczający okazuje się arkusz kalkulacyjny, jednak wraz ze wzrostem liczby uczestników i transakcji rozwiązanie to staje się nieczytelne i trudne w utrzymaniu.
                                </p>
                                <p>
                                    Problem szczególnie uwidacznia się w bardziej złożonych scenariuszach. Przykładowo, podczas wycieczki jedna osoba opłaca rachunek za obiad częściowo własnymi środkami, a częściowo dokłada się druga osoba, natomiast sam rachunek składa się z wielu pozycji przypisanych do konkretnych uczestników. W takiej sytuacji konieczne jest jednoczesne uwzględnienie wielu płatników, podziału kosztów na konkretne osoby oraz powiązania wydatków z rzeczywistymi pozycjami z paragonu. Ręczne rozliczenie takiego przypadku jest czasochłonne i podatne na błędy.
                                </p>
                                <p>
                                    Istnieją dedykowane aplikacje, takie jak Splitwise, jednak często nie oferują one wystarczającej elastyczności — brakuje możliwości rozbijania jednego rachunku na szczegółowe pozycje, przypisywania ich do konkretnych osób oraz jednoczesnego uwzględniania wielu płatników. Celem projektu jest stworzenie systemu, który w prosty i przejrzysty sposób umożliwi zarządzanie nawet złożonymi rozliczeniami — pozwoli definiować szczegółowe wydatki, przypisywać koszty do konkretnych osób, archiwizować paragony oraz automatycznie bilansować należności między użytkownikami.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mt-8 grid gap-4 md:grid-cols-3">
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                        <p class="text-sm font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">Grupy</p>
                        <p class="mt-2 text-3xl font-black text-gray-950 dark:text-white">{{ $groupsCount ?? 0 }}</p>
                    </div>
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                        <p class="text-sm font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">Użytkownicy</p>
                        <p class="mt-2 text-3xl font-black text-gray-950 dark:text-white">{{ $usersCount ?? 0 }}</p>
                    </div>
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                        <p class="text-sm font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">Wydatki</p>
                        <p class="mt-2 text-3xl font-black text-gray-950 dark:text-white">{{ $billsCount ?? 0 }}</p>
                    </div>
                </section>

                <section class="mt-8 grid gap-6 lg:grid-cols-3">
                    <article class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                        <h3 class="text-lg font-black text-gray-950 dark:text-white">Zarządzanie grupami</h3>
                        <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-300">
                            Użytkownik może tworzyć grupy rozliczeniowe, dodawać uczestników i oddzielać rozliczenia różnych wyjazdów, imprez lub mieszkań.
                        </p>
                    </article>
                    <article class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                        <h3 class="text-lg font-black text-gray-950 dark:text-white">Szczegółowe wydatki</h3>
                        <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-300">
                            Rachunki mogą zawierać pozycje z paragonu przypisane do konkretnych osób, dzięki czemu rozliczenie jest dokładniejsze niż prosty podział po równo.
                        </p>
                    </article>
                    <article class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                        <h3 class="text-lg font-black text-gray-950 dark:text-white">Automatyczne bilansowanie</h3>
                        <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-300">
                            System pokazuje, kto zapłacił, ile wynosi udział poszczególnych osób i jakie należności pozostają do wyrównania.
                        </p>
                    </article>
                </section>
            </main>
        </div>
    </body>
</html>
