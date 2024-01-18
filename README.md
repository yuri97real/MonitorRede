# Monitor de Rede

Este projeto consiste em realizar *ping* (utilitário para testar conectividade entre equipamentos) em *hosts* (dispositivos conectados a rede) de ip fixo previamente cadastrados no banco de dados em intervalos de tempo predefinidos.

## Pré-requisitos

- PHP 8
- Composer 2
- SGBD (MySQL, MariaDB, Oracle e etc)

## Instruções

Fazer um *ping* é basicamente enviar pacotes, utilizando o protocolo ICMP, para um *host* e esperar por uma resposta. Para realizar tal ação com PHP, precisamos da extensão *sockets* habilitada.

Para habilitar a extensão sockets, basta abrir o arquivo **php.ini** e remover o ";" da linha:

    ;extension=sockets

Crie uma cópia do arquivo **config.example.php** e o salve como **config.php**.

    cp config.example.php config.php

Abra o arquivo **config.php** e informe os valores necessários para conexão com banco de dados e etc.

    define("BASE_URL", "http://localhost");

    define("DB_CONFIG", [
        "driver" => "mysql",
        "host" => "localhost",
        "port" => "3306",
        "dbname" => "hosts",
        "username" => "root",
        "password" => "",
        "options" => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_CASE => PDO::CASE_NATURAL,
        ],
    ]);

Baixe os pacotes necessários com o composer:

    composer update

Inicie um servidor apache ou utilize o servidor embutido do PHP:

    php -S localhost:3333

## Objetivos

- [ ] Cadastro, edição e remoção de hosts;
- [ ] Listagem e monitoramento de hosts;
