# BillsBuddy - podrecznik uzytkownika i pokrycie wymagan

Punkt odniesienia: <https://ia.lazysolutions.pl/project_requirements.html>

BillsBuddy realizuje temat **Problem 3: wspolne rozliczanie wydatkow**. Aplikacja pozwala tworzyc grupy, dodawac uczestnikow, rejestrowac rachunki z pozycjami paragonu oraz automatycznie wyliczac salda i proponowane przelewy.

## 1. Podrecznik uzytkownika

### Dostep publiczny bez logowania

1. Wejdz na strone glowna aplikacji.
2. Kliknij **Katalog grup** albo przejdz pod adres `/katalog-grup`.
3. Przegladaj grupy w trybie read-only:
   - wyszukuj po nazwie lub opisie,
   - filtruj po minimalnej sumie wydatkow,
   - filtruj po minimalnej liczbie osob,
   - sortuj po dacie, nazwie, sumie wydatkow lub liczbie czlonkow.
4. Kliknij **Zobacz szczegoly**, aby zobaczyc publiczny podglad grupy, salda i proponowane rozliczenia.

Uzytkownik niezalogowany nie widzi formularzy tworzenia, edycji ani usuwania zasobow. Proby wejscia na trasy zarzadzania przekierowuja do logowania.

### Rejestracja i logowanie

1. Wybierz **Utworz konto** albo **Zaloguj**.
2. Po zalogowaniu przejdz do **Moje grupy**.
3. Konto testowe po seedzie:
   - admin: `oliwia@example.com` / `password`,
   - user: `adam@example.com` / `password`,
   - user: `ewa@example.com` / `password`.

### Zarzadzanie grupami

1. W widoku **Moje grupy** wypelnij formularz **Dodaj nowa grupe**.
2. Pola:
   - nazwa grupy - wymagana i unikalna,
   - opis - opcjonalny, maksymalnie 1000 znakow.
3. Lista grup ma:
   - wyszukiwanie po nazwie/opisie,
   - filtr po wlascicielu dla administratora,
   - sortowanie po nazwie, dacie utworzenia, liczbie osob i liczbie wydatkow.
4. Kliknij **Otworz**, aby wejsc do szczegolow grupy.
5. Wlasciciel lub administrator moze kliknac **Edytuj** i zmienic nazwe/opis.
6. Wlasciciel lub administrator moze kliknac **Usun** i potwierdzic usuniecie.

### Zarzadzanie uczestnikami grupy

1. Otworz szczegoly grupy.
2. W formularzu dodawania uczestnika wpisz adres e-mail istniejacego uzytkownika.
3. System sprawdza:
   - czy e-mail ma poprawny format,
   - czy uzytkownik istnieje,
   - czy nie jest juz czlonkiem grupy.

### Zarzadzanie rachunkami i pozycjami paragonu

1. W szczegolach grupy dodaj rachunek:
   - opis rachunku - wymagany, nie moze skladac sie tylko z cyfr,
   - kwota - wymagana, dodatnia,
   - platnik - wybierany z listy czlonkow grupy,
   - data - opcjonalna.
2. Po zapisaniu rachunku aplikacja tworzy wstepny rowny podzial kosztu.
3. Do rachunku mozna dodac pozycje paragonu:
   - nazwa pozycji,
   - cena,
   - liczba sztuk.
4. Po dodaniu pozycji aplikacja przelicza podzial rachunku na podstawie pozycji.
5. Rachunek mozna usunac z poziomu szczegolow grupy.

### Panel administratora

Administrator widzi link **Panel admina**. W panelu moze:

- wyszukiwac uzytkownikow po imieniu lub e-mailu,
- filtrowac po roli `user` albo `admin`,
- sortowac po imieniu, dacie utworzenia konta i liczbie grup,
- zmieniac imie uzytkownika,
- zmieniac role uzytkownika,
- usuwac konta innych uzytkownikow.

Administrator nie moze odebrac roli administratora samemu sobie ani usunac wlasnego konta z panelu administracyjnego.

## 2. CRUD zasobu zaleznego od innych zasobow

Glownym zasobem CRUD jest `groups`, powiazany relacjami z:

- `users` - wlasciciel grupy i czlonkowie przez tabele pivot,
- `bills` - rachunki nalezace do grupy,
- `bill_items` - pozycje rachunkow,
- `bill_splits` - podzial kosztu rachunku na uzytkownikow.

### CREATE

- Trasa: `POST /groups`
- Kontroler: `GroupController::store`
- Widok: `resources/views/groups/index.blade.php`
- Walidacja serwera:
  - `name` wymagane, tekst, maksymalnie 255 znakow, unikalne,
  - `description` opcjonalne, tekst, maksymalnie 1000 znakow.
- Walidacja klienta:
  - pole nazwy ma atrybut `required`,
  - formularz pokazuje komunikaty bledow walidacji.
- Po utworzeniu wlasciciel jest automatycznie dopisywany do czlonkow grupy.

### READ

- Trasa prywatna: `GET /groups`
- Trasa publiczna read-only: `GET /katalog-grup`
- Kontrolery: `GroupController::index`, `PublicGroupController::index`
- Lista prywatna:
  - zwykly uzytkownik widzi swoje grupy,
  - administrator widzi wszystkie grupy,
  - filtrowanie: wyszukiwanie po nazwie/opisie, wlasciciel dla administratora,
  - sortowanie: nazwa, data utworzenia, liczba osob, liczba wydatkow.
- Lista publiczna:
  - dostepna bez logowania,
  - nie zawiera akcji edycji/usuwania,
  - filtrowanie: tekst, minimalna suma wydatkow, minimalna liczba osob,
  - sortowanie: data, nazwa, suma wydatkow, liczba czlonkow.

### UPDATE

- Trasa: `PATCH /groups/{group}`
- Kontroler: `GroupController::update`
- Widok: `resources/views/groups/edit.blade.php`
- Dostep:
  - wlasciciel grupy,
  - administrator.
- Walidacja serwera:
  - `name` wymagane, tekst, maksymalnie 255 znakow, unikalne z pominieciem edytowanej grupy,
  - `description` opcjonalne, maksymalnie 1000 znakow.
- Walidacja klienta:
  - formularz wymaga nazwy,
  - komunikaty bledow sa wyswietlane przy polach.

### DELETE

- Trasa: `DELETE /groups/{group}`
- Kontroler: `GroupController::destroy`
- Dostep:
  - wlasciciel grupy,
  - administrator.
- Formularz zawiera token CSRF i metode `_method=DELETE`.
- Interfejs wymaga potwierdzenia usuniecia przez `confirm()`.
- Serwer ponownie sprawdza uprawnienia przed usunieciem.

## 3. Role i uprawnienia

### Rola `user`

Uzytkownik standardowy moze:

- tworzyc wlasne grupy,
- widziec i zarzadzac grupami, do ktorych nalezy,
- edytowac/usuwac tylko grupy, ktorych jest wlascicielem,
- dodawac rachunki i pozycje w dostepnych grupach,
- edytowac wlasny profil.

### Rola `admin`

Administrator moze:

- widziec wszystkie grupy,
- edytowac i usuwac grupy,
- korzystac z panelu administracyjnego,
- zmieniac profile i role uzytkownikow,
- usuwac konta innych uzytkownikow.

Ochrona tras administracyjnych jest realizowana przez middleware `admin`. Ochrona zasobow grupowych jest realizowana w kontrolerach metodami sprawdzajacymi wlasciciela, czlonkostwo lub role administratora.

## 4. Logika biznesowa ponad CRUD

### Automatyczny podzial rachunku

Klasa `BillSplitService` tworzy podzial kosztu rachunku:

1. Po dodaniu rachunku bez pozycji koszt jest dzielony rowno miedzy czlonkow grupy.
2. Po dodaniu pozycji paragonu podzial jest przeliczany na podstawie sumy pozycji.
3. Kwoty sa liczone w groszach, aby ograniczyc problemy zaokraglen.
4. Jezeli suma pozycji jest mniejsza niz kwota rachunku, brakujaca czesc jest dzielona rowno.
5. Jezeli suma pozycji jest wieksza niz kwota rachunku, udzialy sa normalizowane do kwoty rachunku.

### Minimalizacja liczby przelewow

Model `Group` udostepnia `getSettlementPlan()`:

1. Zlicza kwoty zaplacone przez kazdego uzytkownika.
2. Zlicza kwoty nalezne od kazdego uzytkownika.
3. Wyznacza bilans: `zaplacone - nalezne`.
4. Dzieli uczestnikow na dluznikow i wierzycieli.
5. Generuje plan przelewow, ktory rozlicza salda mozliwie mala liczba transakcji.

### Statystyki dla uzytkownika koncowego

Widok szczegolow grupy pokazuje:

- wydatki w biezacym miesiacu,
- wydatki z ostatnich 30 dni,
- najwyzszy rachunek,
- sredni rachunek,
- liczbe aktywnych czlonkow,
- liczbe rachunkow,
- salda uczestnikow,
- proponowane przelewy.

## 5. Wymagania technologiczne

Specyfikacja z dnia weryfikacji wskazuje PHP 8.5 i Laravel 13. Ten branch poprawia wymagania funkcjonalne, dokumentacyjne i bezpieczenstwo zaleznosci frontendowych. Aktualizacja glownego stacku wymaga osobnej migracji Composerem, poniewaz trzeba przebudowac `composer.lock`, sprawdzic kompatybilnosc pakietow Laravel oraz uruchomic pelny zestaw testow PHP w srodowisku z PHP i Composerem.

## 6. Pytania do projektu

Pytania ze strony sa lista zagadnien do odpowiedzi podczas obrony. Nie sa funkcjonalnoscia aplikacji. Odpowiedzi nalezy przygotowac na podstawie:

- tras w `routes/web.php`,
- kontrolerow w `app/Http/Controllers`,
- modeli w `app/Models`,
- migracji w `database/migrations`,
- widokow Blade w `resources/views`,
- testow w `tests/Feature`.
