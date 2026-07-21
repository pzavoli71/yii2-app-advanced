<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
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
            'updated_at' => $this->integer()->notNull()
        ], $tableOptions);
        
        $this->insert('{{%user}}', ['id'=>1, 'username'=>'admin','auth_key'=>'ld6MMMH8M0kTi2qxlyIzLRxzHsDY2ED2',
            'password_hash'=>'$2y$13$kWLttWlOtn08OgbPeIYGIOwJsbCBAkdjIINXcP2VMVLxsw6xVU9FO', // adminpwd
            'email'=>'p@p.org','status'=>10,'created_at'=>1686232983, 'updated_at'=>1720523687]);
                
        $this->createTable('{{%zruolo}}', [
            'idruolo' => $this->primaryKey(),
            'dsruolo' => $this->string(100)->notNull(),
            'ultagg' => $this->datetime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'utente' => $this->string(45)->notNull()->defaultValue(''),
        ], $tableOptions);

        $this->createTable('{{%zgruppo}}', [
            'idgruppo' => $this->primaryKey(),
            'nomegruppo' => $this->string(200)->notNull(),
            'ultagg' => $this->datetime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'utente' => $this->string(45)->notNull()->defaultValue(''),
        ], $tableOptions);

        $this->insert('{{%zgruppo}}', ['idgruppo'=>1, 'nomegruppo'=>'admin']);
        
        $this->createTable('{{%ztrans}}', [
            'idtrans' => $this->primaryKey(),
            'nometrans' => $this->string(200)->notNull(),
            'ultagg' => $this->datetime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'utente' => $this->string(45)->notNull()->defaultValue(''),
        ], $tableOptions);
        $this->insert('{{%ztrans}}', ['idtrans'=>1, 'nometrans'=>'admin']);
        $this->insert('{{%ztrans}}', ['idtrans'=>2, 'nometrans'=>'abilitazione/ztrans/lista']);
        $this->insert('{{%ztrans}}', ['idtrans'=>3, 'nometrans'=>'abilitazione/ztrans/create']);
        $this->insert('{{%ztrans}}', ['idtrans'=>4, 'nometrans'=>'abilitazione/ztrans/delete']);
        $this->insert('{{%ztrans}}', ['idtrans'=>5, 'nometrans'=>'abilitazione/ztrans/update']);
        $this->insert('{{%ztrans}}', ['idtrans'=>6, 'nometrans'=>'abilitazione/ztrans/view']);

        $this->insert('{{%ztrans}}', ['idtrans'=>7, 'nometrans'=>'abilitazione/zutgr/index']);
        $this->insert('{{%ztrans}}', ['idtrans'=>8, 'nometrans'=>'abilitazione/zutgr/view']);
        $this->insert('{{%ztrans}}', ['idtrans'=>9, 'nometrans'=>'abilitazione/zutgr/create']);
        $this->insert('{{%ztrans}}', ['idtrans'=>10, 'nometrans'=>'abilitazione/zutgr/update']);
        $this->insert('{{%ztrans}}', ['idtrans'=>11, 'nometrans'=>'abilitazione/zutgr/delete']);

        $this->insert('{{%ztrans}}', ['idtrans'=>12, 'nometrans'=>'abilitazione/zpermessi/index']);
        $this->insert('{{%ztrans}}', ['idtrans'=>13, 'nometrans'=>'abilitazione/zpermessi/view']);
        $this->insert('{{%ztrans}}', ['idtrans'=>14, 'nometrans'=>'abilitazione/zpermessi/create']);
        $this->insert('{{%ztrans}}', ['idtrans'=>15, 'nometrans'=>'abilitazione/zpermessi/update']);
        $this->insert('{{%ztrans}}', ['idtrans'=>16, 'nometrans'=>'abilitazione/zpermessi/delete']);

        $this->insert('{{%ztrans}}', ['idtrans'=>17, 'nometrans'=>'abilitazione/zgruppo/index']);
        $this->insert('{{%ztrans}}', ['idtrans'=>18, 'nometrans'=>'abilitazione/zgruppo/lista']);
        $this->insert('{{%ztrans}}', ['idtrans'=>19, 'nometrans'=>'abilitazione/zgruppo/view']);
        $this->insert('{{%ztrans}}', ['idtrans'=>20, 'nometrans'=>'abilitazione/zgruppo/create']);
        $this->insert('{{%ztrans}}', ['idtrans'=>21, 'nometrans'=>'abilitazione/zgruppo/update']);
        $this->insert('{{%ztrans}}', ['idtrans'=>22, 'nometrans'=>'abilitazione/zgruppo/delete']);
               
        $this->createTable('{{%zutgr}}', [
            'idutgr' => $this->primaryKey()->append('AUTO_INCREMENT'),
            'id' => $this->integer()->notNull(),
            'idgruppo' => $this->integer()->notNull(),
            'ultagg' => $this->datetime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'utente' => $this->string(45)->notNull()->defaultValue(''),
        ], $tableOptions);
        $this->addForeignKey('fix_utgr_user', '{{%zutgr}}', 'id', '{{%user}}' , 'id');
        $this->addForeignKey('fix_utgr_gruppo', '{{%zutgr}}', 'idgruppo', '{{%zgruppo}}' , 'idgruppo');

        $this->insert('{{%zutgr}}', ['id'=>1, 'idgruppo'=>1]);
        
        $this->createTable('{{%zruoligruppo}}', [
            'idruoligruppo' => $this->primaryKey()->append('AUTO_INCREMENT'),
            'idruolo' => $this->integer()->notNull(),
            'idgruppo' => $this->integer()->notNull(),
            'ultagg' => $this->datetime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'utente' => $this->string(45)->notNull()->defaultValue(''),
        ], $tableOptions);
        $this->addForeignKey('fix_ruoligruppo_ruolo', '{{%zruoligruppo}}', 'idruolo', '{{%zruolo}}' , 'idruolo');
        $this->addForeignKey('fix_ruoligruppo_gruppo', '{{%zruoligruppo}}', 'idgruppo', '{{%zgruppo}}' , 'idgruppo');
        
        $this->createTable('{{%zpermessi}}', [
            'idpermessi' => $this->primaryKey()->append('AUTO_INCREMENT'),
            'idtrans' => $this->integer()->notNull(),
            'idgruppo' => $this->integer()->notNull(),
            'permesso' => $this->string(50),
            'ultagg' => $this->datetime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'utente' => $this->string(45)->notNull()->defaultValue(''),
        ], $tableOptions);
        $this->addForeignKey('fix_zpermessi_ztrans', '{{%zpermessi}}', 'idtrans', '{{%ztrans}}' , 'idtrans');
        $this->addForeignKey('fix_zpermessi_gruppo', '{{%zpermessi}}', 'idgruppo', '{{%zgruppo}}' , 'idgruppo');

        $this->insert('{{%zpermessi}}', ['idtrans'=>1, 'idgruppo'=>1]);
        $this->insert('{{%zpermessi}}', ['idtrans'=>2, 'idgruppo'=>1]);
        $this->insert('{{%zpermessi}}', ['idtrans'=>3, 'idgruppo'=>1]);
        $this->insert('{{%zpermessi}}', ['idtrans'=>4, 'idgruppo'=>1]);
        $this->insert('{{%zpermessi}}', ['idtrans'=>5, 'idgruppo'=>1]);
        $this->insert('{{%zpermessi}}', ['idtrans'=>6, 'idgruppo'=>1]);
        $this->insert('{{%zpermessi}}', ['idtrans'=>7, 'idgruppo'=>1]);
        $this->insert('{{%zpermessi}}', ['idtrans'=>8, 'idgruppo'=>1]);
        $this->insert('{{%zpermessi}}', ['idtrans'=>9, 'idgruppo'=>1]);
        $this->insert('{{%zpermessi}}', ['idtrans'=>10, 'idgruppo'=>1]);
        $this->insert('{{%zpermessi}}', ['idtrans'=>11, 'idgruppo'=>1]);
        $this->insert('{{%zpermessi}}', ['idtrans'=>12, 'idgruppo'=>1]);
        $this->insert('{{%zpermessi}}', ['idtrans'=>13, 'idgruppo'=>1]);
        $this->insert('{{%zpermessi}}', ['idtrans'=>14, 'idgruppo'=>1]);
        $this->insert('{{%zpermessi}}', ['idtrans'=>15, 'idgruppo'=>1]);
        $this->insert('{{%zpermessi}}', ['idtrans'=>16, 'idgruppo'=>1]);
        $this->insert('{{%zpermessi}}', ['idtrans'=>17, 'idgruppo'=>1]);
        $this->insert('{{%zpermessi}}', ['idtrans'=>18, 'idgruppo'=>1]);
        $this->insert('{{%zpermessi}}', ['idtrans'=>19, 'idgruppo'=>1]);
        $this->insert('{{%zpermessi}}', ['idtrans'=>20, 'idgruppo'=>1]);
        $this->insert('{{%zpermessi}}', ['idtrans'=>21, 'idgruppo'=>1]);
        $this->insert('{{%zpermessi}}', ['idtrans'=>22, 'idgruppo'=>1]);
        
        
        $this->createTable('session', [
            'id' => $this->char(40)->notNull(),
            'expire' => $this->integer(),
            'data' => $this->binary(),
            'user_id' => $this->integer(),
            'last_write' => $this->dateTime(),
            'browser_platform' => $this->char(200),
            'ipaddress' => $this->char(30),
        ]);

        $this->addPrimaryKey('session_pk', 'session', 'id');        
        
        $this->createTable('profilo', [
            'IdProfilo' => $this->primaryKey()->append('AUTO_INCREMENT'),
            'id' => $this->integer()->notNull(),
            'Cognome' => $this->char(100),
            'Nome' => $this->char(100),
            'Nascita' => $this->date(),
            'AnnoNascita' => $this->integer(),
            'Avatar' => $this->char(255),
            'ultagg' => $this->datetime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'utente' => $this->string(45)->notNull()->defaultValue(''),
        ]);
        $this->insert('{{%profilo}}', ['id'=>1, 'Cognome'=>'Amministratore','Nome'=>'Sistema']);
        
    }

    public function down()
    {
        $this->dropTable('{{%zpermessi}}');
        $this->dropTable('{{%zutgr}}');
        $this->dropTable('{{%profilo}}');       
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%ztrans}}');
        $this->dropTable('{{%zruoligruppo}}');
        $this->dropTable('{{%zgruppo}}');
        $this->dropTable('{{%zruolo}}');
        $this->dropTable('{{%session}}');         
        
    }
}
