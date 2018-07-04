<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180704000206 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE empresa (id INT AUTO_INCREMENT NOT NULL, cnpj VARCHAR(18) NOT NULL, razao_social VARCHAR(100) NOT NULL, nome_fantasia VARCHAR(100) NOT NULL, situacao_tributaria VARCHAR(7) NOT NULL, cep VARCHAR(9) NOT NULL, rua VARCHAR(100) NOT NULL, numero VARCHAR(10) NOT NULL, bairro VARCHAR(100) NOT NULL, cidade VARCHAR(100) NOT NULL, uf VARCHAR(50) NOT NULL, pais VARCHAR(20) NOT NULL, UNIQUE INDEX UNIQ_B8D75A50C8C6906B (cnpj), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE empresa');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:simple_array)\'');
    }
}
