<?php

/**
 * Created by PhpStorm.
 * User: thomaz
 * Date: 02/05/15
 * Time: 3:22 AM
 */

class EmprestimoPagamento extends Conexao {

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'emprestimoPagamento';

    /**
     * Grava novo pagamento de emprestimo
     *
     * @param array $data
     * @return int
     */
    public function storePagamento(array $data){

        $data['valor'] = Uses::parseFloat($data['valor']);
        $data['data'] = Uses::formatarData($data['data'], '/', '-');

        return $this->insert(array('emprestimo_id', 'valor', 'data'), $data);
    }

    /**
     * Metodo para somar os valores ja pagos
     *
     * @param $id
     * @return array
     */
    public function getAllValoresPagos($id){
        return $this->select(array('valor', 'data'), array('emprestimo_id' => array('=', $id)));
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