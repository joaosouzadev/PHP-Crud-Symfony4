<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180710195854 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE empresa ADD user_id INT DEFAULT NULL, CHANGE rua rua VARCHAR(100) NOT NULL, CHANGE numero numero VARCHAR(10) NOT NULL, CHANGE bairro bairro VARCHAR(100) NOT NULL, CHANGE pais pais VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE empresa ADD CONSTRAINT FK_B8D75A50A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B8D75A50A76ED395 ON empresa (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE empresa DROP FOREIGN KEY FK_B8D75A50A76ED395');
        $this->addSql('DROP INDEX IDX_B8D75A50A76ED395 ON empresa');
        $this->addSql('ALTER TABLE empresa DROP user_id, CHANGE rua rua VARCHAR(100) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE numero numero VARCHAR(10) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE bairro bairro VARCHAR(100) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE pais pais VARCHAR(20) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
