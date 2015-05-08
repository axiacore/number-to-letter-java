#! /usr/bin/env python
# -*- coding: utf-8 -*-
from itertools import ifilter

UNIDADES = (
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
)

DECENAS = (
    'VENTI',
    'TREINTA ',
    'CUARENTA ',
    'CINCUENTA ',
    'SESENTA ',
    'SETENTA ',
    'OCHENTA ',
    'NOVENTA ',
    'CIEN '
)

CENTENAS = (
    'CIENTO ',
    'DOSCIENTOS ',
    'TRESCIENTOS ',
    'CUATROCIENTOS ',
    'QUINIENTOS ',
    'SEISCIENTOS ',
    'SETECIENTOS ',
    'OCHOCIENTOS ',
    'NOVECIENTOS '
)

UNITS = (
        ('',''),
        ('MIL ','MIL '),
        ('MILLON ','MILLONES '),
        ('MIL MILLONES ','MIL MILLONES '),
        ('BILLON ','BILLONES '),
        ('MIL BILLONES ','MIL BILLONES '),
        ('TRILLON ','TRILLONES '),
        ('MIL TRILLONES','MIL TRILLONES'),
        ('CUATRILLON','CUATRILLONES'),
        ('MIL CUATRILLONES','MIL CUATRILLONES'),
        ('QUINTILLON','QUINTILLONES'),
        ('MIL QUINTILLONES','MIL QUINTILLONES'),
        ('SEXTILLON','SEXTILLONES'),
        ('MIL SEXTILLONES','MIL SEXTILLONES'),
        ('SEPTILLON','SEPTILLONES'),
        ('MIL SEPTILLONES','MIL SEPTILLONES'),
        ('OCTILLON','OCTILLONES'),
        ('MIL OCTILLONES','MIL OCTILLONES'),
        ('NONILLON','NONILLONES'),
        ('MIL NONILLONES','MIL NONILLONES'),
        ('DECILLON','DECILLONES'),
        ('MIL DECILLONES','MIL DECILLONES'),
        ('UNDECILLON','UNDECILLONES'),
        ('MIL UNDECILLONES','MIL UNDECILLONES'),
        ('DUODECILLON','DUODECILLONES'),
        ('MIL DUODECILLONES','MIL DUODECILLONES'),
)


MONEDAS = (
    {'country': u'Colombia', 'currency': 'COP', 'singular': u'PESO COLOMBIANO', 'plural': u'PESOS COLOMBIANOS', 'symbol': u'$'},
    {'country': u'Estados Unidos', 'currency': 'USD', 'singular': u'DÓLAR', 'plural': u'DÓLARES', 'symbol': u'US$'},
    {'country': u'Europa', 'currency': 'EUR', 'singular': u'EURO', 'plural': u'EUROS', 'symbol': u'€'},
    {'country': u'México', 'currency': 'MXN', 'singular': u'PESO MEXICANO', 'plural': u'PESOS MEXICANOS', 'symbol': u'$'},
    {'country': u'Perú', 'currency': 'PEN', 'singular': u'NUEVO SOL', 'plural': u'NUEVOS SOLES', 'symbol': u'S/.'},
    {'country': u'Reino Unido', 'currency': 'GBP', 'singular': u'LIBRA', 'plural': u'LIBRAS', 'symbol': u'£'}
)
# Para definir la moneda me estoy basando en los código que establece el ISO 4217
# Decidí poner las variables en inglés, porque es más sencillo de ubicarlas sin importar el país
# Si, ya sé que Europa no es un país, pero no se me ocurrió un nombre mejor para la clave.


def hundreds_word(number):
    """Converts a positive number less than a thousand (1000) to words in Spanish

    Args:
        number (int): A positive number less than 1000
    Returns:
        A string in Spanish with first letters capitalized representing the number in letters

    Examples:
        >>> to_word(123)
        'Ciento Ventitres'
    """
    converted = ''
    if not (0 < number < 1000):
        return 'No es posible convertir el numero a letras'

    number_str = str(number).zfill(9)
    cientos = number_str[6:]


    if(cientos):
        if(cientos == '001'):
            converted += 'UN '
        elif(int(cientos) > 0):
            converted += '%s ' % __convert_group(cientos)


    return converted.title().strip()



def __convert_group(n):
    """Turn each group of numbers into letters"""
    output = ''

    if(n == '100'):
        output = "CIEN "
    elif(n[0] != '0'):
        output = CENTENAS[int(n[0]) - 1]

    k = int(n[1:])
    if(k <= 20):
        output += UNIDADES[k]
    else:
        if((k > 30) & (n[2] != '0')):
            output += '%sY %s' % (DECENAS[int(n[1]) - 2], UNIDADES[int(n[2])])
        else:
            output += '%s%s' % (DECENAS[int(n[1]) - 2], UNIDADES[int(n[2])])

    return output

def to_word(number, mi_moneda=None):

    """Converts a positive number less than:
    (999999999999999999999999999999999999999999999999999999999999999999999999)
    to words in Spanish

    Args:
        number (int): A positive number less than specified above
        mi_moneda(str,optional): A string in ISO 4217 short format
    Returns:
        A string in Spanish with first letters capitalized representing the number in letters

    Examples:
        >>> number_words(53625999567)
        'Cincuenta Y Tres Mil Seiscientos Venticinco Millones Novecientos Noventa Y Nueve Mil Quinientos Sesenta Y Siete'
    """
    if mi_moneda != None:
        try:
            moneda = ifilter(lambda x: x['currency'] == mi_moneda, MONEDAS).next()
            if number < 2:
                moneda = moneda['singular']
            else:
                moneda = moneda['plural']
        except:
            return "Tipo de moneda inválida"
    else:
        moneda = ""

    human_readable = []
    num_units = format(number,',').split(',')
    #print num_units
    for i,n in enumerate(num_units):
        if int(n) != 0:
            words = hundreds_word(int(n))
            units = UNITS[len(num_units)-i-1][0 if int(n) == 1 else 1]
            human_readable.append([words,units])

    #filtrar MIL MILLONES - MILLONES -> MIL - MILLONES
    for i,item in enumerate(human_readable):
        try:
            if human_readable[i][1].find(human_readable[i+1][1]):
                human_readable[i][1] = human_readable[i][1].replace(human_readable[i+1][1],'')
        except IndexError:
            pass
    human_readable = [item for sublist in human_readable for item in sublist]
    human_readable.append(moneda)
    return ' '.join(human_readable).replace('  ',' ').title().strip()


