<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210325113949 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE conversacion (id INT AUTO_INCREMENT NOT NULL, participante_id INT NOT NULL, INDEX IDX_474049CFF6F50196 (participante_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mensaje (id INT AUTO_INCREMENT NOT NULL, conversacion_id INT NOT NULL, mensaje LONGTEXT NOT NULL, leido TINYINT(1) NOT NULL, fecha DATETIME NOT NULL, id_remitente INT NOT NULL, id_destinatario INT NOT NULL, INDEX IDX_9B631D01ABD5A1D6 (conversacion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participante (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_85BDC5C3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE conversacion ADD CONSTRAINT FK_474049CFF6F50196 FOREIGN KEY (participante_id) REFERENCES participante (id)');
        $this->addSql('ALTER TABLE mensaje ADD CONSTRAINT FK_9B631D01ABD5A1D6 FOREIGN KEY (conversacion_id) REFERENCES conversacion (id)');
        $this->addSql('ALTER TABLE participante ADD CONSTRAINT FK_85BDC5C3A76ED395 FOREIGN KEY (user_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE usuario CHANGE foto foto VARCHAR(200) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mensaje DROP FOREIGN KEY FK_9B631D01ABD5A1D6');
        $this->addSql('ALTER TABLE conversacion DROP FOREIGN KEY FK_474049CFF6F50196');
        $this->addSql('DROP TABLE conversacion');
        $this->addSql('DROP TABLE mensaje');
        $this->addSql('DROP TABLE participante');
        $this->addSql('ALTER TABLE usuario CHANGE foto foto VARCHAR(200) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
