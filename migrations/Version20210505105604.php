<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210505105604 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE revision DROP CONSTRAINT fk_6d6315cc60bb6fe6');
        $this->addSql('DROP INDEX uniq_6d6315cc60bb6fe6');
        $this->addSql('ALTER TABLE revision RENAME COLUMN auteur_id TO "user"');
        $this->addSql('ALTER TABLE revision ADD CONSTRAINT FK_6D6315CC8D93D649 FOREIGN KEY ("user") REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6D6315CC8D93D649 ON revision ("user")');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT fk_8d93d6496d6315cc');
        $this->addSql('DROP INDEX uniq_8d93d6496d6315cc');
        $this->addSql('ALTER TABLE "user" DROP revision');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" ADD revision INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT fk_8d93d6496d6315cc FOREIGN KEY (revision) REFERENCES revision (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d6496d6315cc ON "user" (revision)');
        $this->addSql('ALTER TABLE revision DROP CONSTRAINT FK_6D6315CC8D93D649');
        $this->addSql('DROP INDEX IDX_6D6315CC8D93D649');
        $this->addSql('ALTER TABLE revision RENAME COLUMN "user" TO auteur_id');
        $this->addSql('ALTER TABLE revision ADD CONSTRAINT fk_6d6315cc60bb6fe6 FOREIGN KEY (auteur_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_6d6315cc60bb6fe6 ON revision (auteur_id)');
    }
}
