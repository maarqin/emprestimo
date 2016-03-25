<?php
/**
 * Created by PhpStorm.
 * User: thomaz
 * Date: 02/05/15
 * Time: 3:25 AM
 */

final class Uses {

    /**
     * Converter a data de um formato ao outro
     *
     * @param $data
     * @param $de
     * @param $para
     * @return string
     */
    public static function formatarData($data, $de, $para){
        return implode($para, array_reverse(explode($de, $data)));
    }

    /**
     * Cria um formato monetario pt-BR|BRL
     *
     * @param $valor
     * @return string
     */
    public static function moeda($valor){
        return number_format($valor, 2, ',', '.');
    }

    /**
     * Cria um formato do tipo decimal para o banco de dados
     * Formato monetario americano
     *
     * @param $valor
     * @return float
     */
    public static function parseFloat($valor){

        $valor = str_replace('.', '', $valor);
        $valor = str_replace(',', '.', $valor);
        return floatval($valor);
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