<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220507155854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bookings DROP FOREIGN KEY FK_7A853C3572428213');
        $this->addSql('DROP INDEX IDX_7A853C3572428213 ON bookings');
        $this->addSql('ALTER TABLE bookings CHANGE space_id_id car_park_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE bookings ADD CONSTRAINT FK_7A853C354F7EAB82 FOREIGN KEY (car_park_id_id) REFERENCES car_parks (id)');
        $this->addSql('CREATE INDEX IDX_7A853C354F7EAB82 ON bookings (car_park_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bookings DROP FOREIGN KEY FK_7A853C354F7EAB82');
        $this->addSql('DROP INDEX IDX_7A853C354F7EAB82 ON bookings');
        $this->addSql('ALTER TABLE bookings CHANGE car_park_id_id space_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE bookings ADD CONSTRAINT FK_7A853C3572428213 FOREIGN KEY (space_id_id) REFERENCES spaces (id)');
        $this->addSql('CREATE INDEX IDX_7A853C3572428213 ON bookings (space_id_id)');
    }
}
