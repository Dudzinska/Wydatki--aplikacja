# Weryfikacja wymagan projektu (Aplikacje Internetowe 2025/2026)

Punkt odniesienia: <https://ia.lazysolutions.pl/project_requirements.html>

## Pokrycie wymagan funkcjonalnych

### Ocena 3.0
- [x] CRUD zasobu `groups` (tworzenie, lista, edycja, usuwanie) z walidacja po stronie serwera i klienta.
- [x] Relacje miedzy zasobami (`groups`, `bills`, `bill_items`, `users`).
- [x] Panel administratora do zarzadzania kontami i rolami.
- [x] Lista zasobow z filtrowaniem i sortowaniem (`groups.index`, `public.groups.index`, `groups.show`, `admin.users.index`).

### Ocena 4.0
- [x] Rozroznienie rol `admin` oraz `user` (middleware `admin`, role w modelu `User`).
- [x] Zarzadzanie wlasnymi zasobami przez uzytkownika (kontrola dostepu w `GroupController` i kontrolerach rachunkow).
- [x] Zarzadzanie profilami i rolami przez administratora (`Admin\UserController`).
- [x] Dostep publiczny read-only dla niezalogowanych:
  - `GET /katalog-grup`
  - `GET /katalog-grup/{group}`

### Ocena 5.0
- [x] Dodatkowa logika biznesowa: algorytm propozycji splat minimalizujacy liczbe przelewow (`Group::getSettlementPlan()`).
- [x] Dodatkowa logika biznesowa: automatyczne przeliczanie podzialu kosztu rachunku na podstawie pozycji paragonu (`BillSplitService`).
- [x] Funkcje dla uzytkownika koncowego ponad CRUD:
  - statystyki wydatkow w grupie,
  - publiczny katalog grup,
  - automatyczne podpowiedzi rozliczen.

## Warstwa UI / UX
- [x] Motyw glamour (gradientowe sekcje, karty, delikatne akcenty kolorystyczne) w layoutach i kluczowych widokach.

## Dokumentacja
- [x] Podrecznik uzytkownika i opis wymagan: `docs/user_manual_and_requirements.md`.
- [x] Opis CRUD zasobu zaleznego od innych zasobow wraz z walidacja serwera/klienta.
- [x] Opis publicznego dostepu read-only, rol, uprawnien, zarzadzania wlasnymi zasobami i panelu administratora.
- [x] Opis nietrywialnej logiki biznesowej i funkcji uzytkownika koncowego.

## Wymagania technologiczne
- [ ] Specyfikacja wskazuje PHP 8.5 i Laravel 13. Repozytorium nadal deklaruje PHP `^8.2` i Laravel `^12.0`; migracja glownego stacku wymaga osobnego przebudowania `composer.lock` i pelnej weryfikacji PHP/Composer.

## Uwaga organizacyjna
- Pytania egzaminacyjne z dokumentu (sekcja "Pytania do projektu") wymagaja przygotowania odpowiedzi opisowych i sa poza zakresem samej implementacji kodu.
