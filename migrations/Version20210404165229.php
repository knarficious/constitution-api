<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210404165229 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE greeting_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE article_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE revision_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE texte_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE article (id INT NOT NULL, texte INT NOT NULL, numero INT NOT NULL, contenu TEXT NOT NULL, date_creation TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_modification TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_23A0E66EAE1A6EE ON article (texte)');
        $this->addSql('CREATE TABLE revision (id INT NOT NULL, auteur_id INT DEFAULT NULL, article_id INT DEFAULT NULL, evaluation SMALLINT NOT NULL, contenu TEXT NOT NULL, date_publication TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6D6315CC60BB6FE6 ON revision (auteur_id)');
        $this->addSql('CREATE INDEX IDX_6D6315CC7294869C ON revision (article_id)');
        $this->addSql('CREATE TABLE texte (id INT NOT NULL, titre VARCHAR(255) NOT NULL, contenu TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, revision INT DEFAULT NULL, username VARCHAR(180) NOT NULL, email VARCHAR(50) NOT NULL, roles TEXT NOT NULL, password VARCHAR(255) NOT NULL, salt VARCHAR(255) DEFAULT NULL, api_token VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6497BA2F5EB ON "user" (api_token)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6496D6315CC ON "user" (revision)');
        $this->addSql('COMMENT ON COLUMN "user".roles IS \'(DC2Type:simple_array)\'');
        $this->addSql('CREATE TABLE user_texte (user_id INT NOT NULL, texte_id INT NOT NULL, PRIMARY KEY(user_id, texte_id))');
        $this->addSql('CREATE INDEX IDX_8235DB6A76ED395 ON user_texte (user_id)');
        $this->addSql('CREATE INDEX IDX_8235DB6EA6DF1F1 ON user_texte (texte_id)');
        $this->addSql('CREATE TABLE user_article (user_id INT NOT NULL, article_id INT NOT NULL, PRIMARY KEY(user_id, article_id))');
        $this->addSql('CREATE INDEX IDX_5A37106CA76ED395 ON user_article (user_id)');
        $this->addSql('CREATE INDEX IDX_5A37106C7294869C ON user_article (article_id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66EAE1A6EE FOREIGN KEY (texte) REFERENCES texte (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE revision ADD CONSTRAINT FK_6D6315CC60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE revision ADD CONSTRAINT FK_6D6315CC7294869C FOREIGN KEY (article_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6496D6315CC FOREIGN KEY (revision) REFERENCES revision (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_texte ADD CONSTRAINT FK_8235DB6A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_texte ADD CONSTRAINT FK_8235DB6EA6DF1F1 FOREIGN KEY (texte_id) REFERENCES texte (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_article ADD CONSTRAINT FK_5A37106CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_article ADD CONSTRAINT FK_5A37106C7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE greeting');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE revision DROP CONSTRAINT FK_6D6315CC7294869C');
        $this->addSql('ALTER TABLE user_article DROP CONSTRAINT FK_5A37106C7294869C');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6496D6315CC');
        $this->addSql('ALTER TABLE article DROP CONSTRAINT FK_23A0E66EAE1A6EE');
        $this->addSql('ALTER TABLE user_texte DROP CONSTRAINT FK_8235DB6EA6DF1F1');
        $this->addSql('ALTER TABLE revision DROP CONSTRAINT FK_6D6315CC60BB6FE6');
        $this->addSql('ALTER TABLE user_texte DROP CONSTRAINT FK_8235DB6A76ED395');
        $this->addSql('ALTER TABLE user_article DROP CONSTRAINT FK_5A37106CA76ED395');
        $this->addSql('DROP SEQUENCE article_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE revision_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE texte_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('CREATE SEQUENCE greeting_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE greeting (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE revision');
        $this->addSql('DROP TABLE texte');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_texte');
        $this->addSql('DROP TABLE user_article');
    }
}
