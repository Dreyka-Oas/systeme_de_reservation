<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241213103440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity ADD level_id INT NOT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A5FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_AC74095A5FB14BA7 ON activity (level_id)');
        $this->addSql('ALTER TABLE session DROP CONSTRAINT fk_session_activity');
        $this->addSql('ALTER TABLE session ALTER activity_id SET NOT NULL');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D481C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE activity DROP CONSTRAINT FK_AC74095A5FB14BA7');
        $this->addSql('DROP INDEX IDX_AC74095A5FB14BA7');
        $this->addSql('ALTER TABLE activity DROP level_id');
        $this->addSql('ALTER TABLE session DROP CONSTRAINT FK_D044D5D481C06096');
        $this->addSql('ALTER TABLE session ALTER activity_id DROP NOT NULL');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT fk_session_activity FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
