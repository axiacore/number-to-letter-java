<?php
/**
 * Clase que implementa un conversor de números a letras. 
 * @author AxiaCore S.A.S
 *
 */

class NumberToLetterConverter {
    private $UNIDADES = [
        '',
        'UN ',
        'DOS ',
        'TRES ',
        'CUATRO ',
        'CINCO ',
        'SEIS ',
        'SIETE ',
        'OCHO ',
        'NUEVE ',
        'DIEZ ',
        'ONCE ',
        'DOCE ',
        'TRECE ',
        'CATORCE ',
        'QUINCE ',
        'DIECISEIS ',
        'DIECISIETE ',
        'DIECIOCHO ',
        'DIECINUEVE ',
        'VEINTE '
    ];

    private $DECENAS = [
        'VENTI',
        'TREINTA ',
        'CUARENTA ',
        'CINCUENTA ',
        'SESENTA ',
        'SETENTA ',
        'OCHENTA ',
        'NOVENTA ',
        'CIEN '
    ];

    private $CENTENAS = [
        'CIENTO ',
        'DOSCIENTOS ',
        'TRESCIENTOS ',
        'CUATROCIENTOS ',
        'QUINIENTOS ',
        'SEISCIENTOS ',
        'SETECIENTOS ',
        'OCHOCIENTOS ',
        'NOVECIENTOS '
    ];

    private $MONEDAS = array(
        ['country' => 'Colombia','currency' => 'COP', 'entero' => ['PESO COLOMBIANO', 'PESOS COLOMBIANOS'], 'decimal' => ['', ''], 'symbol', '$'],
        ['country' => 'Estados Unidos', 'currency' => 'USD', 'entero' => ['DÓLAR', 'DÓLARES'], 'decimal' => ['CENTAVO', 'CENTAVOS'], 'symbol', 'US$'],
        ['country' => 'Europa', 'currency' => 'EUR', 'entero' => ['EURO', 'EUROS'], 'decimal' => ['CENTIMO', 'CENTIMOS'], 'symbol', '€'],
        ['country' => 'México', 'currency' => 'MXN', 'entero' => ['PESO MEXICANO', 'PESOS MEXICANOS'], 'decimal' => ['', ''], 'symbol', '$'],
        ['country' => 'Perú', 'currency' => 'PEN', 'entero' => ['NUEVO SOL', 'NUEVOS SOLES'], 'decimal' => ['', ''], 'symbol', 'S/'],
        ['country' => 'Reino Unido', 'currency' => 'GBP', 'entero' => ['LIBRA', 'LIBRAS'], 'decimal' => ['', ''], 'symbol', '£'],
        ['country' => 'Argentina', 'currency' => 'ARS', 'entero' => ['PESO', 'PESOS'], 'decimal' => ['CENTAVO', 'CENTAVOS'], 'symbol', '$']
    );

    private $separator = '.';
    private $decimal_mark = ',';
    private $glue = ' CON ';

    /**
     * Evalua si el número contiene separadores o decimales
     * formatea y ejecuta la función conversora
     * @param $number número a convertir
     * @param $miMoneda clave de la moneda
     * @return string completo
     */
    public function to_word($number, $miMoneda = null) {

        $number = explode($this->decimal_mark, str_replace($this->separator, '', trim($number)));

        $convertedNumber = array(
            $this->convertNumber($number[0], $miMoneda, 'entero'),
            $this->convertNumber($number[1], $miMoneda, 'decimal'),
        );
        return implode($this->glue, $convertedNumber);
    }

    /**
     * Convierte número a letras
     * @param $number
     * @param $miMoneda
     * @param $type tipo de dígito (entero/decimal)
     * @return $converted string convertido
     */
    private function convertNumber($number, $miMoneda = null, $type) {   
        
        $converted = '';
        if ($miMoneda !== null) {
            try {
                
                $moneda = array_filter($this->MONEDAS, function($m) use ($miMoneda) {
                    return ($m['currency'] == $miMoneda);
                });

                $moneda = array_values($moneda);

                if (count($moneda) <= 0) {
                    throw new Exception("Tipo de moneda inválido");
                    return;
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                return;
            }
        }

        ($number < 2 ? $moneda = $moneda[0][$type][0] : $moneda = $moneda[0][$type][1]);

        if (($number < 0) || ($number > 999999999)) {
            return false;
        }

        $numberStr = (string) $number;
        $numberStrFill = str_pad($numberStr, 9, '0', STR_PAD_LEFT);
        $millones = substr($numberStrFill, 0, 3);
        $miles = substr($numberStrFill, 3, 3);
        $cientos = substr($numberStrFill, 6);

        if (intval($millones) > 0) {
            if ($millones == '001') {
                $converted .= 'UN MILLON ';
            } else if (intval($millones) > 0) {
                $converted .= sprintf('%sMILLONES ', $this->convertGroup($millones));
            }
        }
        
        if (intval($miles) > 0) {
            if ($miles == '001') {
                $converted .= 'MIL ';
            } else if (intval($miles) > 0) {
                $converted .= sprintf('%sMIL ', $this->convertGroup($miles));
            }
        }

        if (intval($cientos) > 0) {
            if ($cientos == '001') {
                $converted .= 'UN ';
            } else if (intval($cientos) > 0) {
                $converted .= sprintf('%s ', $this->convertGroup($cientos));
            }
        }

        $converted .= $moneda;

        return $converted;
    }

    /**
     * Define el tipo de representación decimal (centenas/millares/millones)
     * @param $n
     * @return $output
     */
    private function convertGroup($n) {

        $output = '';

        if ($n == '100') {
            $output = "CIEN ";
        } else if ($n[0] !== '0') {
            $output = $this->CENTENAS[$n[0] - 1];   
        }

        $k = intval(substr($n,1));

        if ($k <= 20) {
            $output .= $this->UNIDADES[$k];
        } else {
            if(($k > 30) && ($n[2] !== '0')) {
                $output .= sprintf('%sY %s', $this->DECENAS[intval($n[1]) - 2], $this->UNIDADES[intval($n[2])]);
            } else {
                $output .= sprintf('%s%s', $this->DECENAS[intval($n[1]) - 2], $this->UNIDADES[intval($n[2])]);
            }
        }

        return $output;
    }
}
