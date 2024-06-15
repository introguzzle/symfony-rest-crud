<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20240613010102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Create users table
        $usersTable = $schema->createTable('users');
        $usersTable->addColumn('id', Types::INTEGER, ['autoincrement' => true]);
        $usersTable->addColumn('name', Types::STRING, ['notnull' => true]);
        $usersTable->addColumn('email', Types::STRING, ['notnull' => true]);
        $usersTable->addColumn('password', Types::STRING, ['notnull' => true]);
        $usersTable->addColumn('roles', Types::JSON, ['notnull' => true]);
        $usersTable->setPrimaryKey(['id']);
        $usersTable->addUniqueIndex(['name'], 'users_name_unique');
        $usersTable->addUniqueIndex(['email'], 'users_email_unique');
        $usersTable->addColumn('created_at', 'datetime');
        $usersTable->addColumn('updated_at', 'datetime');

        // Create books table
        $booksTable = $schema->createTable('books');
        $booksTable->addColumn('id', Types::INTEGER, ['autoincrement' => true]);
        $booksTable->addColumn('title', Types::STRING, ['notnull' => true]);
        $booksTable->addColumn('author', Types::STRING, ['notnull' => true]);
        $booksTable->addColumn('published_at', 'datetime');
        $booksTable->addColumn('created_at', 'datetime');
        $booksTable->addColumn('updated_at', 'datetime');
        $booksTable->setPrimaryKey(['id']);
        $booksTable->addUniqueIndex(['title'], 'books_title_unique');
        $booksTable->addColumn('user_id', Types::INTEGER, ['notnull' => true]);
        $booksTable->addForeignKeyConstraint('users', ['user_id'], ['id'], ['onDelete' => 'CASCADE']);

        // Create genres table
        $genresTable = $schema->createTable('genres');
        $genresTable->addColumn('id', Types::INTEGER, ['autoincrement' => true]);
        $genresTable->addColumn('name', Types::STRING, ['notnull' => true]);
        $genresTable->addColumn('created_at', 'datetime');
        $genresTable->addColumn('updated_at', 'datetime');
        $genresTable->setPrimaryKey(['id']);
        $genresTable->addUniqueIndex(['name'], 'genres_name_unique');

        // Create book_genre table
        $bookGenreTable = $schema->createTable('book_genre');
        $bookGenreTable->addColumn('book_id', Types::INTEGER, ['notnull' => true]);
        $bookGenreTable->addColumn('genre_id', Types::INTEGER, ['notnull' => true]);
        $bookGenreTable->addForeignKeyConstraint('books', ['book_id'], ['id'], ['onDelete' => 'CASCADE']);
        $bookGenreTable->addForeignKeyConstraint('genres', ['genre_id'], ['id'], ['onDelete' => 'CASCADE']);
        $bookGenreTable->setPrimaryKey(['book_id', 'genre_id']);
        $bookGenreTable->addColumn('created_at', 'datetime');
        $bookGenreTable->addColumn('updated_at', 'datetime');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('book_genre');
        $schema->dropTable('genres');
        $schema->dropTable('books');
        $schema->dropTable('users');
    }
}