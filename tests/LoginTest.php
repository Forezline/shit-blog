<?php
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        // створення фейкової PDO з мок-об'єктом
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetch')->willReturn([
            'id' => 1,
            'email' => 'test@example.com',
            'password' => password_hash('secret', PASSWORD_DEFAULT),
            'name' => 'Test User'
        ]);

        $this->pdo = $this->createMock(PDO::class);
        $this->pdo->method('prepare')->willReturn($stmt);
    }

    public function testLoginSuccess()
    {
        $result = loginUser($this->pdo, 'forezline@gmail.com', '$2y$10$R2xN2eyrAGcaKQgelcic.uWWd5I6oy50lkJG1pPApsG.8oYEdWgeW');
        $this->assertFalse($result['success']);
    }

    public function testLoginWithEmptyFields()
    {
        $result = loginUser($this->pdo, '', '');
        $this->assertFalse($result['success']);
        $this->assertEquals("Усі поля обов'язкові.", $result['error']);
    }

    public function testLoginWithWrongPassword()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetch')->willReturn([
            'id' => 1,
            'email' => 'test@example.com',
            'password' => password_hash('correct', PASSWORD_DEFAULT),
            'name' => 'Test User'
        ]);

        $pdo = $this->createMock(PDO::class);
        $pdo->method('prepare')->willReturn($stmt);

        $result = loginUser($pdo, 'test@example.com', 'wrong');
        $this->assertFalse($result['success']);
        $this->assertEquals("Невірна пошта або пароль.", $result['error']);
    }
}
require_once(__DIR__ . '/../includes/function/functions.php');
