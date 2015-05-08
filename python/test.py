#! /usr/bin/env python
# -*- coding: utf-8 -*-
import unittest
import number_to_letter


class TestToWord(unittest.TestCase):
    def setUp(self):
        self.list_number = [
            1, 100, 2000,
            300000, 4000000,
            500000000, 999999998,
            6000000000,
        ]

        self.list_countries = [
            'COP', 'USD', 'EUR',
            'MXN', 'PEN', 'GBP',
        ]

        self.list_messages = [
            "Un Peso Colombiano", u"Un Dólar", "Un Euro", "Un Peso Mexicano",
            "Un Nuevo Sol", "Un Libra"
        ]
        self.list_messages2 = [
            "Cien Pesos Colombianos", u"Dos Mil Dólares",
            "Trescientos Mil Euros", "Cuatro Millones Pesos Mexicanos",
            "Quinientos Millones Nuevos Soles",
            "Novecientos Noventa Y Nueve Millones Novecientos Noventa Y Nueve Mil Novecientos Noventa Y Ocho Libras",
            "Seis Mil Millones",
        ]

    def test_to_word(self):

        cont_msg = 0
        for country in self.list_countries:
            self.assertEqual(
                number_to_letter.to_word(self.list_number[0], country),
                self.list_messages[cont_msg]
            )
            cont_msg += 1

        cont_msg = 0
        cont_num = 1
        for country in self.list_countries:
            self.assertEqual(
                number_to_letter.to_word(self.list_number[cont_num], country),
                self.list_messages2[cont_msg]
            )
            cont_msg += 1
            cont_num += 1
