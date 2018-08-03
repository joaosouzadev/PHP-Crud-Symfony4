<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180802232929 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pedido (id INT AUTO_INCREMENT NOT NULL, vendedor_id INT DEFAULT NULL, INDEX IDX_C4EC16CE8361A8B8 (vendedor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pedido_item (id INT AUTO_INCREMENT NOT NULL, produto_id INT DEFAULT NULL, pedido_id INT DEFAULT NULL, quantidade INT NOT NULL, INDEX IDX_6E073072105CFD56 (produto_id), INDEX IDX_6E0730724854653A (pedido_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pedido ADD CONSTRAINT FK_C4EC16CE8361A8B8 FOREIGN KEY (vendedor_id) REFERENCES vendedor (id)');
        $this->addSql('ALTER TABLE pedido_item ADD CONSTRAINT FK_6E073072105CFD56 FOREIGN KEY (produto_id) REFERENCES produto (id)');
        $this->addSql('ALTER TABLE pedido_item ADD CONSTRAINT FK_6E0730724854653A FOREIGN KEY (pedido_id) REFERENCES pedido (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pedido_item DROP FOREIGN KEY FK_6E0730724854653A');
        $this->addSql('DROP TABLE pedido');
        $this->addSql('DROP TABLE pedido_item');
    }
}
