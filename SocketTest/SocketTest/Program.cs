using System;
using System.Text;
using System.Net;
using System.Net.Sockets;
using System.IO;
using System.IO.Ports;

namespace SocketTest
{
    class Program
    {
        static void Main(string[] args)
        {

            //настроим ком порт для ардуины
            string portName = GetPortName();
            while (portName == "")
            {
                Console.WriteLine("Ничего не подключено. Подключите устройство и нажмите 'ENTER' ");
                Console.ReadLine();
                portName = GetPortName();
            }
            SerialPort port = new SerialPort(GetPortName(), 9600);
            port.ReadTimeout = 5000;
            port.WriteTimeout = 10000;


            // Устанавливаем для сокета локальную конечную точку
            IPHostEntry ipHost = Dns.GetHostEntry("localhost");
            IPAddress ipAddr = ipHost.AddressList[0];
            IPEndPoint ipEndPoint = new IPEndPoint(ipAddr, 11000);

            // Создаем сокет Tcp/Ip
            Socket sListener = new Socket(ipAddr.AddressFamily, SocketType.Stream, ProtocolType.Tcp);

            // Назначаем сокет локальной конечной точке и слушаем входящие сокеты
            try
            {
                sListener.Bind(ipEndPoint);
                sListener.Listen(10);

                // Начинаем слушать соединения
                while (true)
                {
                    Console.WriteLine("Ожидаем соединение через порт {0}", ipEndPoint);

                    // Программа приостанавливается, ожидая входящее соединение
                    Socket handler = sListener.Accept();
                    string data = null;

                    // Мы дождались клиента, пытающегося с нами соединиться

                    byte[] bytes = new byte[1024];
                    int bytesRec = handler.Receive(bytes);

                    data += Encoding.UTF8.GetString(bytes, 0, bytesRec);

                    // Показываем данные на консоли
                    Console.Write("Полученный текст: " + data + "\n\n");

                    // Отправляем ответ клиенту\
                    string reply = "Спасибо за запрос в " + data.Length.ToString()
                            + " символов";
                    byte[] msg = Encoding.UTF8.GetBytes(reply);
                    handler.Send(msg);

                    if (data.IndexOf("<TheEnd>") > -1)
                    {
                        Console.WriteLine("Сервер завершил соединение с клиентом.");
                        break;
                    }

                    handler.Shutdown(SocketShutdown.Both);
                    handler.Close();
                    port.Open();
                    string message = "WAIT" + data;
                    port.Write(message);
                    Console.WriteLine("Отправлено: " + message+ "\n");
                    port.Close();
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex.ToString());
            }
            finally
            {
                Console.ReadLine();
            }
        }


        /// <summary>
        /// Возвращает имя COM-порта, подключенного к компьютеру
        /// </summary>
        /// <returns></returns>
        static string GetPortName()
        {

            //Представляет ресурс последовательного порта.
            SerialPort Port;
            string portName = "";
            //Выполняем проход по массиву имен 
            //последовательных портов для текущего компьютера
            //которые возвращает функция SerialPort.GetPortNames().
            foreach (string str in SerialPort.GetPortNames())
            {
                try
                {
                    Port = new SerialPort(str);
                    //Открываем новое соединение последовательного порта.
                    Port.Open();

                    //Выполняем проверку полученного порта
                    //true, если последовательный порт открыт, в противном случае — false.
                    //Значение по умолчанию — false.
                    if (Port.IsOpen)
                    {

                        //Уничтожаем внутренний объект System.IO.Stream.
                        Port.Close();
                        portName = str;
                    }

                }
                //Ловим все ошибки и отображаем, что открытых портов не найдено               
                catch (Exception ex)
                {
                    Console.WriteLine("Connect OTP generator");
                    Console.ReadLine();
                }

            }
            //возвращаем имя порта
            return portName;

        }
    }
}