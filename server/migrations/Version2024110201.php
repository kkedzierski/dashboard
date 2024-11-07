<?php

namespace App\Migrations;

use App\Kernel\Database\Migration\AbstractMigration;

final class Version2024110201 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add User, NewsPost migrations tables';
    }

    public function up(): void
    {
        $this->addSql('CREATE TABLE user (
            id CHAR(36) NOT NULL, 
            email VARCHAR(255) NOT NULL, 
            username VARCHAR(255) NOT NULL, 
            lastname VARCHAR(255) DEFAULT NULL, 
            password VARCHAR(255) NOT NULL, 
            roles JSON NOT NULL,
            PRIMARY KEY(id)
        )');

        $this->addSql('CREATE TABLE news_post (
            id CHAR(36) NOT NULL, 
            title VARCHAR(255) NOT NULL, 
            content TEXT NOT NULL, 
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
            PRIMARY KEY(id)
        )');
    }

    public function down(): void
    {
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE news_post');
    }
}
