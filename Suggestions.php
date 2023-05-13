<?php

namespace uzdevid\korrektor;

use yii\base\Exception;

/**
 * @property string $language
 * @property string $text
 * @property-read array $suggestions
 */
class Suggestions extends BaseMatn {
    private string $_text;
    private array $_suggestions = [];

    public string $method = '/suggestions';

    public function isCorrect(): bool {
        if (empty($this->text)) {
            throw new Exception('Text is required');
        }

        $raw = [
            'text' => $this->text
        ];

        $response = $this->curlExecute($this->url, $raw);

        $this->_suggestions = $response['data'];
        return !$response['errors'];
    }

    public function getText(): string {
        return $this->_text;
    }

    public function setText(string $text): static {
        $this->_text = strip_tags($text);
        return $this;
    }

    public function getSuggestions(): array {
        return $this->_suggestions;
    }

    public function highlight(): string {
        $replacements = [];

        foreach ($this->suggestions as $suggestion) {
            $word = $suggestion["word"];
            $suggestions = implode(', ', $suggestion["suggestions"]);
            $replacement = "<span style='color: red;' title='{$suggestions}'>$word</span>";
            $replacements[$word] = $replacement;
        }

        return str_replace(array_keys($replacements), array_values($replacements), $this->text);
    }

}