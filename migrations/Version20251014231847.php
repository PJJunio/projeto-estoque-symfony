<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251014231847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sell ADD product_id INT DEFAULT NULL, DROP name, DROP value');
        $this->addSql('ALTER TABLE sell ADD CONSTRAINT FK_9B9ED07D4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_9B9ED07D4584665A ON sell (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sell DROP FOREIGN KEY FK_9B9ED07D4584665A');
        $this->addSql('DROP INDEX IDX_9B9ED07D4584665A ON sell');
        $this->addSql('ALTER TABLE sell ADD name VARCHAR(50) NOT NULL, ADD value NUMERIC(10, 2) NOT NULL, DROP product_id');
    }
}
