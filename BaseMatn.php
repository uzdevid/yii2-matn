<?php

namespace uzdevid\matn;

use yii\base\Component;
use yii\base\Exception;

/**
 * @property string $token
 * @property string $url
 */
class BaseMatn extends Component {
    public string $endpointUrl = 'https://matn.uz/api';
    public string $version = '/v1';
    public string $method;
    public int $maxChars = 255;
    private string $_token = '';

    public function getToken(): string {
        return $this->_token;
    }

    public function setToken(string $token): void {
        $this->_token = $token;
    }

    protected function getUrl(): string {
        return $this->endpointUrl . $this->version . $this->method;
    }

    protected function curlExecute(string $url, array $postFields) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($postFields, JSON_UNESCAPED_UNICODE),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->token}",
                'Content-Type: application/json'
            ],
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true);
    }

    protected function splitText(): array {
        $sentences = preg_split('/(?<=[.?!])/', $this->text);
        $chunks = [];
        $currentChunk = '';

        foreach ($sentences as $sentence) {
            if (mb_strlen($currentChunk . $sentence) + 1 > $this->maxChars) {
                $chunks[] = $currentChunk;
                $currentChunk = $sentence;
            } elseif (mb_strlen($currentChunk) == 0) {
                $currentChunk = $sentence;
            } else {
                $currentChunk .= $sentence;
            }
        }

        if (!empty($currentChunk)) {
            $chunks[] = $currentChunk;
        }

        return $chunks;
    }
}