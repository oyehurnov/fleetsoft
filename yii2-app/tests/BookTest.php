<?php
namespace tests;

require_once __DIR__ . '/AuthTest.php';

class BookTest extends TestCase
{
    private static $token;

    public static function setUpBeforeClass(): void
    {
        // First, ensure we can login
        $authTest = new AuthTest();
        $authTest->testUserRegistration();
        $authTest->testLoginAndGetToken();
        self::$token = AuthTest::getToken();
    }

    public function testCreateBook()
    {
        [$status, $data] = $this->post('/books', [
            'title' => 'Test Book',
            'author' => 'John Doe',
            'description' => 'Testing book creation',
            'published_at' => '2020-01-01'
        ], ['Authorization: Bearer ' . self::$token]);

        $this->assertEquals(201, $status);
        $this->assertEquals('Test Book', $data['title']);
    }

    public function testListBooks()
    {
        [$status, $data] = $this->get('/books');
        $this->assertEquals(200, $status);
        $this->assertArrayHasKey('items', $data);
        $this->assertNotEmpty($data['items']);
    }
}
