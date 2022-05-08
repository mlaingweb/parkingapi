<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220507121438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bookings (id INT AUTO_INCREMENT NOT NULL, space_id_id INT NOT NULL, customer_id INT NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, INDEX IDX_7A853C3572428213 (space_id_id), INDEX IDX_7A853C359395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE car_parks (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, car_registration VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prices (id INT AUTO_INCREMENT NOT NULL, car_park_id_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, enabled TINYINT(1) NOT NULL, INDEX IDX_E4CB6D594F7EAB82 (car_park_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spaces (id INT AUTO_INCREMENT NOT NULL, car_park_id_id INT NOT NULL, INDEX IDX_DD2B64784F7EAB82 (car_park_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bookings ADD CONSTRAINT FK_7A853C3572428213 FOREIGN KEY (space_id_id) REFERENCES spaces (id)');
        $this->addSql('ALTER TABLE bookings ADD CONSTRAINT FK_7A853C359395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE prices ADD CONSTRAINT FK_E4CB6D594F7EAB82 FOREIGN KEY (car_park_id_id) REFERENCES car_parks (id)');
        $this->addSql('ALTER TABLE spaces ADD CONSTRAINT FK_DD2B64784F7EAB82 FOREIGN KEY (car_park_id_id) REFERENCES car_parks (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prices DROP FOREIGN KEY FK_E4CB6D594F7EAB82');
        $this->addSql('ALTER TABLE spaces DROP FOREIGN KEY FK_DD2B64784F7EAB82');
        $this->addSql('ALTER TABLE bookings DROP FOREIGN KEY FK_7A853C359395C3F3');
        $this->addSql('ALTER TABLE bookings DROP FOREIGN KEY FK_7A853C3572428213');
        $this->addSql('DROP TABLE bookings');
        $this->addSql('DROP TABLE car_parks');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE prices');
        $this->addSql('DROP TABLE spaces');
    }
}
