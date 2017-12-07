<?php
/**
 * Clase que implementa un conversor de números a letras.
 * @author AxiaCore S.A.S
 */
class NumberToLetterConverter
{
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
        'VEINTI',
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
    private $MONEDAS = [
        [
            'country' => 'Colombia',
            'currency' => 'COP',
            'singular' => 'PESO COLOMBIANO',
            'plural' => 'PESOS COLOMBIANOS',
            'decimal' => [
                'singular' => 'CENTAVO',
                'plural' => 'CENTAVOS'
            ],
            'symbol',
            '$'
        ],
        [
            'country' => 'Estados Unidos',
            'currency' => 'USD',
            'singular' => 'DÓLAR',
            'plural' => 'DÓLARES',
            'decimal' => [
                'singular' => 'CENTAVO',
                'plural' => 'CENTAVOS'
            ],
            'symbol',
            'US$'
        ],
        [
            'country' => 'El Salvador',
            'currency' => 'USD',
            'singular' => 'DÓLAR',
            'plural' => 'DÓLARES',
            'decimal' => [
                'singular' => 'CENTAVO',
                'plural' => 'CENTAVOS'
            ],
            'symbol',
            'US$'
        ],
        [
            'country' => 'Europa',
            'currency' => 'EUR',
            'singular' => 'EURO',
            'plural' => 'EUROS',
            'decimal' => [
                'singular' => 'CENTAVO',
                'plural' => 'CENTAVOS'
            ],
            'symbol',
            '€'
        ],
        [
            'country' => 'México',
            'currency' => 'MXN',
            'singular' => 'PESO MEXICANO',
            'plural' => 'PESOS MEXICANOS',
            'decimal' => [
                'singular' => 'CENTAVO',
                'plural' => 'CENTAVOS'
            ],
            'symbol',
            '$'
        ],
        [
            'country' => 'Perú',
            'currency' => 'PEN',
            'singular' => 'NUEVO SOL',
            'plural' => 'NUEVOS SOLES',
            'decimal' => [
                'singular' => 'CENTAVO',
                'plural' => 'CENTAVOS'
            ],
            'symbol',
            'S/'
        ],
        [
            'country' => 'Reino Unido',
            'currency' => 'GBP',
            'singular' => 'LIBRA',
            'plural' => 'LIBRAS',
            'decimal' => [
                'singular' => 'CENTAVO',
                'plural' => 'CENTAVOS'
            ],
            'symbol',
            '£'
        ],
        [
            'country' => 'Argentina',
            'currency' => 'ARS',
            'singular' => 'PESO',
            'plural' => 'PESOS',
            'decimal' => [
                'singular' => 'CENTAVO',
                'plural' => 'CENTAVOS'
            ],
            'symbol',
            '$'
        ]
    ];
    private $separator;
    private $decimal_mark;
    private $glue;

    public function __construct($separator = ',', $decimalMark = '.', $glue = ' CON ')
    {
        $this->separator = $separator;
        $this->decimal_mark = $decimalMark;
        $this->glue = $glue;
    }

    /**
     * Evalua si el número contiene separadores o decimales
     * formatea y ejecuta la función conversora
     * @param $number número a convertir
     * @param $miMoneda clave de la moneda
     * @return string completo
     */
    public function toWord($number, $miMoneda = null)
    {
        if (strpos($number, $this->decimal_mark) === false) {
            $convertedNumber = [ $this->convertNumber($number, $miMoneda, 'entero') ];
        } else {
            $number = explode($this->decimal_mark, str_replace($this->separator, '', trim($number)));
            $convertedNumber = [
                $this->convertNumber($number[0], $miMoneda, 'entero'),
                $this->convertNumber($number[1], $miMoneda, 'decimal'),
            ];
        }
        return implode($this->glue, array_filter($convertedNumber));
    }

    /**
     * Convierte número a letras
     * @param $number
     * @param $miMoneda
     * @param $type tipo de dígito (entero/decimal)
     * @return $converted string convertido
     */
    private function convertNumber($number, $miMoneda, $type)
    {
        $converted = '';
        if ($miMoneda !== null) {
            try {
                $moneda = array_filter($this->MONEDAS, function ($m) use ($miMoneda) {
                    return ($m['currency'] == $miMoneda);
                });
                $moneda = array_values($moneda);
                if (count($moneda) <= 0) {
                    throw new Exception("Tipo de moneda inválido");
                    return;
                }
                
                if ($number == 0) {
                    if ($type == 'entero') {
                        $moneda = $moneda[0]['plural'];
                    } else {
                        $moneda = $moneda[0]['decimal']['plural'];
                    }
                } elseif ($number < 2) {
                    if ($type == 'entero') {
                        $moneda = $moneda[0]['singular'];
                    } else {
                        $moneda = $moneda[0]['decimal']['singular'];
                    }
                } else {
                    if ($type == 'entero') {
                        $moneda = $moneda[0]['plural'];
                    } else {
                        $moneda = $moneda[0]['decimal']['plural'];
                    }
                }
                // ($number < 2 ? $moneda = $moneda[0]['singular'] : $moneda = $moneda[0]['plural']);
            } catch (Exception $e) {
                echo $e;
                return;
            }
        } else {
            $moneda = '';
        }
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
            } else /*if (intval($millones) > 0) */{
                $converted .= sprintf('%sMILLONES ', $this->convertGroup($millones));
            }
        }
        
        if (intval($miles) > 0) {
            if ($miles == '001') {
                $converted .= 'MIL ';
            } else /*if (intval($miles) > 0) */{
                $converted .= sprintf('%sMIL ', $this->convertGroup($miles));
            }
        }
        if (intval($cientos) > 0) {
            if ($cientos == '001') {
                $converted .= 'UN ';
            } else /*if (intval($cientos) > 0) */{
                $converted .= sprintf('%s ', $this->convertGroup($cientos));
            }
        } elseif (intval($millones) < 1 && intval($miles) < 1) {
            $converted .= 'CERO ';
        }
        $converted .= $moneda;
        return $converted;
    }

    /**
     * Define el tipo de representación decimal (centenas/millares/millones)
     * @param $n
     * @return $output
     */
    private function convertGroup($n)
    {
        $output = '';
        if ($n == '100') {
            $output = "CIEN ";
        } elseif ($n[0] !== '0') {
            $output = $this->CENTENAS[$n[0] - 1];
        }
        $k = intval(substr($n, 1));
        if ($k <= 20) {
            $output .= $this->UNIDADES[$k];
        } else {
            if (($k > 30) && ($n[2] !== '0')) {
                $output .= sprintf('%sY %s', $this->DECENAS[intval($n[1]) - 2], $this->UNIDADES[intval($n[2])]);
            } else {
                $output .= sprintf('%s%s', $this->DECENAS[intval($n[1]) - 2], $this->UNIDADES[intval($n[2])]);
            }
        }
        return $output;
    }
}
