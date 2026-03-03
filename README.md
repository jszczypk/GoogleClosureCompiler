# Google Closure Compiler PHP Wrapper

A simple PHP wrapper for Google Closure Compiler.

## Installation

```bash
composer require jszczypk/google-closure-compiler
```

## Setup

Requires PHP 8.1+ and the `curl` extension (for remote compilation).  
For local compilation, you need Node and npm package google-closure-compiler.

## Usage

```php
use JSzczypk\GoogleClosureCompiler\Compiler;
use JSzczypk\GoogleClosureCompiler\CompilationLevel;

$compiler = new Compiler(
    compilationLevel: CompilationLevel::SIMPLE_OPTIMIZATIONS
);

$yourJavaScriptCode = 'console.log("Hello World!");';

$compiledCode = $compiler->compile($yourJavaScriptCode);
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
