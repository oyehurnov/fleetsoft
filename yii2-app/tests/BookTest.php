<?php
namespace tests;

class BookTest extends TestCase
{
    private static $token;

    public static function setUpBeforeClass(): void
    {
        // First register user
        [$status, $data] = self::postStatic('/users', [
            'username' => 'apitester',
            'email' => 'apitester@example.com',
            'password' => 'secret123'
        ]);

        // Then login
        [$status, $loginData] = self::postStatic('/auth/login', [
            'username' => 'apitester',
            'password' => 'secret123'
        ]);

        self::$token = $loginData['token'] ?? null;
    }

    // --- Static helper for self::setUpBeforeClass() ---
    private static function postStatic($url, $data = [], $headers = [])
    {
        $ch = curl_init("http://127.0.0.1:8080{$url}");
        $defaultHeaders = ['Content-Type: application/json'];
        $headers = array_merge($defaultHeaders, $headers);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => json_encode($data),
        ]);

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        return [$info['http_code'] ?? 0, json_decode($response, true)];
    }

    // ---- Tests ----

    public function testCreateBook()
    {
        [$status, $data] = $this->post('/books', [
            'title' => 'API Testing Book',
            'author' => 'Jane QA',
            'description' => 'Testing via PHPUnit',
            'published_at' => '2020-01-01'
        ], ['Authorization: Bearer ' . self::$token]);

        $this->assertEquals(201, $status, "Book creation failed");
        $this->assertEquals('API Testing Book', $data['title']);
    }

    public function testListBooks()
    {
        [$status, $data] = $this->get('/books');
        $this->assertEquals(200, $status, 'Book list failed');

        // If controller returns a plain array
        $this->assertIsArray($data, 'Expected array of books');
        $this->assertNotEmpty($data, 'Book list should not be empty');
    }
}
