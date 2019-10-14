# BMF - Bundeshaushalt

Dies ist der Quellcode für das Projekt https://www.bundeshaushalt.de, herausgegeben vom:

[Bundesministerium der Finanzen](https://www.bundesfinanzministerium.de)


## Kurzbeschreibung

Bei der Applikation Bundeshaushalt.de (kurz BHH) handelt es sich um eine **TYPO3 CMS** Installation,
die neben der Hauptapplikation auch Konfigurationen und Templates der Gesamtwebseite beinhaltet. 

Die Daten zum Bundeshaushalt werden über XML-Exporte im Backend von TYPO3 importiert. 
Zusätzlich werden die Daten in einem Solr-Server indexiert um beste Performance zu erzielen.

Die Darstellung der Daten im Frontend erfolgt über JavaScript. Die Daten werden über eine
JSON-Schnittstelle an die Applikation gereicht.


## Voraussetzungen

Damit die BHH-Applikation im vollen Umfang betrieben werden kann, sind folgende technische 
Voraussetzungen erforderlich:

- [Alle Anforderungen von TYPO3 CMS](https://docs.typo3.org/m/typo3/guide-installation/master/en-us/In-depth/SystemRequirements/Index.html)
    - Apache2 Webserver ("Allow Overrides All" muss gesetzt sein, sowie das Rewrite Modul aktiviert)
    - PHP 7.2 oder höher
    - Composer (z.B. als PHAR-Datei)
    - MySQL/MariaDB
- Apache Solr Server
    - mit konfiguriertem TYPO3-Kern ([siehe EXT:solr Anleitung](https://docs.typo3.org/typo3cms/extensions/solr/stable/GettingStarted/Solr.html))
    - in diesem Projekt wird im Verzeichnis ``/typo3_solr`` ein vorkonfigurierter Solr-Kern mitgeliefert
- Cron job für TYPO3's Scheduler-Runner (CLI), jede Minute
 

## Installation TYPO3 CMS

Die nachfolgenden Installationsschritte gehen davon aus, dass das Projekt in das Verzeichnis ``/var/www/html``
geklont wurde. 


1. Als erstes muss das Projekt-Git-Repository heruntergeladen/geklont werden
    ```
    $ git clone git@git.pixelpark.com:bmf-bundeshaushalt/project.git /var/www/html
    ```

2. Wechseln Sie in das Verzeichnis ``/var/www/html/root`` und führen Sie den Paket-Manager Composer aus:
    ```
   $ composer install --no-dev
    ```
   
3. Kopieren Sie im selben Verzeichnis die Datei ``.env-example`` zu ``.env`` und ändern Sie in der kopierten 
   .env-Datei mindestens folgende Konfigurationswerte:
   
    - ``TYPO3_DB_NAME`` (die Datenbank muss existieren (mit Kollation: ``utf8mb4_general_ci``!))
    - ``TYPO3_DB_HOST``
    - ``TYPO3_DB_USER``
    - ``TYPO3_DB_PASS``
    
    **Wichtiger Hinweis:** 
    Die .env Datei beinhaltet extrem sensible Informationen (wie Passwörter) und wird daher nicht in die
    Versionierung (Git) aufgenommen!
   

4. Laden Sie [diesen vorbereiteten Datenbank-Dump](https://github.com/Bundesfinanzministerium/Bundeshaushalt-Applikation/releases/download/2019-10/typo3_dump.zip) herunter und entpacken und importieren Sie ihn in TYPO3 CMS:
    ```
    $ bin/typo3cms database:import < /path/to/typo3_dump.sql
    ```

5. Erstellen Sie folgenden Ordner ``/var/www/html/public/fileadmin`` und entpacken Sie [dieses ZIP-Archiv](https://github.com/Bundesfinanzministerium/Bundeshaushalt-Applikation/releases/download/2019-10/fileadmin.zip)
   (fileadmin.zip) dort hinein. Im **fileadmin**-Verzeichnis sollte anschließend ein ``_migrated``-Verzeichnis existieren.

6. Konfigurieren Sie nun das **htdocs-Verzeichnis von Apache** auf ``/var/www/html/public`` und starten Sie den
   Apache-Prozess neu.

7. Wechseln Sie anschließend über Ihren Browser in das Install-Tool von TYPO3 (z.B. http://domain.com/typo3/install.php). 
   Der Zugriff ist geschützt. Erstellen Sie eine leere ``ENABLE_INSTALL_TOOL`` Datei um den Zugriff für eine Stunde 
   freizuschalten. Das Install-Tool-Passwort wird in der .env definiert und lautet per Default ``password``:
    ```
    $ touch /var/www/html/public/typo3conf/ENABLE_INSTALL_TOOL
    ```
   
    1. Klicken Sie auf **Environment** > **Check Directory Status** und lassen Sie TYPO3 fehlende Verzeichnisse/Dateien
       anlegen.
    2. Führen Sie nun unter **Maintenance** den Punkt **Analyse Database Structure** aus und aktualisieren Sie das
       Datenbank-Schema.
    3. Erstellen Sie abschließend einen Backend-Admin-Benutzer (**Maintenance** > **Create Administrative User**).

8. Loggen Sie sich als Admin-Benutzer in das TYPO3 Backend ein:

    1. Legen Sie einen neuen sys_domain-Record (Domain) auf der Seite mit der UID 2 an. Nutzen Sie hierfür die
       Listenansicht von TYPO3. Der Domainname muss dem aktuellen Hostnamen entsprechen. z.B. "bhh.local".
    2. Ändern Sie die Domain, bzw. BaseURL in allen TypoScript-Templates. Das betrifft die folgenden Seiten: 
        - www.Bundeshaushalt.de (``uid=2``)
        - [S] Embed Service (``uid=38``)
   
   
TYPO3 CMS sollte nun fehlerfrei laufen und die Inhalte aus dem Datenbank-Backup anzeigen. 
**Die BHH-Applikation läuft bisher nicht.** Die JSON-Schnittstelle auf der Startseite quittiert dies mit einer
Fehlermeldung "Problem beim Verbindungsaufbau".

### Mögliche Fehler

#### /rest/service.json gibt TYPO3 "Page Not Found" Fehler zurück

Die BHH-Applikation hat eine Ebene oberhalb des /public-Verzeichnisses (htdocs) eine .htaccess Datei liegen.
Damit diese berücksichtigt wird, muss Allow Overrides auf dieser Ebene aktiviert sein, in der Apache-Konfiguration, z.B.:

```
<Directory />
        Options FollowSymLinks
        AllowOverride All
        Require all denied
</Directory>
```


## Installation BHH-Applikation (in TYPO3 CMS)

1. Solr einrichten
    1. Java installieren (wenn ``java -version`` kein Ergebnis liefert)
        ```
        sudo -i
        apt update
        apt install openjdk-8-jre
        ```
    2. Solr 6.6.3 herunterladen und installieren
        ```
        sudo -i
        mkdir /tmp/solr
        cd /tmp/solr
        wget -q https://archive.apache.org/dist/lucene/solr/6.6.3/solr-6.6.3.tgz
        tar xzf solr-6.6.3.tgz solr-6.6.3/bin/install_solr_service.sh --strip-components=2
        ./install_solr_service.sh solr-6.6.3.tgz
        rm -Rf /tmp/solr
        ```
       Im Anschluss kann Solr so aufgerufen werden: http://hostname:8983
    3. Solr Kern bereitstellen
        ```
        sudo -i
        cd /var/www/html/external/
        unzip typo3_solr_core.zip -d /var
        cd /var/solr-typo3/typo3cores/
        cp -r ./german /var/solr/data
        chown -R solr /var/solr/data
        chgrp -R solr /var/solr/data
        service solr restart
        ```
       **Hinweis:** Der mitgelieferte und angepasste Solr-Kern erwartet die TYPO3-Solr-Plugins in folgendem Verzeichnis:
       ``/var/solr-typo3`` (siehe solrconfig.xml Zeile 33)

2. Solr im TYPO3 Backend konfigurieren
    1. Wenn nicht bereits erfolgt, muss auf der Root-Seite ein Domain-Record mit dem aktuellen Hostnamen angelegt werden.
    2. Im Anschluss muss der Hostname und das verwendete Schema (http/https) im TypoScript-Template (Konstanten & Setup) 
       übernommen werden.
    3. Um die Verbindung zwischen TYPO3 und Solr zu initialisieren, klicken Sie im Cache-Leeren-Menü (Blitz-Symbol) den
       Punkt "Initialize Solr Connections" an.
    4. Es muss zusätzlich ein Scheduler-Task eingerichtet werden, vom Typ "BMF, Budget Crawler". 
       Dieser sollte auf eine Frequzenz von 1 gestellt werden, damit jeder Cronjob-Aufruf die Solr-Indexierung vorantreibt.

3. Laden Sie [diesen vorbereiteten Datenbank-Dump (BHH-Applikation)](https://github.com/Bundesfinanzministerium/Bundeshaushalt-Applikation/releases/download/2019-10/bhh_app_dump.zip) herunter und entpacken und importieren Sie ihn in TYPO3 CMS:
    ```
    $ bin/typo3cms database:import < /path/to/bhh_app_dump.sql
    ```
   Im Gegensatz zum vorherigen Datenbank-Dump beinhaltet dieser die importierten Datensätze der BHH-Anwendung.
   
   **Stand: 25.09.2019** (einschl. 2019 Soll)

4. Nach dem erfolgreichen Import der Daten, sehen Sie im TYPO3 Backend in der Listen-Ansicht unterhalb von 
   "DB: Bundeshaushalt", die entsprechenden Einträge. Damit die Applikation diese darstellen kann, müssen sie in
   einem letzten Schritt in den Solr-Server indexiert werden.
   
   Hierzu folgen Sie bitte den Schritten auf den Seiten 8-10 ("Kapitel 6: Budget indexieren"), in [dieser Anleitung](https://github.com/Bundesfinanzministerium/Bundeshaushalt-Applikation/releases/download/2019-10/anleitung-bhh-import.pdf)  

5. Wenn alle Daten im Solr-Server indexiert sind, kann die Anwendung regulär verwendet werden.

6. Alle Daten des Bundeshaushalts liegen zusätzlich als PDF-Dateien vor und sind mit den Daten innerhalb der Applikation
   verknüpft. Wenn Sie die PDF-Dateien hinzufügen möchten, können Sie sich hier als ZIP-Dateuen herunterladen. 
   Entpacken Sie, die Archive jeweils direkt in ``/var/www/html/public/fileadmin``.
   
   -  [fileadmin_data_2012.zip](https://github.com/Bundesfinanzministerium/Bundeshaushalt-Applikation/releases/download/2019-10/fileadmin_data_2012.zip)
   -  [fileadmin_data_2013.zip](https://github.com/Bundesfinanzministerium/Bundeshaushalt-Applikation/releases/download/2019-10/fileadmin_data_2013.zip)
   -  [fileadmin_data_2014.zip](https://github.com/Bundesfinanzministerium/Bundeshaushalt-Applikation/releases/download/2019-10/fileadmin_data_2014.zip)
   -  [fileadmin_data_2015.zip](https://github.com/Bundesfinanzministerium/Bundeshaushalt-Applikation/releases/download/2019-10/fileadmin_data_2015.zip)
   -  [fileadmin_data_2016.zip](https://github.com/Bundesfinanzministerium/Bundeshaushalt-Applikation/releases/download/2019-10/fileadmin_data_2016.zip)
   -  [fileadmin_data_2017.zip](https://github.com/Bundesfinanzministerium/Bundeshaushalt-Applikation/releases/download/2019-10/fileadmin_data_2017.zip)
   -  [fileadmin_data_2018.zip](https://github.com/Bundesfinanzministerium/Bundeshaushalt-Applikation/releases/download/2019-10/fileadmin_data_2018.zip)
   -  [fileadmin_data_2019.zip](https://github.com/Bundesfinanzministerium/Bundeshaushalt-Applikation/releases/download/2019-10/fileadmin_data_2019.zip)
