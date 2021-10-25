# SZE-Musify | Projektmunka 1./2.
## Alapinformációk
Ez a webalkalmazás a *Projektmunka 1.* és *Projektmunka 2.* című tantárgy keretein belül készült el. A webalkalmazás képes a felhőben tárolt zenei fájlokat lejátszani, így a **SZE-Musify** zeneszolgáltató streaming platform. Megfelelő előfizetéssel, a zenei tartalom fogyasztása korlátlan.
A webalkalmazás külön kezeli a "vendégfelhasználókat", az előfizető felhasználókat, a zenei disztribútorokat és az adminisztrátorokat. A lejátszó egyszerű HTTP protokollal működik, így elkerülve az esetleges minőségromlásokat.

## Install requirements
- Apache2 Web server
- MySQL
- +>PHP 7.3
- JS enabled browser

Jelenleg a telepítővarázsló még folyamatban van, addig is a használathoz, egy megfelelő `configuration.php` fájlra van szükség, a `Classes/` mappába!


### Configuration.php példa

    <?php
	    const HOST = "path-to-mysqlserver.com"
	    const USER = "MySQLusername"
	    const PASS = "MySQLpassword"
	    const DBNAME = "DatabaseName"
