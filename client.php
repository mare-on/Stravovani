<?php error_reporting(0) ?>
<?= "<!DOCTYPE html>" ?>
<html lang="cs">
    <head>
        <meta charset="UTF-8">
        <title>Stravovací systém OU</title>
    </head>
    <body>
        <form method="post">
            <table>
                <tr>
                    <td>
                        <label>Zpráva:</label>
                        <input type="text" name="msg">
                        <input type="submit" name="send" value="Odeslat">
                    </td>
                </tr>
                <?php
                    // Detaily komunikujícího klienta
                    $host = "127.0.0.1";
                    $port = "20202";

                    // Akce po zmáčknutí tlačítka
                    if (isset($_POST['send'])) {
                        $msg = $_POST['msg'];

                        // Vytvoření nového připojení k serveru
                        if ($server = socket_create(AF_INET, SOCK_STREAM, 0)) {

                            // Zahájení připojení k serveru
                            if (socket_connect($server, $host, $port)) {

                                // Odeslání zprávy klienta na server
                                if (!socket_write($server, $msg, strlen($msg))) {
                                    die("Žádná odchozí data!\n");
                                }

                                $reply = ":> Odpověď serveru: ";
                                $reply .= trim(socket_read($server, 024));

                            } else {
                                die("Připojení k serveru selhalo!\n");
                            }
                        } else {
                            die("Vytvoření počátečního bodu selhalo!\n");
                        }
                    }
                ?>
                <tr>
                    <td>
                        <?= $reply ?>
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>
