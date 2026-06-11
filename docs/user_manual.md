# BillsBuddy - podrecznik uzytkownika i dokumentacja wymagan

Ten dokument opisuje sposob korzystania z aplikacji BillsBuddy oraz wskazuje, jak projekt spelnia wymagania przedmiotu Aplikacje Internetowe 2025/2026 dla tematu wspolnych rozliczen wydatkow.

## 1. Cel aplikacji

BillsBuddy sluzy do rozliczania kosztow w grupach, np. podczas wyjazdow, imprez lub wspolnego mieszkania. Uzytkownik tworzy grupe, dodaje uczestnikow, zapisuje wydatki i pozycje z paragonu, a system automatycznie wylicza salda oraz proponuje przelewy potrzebne do rozliczenia grupy.

## 2. Role i zakres dostepu

| Rola | Uprawnienia |
| --- | --- |
| Gosc niezalogowany | Moze wejsc na strone glowna, przegladac publiczny katalog grup, otworzyc publiczny podglad grupy, zobaczyc podstawowe statystyki, ostatnie wydatki, salda i propozycje rozliczen. Nie moze tworzyc, edytowac ani usuwac danych. |
| Uzytkownik | Moze tworzyc wlasne grupy, przegladac grupy, do ktorych nalezy, dodawac uczestnikow, dodawac wydatki i pozycje paragonu oraz usuwac rachunki w swoich grupach. Moze edytowac i usunac grupe, ktorej jest wlascicielem. |
| Administrator | Ma uprawnienia zwyklego uzytkownika oraz widzi wszystkie grupy. Moze edytowac i usuwac dowolna grupe, a w panelu administratora zarzadzac profilami uzytkownikow, rolami oraz kontami. |

Uprawnienia sa egzekwowane w trasach Laravel i kontrolerach:

- publiczne trasy read-only: `GET /katalog-grup`, `GET /katalog-grup/{group}`,
- trasy prywatne: `groups`, `bills`, `bill-items`, `profile` w middleware `auth` i `verified`,
- panel administratora: prefiks `/admin` w middleware `admin`,
- sprawdzanie wlasciciela lub czlonkostwa grupy: metody autoryzacyjne w `GroupController`, `BillController` i `BillItemController`.

## 3. Logowanie i konta demonstracyjne

Po uruchomieniu seederow dostepne sa konta:

- administrator: `oliwia@example.com`,
- uzytkownicy: `adam@example.com`, `ewa@example.com`,
- haslo dla kont demo: `password`.

Uzytkownik moze zalozyc konto przez formularz rejestracji, zalogowac sie, edytowac profil oraz usunac wlasne konto z poziomu widoku profilu.

## 4. Publiczny katalog grup

Katalog jest dostepny bez logowania pod adresem `/katalog-grup`.

### Funkcje katalogu

- wyszukiwanie grup po nazwie i opisie,
- filtrowanie po minimalnej sumie wydatkow,
- filtrowanie po minimalnej liczbie czlonkow,
- sortowanie po dacie, nazwie, sumie wydatkow lub liczbie czlonkow,
- wejscie w publiczny podglad szczegolow grupy.

Publiczny podglad jest tylko do odczytu. Gosc nie widzi formularzy dodawania wydatkow, edycji grupy ani usuwania danych. Proba uzycia prywatnych tras modyfikujacych przekierowuje do logowania.

## 5. CRUD zasobu `groups`

Glownym zasobem CRUD sa grupy rozliczeniowe. Grupa jest powiazana relacjami z:

- wlascicielem (`groups.owner_id` -> `users.id`),
- uczestnikami (`group_user`),
- rachunkami (`bills.group_id`),
- pozycjami paragonu i podzialami kosztow przez rachunki.

### CREATE - tworzenie grupy

Sciezka: `POST /groups`

Formularz znajduje sie w widoku "Moje grupy". Uzytkownik podaje:

- nazwe grupy,
- opis grupy.

Walidacja po stronie klienta:

- nazwa jest wymagana,
- nazwa ma limit 255 znakow,
- opis ma limit 1000 znakow.

Walidacja po stronie serwera:

- `name`: wymagane, tekst, maksymalnie 255 znakow, unikalne w tabeli `groups`,
- `description`: opcjonalny tekst, maksymalnie 1000 znakow.

Po utworzeniu system automatycznie ustawia zalogowanego uzytkownika jako wlasciciela i dodaje go do uczestnikow grupy.

### READ - lista i szczegoly grup

Sciezki:

- `GET /groups` - prywatna lista grup uzytkownika,
- `GET /groups/{group}` - prywatne szczegoly grupy dla czlonkow i administratorow,
- `GET /katalog-grup` - publiczny katalog,
- `GET /katalog-grup/{group}` - publiczny podglad.

Lista "Moje grupy" obsluguje:

- wyszukiwanie po nazwie i opisie,
- filtrowanie po wlascicielu dla administratora,
- sortowanie: nazwa A-Z, nazwa Z-A, najnowsze, najstarsze, najwiecej wydatkow, najwiecej osob.

Widok szczegolow grupy pokazuje:

- uczestnikow,
- bilans kazdego uczestnika,
- sume wydatkow,
- szybkie statystyki,
- propozycje splat,
- historie rachunkow z filtrami po nazwie, platniku i zakresie kwot,
- pozycje paragonu dodane do rachunkow.

### UPDATE - edycja grupy

Sciezka: `PUT/PATCH /groups/{group}`

Edycja jest dostepna dla wlasciciela grupy i administratora. Formularz ma takie same reguly jak tworzenie:

- nazwa wymagana, unikalna, maksymalnie 255 znakow,
- opis opcjonalny, maksymalnie 1000 znakow.

Zwykly uzytkownik nie moze edytowac grup, ktorych nie jest wlascicielem.

### DELETE - usuwanie grupy

Sciezka: `DELETE /groups/{group}`

Usuwanie jest dostepne dla wlasciciela grupy i administratora. Formularz usuwania uzywa tokenu CSRF, metody `DELETE` symulowanej przez Blade (`@method('DELETE')`) i potwierdzenia po stronie klienta przez `confirm()`.

## 6. Zasoby zalezne od grupy

### Uczestnicy grupy

W widoku szczegolow grupy mozna dodac uczestnika po adresie e-mail. System sprawdza:

- czy e-mail jest poprawny,
- czy istnieje konto o takim e-mailu,
- czy uzytkownik nie jest juz czlonkiem grupy.

### Rachunki

Rachunek jest zasobem zaleznym od grupy (`bills.group_id`). Formularz dodawania rachunku zawiera:

- nazwe wydatku,
- kwote,
- platnika wybieranego z listy czlonkow grupy.

Walidacja po stronie klienta:

- nazwa wymagana, limit 255 znakow, nie moze skladac sie wylacznie z cyfr,
- kwota wymagana, minimalnie 0.01, krok 0.01,
- platnik wybierany z listy, bez recznego wpisywania identyfikatora.

Walidacja po stronie serwera:

- `description`: wymagane, tekst, maksymalnie 255 znakow, nie tylko cyfry,
- `amount`: wymagane, liczba, minimum 0.01,
- `payer_id`: wymagane, musi wskazywac czlonka tej grupy.

### Pozycje z paragonu

Do rachunku mozna dodac pozycje paragonu:

- nazwa pozycji,
- cena jednostkowa,
- liczba sztuk.

System przelicza podzial kosztu po dodaniu pozycji. Aktualnie pozycje sa dzielone po rowno na wszystkich czlonkow grupy, a roznica pomiedzy suma pozycji i kwota rachunku jest obslugiwana automatycznie przez logike biznesowa.

## 7. Panel administratora

Panel administratora znajduje sie pod `/admin/users`.

Administrator moze:

- wyszukiwac uzytkownikow po imieniu, nazwie lub e-mailu,
- filtrowac po roli,
- zmieniac nazwe uzytkownika,
- zmieniac role `user`/`admin`,
- usuwac konta uzytkownikow z zabezpieczeniem przed usunieciem wlasnego konta.

Administrator widzi rowniez wszystkie grupy na liscie "Moje grupy" i moze otwierac, edytowac oraz usuwac zasoby uzytkownikow.

## 8. Nietrywialna logika biznesowa

Projekt wykracza poza prosty CRUD dzieki logice rozliczen:

1. Automatyczny rowny podzial nowego rachunku
   - Po dodaniu rachunku `BillSplitService::createInitialEqualSplit()` tworzy udzialy kosztowe dla uczestnikow grupy.
   - Platnik nie oddaje sam sobie, a pozostali czlonkowie otrzymuja wpisy dlugu.

2. Przeliczanie rachunku na podstawie pozycji paragonu
   - `BillSplitService::recalculateFromItems()` sumuje pozycje, dzieli je na uczestnikow i koryguje groszowe reszty.
   - Jesli suma pozycji jest mniejsza od kwoty rachunku, brakujaca kwota jest dzielona po rowno.
   - Jesli suma pozycji przekracza kwote rachunku, udzialy sa proporcjonalnie skalowane do wartosci rachunku.

3. Bilans uczestnikow
   - Model `Group` wylicza, ile kazdy uczestnik zaplacil, ile powinien poniesc kosztow i jaki ma bilans.
   - Wynik dodatni oznacza kwote do odzyskania, wynik ujemny kwote do oddania.

4. Propozycje splat
   - `Group::getSettlementPlan()` tworzy liste przelewow pomiedzy dluznikami i wierzycielami.
   - Algorytm laczy najwieksze salda dodatnie i ujemne, aby ograniczyc liczbe przelewow potrzebnych do rozliczenia grupy.

5. Statystyki wydatkow
   - Widok grupy pokazuje wydatki z biezacego miesiaca, ostatnich 30 dni, najwyzszy rachunek, srednia kwote rachunku, liczbe aktywnych czlonkow i liczbe rachunkow.

## 9. Odwzorowanie wymagan ocen

### Ocena 3.0

- Aplikacja udostepnia zasoby przez UI.
- Zaimplementowano CRUD grup z walidacja klienta i serwera.
- Grupy maja relacje z uzytkownikami, rachunkami i pozycjami.
- Listy maja filtrowanie i sortowanie.
- Administrator zarzadza uzytkownikami i zasobami.

### Ocena 4.0

- Istnieja role `admin` i `user`.
- Uzytkownicy zarzadzaja wlasnymi grupami.
- Administrator zarzadza profilami, rolami i zasobami uzytkownikow.
- Niezalogowany gosc ma publiczny dostep read-only do katalogu i szczegolow grup.
- Edycja i usuwanie pozostaja zabezpieczone logowaniem, CSRF i kontrola uprawnien.

### Ocena 5.0

- System zawiera nietrywialna logike biznesowa rozliczen.
- Aplikacja automatycznie dzieli koszty, przelicza salda i proponuje przelewy.
- Uzytkownik otrzymuje statystyki oraz podpowiedzi rozliczen, czyli funkcje wykraczajace poza proste CRUD.

