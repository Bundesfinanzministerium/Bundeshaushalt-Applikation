<?php

// .env Overrides

// Append Application Context to sitename
if (!empty($GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] .=
        ' [' . \TYPO3\CMS\Core\Utility\GeneralUtility::getApplicationContext() . ']';
}

// Database Default Connection
if (getenv('TYPO3_DB_CHARSET')) {
    $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['charset'] = getenv('TYPO3_DB_CHARSET');
}
if (getenv('TYPO3_DB_NAME')) {
    $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['dbname'] = getenv('TYPO3_DB_NAME');
}
if (getenv('TYPO3_DB_DRIVER')) {
    $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['driver'] = getenv('TYPO3_DB_DRIVER');
}
if (getenv('TYPO3_DB_HOST')) {
    $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['host'] = getenv('TYPO3_DB_HOST');
}
if (getenv('TYPO3_DB_PASS')) {
    $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['password'] = getenv('TYPO3_DB_PASS');
}
if (getenv('TYPO3_DB_PORT')) {
    $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['port'] = getenv('TYPO3_DB_PORT');
}
if (getenv('TYPO3_DB_USER')) {
    $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['user'] = getenv('TYPO3_DB_USER');
}

// Encryption key
if (getenv('TYPO3_ENCRYPTION_KEY')) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'] = getenv('TYPO3_ENCRYPTION_KEY');
}

// Install Tool Password
if (getenv('TYPO3_INSTALL_TOOL')) {
    $GLOBALS['TYPO3_CONF_VARS']['BE']['installToolPassword'] = getenv('TYPO3_INSTALL_TOOL');
}


// Mail
if (getenv('TYPO3_MAIL_TRANSPORT')) {
    $GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport'] = getenv('TYPO3_MAIL_TRANSPORT');
}
if (getenv('TYPO3_MAIL_SMTP')) {
    $GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport_smtp_server'] = getenv('TYPO3_MAIL_SMTP');
}

// Graphics
if (getenv('TYPO3_GFX_PROCESSOR')) {
    $GLOBALS['TYPO3_CONF_VARS']['GFX']['processor'] = getenv('TYPO3_GFX_PROCESSOR');
}
if (getenv('TYPO3_GFX_PROCESSOR_PATH')) {
    $GLOBALS['TYPO3_CONF_VARS']['GFX']['processor_path'] = getenv('TYPO3_GFX_PROCESSOR_PATH');
}
if (getenv('TYPO3_GFX_PROCESSOR_PATH_LZW')) {
    $GLOBALS['TYPO3_CONF_VARS']['GFX']['processor_path_lzw'] = getenv('TYPO3_GFX_PROCESSOR_PATH_LZW');
}

// Trusted Hosts Pattern
if (getenv('TYPO3_TRUSTED_HOSTS_PATTERN')) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['trustedHostsPattern'] = getenv('TYPO3_TRUSTED_HOSTS_PATTERN');
}

// Debug
if (getenv('TYPO3_DEBUG')) {
    $GLOBALS['TYPO3_CONF_VARS']['BE']['debug'] = true;
    $GLOBALS['TYPO3_CONF_VARS']['FE']['debug'] = true;
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['displayErrors'] = 1;
}
