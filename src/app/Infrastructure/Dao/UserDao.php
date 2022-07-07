<?php
namespace App\Infrastructure\Dao;

require_once __DIR__ . '/../../../vendor/autoload.php';

use PDO;

/**
 * ユーザー情報を操作するDAO
 */
final class UserDao extends Dao
{
    /**
     * DBのテーブル名
     */
    const TABLE_NAME = 'users';

    /**
     * ユーザーを追加する
     * @param  string $name
     * @param  string $mail
     * @param  string $password
     */
    public function create(string $name, string $email, string $password): void
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = sprintf(
            'INSERT INTO %s (name, email, password) VALUES (:name, :email, :password)',
            self::TABLE_NAME
        );
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':name', $name, PDO::PARAM_STR);
        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
        $statement->execute();
    }

    /**
     * ユーザーを検索する
     * @param  string $mail
     * @return array | null
     */
    public function findByEmail(string $email): ?array
    {
        $sql = sprintf(
            'SELECT * FROM %s WHERE email = :email',
            self::TABLE_NAME
        );
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        return $user ? $user : null;
    }
}
