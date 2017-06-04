/*
Name:    Arduino_sketch_vs.ino
Created: 14.03.2017 12:18:36
Author:  roman
*/

#include <TM1638.h>
#include <TM1638QYF.h>
#include <EEPROM.h>
#include <sha1.h>

//переменные непосредственно для модуля
TM1638QYF module(3, 2, 5);
word mode;

//переменные для ввода пин-кода
int codeIndex = 0;
word userInputs[4];
word button = 0;
word doorPass[4] = { 1, 2, 4, 8 };

//переменные флагов для определения состояния
bool pinEntered = false;

//переменная для хэша (WHDQ9I4W5FZSCCI0)
uint8_t hmacKey[] = {
	0x57,0x48,0x44,0x51,0x39,0x49,0x34,0x57,0x35,0x46,0x5a,0x53,0x43,0x43,0x49,0x30
};
int interval = 60;
int digits = 6;


///перезагрузка ардуино
void(*resetFunc) (void) = 0;


// the setup function runs once when you press reset or power the board
void setup() {
	module.setupDisplay(true, 7);
	//mode = 0;
	module.setDisplayToString("");

	char key[20]; // переменная для хранения ключа
	EEPROM.get(0, key); // считываем ключ (хранится в начале EEPROM)

	Serial.begin(9600);
	Serial.flush();

	//Sha1.initHmac(hmacKey, 16); //инициализируем hmac ключом
	//long message = floor(1496530436 / interval); //сообщение (делить метку времени на интервал)
	//Serial.println(message);
	//Sha1.print(message);
	//String otp = convertHash(Sha1.resultHmac());
	//Serial.println(otp);
	//otp = truncate(otp);
	//Serial.println(otp);
}

// the loop function runs over and over again until power down or reset
void loop() {

	String content = readSerial();
	//module.setDisplayToString(content);
	//delay(2000);
	//module.setDisplayToString(content.substring(12));
	//delay(2000);

	if (content != "") {
		//module.setDisplayToString(content);
		if (content.indexOf("WAIT") != -1) { //если программа отправила, что ожидает пинкода
			module.setDisplayToString("WAIT PIN");
			if (pinCheck()) {
				Sha1.initHmac(hmacKey, 16); //инициализируем hmac ключом
				Serial.println("content:" + content);
				//module.setDisplayToString(content);
				//delay(3000);
				long message = floor(content.substring(4).toInt() / interval); //сообщение (делить метку времени на интервал)
				//Serial.println(strtol(content.substring(9).c_str(), NULL, 10));
				//Serial.println(message);
				//module.setDisplayToString(content.substring(4, content.length()));
				//delay(3000);
				//module.setDisplayToString((String)message);
				//delay(3000);
				Sha1.print(message);
				String otp = convertHash(Sha1.resultHmac());
				//Serial.println("CONVERTED HASH:" + otp);
				//module.setDisplayToString(otp);
				//delay(3000);
				otp = truncate(otp);
				//Serial.println("RESULT:" + otp);
				module.setDisplayToString(otp);
				delay(10000);
				module.setDisplayToString("");
			}

			delay(2000);
			module.setDisplayToString("");
		}
		else {
			module.setDisplayToString("GO  OUT");

			//Serial.println("GO  OUT");

			delay(2000);
			module.setDisplayToString("");
		}
	}

}

///читает данные из сериал порта
String readSerial() {
	/*
	String content = "";
	char character;

	while (Serial.available() && content.length()<14) {
		character = Serial.read();
		delay(20);
		content.concat(character);
		delay(20);
	}

	return content;
	*/
	String input_string;
	while (Serial.available() > 0) {
		char c = Serial.read();
		if (c == '\n') {
			return input_string;
		}
		input_string += c;
		delay(30);
	}
	return input_string;
}

///проверка пин-кода
bool pinCheck() {
	unsigned long currentMillis = millis();
	while (millis() - currentMillis <= 10000) {
		//пока не нажаты все 4 кнопки-из цикла не выходит
		button = module.getButtons();

		if (button != 0) { // Что-то нажали
			userInputs[codeIndex] = button; // Запомнили что нажали
			delay(250); // Защита от дребезга кнопок
			codeIndex++; // Ждем следующую кнопку
			button = 0;

			if (codeIndex >= 4) { // Пользователь ввел код целиком
				if (userInputs[0] == doorPass[0] &&
					userInputs[1] == doorPass[1] &&  // Сверка массивов
					userInputs[2] == doorPass[2] &&  // Проверка кода на правильность
					userInputs[3] == doorPass[3]) {
					module.setDisplayToString("YES");
					userInputs[0] = 0;
					userInputs[1] = 0;
					userInputs[2] = 0;  // Обнуляем введеные цифры
					userInputs[3] = 0;
					userInputs[4] = 0;
					pinEntered = true;
					delay(1000);
					module.setDisplayToString("");
					return true;
					break;
				}

				else {
					module.setDisplayToString("NO");
					userInputs[0] = 0;
					userInputs[1] = 0;
					userInputs[2] = 0;  // Обнуляем введеные цифры
					userInputs[3] = 0;
					userInputs[4] = 0;
					pinEntered = false;
					delay(1000);
					module.setDisplayToString("");
					return false;
					break;
				}
			}
		}
	}
}

void printHash(uint8_t* hash) {
	int i;
	for (i = 0; i < 20; i++) {
		Serial.print("0123456789abcdef"[hash[i] >> 4]);
		Serial.print("0123456789abcdef"[hash[i] & 0xf]);
	}
	Serial.println();
}

static String convertHash(uint8_t* hash) {
	int i;
	String result = "";
	for (i = 0; i < 20; i++) {
		char temp = "0123456789abcdef"[hash[i] >> 4];
		result += temp;
		temp = "0123456789abcdef"[hash[i] & 0xf];
		result += temp;
	}
	return result;
}

static String truncate(String hash)
{
	Serial.println();
	Serial.println();
	String lastByte = hash.substring(hash.length() - 1);
	Serial.println(lastByte);
	long offset = 2 * strtol(lastByte.c_str(), NULL, 16);
	Serial.println(offset);
	String otp = hash.substring(offset, offset + 8);
	Serial.println(otp);
	//otp = strtol(otp.c_str(), NULL, 16);
	otp = strtoul(otp.c_str(), 0, 16);
	//unsigned long int iri = strtoul(otp.c_str(), 0, 16);
	Serial.println(otp);
	otp = otp.substring(otp.length() - 6);
	Serial.println(otp);
	Serial.println();
	Serial.println();
	return otp;
}

