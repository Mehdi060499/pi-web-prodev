<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240315144058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY clientcommande');
        $this->addSql('ALTER TABLE don DROP FOREIGN KEY commandedon');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY livreurlivraison');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY commandelivraison');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY fk1');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY commandereclamation');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY clientreclamation');
        $this->addSql('ALTER TABLE wishlist DROP FOREIGN KEY `foreign`');
        $this->addSql('ALTER TABLE wishlist DROP FOREIGN KEY foreign2');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE don');
        $this->addSql('DROP TABLE livraison');
        $this->addSql('DROP TABLE livreur');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE vendeur');
        $this->addSql('DROP TABLE wishlist');
        $this->addSql('ALTER TABLE articles MODIFY idarticle INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON articles');
        $this->addSql('ALTER TABLE articles CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE description description VARCHAR(255) NOT NULL, CHANGE image image VARCHAR(255) NOT NULL, CHANGE idarticle id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE articles ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, motdepasse INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE client (IdClient INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prenom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, motdepasse INT NOT NULL, adresse VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(IdClient)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commande (idcommande INT AUTO_INCREMENT NOT NULL, idclient INT NOT NULL, date DATE NOT NULL, typedecommande VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, quantite INT NOT NULL, Total DOUBLE PRECISION NOT NULL, INDEX clientcommande (idclient), PRIMARY KEY(idcommande)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE don (iddon INT AUTO_INCREMENT NOT NULL, idcommande INT NOT NULL, date DATE NOT NULL, cordinaliter VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX commandedon (idcommande), PRIMARY KEY(iddon)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE livraison (idlivraison INT AUTO_INCREMENT NOT NULL, idcommande INT NOT NULL, adresse de livraison VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, IdLivreur INT NOT NULL, INDEX commandelivraison (idcommande), INDEX livreurlivraison (IdLivreur), PRIMARY KEY(idlivraison)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE livreur (IdLivreur INT AUTO_INCREMENT NOT NULL, Nom VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Email VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, mdp VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Telephone INT NOT NULL, PRIMARY KEY(IdLivreur)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE panier (IdClient INT NOT NULL, PRIMARY KEY(IdClient)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reclamation (idcommande INT NOT NULL, IdAvis INT AUTO_INCREMENT NOT NULL, IdClient INT NOT NULL, Commentaire VARCHAR(500) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Titre VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Statut TINYINT(1) NOT NULL, INDEX commandereclamation (idcommande), INDEX clientreclamation (IdClient), PRIMARY KEY(IdAvis)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE vendeur (IdVendeur INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, nomproduit VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, motdepasse VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(IdVendeur)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE wishlist (idarticle INT NOT NULL, IdClient INT NOT NULL, INDEX foreign2 (IdClient), INDEX IDX_9CE12A31DD3E5C08 (idarticle), PRIMARY KEY(idarticle, IdClient)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT clientcommande FOREIGN KEY (idclient) REFERENCES client (IdClient)');
        $this->addSql('ALTER TABLE don ADD CONSTRAINT commandedon FOREIGN KEY (idcommande) REFERENCES commande (idcommande)');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT livreurlivraison FOREIGN KEY (IdLivreur) REFERENCES livreur (IdLivreur)');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT commandelivraison FOREIGN KEY (idcommande) REFERENCES commande (idcommande)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT fk1 FOREIGN KEY (IdClient) REFERENCES client (IdClient)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT commandereclamation FOREIGN KEY (idcommande) REFERENCES commande (idcommande)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT clientreclamation FOREIGN KEY (IdClient) REFERENCES client (IdClient)');
        $this->addSql('ALTER TABLE wishlist ADD CONSTRAINT `foreign` FOREIGN KEY (idarticle) REFERENCES articles (idarticle)');
        $this->addSql('ALTER TABLE wishlist ADD CONSTRAINT foreign2 FOREIGN KEY (IdClient) REFERENCES client (IdClient)');
        $this->addSql('ALTER TABLE articles MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON articles');
        $this->addSql('ALTER TABLE articles CHANGE nom nom VARCHAR(30) NOT NULL, CHANGE description description VARCHAR(50) NOT NULL, CHANGE image image VARCHAR(100) NOT NULL, CHANGE id idarticle INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE articles ADD PRIMARY KEY (idarticle)');
    }
}
