<?php

function sessao_ativa()
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return true;
    }
    return false;
}