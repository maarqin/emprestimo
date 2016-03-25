<?php

/**
 * Created by PhpStorm.
 * User: thomaz
 * Date: 23/04/15
 * Time: 7:42 PM
 */

class Emprestimo extends Conexao {

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'emprestimo';

    /**
     * Método que popula a tabela de emprestimos
     *
     * @param array $data
     * @return int
     */
    public function store(array $data){

        $data['data'] = Uses::formatarData($data['data'], '/', '-');
        $data['valor'] = Uses::parseFloat($data['valor']);

        return $this->insert(array('cliente', 'email', 'dia', 'celular', 'telefone', 'valor', 'taxaJuros'), $data);
    }

    /**
     * Metodo atualizar registro de emprestimo para pago
     *
     * @param $id
     * @return int
     */
    public function finalizar($id){
        return $this->update(array('pago', 'diaPago'), array(1, date('Y-m-d')), array('id' => array('=', $id)));
    }

    /**
     * Alterar o juros de um emprestimo
     *
     * @param $id
     * @param $value
     * @return int
     */
    public function renewJuros($id, $value){
        return $this->update(array('taxaJuros'), array($value), array('id' => array('=', $id)));
    }

    /**
     * Get all da tabela emprestimo
     * Retorna todos os registros que ainda não foram pagos
     *
     * @param array $where
     * @return array
     */
    public function all(array $where = array()){

        $resultado = $this->select(array('id', 'cliente', 'email', 'celular', 'telefone', 'valor','taxaJuros', 'dia',
            '(SELECT DATEDIFF(NOW(), dia))' => 'diff', '(SELECT DATEDIFF(diaPago, dia))' => 'diffPago', 'pago'), $where,
            array('asc' => 'id'));

        $retorno = array();
        foreach( $resultado as $dados ){
            $dados = (array) $dados; // typecasting

            // Insere valores pagos, para abater no saldo devedor
            $dados['pagos'] = $this->sumAllValoresPagos($dados['id']);
            array_push($retorno, (object)$dados);
        }
        return $retorno;
    }

    /**
     * Get todos os dados da tabela por id
     *
     * @param $id
     * @return object
     */
    public function getById($id){
        return current($this->select(array('*', '(SELECT DATEDIFF(diaPago, dia))' => 'diffPago'), array('id' => array('=', $id))));
    }

    /**
     * Busca o juros a ser atualizado
     *
     * @param $id
     * @return object
     */
    public function getJurosById($id){
        return current($this->select(array('id', 'taxaJuros' => 'juros'), array('id' => array('=', $id))));
    }

    /**
     * Get by id o emprestimo
     * Retorna valores financeiros
     *
     * @param $id
     * @return object
     */
    public function getDadosFinanceirosById($id){
        return current($this->select(array('valor', 'taxaJuros', '(SELECT DATEDIFF(NOW(), dia))' => 'diff'),
            array('id' => array('=', $id))));
    }

    /**
     * Calcula saldo devedor a pagar
     *
     * @param $juros
     * @param $dias
     * @param int $valor
     * @param null $pagos
     * @return string
     */
    public function valorComJurosAPagar($juros, $dias, $valor, $pagos = null){

        $sumJuros = $this->calcularJurosAPagar($juros, $dias);
//        $saldoDevedor = $valor-$pagos;
//        $valorAPagar = ($saldoDevedor*$sumJuros)+$saldoDevedor;
        $valorAPagar = (($valor*$sumJuros)+$valor)-$pagos;


        return Uses::moeda($valorAPagar);
    }

    /**
     * Metodo que soma os valores ja pagos de um emprestimo
     *
     * @param $id
     * @return float|int
     */
    public function sumAllValoresPagos($id){

        $ep = new EmprestimoPagamento();

        $retorno = 0;
        $valores = $ep->getAllValoresPagos($id);
        for($i = 0; $i < count($valores); $i++){
            $retorno += floatval($valores[$i]->valor);
        }
        return $retorno;
    }

    /**
     * Método para calcular o valor que o cliente deve à pagar
     *
     * @param $juros
     * @param $dias
     * @return float|int
     */
    private function calcularJurosAPagar($juros, $dias){

        $retorno = 0;
        $ano = date('Y');
        $mes = date('m');
        $hoje = date('d');
        while( $dias > 0 ){
            $diasMes = $this->diasMes($ano, $mes);
            $sDia = $diasMes;
            if( $diasMes > $dias ){
                $diasMes = $dias;
                $diaUsado = $diasMes;
            } else {
                $diaUsado = ($hoje > 0) ? $hoje : $diasMes; // Pegar os dias do mes atual
            }
            $percEsteMes = ($juros / $sDia); // Sempre valor de dias dos mes cheio !
            $retorno += ($percEsteMes * $diaUsado);
            $dias -= $diaUsado;

            $hoje = 0;
            $this->controladorAnoMes($ano, $mes);// Mudou o mes ou o ano
        }
        return ($retorno/100);
    }

    /**
     * Retorna a quantidade de dias que determinado mes tem
     *
     * @param $ano
     * @param $mes
     * @return int
     */
    private function diasMes($ano, $mes){
        return cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
    }

    /**
     * Controlador do mes e do ano para o calculo do juros
     * Passagem por referencia
     *
     * @param $ano
     * @param $mes
     */
    private function controladorAnoMes(&$ano, &$mes){

        if( ($mes-1) == 0 ){
            $mes = 12;
            $ano--;
        } else {
            $mes--;
        }
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