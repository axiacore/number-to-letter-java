import unittest
import number_to_letter


class TestToWord(unittest.TestCase):
    def setUp(self):
        self.number1 = 100
        self.number2 = 2000
        self.number3 = 300000
        self.number4 = 4000000
        self.number5 = 500000000
        self.number6 = 6000000000

    def test_to_word(self):
        self.assertEqual(
            number_to_letter.to_word(self.number1),
            "Cien  Pesos"
        )

        self.assertEqual(
            number_to_letter.to_word(self.number2),
            "Dos Mil Pesos"
        )

        self.assertEqual(
            number_to_letter.to_word(self.number3),
            "Trescientos Mil Pesos"
        )

        self.assertEqual(
            number_to_letter.to_word(self.number4),
            "Cuatro Millones Pesos"
        )

        self.assertEqual(
            number_to_letter.to_word(self.number5),
            "Quinientos Millones Pesos"
        )

        self.assertEqual(
            number_to_letter.to_word(self.number6),
            "No es posible convertir el numero a letras"
        )
