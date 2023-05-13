<?php

namespace uzdevid\matn;

/**
 * @property string $language
 * @property string $text
 * @property-read array $errors
 */
class Correct extends BaseMatn {
    private string $_text;
    private array $_errors = [];
    public string $method = '/correct';

    public function isCorrect(): bool {
        if (empty($this->text)) {
            throw new Exception('Text is required');
        }

        $hasError = false;
        foreach ($this->splitText() as $chunk) {
            $raw = [
                'text' => $chunk
            ];

            $response = $this->curlExecute($this->url, $raw);

            if ($response['errors']) {
                $hasError = true;
            }

            $this->_errors = array_merge($response['data'], $this->_errors);
        }

        return $hasError;
    }

    public function getText(): string {
        return $this->_text;
    }


    public function setText(string $text): static {
        $this->_text = strip_tags($text);
        $this->_text = preg_replace('/\s+/', ' ', $this->_text);
        $this->_text = preg_replace('/\s*\n\s*/', "\n", $this->_text);
        return $this;
    }

    public function getErrors(): array {
        return $this->_errors;
    }
}