<?php

function sessao_ativa()
{
    if(PHP_SESSION_ACTIVE == false)
    {
        return false;
    }

    return true;
}