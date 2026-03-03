<?php

declare(strict_types=1);

namespace JSzczypk\GoogleClosureCompiler;

enum CompilationLevel: string
{
    case WHITESPACE_ONLY = 'WHITESPACE_ONLY';
    case SIMPLE_OPTIMIZATIONS = 'SIMPLE_OPTIMIZATIONS';
    case ADVANCED = 'ADVANCED';
}
