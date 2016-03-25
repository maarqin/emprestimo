<?php

/**
 * Created by PhpStorm.
 * User: thomaz
 * Date: 24/04/15
 * Time: 12:10 PM
 */

class Conexao extends PDO {

    /**
     * Table name
     *
     * @var string
     */
    protected $table;

    /**
     * Informe o arquivo de configuracao do banco de dados
     *
     * @param null $config
     * @throws Exception
     */
    public function __construct($config = null){
        $config = $config ?: __DIR__ . '/config.ini';

        if (!$dados = parse_ini_file($config, true))
            throw new Exception('Erro ao acessar o arquivo: ' . $config . '.');

        $dns = $dados['database']['driver'] .
            ':host=' . $dados['database']['host'] .
            ((!empty($dados['database']['port'])) ? (';port=' . $dados['database']['port']) : '') .
            ';dbname=' . $dados['database']['schema'];

        try {
            parent::__construct($dns, $dados['database']['username'], $dados['database']['password']);
        }catch (PDOException $e){
            printf("<b>Erro:</b> %s na linha %d do arquivo %s", $e->getMessage(), $e->getLine(), $e->getFile());
        }
    }

    /**
     * Metodo para inserir registros
     *
     * @param array $fields
     * @param array $values
     * @return int
     */
    protected function insert(array $fields, array $values){
        $sql = "insert into " . $this->table . " (" . implode(",", $fields) . ") values (" . substr(str_repeat('?,', count($values)),0,-1) . ")";
        $stmt = $this->execute($sql, $values);

        return $stmt->rowCount();
    }

    /**
     * Metodo de atualizacao de registro
     *
     * @param array $fields
     * @param array $values
     * @param array $conditions
     * @return int
     */
    protected function update(array $fields, array $values, array $conditions){

        $params = array();
        $sql = "update " . $this->table . " set " . implode('=?,', $fields)."=?" . $this->where($conditions, $params);
        $union = array_merge($values, $params);

        $stmt = $this->execute($sql, $union);

        return $stmt->rowCount();
    }

    /**
     * Metodo de consulta simples ao banco de dados
     *
     * @param array $fields
     * @param array $conditions
     * @param array $final
     * @return array
     */
    protected function select(array $fields = array('*'), array $conditions = array(), array $final = array()){

        // Campos
        $fieldsDone = array();
        foreach ($fields as $key => $field) {
            array_push($fieldsDone, (is_numeric($key)) ? $field : $key . ' as `' . $field . '`');
        }
        // Order by
        $orderDone = '';
        if( !empty( $final ) ) {
            $order = array('asc' => '%s asc', 'desc' => '%s desc');
            $orderDone = ' order by ' . sprintf($order[current(array_keys($final))], current($final));
        }
        // Where
        $params = array();
        $sql = "select " . implode(', ' , $fieldsDone) . " from " . $this->table . $this->where($conditions, $params) . $orderDone;

        $stmt = $this->execute($sql, $params);

        $retorno = array();
        while( $dados = $stmt->fetch(PDO::FETCH_OBJ) ){
            array_push($retorno, $dados);
        }
        return $retorno;
    }

    /**
     * Apaga um registro
     *
     * @param array $conditions
     * @return int
     */
    protected function delete(array $conditions){

        $params = array();
        $sql = "delete from " . $this->table . $this->where($conditions, $params);
        $stmt = $this->execute($sql, $params);

        return $stmt->rowCount();
    }

    /**
     * Monta o where de uma query necessita de condicao
     * Metodo tambem cria os valores de parametro por referencia
     *
     * @param array $conditions
     * @param array $params
     * @return array
     */
    private function where(array $conditions, array &$params){
        $whereDone = array(' where ');
        foreach ($conditions as $field => $condition) {
            $aux = ($field . ' ' . current($condition) . ' ? ' . $condition[2] . ' ');

            array_push($whereDone, $aux);
            array_push($params, $condition[1]);
        }
        return implode('', $whereDone);
    }

    /**
     * Executa uma query com o banco de dados
     *
     * @param $sql
     * @param array $values
     * @return PDOStatement
     */
    private function execute($sql, array $values){

        $stmt = $this->prepare($sql);
        if( !empty($values) ){
            $i = 0;
            foreach($values as &$value){
                $stmt->bindParam((++$i), $value);
            }
        }

        // Executa a sql
        // Ou trata os erros ocorridos durante a execução da query
        try {
            $stmt->execute();
        } catch (PDOException $e){
            printf("<b>Falha ao executar query: </b>%s", $e->getMessage());
        }
        return $stmt;
    }

    /**
     * Description of class
     * __toString();
     *
     * @return string
     */
    public function __toString(){
        return sprintf("\\%s.php - &copy; Thought by eu at marcosthomaz dot com", __CLASS__);
    }

}