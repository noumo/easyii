<?php
use yii\db\Schema;

/**
 *
 * Install multilanguage tables
 *
 * Class m000000_000001_multilanguage_install
 * @todo use the migration table directly from webvimark/multilanguage extension
 */
class m000000_000001_multilanguage_install extends \yii\db\Migration
{
    const TABLE_STRING_NAME = 'translations_with_string';
    const TABLE_TEXT_NAME = 'translations_with_text';

    public function up()
    {
        $this->createTable(static::TABLE_STRING_NAME, [
            'id' => Schema::TYPE_PK,
            'table_name' => Schema::TYPE_STRING,
            'model_id' => Schema::TYPE_INTEGER,
            'attribute' => Schema::TYPE_STRING,
            'lang' => Schema::TYPE_STRING,
            'value' => Schema::TYPE_TEXT
        ],  'ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1' );

        $this->createTable(static::TABLE_TEXT_NAME, [
            'id' => Schema::TYPE_PK,
            'table_name' => Schema::TYPE_STRING,
            'model_id' => Schema::TYPE_INTEGER,
            'attribute' => Schema::TYPE_STRING,
            'lang' => Schema::TYPE_STRING,
            'value' => Schema::TYPE_STRING
        ],  'ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1' );

    }

    public function down()
    {
        $this->dropTable(static::TABLE_TEXT_NAME);
        $this->dropTable(static::TABLE_STRING_NAME);
    }
}
