Aplikacija 'Omnirum' koristi MySQL DBMS i potrebna joj je pripadna baza podataka za normalan rad.
U istoj mapi sa ovim uputama se nalazi i SQL potrebne baze podataka.
Dovoljno je uvesti tu datoteku('omnirum.sql') za generiranje baze podataka.

Za pristup bazi podataka potrebno je podesiti postavke pristupa u datoteci 'omnirum/private/config/database.php'.
Potrebno je unijeti adresu poslužitelja, ime baze podataka('omnirum'), ime korisnika i lozinku.
Podrazumijeva se da ekstenzije PDO i mysql trebaju biti ukljucene u php.ini datoteci.

Izvorišna mapa aplikacije je 'omnirum/public', to je root domene, tamo pocinje aplikacija.
Mapa 'omnirum/private' sadrži datoteke kojima korisnik ne smije direktno pristupati.

Aplikacija prepoznaje tri tipa korisnika: guest, user, i admin, s razlikama u funkcionalnosti.
Svaki tip korisnika se može prijaviti kroz standardnu prijavu u aplikaciji.
Admin tip korisnika obilježava 'admin' atribut u tablici 'users'.
Korisnik se mora rucno pretvoriti u administratora prebacivanje tog atributa u '1'.
Priloženi SQL generira pocetnog admin korisnika sa korisnickim imenom 'admin' i lozinkom '12345'.