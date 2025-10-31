<?php
namespace tests;

class AuthTest extends TestCase
{
    private static $token;

    public function testUserRegistration()
    {
        $username = 'user_' . uniqid();
        $email = $username . '@example.com';

        [$status, $data] = $this->post('/users', [
            'username' => $username,
            'email' => $email,
            'password' => 'secret123'
        ]);

        if ($status !== 201) {
            fwrite(STDERR, "Registration failed: " . json_encode($data) . "\n");
        }

        $this->assertEquals(201, $status, 'Registration failed');
        $this->assertArrayHasKey('id', $data);
    }

    public function testLoginAndGetToken()
    {
        [$status, $data] = $this->post('/auth/login', [
            'username' => 'testuser',
            'password' => 'secret123'
        ]);

        $this->assertEquals(200, $status, 'Login failed');
        $this->assertArrayHasKey('token', $data);

        self::$token = $data['token'];
    }

    public static function getToken()
    {
        return self::$token;
    }
}
