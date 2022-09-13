<?php

declare(strict_types=1);

namespace Service;

use Service\Exception\SessionException;

class Session
{
    /**
     * @throws SessionException
     */
    public static function start(): void
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
        } elseif (session_status() == PHP_SESSION_NONE) {
            if (headers_sent()) {
                throw new SessionException("Impossible de modifier les entêtes HTTP");
            } else {
                session_start();
            }
        } elseif (session_status() == PHP_SESSION_DISABLED) {
            throw new SessionException("Les sessions sont désactivées");
        } else {
            throw new SessionException("Erreur inconnue");
        }
    }
}