<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240507141226 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commart (id INT AUTO_INCREMENT NOT NULL, idarticle INT DEFAULT NULL, idcommande INT DEFAULT NULL, INDEX IDX_750AA7EDDD3E5C08 (idarticle), INDEX IDX_750AA7EDC43FEE70 (idcommande), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE don (iddon INT AUTO_INCREMENT NOT NULL, idcommande INT DEFAULT NULL, date DATE NOT NULL, cordinaliter VARCHAR(30) NOT NULL, INDEX IDX_F8F081D9C43FEE70 (idcommande), PRIMARY KEY(iddon)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livraison (idlivraison INT AUTO_INCREMENT NOT NULL, idcommande INT DEFAULT NULL, adresse_de_livraison VARCHAR(100) NOT NULL, INDEX IDX_A60C9F1FC43FEE70 (idcommande), PRIMARY KEY(idlivraison)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commart ADD CONSTRAINT FK_750AA7EDDD3E5C08 FOREIGN KEY (idarticle) REFERENCES articles (idarticle)');
        $this->addSql('ALTER TABLE commart ADD CONSTRAINT FK_750AA7EDC43FEE70 FOREIGN KEY (idcommande) REFERENCES commande (idcommande)');
        $this->addSql('ALTER TABLE don ADD CONSTRAINT FK_F8F081D9C43FEE70 FOREIGN KEY (idcommande) REFERENCES commande (idcommande)');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1FC43FEE70 FOREIGN KEY (idcommande) REFERENCES commande (idcommande)');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY panier_user');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY reclamation_user');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY reclamation_command');
        $this->addSql('ALTER TABLE wishlist DROP FOREIGN KEY user_wishlist');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE wishlist');
        $this->addSql('ALTER TABLE admin CHANGE idClient idClient INT NOT NULL');
        $this->addSql('ALTER TABLE articles CHANGE quantite stockage INT NOT NULL');
        $this->addSql('ALTER TABLE client CHANGE IdClient idClient INT NOT NULL');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY user_command');
        $this->addSql('DROP INDEX user_command ON commande');
        $this->addSql('ALTER TABLE commande ADD idUsers INT DEFAULT NULL, DROP idclient, DROP date, DROP typedecommande, DROP quantite, DROP Total');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D347E6F4 FOREIGN KEY (idUsers) REFERENCES users (idClient)');
        $this->addSql('CREATE INDEX IDX_6EEAA67D347E6F4 ON commande (idUsers)');
        $this->addSql('ALTER TABLE logins DROP FOREIGN KEY login_vendeur');
        $this->addSql('ALTER TABLE logins CHANGE IdVendeur IdVendeur INT DEFAULT NULL, CHANGE ip ip VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX login_vendeur ON logins');
        $this->addSql('CREATE INDEX IDX_613D7A4E8BC70C7 ON logins (IdVendeur)');
        $this->addSql('ALTER TABLE logins ADD CONSTRAINT login_vendeur FOREIGN KEY (IdVendeur) REFERENCES vendeur (IdVendeur)');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY stock_vendeur');
        $this->addSql('ALTER TABLE stock CHANGE nomproduit nomproduit VARCHAR(255) NOT NULL, CHANGE quantite quantite INT NOT NULL');
        $this->addSql('DROP INDEX stock_vendeur ON stock');
        $this->addSql('CREATE INDEX IDX_4B3656602701495B ON stock (Idvendeur)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT stock_vendeur FOREIGN KEY (Idvendeur) REFERENCES vendeur (IdVendeur)');
        $this->addSql('ALTER TABLE vendeur CHANGE nomproduit nomproduit VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE panier (IdClient INT NOT NULL, PRIMARY KEY(IdClient)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reclamation (idcommande INT NOT NULL, IdAvis INT AUTO_INCREMENT NOT NULL, IdClient INT NOT NULL, Commentaire VARCHAR(500) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Titre VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Statut TINYINT(1) NOT NULL, INDEX reclamation_user (IdClient), INDEX reclamation_command (idcommande), PRIMARY KEY(IdAvis)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE wishlist (idarticle INT NOT NULL, IdClient INT NOT NULL, INDEX foreign2 (IdClient), PRIMARY KEY(idarticle, IdClient)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT panier_user FOREIGN KEY (IdClient) REFERENCES users (idClient)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT reclamation_user FOREIGN KEY (IdClient) REFERENCES users (idClient)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT reclamation_command FOREIGN KEY (idcommande) REFERENCES commande (idcommande)');
        $this->addSql('ALTER TABLE wishlist ADD CONSTRAINT user_wishlist FOREIGN KEY (IdClient) REFERENCES users (idClient)');
        $this->addSql('ALTER TABLE commart DROP FOREIGN KEY FK_750AA7EDDD3E5C08');
        $this->addSql('ALTER TABLE commart DROP FOREIGN KEY FK_750AA7EDC43FEE70');
        $this->addSql('ALTER TABLE don DROP FOREIGN KEY FK_F8F081D9C43FEE70');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1FC43FEE70');
        $this->addSql('DROP TABLE commart');
        $this->addSql('DROP TABLE don');
        $this->addSql('DROP TABLE livraison');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE admin CHANGE idClient idClient INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE articles CHANGE stockage quantite INT NOT NULL');
        $this->addSql('ALTER TABLE client CHANGE idClient IdClient INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D347E6F4');
        $this->addSql('DROP INDEX IDX_6EEAA67D347E6F4 ON commande');
        $this->addSql('ALTER TABLE commande ADD idclient INT NOT NULL, ADD date DATE NOT NULL, ADD typedecommande VARCHAR(30) NOT NULL, ADD quantite INT NOT NULL, ADD Total DOUBLE PRECISION NOT NULL, DROP idUsers');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT user_command FOREIGN KEY (idclient) REFERENCES users (idClient)');
        $this->addSql('CREATE INDEX user_command ON commande (idclient)');
        $this->addSql('ALTER TABLE logins DROP FOREIGN KEY FK_613D7A4E8BC70C7');
        $this->addSql('ALTER TABLE logins CHANGE ip ip VARCHAR(20) NOT NULL, CHANGE IdVendeur IdVendeur INT NOT NULL');
        $this->addSql('DROP INDEX idx_613d7a4e8bc70c7 ON logins');
        $this->addSql('CREATE INDEX login_vendeur ON logins (IdVendeur)');
        $this->addSql('ALTER TABLE logins ADD CONSTRAINT FK_613D7A4E8BC70C7 FOREIGN KEY (IdVendeur) REFERENCES vendeur (IdVendeur)');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B3656602701495B');
        $this->addSql('ALTER TABLE stock CHANGE nomproduit nomproduit VARCHAR(20) DEFAULT NULL, CHANGE quantite quantite INT DEFAULT NULL');
        $this->addSql('DROP INDEX idx_4b3656602701495b ON stock');
        $this->addSql('CREATE INDEX stock_vendeur ON stock (Idvendeur)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B3656602701495B FOREIGN KEY (Idvendeur) REFERENCES vendeur (IdVendeur)');
        $this->addSql('ALTER TABLE vendeur CHANGE nomproduit nomproduit VARCHAR(30) NOT NULL');
    }
}
