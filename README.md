# Google Closure Compiler PHP Wrapper

A simple PHP wrapper for Google Closure Compiler.

## Installation

```bash
composer require jszczypk/google-closure-compiler
```

## Setup

Requires PHP 8.1+ and the `curl` extension (for remote compilation).  
For local compilation, you need Node.js and the `npx` binary available in your `PATH`.

## Usage

```php
use JSzczypk\GoogleClosureCompiler\Compiler;
use JSzczypk\GoogleClosureCompiler\CompilationLevel;

$compiler = new Compiler(
    compilationLevel: CompilationLevel::SIMPLE_OPTIMIZATIONS
);

$yourJavaScriptCode = 'console.log("Hello World!");';

// Remote compile using closure-compiler.appspot.com API
$compiledCode = $compiler->compile($yourJavaScriptCode);

// Local compile using npx
$compiledCodeLocal = $compiler->localCompile($yourJavaScriptCode);
```

### Advanced Usage

You can customize `LanguageIn` and `LanguageOut`:

```php
use JSzczypk\GoogleClosureCompiler\Compiler;
use JSzczypk\GoogleClosureCompiler\LanguageIn;
use JSzczypk\GoogleClosureCompiler\LanguageOut;
use JSzczypk\GoogleClosureCompiler\CompilationLevel;

$compiler = new Compiler(
    compilationLevel: CompilationLevel::ADVANCED_OPTIMIZATIONS,
    languageIn: LanguageIn::ECMASCRIPT_NEXT,
    languageOut: LanguageOut::ECMASCRIPT5
);
```

## Publishing to Packagist

1. Push this repository to GitHub (e.g., `jszczypk/google-closure-compiler`).
2. Go to [Packagist.org](https://packagist.org/) and log in.
3. Click **Submit** in the top navigation bar.
4. Paste the URL of your GitHub repository.
5. Set up the GitHub integration in your Packagist account to enable auto-updating the package on push.
