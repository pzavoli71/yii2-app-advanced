<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        
        $this->createTable('{{%ztrans}}', [
            'idtrans' => $this->primaryKey(),
            'nometrans' => $this->string(200)->notNull(),
            'ultagg' => $this->datetime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'utente' => $this->string(45)->notNull(),
        ], $tableOptions);
        
        $this->createTable('{{%zgruppo}}', [
            'idgruppo' => $this->primaryKey(),
            'nomegruppo' => $this->string(200)->notNull(),
            'ultagg' => $this->datetime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'utente' => $this->string(45)->notNull(),
        ], $tableOptions);
        
        $this->createTable('{{%zpermessi}}', [
            'idpermessi' => $this->primaryKey(),
            'idtrans' => $this->integer()->notNull(),
            'idgruppo' => $this->integer()->notNull(),
            'permesso' => $this->string(45)->notNull(),
            'ultagg' => $this->datetime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'utente' => $this->string(45)->notNull(),
        ], $tableOptions);
        $this->addForeignKey('fk_permessi_trans', '{{%zpermessi}}', ['idtrans'], '{{%ztrans}}', ['idtrans']);
        $this->addForeignKey('fk_permessi_gruppo', '{{%zpermessi}}', ['idgruppo'], '{{%zgruppo}}', ['idgruppo']);

        $this->createTable('{{%zutgr}}', [
            'idutgr' => $this->primaryKey(),
            'id' => $this->integer()->notNull(),
            'idgruppo' => $this->integer()->notNull(),
            'ultagg' => $this->datetime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'utente' => $this->string(45)->notNull(),
        ], $tableOptions);
        $this->addForeignKey('fk_utgr_user', '{{%zutgr}}', ['id'], '{{%user}}', ['id']);
        $this->addForeignKey('fk_utgr_gruppo', '{{%zutgr}}', ['idgruppo'], '{{%zgruppo}}', ['idgruppo']);
        
        $this->createTable('{{%profilo}}', [
            'idprofilo' => $this->primaryKey(),
            'id' => $this->integer()->notNull(),
            'Cognome' => $this->string(100),
            'Nome' => $this->string(100)->notNull(),
            'Nascita' => $this->date(),
            'NascitaAnno' => $this->integer()->notNull()->defaultValue(0),
            'ultagg' => $this->datetime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'utente' => $this->string(45)->notNull(),
        ], $tableOptions);
        $this->addForeignKey('fk_profilo_user', '{{%profilo}}', ['id'], '{{%user}}', ['id']);        
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%ztrans}}');
        $this->dropTable('{{%zgruppo}}');
        $this->dropTable('{{%zpermessi}}');
        $this->dropTable('{{%zutgr}}');
        $this->dropTable('{{%profilo}}');        
    }
}
