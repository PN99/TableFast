# Blueprint projektu: TableFast

Tento dokument slouží jako přesná specifikace pro implementaci rezervačního systému TableFast. Systém je navržen s důrazem na uživatelskou přívětivost, efektivitu personálu a technickou preciznost.

---

## 1. Pohled zákazníka (Frontend)
Cílem je maximální konverze a jednoduchost. Rezervace probíhá v jedné plynulé **Livewire komponentě (Wizardu)** bez nutnosti separátní registrace.

### Kroky rezervace:
1.  **Výběr parametrů**: Datum, čas, počet osob, délka rezervace.
    *   Volba: Jídlo (`wants_food`) vs. jen drink.
    *   Možnost diskrétní poznámky (např. "kočárek").
2.  **Chytrá identifikace**: Zadání e-mailu.
    *   *Existující uživatel*: Zobrazí se pole pro heslo.
    *   *Nový uživatel*: Rozbalí se pole pro jméno, telefon a vytvoření hesla.
3.  **Potvrzení**: Systém automaticky najde vhodný stůl (zákazník stůl nevybírá) a vytvoří rezervaci.
    *   Zákazník vidí potvrzení a v profilu má přehled všech svých rezervací.

**Ochrana**: Implementace **Spatie Honeypot** proti spam botům bez nutnosti otravné reCAPTCHA.

---

## 2. Pohled personálu a majitele (Administrace)
Rozhraní zaměřené na přehlednost a rychlost obsluhy.

*   **Dashboard**: Chronologický seznam dnešních rezervací (kdo, kdy, stůl, jídlo/pití).
*   **Správa rezervací**: Změna stavů (`Pending` -> `Confirmed` / `Rejected` / `Cancelled`).
*   **Správa prostor (Zóny a Stoly)**:
    *   Rozdělení na zóny (např. Zahrádka, Salónek, Bar) formou grafických karet.
    *   Možnost hromadně uzavřít celou zónu (např. kvůli dešti) nebo jednotlivý stůl.
*   **Nastavení podniku**:
    *   Otevírací doba pro každý den v týdnu.
    *   **Kitchen Close Time**: Čas zavření kuchyně, který omezí rezervace na jídlo.
    *   Přepínač pro automatické schvalování rezervací.

---

## 3. Technologický Stack
*   **Backend**: Laravel 11 (PHP 8.2+)
*   **Frontend**: Livewire 3 + Alpine.js + Tailwind CSS
*   **Databáze**: MySQL
*   **Autentizace**: Laravel Breeze (Livewire edice)
*   **Testování**: Pest PHP
*   **Lokální prostředí**: Laravel Sail (Docker)

---

## 4. Návrh Databáze (ER Model)

### Tabulka: `users`
*   `id`, `name`, `email`, `password`, `phone`, `is_admin` (boolean)

### Tabulka: `restaurant_settings`
*   `id`, `name`, `contact_email`, `contact_phone`, `auto_confirm_reservations` (boolean)

### Tabulka: `opening_hours`
*   `id`, `day_of_week` (1-7), `is_closed`, `open_time`, `close_time`, `kitchen_close_time`

### Tabulka: `zones`
*   `id`, `name`, `is_open` (boolean)

### Tabulka: `tables`
*   `id`, `zone_id` (FK), `name`, `capacity` (int), `is_active` (boolean)

### Tabulka: `reservations`
*   `id`, `user_id` (FK), `table_id` (FK)
*   `start_time` (datetime), `end_time` (datetime)
*   `guest_count` (int), `wants_food` (boolean), `note` (text)
*   `status` (enum: `pending`, `confirmed`, `cancelled`, `rejected`)

---

## 5. Klíčové technické požadavky (Seniority Features)
*   **Race Conditions Prevention**: Použití databázových transakcí a zamykání řádků (`lockForUpdate()`) při přiřazování stolů.
*   **Overlap Detection**: Logika pro kontrolu překryvů rezervací (včetně kontroly `end_time`).
*   **Robustní Seedery**: Realistická testovací data (stoly, zóny, otevírací doba, admin účet).
*   **Dokumentace**: Kvalitní README s návodem pro Sail, testy a admin přístupy.
