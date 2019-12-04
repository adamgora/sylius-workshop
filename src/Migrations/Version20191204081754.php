<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191204081754 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE app_subcontractors_taxons (subcontractor_id INT NOT NULL, taxon_id INT NOT NULL, INDEX IDX_F709AB60FD2F7858 (subcontractor_id), INDEX IDX_F709AB60DE13F470 (taxon_id), PRIMARY KEY(subcontractor_id, taxon_id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app_subcontractors_taxons ADD CONSTRAINT FK_F709AB60FD2F7858 FOREIGN KEY (subcontractor_id) REFERENCES app_subcontractor (id)');
        $this->addSql('ALTER TABLE app_subcontractors_taxons ADD CONSTRAINT FK_F709AB60DE13F470 FOREIGN KEY (taxon_id) REFERENCES sylius_taxon (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE app_subcontractors_taxons');
    }
}
