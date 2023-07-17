<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230717094645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE module_programme (module_id INT NOT NULL, programme_id INT NOT NULL, INDEX IDX_67BDA637AFC2B591 (module_id), INDEX IDX_67BDA63762BB7AEE (programme_id), PRIMARY KEY(module_id, programme_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE module_programme ADD CONSTRAINT FK_67BDA637AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE module_programme ADD CONSTRAINT FK_67BDA63762BB7AEE FOREIGN KEY (programme_id) REFERENCES programme (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module_programme DROP FOREIGN KEY FK_67BDA637AFC2B591');
        $this->addSql('ALTER TABLE module_programme DROP FOREIGN KEY FK_67BDA63762BB7AEE');
        $this->addSql('DROP TABLE module_programme');
    }
}
