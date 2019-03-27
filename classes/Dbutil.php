<?php

//defined("APPREAL") OR die("Access denied");

/**
 * Clase utiliria de conexiones a BD a traves de clase mysqli
 *
 * @author GDMC
 */
class Dbutil {

    private static $connection;
    private static $consulta;
    private $host = DBHOST;
    private $user = DBUSER;
    private $pswd = DBPSWD;
    private $db = DBNAME;

    //private static $myDB;

    /**
     * Conectar a la BD
     * 
     * @return bool FALSE si falla/ MySQLi object instance en exito 
     */
    function __construct() {
        self::$connection = $this->connect();
        //self::$myDB = $this->connect();
    }

    /**
     * Conectar a la BD
     * 
     * @return bool FALSE si falla/ MySQLi object instance en exito 
     */
    public function connect() {
        // Try and connect to the database
        if (!isset(self::$connection)) {
            // Load configuration as an array. Use the actual location of your configuration file
            //$config = parse_ini_file('./config.ini'); 
            //self::$connection = new mysqli('localhost',$config['username'],$config['password'],$config['dbname']);
            self::$connection = new mysqli($this->host, $this->user, $this->pswd, $this->db);
            //$software = Singleton::getInstance('NotORM');
            //self::$myDB = new NotORM(self::$connection);

            
        }


        // If connection was not successful, handle the error
        if (self::$connection === false) {
            // Handle error - notify administrator, log to a file, show an error screen, etc.
            printf("Error cargando el conjunto de caracteres utf8: %s\n", mysqli_error($enlace));
            return false;
        }else{
            if (!mysqli_set_charset(self::$connection, "utf8")) {
                printf("Error cargando el conjunto de caracteres utf8: %s\n", mysqli_error($enlace));
                exit();
            } else {
                mysqli_character_set_name(self::$connection);
            }
            
        }
        return self::$connection;
        //return self::$myDB;
    }

    /* PREPARE */

    function prepare($sql = '', $parametros = array(), $tipo = 0) {
        self::$consulta = self::$connection->prepare($sql);
        $result = self::$consulta->execute($parametros);
        if ($tipo == 0) {
            return self::$consulta->fetchAll();
        } else {
            return $result;
        }
    }

    /**
     * Ejecutar query en la BD. 
     *
     * @param $query The query string
     * @return mixed The result of the mysqli::query() function
     */
    public function query($query, $tipo = null) {
        // Connect to the database
        //$connection = $this->connect();
        // Query the database
        $result = self::$connection->query($query);
        if ($tipo === 1) {
            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            return $rows;
        }
        return $result;
    }

    /**
     * Obtener registros de la base de datos (SELECT query)
     *
     * @param $query SQL a ejecutar.
     * @return bool False si falla / array si es exitoso el query.
     */
    public function db_select($query) {
        $rows = array();
        $result = $this->query($query);
        if ($result === false) {
            return false;
        }



        /*
          //while ($row = mysql_fetch_assoc($result)) {
          $row = $result->fetch_assoc();
          while ($row = $result->fetch_assoc()) {
          $rows[] = $row;
          }
          return $rows; */
        return $result;
    }

    /**
     * Obtener registros de la base de datos (SELECT query)
     *
     * @param $query SQL a ejecutar.
     * @return bool False si falla / array si es exitoso el query.
     */
    public function db_select_array($query) {
        $rows = array();
        $result = $this->query($query);
        if ($result === false) {
            return false;
        }

        foreach ($result as $row) {
            $inf[] = $row;
        }
        return $inf;
    }

    /**
     * Devuelve el ultimo error en la BD. 
     * 
     * @return string Mensaje con el ultimo error en la BD
     */
    public function error() {
        $connection = $this->connect();
        return $connection->error;
    }

    /**
     * 
     * 
     */
    public function escape($s){
        return self::$connection->real_escape_string($s);
    }

    /**
     * Inserta en la BD
     * 
     *  
     * @param string $query Insert a ejecutar (resultado de funcion query_insert)
     * @param mysqli_link $cnx Conexion a usar
     * 
     * @return bool False en caso de error conectando
     */
    public function db_insert($query, &$err, $cnx = NULL) {
        //global $PCNX;
        $res = ($cnx) ? @mysqli_query($cnx, $query) : self::$connection->query($query);
        if (self::$connection->error) {
            /*
              try {
              throw new Exception("MySQL error ".self::$connection->error ."<br> Query:<br> $query", self::$connection->errno);
              } catch (Exception $e) {
              echo "Error No: " . $e->getCode() . " - " . $e->getMessage() . "<br >";
              echo nl2br($e->getTraceAsString());
              } */
            $err = self::$connection->error;
            return false;
        }
        return self::$connection->insert_id;
    }

    /**
     * 
     * Crear query para insercion
     * 
     * @param $param Arreglo de parametros atributo=>valor
     * @param $table Tabla sobre la cual se va a insertar.
     * 
     * @return string 
     * 
     */
    public function query_insert(&$param, $table) {
        foreach ($param as $key => $p) {
            $attr[] = strtoupper($key);
            if ($p["value"]) {
                switch ($p["type"]) {
                    case "file" :
                    case "image" : $val[] = "0x" . $p["value"];
                        break;
                    case "numeric" : $val[] = $p["value"];
                        break;
                    case "mysql" : $val[] = $p["value"];
                        break;
                    case "date" : ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $p["value"], $r);
                        $val[] = "\"" . $r[3] . "-" . $r[2] . "-" . $r[1] . "\"";
                        break;
                    case "email" :
                    default : $val[] = "\"" . self::$connection->real_escape_string($p["value"]) . "\"";
                }
            } else {
                $val[] = "NULL";
            }
        }
        return "INSERT INTO " . $table . " (" . implode(", ", $attr) . ") VALUES (" . implode(", ", $val) . ")";
    }



/**
 * get update query method (id like "key=value")
 * add_string($registro,"fnac",trim($data[1]));
 * db_update(query_update($registro,"usuario","idusuario=".$res[0]['idusuario']),$err);
 */
function query_update(&$param, $table, $id) {
    foreach ($param as $key => $p) {
        $tmp = $key . "=";
        if ($p["value"]) {
            switch ($p["type"]) {
                case "file" :
                case "image" : $tmp.= "0x" . $p["value"];
                    break;
                case "numeric" : $tmp.= $p["value"];
                    break;
                case "date" : ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $p["value"], $r);
                    $tmp.= "\"" . $r[3] . "-" . $r[2] . "-" . $r[1] . "\"";
                    break;
                case "mysql" : $val[] = $p["value"];
                    break;
                case "email" :
                default : $tmp.= "\"" . self::$connection->real_escape_string($p["value"]) . "\"";
            }
        } else {
            $tmp.= "NULL";
        }
        $attr[] = $tmp;
    }
    return "UPDATE " . $table . " SET " . implode(", ", $attr) . " WHERE " . $id;
}



    /*
     * 
     * Cerrar BD
     * 
     * @param mysqli_link $cnx conexión 
     * @return VOID 
     * 
     */

    public function db_close($cnx = NULL) {
        if ($cnx) {
            @mysqli_close($cnx);
        } else {
            if (self::$connection) {
                self::$connection->close();
            }
        }
    }

    /*
     * 
     * db_connect función para conectarse a una BD alterna
     * 
     * @param string $host servidor
     * @param string $user usuario
     * @param string $pswd password
     * @param string $db base de datos
     * 
     * @return mysqli_link $cnx conexión
     * 
     */

    public function &db_connect($host, $user, $pswd, $db) {
        $cnx = @mysqli_connect($host, $user, $pswd, $db);
        if ($cnx) {

            return $cnx;
        }
        return NULL;
    }

    /*
     * 
     * db_call función para llamado procedimientos
     * 
     *  
     * @param string $proc Nombre de SP 
     * @param string $par parametros separados por coma 
     * @param mysqli_link $cnx
     * 
     * @return mixed Resultado del procedimiento. Puede ser VOID o un 
     * resulset. 
     * 
     */

    function &db_call($proc, $par, $cnx = NULL) {
        @mysqli_free_result($res);
        self::$connection->next_result();
        $param = "";
        foreach ($par as $value) {
            $param.= "$value,";
        }
        $param = substr($param, 0, -1);

        $query = "CALL $proc(" . $param . ")";

        //$_SESSION['llamada'] = "<br>query->" . $query;
        $res = ($cnx) ? @mysqli_query($cnx, $query) : self::$connection->query($query);

        $tab = array();
        if (self::$connection->error OR @mysqli_errno($cnx)) {

            /*  $rv = false;
              return $rv;

              $tab[] = self::$connection->error;
              return $tab; */
            throw new Exception("Problema ejecutando el procedimiento de BD $proc: " . self::$connection->error);
        } else {

            if ($cnx) {
                while ($row = @mysqli_fetch_assoc($res)) {
                    $tab[] = $row;
                }
            } else {
                while ($row = $res->fetch_assoc()) {
                    $tab[] = $row;
                }
            }
            @mysqli_free_result($res);
            self::$connection->next_result();
            return $tab;
        }
    }

    /*
     * 
     * db_call función para llamado de funciones
     * 
     *  
     * @param string $func Nombre de función 
     * @param array $par parametros en cada posicion del array | array("uno", "dos", "tres");
     * @param mysqli_link $cnx
     * 
     * @return mixed Resultado de la función. Puede ser VOID o un 
     * resulset. 
     * 
     */

    function &db_callf($func, $par, $cnx = NULL) {
        $param = "";
        foreach ($par as $value) {
            $param.= "$value ,";
        }
        $param = substr($param, 0, -1);

        $query = "SELECT $func(" . $param . ")";
        //echo "<br>query funcion->".$query;
        $res = ($cnx) ? @mysqli_query($cnx, $query) : self::$connection->query($query);

        if (self::$connection->error OR @ mysqli_errno($cnx)) {
            $rv = false;
            return $rv;
        } else {

            if ($cnx) {
                while ($row = @mysqli_fetch_assoc($res)) {
                    $tab[] = $row;
                }
            } else {
                while ($row = $res->fetch_assoc()) {
                    $tab[] = $row;
                }
            }

            @mysqli_free_result($res);
            @self::$connection->next_result();
            return $tab;
        }
    }

    /*
     * res_callf: la idea de esta funcion es servir de utilitario y 
     * el valor dentro de los arrays que arroja db_callf
     * 
     * @param array $res array resultado de la funcion 
     * @param string $func Nombre de función 
     * @param array $par parametros en cada posicion del array | array("uno", "dos", "tres");

     */

    function res_callf($res, $func, $par) {

        $param = "";
        foreach ($par as $value) {
            $param.= "$value ,";
        }
        $param = substr($param, 0, -1);
        $pari = $func . '(' . $param . ')';
        return $res[0][$pari];
    }

    /*
     * res_call: la idea de esta funcion es servir de utilitario y 
     * el valor dentro de los arrays que arroja db_callf
     * 
     * @param array $res array resultado de la funcion 
     * @param string $func Nombre de función 
     * @param array $par parametros en cada posicion del array | array("uno", "dos", "tres");

     */

    function res_call($res) {

        return $res[0];
    }

    /*
     * 
     * query_delete devuelve el strig correspondiente para borrar un registro
     * 
     * @param string $table   tabla sobre la cual vamos a borrar el registro
     * @param string $id_str  cadena de esta forma  "key=value" donde 
     *          key es el nombre de la columna PK y value el valor del registro a
     *          eliminar 
     * 
     * @return VOID 
     * 
     */

    function query_delete($table, $id_str) {

        return "DELETE FROM " . $table . " WHERE " . $id;
    }

    /*
     * 
     * Función para borrar registros
     * 
     * @param string $table   tabla sobre la cual vamos a borrar el registro
     * @param string $id_str  cadena de esta forma  "key=value" donde 
     *          key es el nombre de la columna PK y value el valor del registro a
     *          eliminar 
     * 
     * @return bool en éxito TRUE/FALSE si hubo un fallo 
     * 
     */

    function db_delete($table, $id_str, $cnx = NULL) {
        $query = query_delete($table, $id_str);
        $res = ($cnx) ? @mysqli_query($cnx, $query) : self::$connection->query($query);

        if (self::$connection->error OR @mysqli_errno($cnx)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /*
     * 
     * Función para obtener info respecto a la operación previa
     * 
     * @param mysqli $cnx  
     * 
     * @return string Información del query anterior
     * 
     */

    function info_query($cnx = NULL) {
        if ($cnx) {
            return @mysqli_sqlstate($cnx);
        } else {
            return self::$connection->sqlstate;
        }
    }

    /**
     * Escapa caracteres especiales en una cadena para su uso en una sentencia SQL
     *
     * @param string $value The value to be quoted and escaped
     * @return string The quoted and escaped string
     */
    public function quote($value) {
        $connection = $this->connect();
        return "'" . $connection->real_escape_string($value) . "'";
    }



    //============================================================================
        // add string method
        function add_string(&$param, $key, $value) {
            $param[$key]["type"] = "string";
            $v = (is_array($value)) ? implode(",", $value) : $value;
            $param[$key]["value"] = $v;
            return TRUE;
        }


        //============================================================================
        // add numeric method
        function add_numeric(&$param, $key, $value) {
            $n = str_replace(",", ".", $value);
            if ($n && !is_numeric($n))
                return -1;
            $param[$key]["type"] = "numeric";
            $param[$key]["value"] = $n;
            return TRUE;
        }


        //============================================================================
        // get value method
        function &get_value(&$param, $key) {
            return $param[$key]["value"];
        }


}
