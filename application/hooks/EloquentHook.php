<?php
/**
 * Created by PhpStorm.
 * User: uriel
 * Date: 20/10/16
 * Time: 12:19
 */

use Illuminate\Database\Capsule\Manager as Capsule;

class EloquentHook {

    /**
     * Holds the instance
     * @var object
     */
    protected $instance;

    /**
     * Gets CI instance
     */
    private function setInstance() {
        $this->instance =& get_instance();
    }

    /**
     * Loads database
     */
    private function loadDatabase() {
        $this->instance->load->database();
    }

    /**
     * Returns the instance of the db
     * @return object
     */
    private function getDB() {
        return $this->instance->db;
    }

    public function bootEloquent() {

        $this->setInstance();

        $this->loadDatabase();

        $config = $this->getDB();

        $capsule = new Capsule;

        $capsule->addConnection([
            'driver'    => $config->eloquent_dbdriver,
            'host'      => $config->eloquent_hostname,
            'database'  => $config->eloquent_database,
            'username'  => $config->eloquent_username,
            'password'  => $config->eloquent_password,
            'charset'   => $config->eloquent_char_set,
            'collation' => $config->eloquent_dbcollat,
            'prefix'    => $config->eloquent_dbprefix,
            'schema' => $config->eloquent_schema,
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

}