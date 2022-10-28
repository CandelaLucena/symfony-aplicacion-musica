<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221026071219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE musica ADD autor_id INT NOT NULL');
        $this->addSql('ALTER TABLE musica ADD CONSTRAINT FK_7E7344EF14D45BBE FOREIGN KEY (autor_id) REFERENCES autor (id)');
        $this->addSql('CREATE INDEX IDX_7E7344EF14D45BBE ON musica (autor_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE musica DROP FOREIGN KEY FK_7E7344EF14D45BBE');
        $this->addSql('DROP INDEX IDX_7E7344EF14D45BBE ON musica');
        $this->addSql('ALTER TABLE musica DROP autor_id');
    }
}
