<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200217200953 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE souvenir (id INT AUTO_INCREMENT NOT NULL, booking_id INT NOT NULL, resume VARCHAR(100) NOT NULL, anecdote LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_53FBDDEE3301C60 (booking_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE souvenir_user (souvenir_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_BB6B2C0EDBC4A80F (souvenir_id), INDEX IDX_BB6B2C0EA76ED395 (user_id), PRIMARY KEY(souvenir_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE souvenir ADD CONSTRAINT FK_53FBDDEE3301C60 FOREIGN KEY (booking_id) REFERENCES booking (id)');
        $this->addSql('ALTER TABLE souvenir_user ADD CONSTRAINT FK_BB6B2C0EDBC4A80F FOREIGN KEY (souvenir_id) REFERENCES souvenir (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE souvenir_user ADD CONSTRAINT FK_BB6B2C0EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE souvenir_user DROP FOREIGN KEY FK_BB6B2C0EDBC4A80F');
        $this->addSql('DROP TABLE souvenir');
        $this->addSql('DROP TABLE souvenir_user');
    }
}
