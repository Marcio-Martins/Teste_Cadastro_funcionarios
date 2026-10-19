<?php
/**
 * Neutraliza valores que planilhas podem interpretar como fórmulas ao abrir CSV.
 */
function protegerFormulaCsv($valor): string
{
    $valor = (string) $valor;

    if (preg_match('/^[\t\r\n ]*[=+@-]/u', $valor) === 1) {
        return "'" . $valor;
    }

    return $valor;
}
