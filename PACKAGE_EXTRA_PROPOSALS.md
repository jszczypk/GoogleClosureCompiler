Oto propozycja przygotowania komponentu `SmartB2B\GoogleClosureCompiler` do wydzielenia jako niezależny pakiet Composer.

### 1. Struktura katalogów pakietu
Sugerowany układ zgodny ze standardami PSR-4:
```text
smartb2b/google-closure-compiler/
├── bin/                # Skrypty wykonywalne (np. wrapper CLI)
├── src/
│   ├── Contract/       # Interfejsy (Public API)
│   ├── Exception/      # Dedykowane wyjątki
│   ├── Model/          # Value Objects (Options, Result)
│   ├── Compiler.php    # Główna implementacja
│   └── Enums/          # Istniejące i nowe enumy
├── tests/              # Unit & Integration tests
├── composer.json
└── README.md
```

### 2. Public API (Interfejsy)
Wprowadzenie interfejsu pozwoli na łatwe podmienianie implementacji (np. Mock w testach):
```php
interface CompilerInterface {
    public function compile(string $jsCode, CompileOptions $options): CompileResult;
    public function compileFiles(array $filePaths, CompileOptions $options): CompileResult;
}
```
`CompileResult` powinien zawierać skompilowany kod, Source Map oraz tablicę ewentualnych błędów/ostrzeżeń.

### 3. Konfiguracja
Zamiast stałych, użyj klasy konfiguracji wstrzykiwanej przez konstruktor:
- **Lokalnie:** ścieżka do `java` i pliku `.jar` kompilatora.
- **Zdalnie (API):** URL endpointa (np. oficjalne API Google lub własny mikrousługa).
- Obsługa przez zmienne środowiskowe (`.env`) dla łatwej integracji w różnych systemach.

### 4. Strategia testów
1.  **Unit Tests:** Testowanie logiki budowania parametrów CLI oraz walidacji opcji (PHPUnit).
2.  **Mock Tests:** Symulowanie odpowiedzi z API Google (Guzzle Mock Handler).
3.  **Integration Tests:** Test „smoke” – próba skompilowania prostego skryptu przy użyciu lokalnego JARa (jeśli dostępny w środowisku CI).

### 5. Plan migracji BC (Deprecations)
1.  **Shim Class:** Pozostaw starą klasę (bez namespace lub w starym miejscu), która dziedziczy po nowej lub ją dekoruje.
2.  **Deprecation Notice:** W konstruktorze starej klasy dodaj:
    `trigger_error('Class X is deprecated, use SmartB2B\GoogleClosureCompiler instead.', E_USER_DEPRECATED);`
3.  **Composer Autoload:** Dodaj mapowanie `classmap` dla starych plików w `composer.json` na okres przejściowy.

### 6. Co jeszcze poprawić w obecnym kodzie (TOP 10)
1.  **Dependency Injection:** Wstrzyknij klienta HTTP (PSR-18) oraz Loggera (PSR-3).
2.  **Custom Exceptions:** Zamień generyczne `\Exception` na np. `CompilationFailedException`.
3.  **Result Object:** Przestań zwracać surowy string; zwracaj obiekt z metadanymi (czas kompilacji, statystyki kompresji).
4.  **Logging:** Loguj każde wywołanie kompilatora i błędy do standardowego interfejsu Loggera.
5.  **Validation:** Dodaj walidację kodu wejściowego przed wysyłką (czy nie jest pusty).
6.  **Timeout Handling:** Dodaj jawny parametr timeout dla procesów CLI i żądań sieciowych.
7.  **Source Maps Support:** Dodaj natywną obsługę generowania i pobierania map źródeł.
8.  **Strict Types:** Upewnij się, że we wszystkich nowych plikach jest `declare(strict_types=1);`.
9.  **Cache:** Opcjonalny mechanizm cache'owania wyników (hash treści JS -> wynik) dla przyspieszenia powtarzalnych buildów.
10. **Async/Parallel:** Jeśli kompilujesz wiele plików, rozważ wsparcie dla asynchronicznych żądań (np. ReactPHP lub Guzzle Promises).
