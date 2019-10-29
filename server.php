<?php

date_default_timezone_set('Europe/Prague');
mb_internal_encoding('UTF-8');
error_reporting(0);
set_time_limit(0);

// Detaily naslouchajícího serveru
$host = "127.0.0.1";
$port = "20202";

// Vytvoření nového koncového serveru
if ($server = socket_create(AF_INET, SOCK_STREAM, 0)) {
    echo "=============================================\n";
    echo "  Vytvoření koncového bodu proběhlo úspěšně\n\n";

    // Kontrola naslouchacího portu serveru
    if (socket_bind($server, $host, $port)) {
        echo "  - Naslouchání na portu " . $port . " je aktivní -\n";

        // Kontrola naslouchání nových připojení k serveru s maximální frontou 3 klientů
        if (socket_listen($server, 3)) {
            echo " - Naslouchání nových připojení je aktivní -\n";
            echo "=============================================\n";
            echo "Čekání na klienta...";

            // Aktivace čekání na příchozí klienty a data
            do {

                // Zjištění připojení nového klienta
                if ($client = socket_accept($server)) {

                    // Zjištění přijetí nových dat klienta
                    if ($data = trim(socket_read($client, 1024))) {

                        // Výpis zprávy od klienta
                        echo "\n\n:> Zpráva od klienta: " . $data . "\n";

                        // Přípŕava odpovědi serveru
                        echo ":> Odpověď serveru: ";
                        $reply = readline();

                        // Odeslání odpovědi serveru klientovi
                        if (!socket_write($client, $reply, strlen($reply))) {
                            die("Žádná odchozí data!\n");
                        }

                        // Rozšíření pro okamžité uzavření serveru a ukončení spojení
                        if ($reply === "stop") {
                            exit;
                        }
                    } else {
                        die("Žádná příchozí data!\n");
                    }
                } else {
                    die("Žádná příchozí spojení!\n");
                }
            } while (true);

            // Uzavření spojení mezi klientem a serverem
            socket_close($client, $server);
        } else {
            die("Naslouchání nových připojení selhalo!\n");
        }
    } else {
        die("Naslouchání na portu " . $port . " selhalo!\n");
    }
} else {
    die("Vytvoření koncového bodu selhalo!\n");
}