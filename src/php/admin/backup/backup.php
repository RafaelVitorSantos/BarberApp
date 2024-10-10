<?php

// Incluir arquivo com conexão com banco de dados
include_once "../../connection/connection.php";

// Receber as tabelas exportadas
$tabelas_exportadas = "";

// QUERY para recuperar as tabelas do banco de dados
$query_listar_tabelas = "SHOW TABLES";

// Preparar a QUERY
$result_listar_tabelas = $conn->prepare($query_listar_tabelas);

// Executar a QUERY
$result_listar_tabelas->execute();

// Verificar se encontrou alguma tabela no banco de dados
if (($result_listar_tabelas) and ($result_listar_tabelas->rowCount() != 0)) {

    // Criar o nome do arquivo de backup
    $nome_arquivo = "bkp/backup_" . date('dmY') . ".sql";

    // Abrir o arquivo somente para escrita, coloca o ponteiro no final do arquivo. Se o arquivo não existir, tenta criar.
    $arquivo = fopen($nome_arquivo, 'a');

    // Criar o laço de repetição para ler as tabelas
    while ($row_tabela = $result_listar_tabelas->fetch(PDO::FETCH_NUM)) {
        //var_dump($row_tabela);

        // Criar a QUERY exclui tabela
        //$instrucao_sql = "DROP TABLE IF EXISTS `{$row_tabela[0]}`;\n";

        // Escrever instrução SQL no arquivo
        //fwrite($arquivo, $instrucao_sql);

        // Recuperar o nome das colunas da tabela
        $query_nome_colunas = "SHOW COLUMNS FROM {$row_tabela[0]}";

        // Preparar a QUERY
        $result_nome_colunas = $conn->prepare($query_nome_colunas);

        // Executar a QUERY
        $result_nome_colunas->execute();

        // Verificar se encontrou alguma coluna para a tabela
        if (($result_nome_colunas) and ($result_nome_colunas->rowCount() != 0)) {

            // Criar a QUERY criar tabela
            $instrucao_sql = "CREATE TABLE IF NOT EXISTS `{$row_tabela[0]}` (\n";

            // Variável para receber a coluna que é chave primaria
            $chave_primaria = "";

            // Ler as colunas da tabela
            while ($row_nome_coluna = $result_nome_colunas->fetch(PDO::FETCH_ASSOC)) {
                //var_dump($row_nome_coluna);

                // Extrair o array de dados para imprimir através do nome da chave no array
                extract($row_nome_coluna);

                // Acrescentar o nome da coluna
                $instrucao_sql .= "`$Field` ";

                // Acrescentar o nome da coluna
                $instrucao_sql .= "$Type ";

                // Acessa o if quando tem valor default
                if ($Default != null) {
                    $instrucao_sql .= "DEFAULT $Default ";
                } else {
                    // Acrescentar se a coluna é nula
                    $instrucao_sql .= ($Null == "YES" ? "DEFAULT NULL " : "NOT NULL ");
                }

                // Acrescentar se a coluna é autoincrementa
                $instrucao_sql .= ($Extra == "auto_increment" ? "AUTO_INCREMENT,\n" : ",\n");

                // Acrescentar a coluna que é chave primaria
                $chave_primaria = ($Key == "PRI" ? "PRIMARY KEY (`$Field`)" :  $chave_primaria);
            }

            // Atribuir a chave primaria
            $instrucao_sql .= $chave_primaria;

            // QUERY para recuperar as configurações da tabela
            $query_conf_tabela = "SHOW TABLE STATUS WHERE Name = '{$row_tabela[0]}'";

            // Preparar a QUERY
            $result_conf_tabela = $conn->prepare($query_conf_tabela);

            // Executar a QUERY
            $result_conf_tabela->execute();

            // Ler as configurações da tabela
            $row_conf_tabela = $result_conf_tabela->fetch(PDO::FETCH_ASSOC);
            //var_dump($row_conf_tabela);

            // Extrair o array de dados para imprimir através do nome da chave no array
            extract($row_conf_tabela);

            // Finalizar a QUERY criar tabela
            $instrucao_sql .= "\n) ENGINE=$Engine AUTO_INCREMENT=$Auto_increment DEFAULT CHARSET=utf8mb4 COLLATE=$Collation; \n\n";

            // Escrever instrução SQL no arquivo
            fwrite($arquivo, $instrucao_sql);

            // Recebe as tabelas exportadas
            $tabelas_exportadas .= "{$row_tabela[0]}, ";

            // Recuperar os registros da tabela
            $query_registros = "SELECT * FROM {$row_tabela[0]}";

            // Preparar a QUERY
            $result_registros = $conn->prepare($query_registros);

            // Executar a QUERY
            $result_registros->execute();

            // Verificar se encontrou algum registro no banco de dados
            if (($result_registros) and ($result_registros->rowCount() != 0)) {

                // Criar a instrução SQL inserir registro no banco de dados
                $instrucao_sql = "INSERT INTO `$row_tabela[0]` VALUES \n";

                // Escrever instrução SQL no arquivo
                fwrite($arquivo, $instrucao_sql);

                // Quantidade de registros retornado do banco de dados
                $qtd_registros = $result_registros->rowCount();

                // Quantidade de registros impresso
                $qtd_registros_impressos = 1;

                // Criar o laço de repetição para ler os registros
                while ($row_registro = $result_registros->fetch(PDO::FETCH_ASSOC)) {
                    //var_dump($row_registro);

                    // Inicio dos dados do registro
                    $instrucao_sql = "(";

                    // Quantidade de colunas
                    $qtd_colunas = count($row_registro);

                    // Quantidade de colunas impressa
                    $qtd_colunas_impressas = 1;

                    // Laço de repetição para ler os valores das colunas
                    foreach ($row_registro as $chave => $valor) {

                        // Adicionar barra antes dos caracteres (', ", \)
                        if (!empty($valor)) {
                            $valor = addslashes($valor);

                            // Substituir todas as ocorrências da string \n pela \\n
                            $valor = str_replace("\n", "\\n", $valor);

                            // Acessa o IF quando a coluna possui valor
                            // Atribuir o valor da coluna e verificar se deve colocar a vírgula
                            $instrucao_sql .= '"' . $valor . '"' . ($qtd_colunas_impressas >= $qtd_colunas ? "" : ",");
                        } else {
                            // Atribuir o valor NULL na coluna e verificar se deve colocar a vírgula
                            $instrucao_sql .= 'NULL' . ($qtd_colunas_impressas >= $qtd_colunas ? "" : ",");
                        }

                        // Incrementa mais 1 para a variável de controle das colunas impressas, utilizada para verificar se deve colocar a vírgula
                        $qtd_colunas_impressas = $qtd_colunas_impressas + 1;
                    }

                    // Fim dos dados do registro
                    $instrucao_sql .= ")" . ($qtd_registros_impressos >= $qtd_registros ? ";\n\n" : ",\n");

                    // Incrementa mais 1 para a variável de controle dos registros impressos, utilizada para verificar se deve colocar a vírgula
                    $qtd_registros_impressos = $qtd_registros_impressos + 1;

                    // Escrever instrução SQL no arquivo
                    fwrite($arquivo, $instrucao_sql);
                }
            } else {
                // Imprimir o erro quando não encontrar nenhum registro
                echo "<p style='color: #f00;'>Erro: Nenhum registro encontrado na tabela {$row_tabela[0]}!</p>";
            }
        } else {

            // Imprimir o erro quando não encontrar nenhuma coluna para tabela
            echo "<p style='color: #f00;'>Erro: Nenhuma coluna para a tabela {$row_tabela[0]} encontrada!</p>";
        }
    }

    // Retirar a última vírgula
    $tabelas_exportadas = rtrim($tabelas_exportadas, ", ");
    $caminhoArquivo = $nome_arquivo;

    // Verifica se o arquivo existe
    if (file_exists($caminhoArquivo)) {
        // Define os headers para forçar o download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($caminhoArquivo) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($caminhoArquivo));

        // Limpa o buffer de saída para evitar conteúdo extra
        ob_clean();
        flush();

        // Lê o arquivo e envia para o navegador
        readfile($caminhoArquivo);
        exit;
    }

    header("Location: ../../../../admin/page/index.php");
    die();
} else {
    // Imprimir o erro quando não encontrar nenhuma tabela
    echo "<p style='color: #f00;'>Erro: Nenhuma tabela encontrada!</p>";
}
