<?php

namespace MintHCM\MintCLI\Services;

class ConfigOverrideService
{
    public function writeConfigOverride(string $file_path, array $config): void
    {
        $config_content = file_exists($file_path)
            ? file_get_contents($file_path) . "\n"
            : "<?php\n\n";

        foreach ($this->iterateOptions($config) as $option) {
            $path = $this->buildArrayElementPath($option['path']);
            $value = $this->buildValue($option['value']);
            $config_content .= "\$mint_config{$path} = $value;\n";
        }

        file_put_contents($file_path, $config_content);
    }

    protected function iterateOptions(array $array, array $path = []) {
        foreach ($array as $key => $value) {
            $current_path = array_merge($path, [$key]);
            if (!is_array($value)) {
                yield [
                    'path' => $current_path,
                    'value' => $value,
                ];
                continue;
            }

            yield from $this->iterateOptions($value, $current_path);
        }
    }

    protected function buildArrayElementPath(array $path): string
    {
        return implode('', array_map(function ($key) {
            return "[" . $this->buildValue($key) . "]";
        }, $path));
    }

    protected function buildValue($value): string
    {
        if (is_int($value) || is_float($value)) {
            return $value;
        }

        return "'$value'";
    }
}
