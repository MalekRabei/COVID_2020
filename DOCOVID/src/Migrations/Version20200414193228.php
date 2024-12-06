<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200414193228 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1F80E95E18');
        $this->addSql('DROP INDEX UNIQ_A60C9F1F80E95E18 ON livraison');
        $this->addSql('ALTER TABLE livraison DROP demande_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE livraison ADD demande_id INT NOT NULL');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1F80E95E18 FOREIGN KEY (demande_id) REFERENCES demande (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A60C9F1F80E95E18 ON livraison (demande_id)');
    }
}
