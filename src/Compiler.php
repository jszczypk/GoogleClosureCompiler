<?php

declare(strict_types=1);

namespace JSzczypk\GoogleClosureCompiler;

class Compiler
{

    public string $remoteCompilerUrl = 'https://jscompressor.treblereel.dev/compile';
    public string $compilerBinary = 'npx google-closure-compiler';

    public function __construct(
      public CompilationLevel $compilationLevel = CompilationLevel::WHITESPACE_ONLY,
      public LanguageIn $languageIn = LanguageIn::ECMASCRIPT5,
      public LanguageOut $languageOut = LanguageOut::STABLE,
    )
    {
    }

    public function remoteCompile(string $javascript): string
    {

        $post = [
            'compilation_level' => $this->compilationLevel->value,
            'output_format' => 'text',
            'output_info' => 'compiled_code',
            'js_code' => $javascript,
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->remoteCompilerUrl,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($post),
        ]);

        $result = curl_exec($curl);

        $info = curl_getinfo($curl);
        if ($info === false) {
            throw new Exception('Błąd podczas pobierania informacji o odpowiedzi.');
        }

        if ($result === false) {
            throw new Exception('Błąd podczas otrzymywania danych.');
        }

        if (empty(trim($result))) {
            throw new Exception('Puste dane.');
        }

        return $result;
    }

    public function compile(
        string $code,
        ?string $sourceMapFile = null,
    ): string {
        $cmd = [ 
            $this->compilerBinary,
            '--language_in', escapeshellarg($this->languageIn->value),
            '--language_out', escapeshellarg($this->languageOut->value),
            '--compilation_level', escapeshellarg($this->compilationLevel->value),
        ];

        if (!is_null($sourceMapFile)) {
            $cmd[] = '--create_source_map';
            $cmd[] = escapeshellarg($sourceMapFile);
            $cmd[] = '--source_map_include_content';
        }

        // TODO usunąć --strict_mode_input=false po usunięciu starych bibliotek
        $cmd[] = '--strict_mode_input=false';

        $cmd = implode(' ', $cmd);

        $descriptorspec = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open($cmd, $descriptorspec, $pipes, null, ['PATH' => '/usr/bin']);

        if (!is_resource($process)) {
            throw new Exception('Nie mogę uruchomić programu.');
        }

        fwrite($pipes[0], $code);
        fclose($pipes[0]);

        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        $stderr = str_replace("The compiler is waiting for input via stdin.\n", '', $stderr);

        $returnValue = proc_close($process);

        if ($returnValue !== 0) {
            throw new Exception('Błąd podczas uruchamiania programu: '.$stderr);
        }
        if ($stderr) {
            trigger_error($stderr, E_USER_WARNING);
        }

        return $stdout;
    }
}
