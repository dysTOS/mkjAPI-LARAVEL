<?php

namespace App\Configurations;

class PermissionMap
{
    //to be added
    public const ADMIN = "admin";
    public const ANSCHRIFTEN_READ = "anschriften_read";
    public const ANSCHRIFTEN_SAVE = "anschriften_save";
    public const ANSCHRIFTEN_DELETE = "anschriften_delete";
    public const KASSABUCHUNGEN_READ = "kassabuchungen_read";
    public const KASSABUCHUNGEN_SAVE = "kassabuchungen_save";
    public const KASSABUCHUNGEN_DELETE = "kassabuchungen_delete";

    //added
    public const TERMIN_READ = "ausrueckungen_read";
    public const TERMIN_SAVE = "ausrueckungen_save";
    public const TERMIN_DELETE = "ausrueckungen_delete";
    public const TERMIN_GRUPPENLEITER_SAVE = "termin_gruppenleiter_save";
    public const MITGLIEDER_READ = "mitglieder_read";
    public const MITGLIEDER_SAVE = "mitglieder_save";
    public const MITGLIEDER_DELETE = "mitglieder_delete";
    public const MITGLIEDER_ASSIGN = "mitglieder_assign";
    public const GRUPPEN_READ = "gruppen_read";
    public const GRUPPEN_SAVE = "gruppen_save";
    public const GRUPPEN_DELETE = "gruppen_delete";
    public const GRUPPEN_ASSIGN = "gruppen_assign";
    public const NOTENMAPPE_READ = "notenmappe_read";
    public const NOTENMAPPE_SAVE = "notenmappe_save";
    public const NOTENMAPPE_ASSIGN = "notenmappe_assign";
    public const NOTENMAPPE_DELETE = "notenmappe_delete";
    public const NOTEN_READ = "noten_read";
    public const NOTEN_SAVE = "noten_save";
    public const NOTEN_DELETE = "noten_delete";
    public const NOTEN_ASSIGN = "noten_assign";
    public const INSTRUMENTE_READ = "instrumente_read";
    public const INSTRUMENTE_SAVE = "instrumente_save";
    public const INSTRUMENTE_DELETE = "instrumente_delete";
    public const ROLE_READ = "role_read";
    public const ROLE_SAVE = "role_save";
    public const ROLE_DELETE = "role_delete";
    public const ROLE_ASSIGN = "role_assign";
    public const USER_DELETE = "user_delete";
}
