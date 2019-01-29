<?php /** @noinspection PhpCSValidationInspection */

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190129014815 extends AbstractMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql(
            'CREATE TABLE notification_settings (id UUID NOT NULL, enable_notifications BOOLEAN DEFAULT \'true\', email_notifications VARCHAR(5) DEFAULT NULL, device_notifications BOOLEAN DEFAULT \'false\', browser_notifications BOOLEAN DEFAULT \'false\', notification_frequency INT DEFAULT 3 NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, modified TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('COMMENT ON COLUMN notification_settings.id IS \'(DC2Type:uuid)\'');
        $this->addSql(
            'CREATE TABLE privacy_settings (id UUID NOT NULL, show_activity_status BOOLEAN DEFAULT \'true\', private_account BOOLEAN DEFAULT \'false\', enable_account_discovery BOOLEAN DEFAULT \'true\', address_book_public BOOLEAN DEFAULT \'false\', accepts_newsletter BOOLEAN DEFAULT \'false\', created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, modified TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('COMMENT ON COLUMN privacy_settings.id IS \'(DC2Type:uuid)\'');
        $this->addSql(
            'CREATE TABLE users (id UUID NOT NULL, privacy_id UUID DEFAULT NULL, ntf_settings_id UUID DEFAULT NULL, account_id UUID DEFAULT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled BOOLEAN NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, roles TEXT NOT NULL, avatar_url VARCHAR(255) DEFAULT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, modified TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E992FC23A8 ON users (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9A0D96FBF ON users (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9C05FB297 ON users (confirmation_token)');
        $this->addSql('CREATE INDEX IDX_1483A5E919877A6A ON users (privacy_id)');
        $this->addSql('CREATE INDEX IDX_1483A5E959B78BB5 ON users (ntf_settings_id)');
        $this->addSql('CREATE INDEX IDX_1483A5E99B6B5FBA ON users (account_id)');
        $this->addSql('COMMENT ON COLUMN users.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN users.privacy_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN users.ntf_settings_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN users.account_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN users.roles IS \'(DC2Type:array)\'');
        $this->addSql(
            'CREATE TABLE user_accounts (id UUID NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, date_of_birth TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, fiat_currency VARCHAR(255) DEFAULT \'EUR\', preferred_currency_type VARCHAR(5) DEFAULT \'none\', country VARCHAR(255) DEFAULT NULL, language VARCHAR(255) DEFAULT NULL, timezone VARCHAR(255) DEFAULT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, modified TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('COMMENT ON COLUMN user_accounts.id IS \'(DC2Type:uuid)\'');
        $this->addSql(
            'CREATE TABLE user_devices (id UUID NOT NULL, user_id UUID DEFAULT NULL, device_id VARCHAR(255) DEFAULT NULL, device_token VARCHAR(255) DEFAULT NULL, last_seen TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, capabilities JSON DEFAULT NULL, extra JSON DEFAULT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, modified TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_490A5090A76ED395 ON user_devices (user_id)');
        $this->addSql('COMMENT ON COLUMN user_devices.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_devices.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_devices.capabilities IS \'(DC2Type:json_array)\'');
        $this->addSql('COMMENT ON COLUMN user_devices.extra IS \'(DC2Type:json_array)\'');
        $this->addSql(
            'ALTER TABLE users ADD CONSTRAINT FK_1483A5E919877A6A FOREIGN KEY (privacy_id) REFERENCES privacy_settings (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE users ADD CONSTRAINT FK_1483A5E959B78BB5 FOREIGN KEY (ntf_settings_id) REFERENCES notification_settings (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE users ADD CONSTRAINT FK_1483A5E99B6B5FBA FOREIGN KEY (account_id) REFERENCES user_accounts (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE user_devices ADD CONSTRAINT FK_490A5090A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
    }

    /**
     * @param Schema $schema
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E959B78BB5');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E919877A6A');
        $this->addSql('ALTER TABLE user_devices DROP CONSTRAINT FK_490A5090A76ED395');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E99B6B5FBA');
        $this->addSql('DROP TABLE notification_settings');
        $this->addSql('DROP TABLE privacy_settings');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE user_accounts');
        $this->addSql('DROP TABLE user_devices');
    }
}
