<?php

require_once MODELS . DS . 'Api.php';

class GeminiApi extends Api {
  private $model = 'gemini-2.0-flash';
  private $apiKey;
  private $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/';

  public function __construct() {
    if (defined('GEMINI_API_KEY')) {
        $this->apiKey = $_ENV['GEMINI_API_KEY'];
    } else {
        $this->apiKey = null;
        error_log('GEMINI_API_KEY is not defined. Check config.php and Replit secrets.');
    }
  }

  public function generateContent($prompt) {
      if (!$this->apiKey) {
          error_log('API key is missing.');
          return false;
      }

      $endPoint = $this->baseUrl . $this->model . ':generateContent';

      $payload = [
          'contents' => [
              [
                  'parts' => [
                      ['text' => $prompt]
                  ]
              ]
          ]
      ];

      $headers = [
          'X-goog-api-key: '. $this->apiKey .'',
          'Content-Type: application/json'
      ];

      $response = $this->makeRequest($endPoint, $payload, 'POST', $headers);

      $text = $response['candidates'][0]['content']['parts'][0]['text'] ?? 'No review generated.';
      return $text;
      die;
  }
}

?>