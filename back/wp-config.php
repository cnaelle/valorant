<?php


/**

 * Récupère les valeurs du fichier .env

 * à la racine de Kronos et initialise le CLI

 */

$backDir = __DIR__;

$rootDir = dirname(__DIR__);


if (file_exists($backDir . '/vendor/autoload.php')) {

	require_once($backDir . '/vendor/autoload.php');

}


if (file_exists($backDir . '/cli/kronos-cli.php')) {

	require_once($backDir . '/cli/kronos-cli.php');

}


$dotenv = Dotenv\Dotenv::createImmutable($rootDir);

if (file_exists($rootDir . '/.env')) {

	$dotenv->load();

}


/**

 * La configuration de base de votre installation WordPress.

 *

 * Ce fichier est utilisé par le script de création de wp-config.php pendant

 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous

 * pouvez simplement renommer ce fichier en « wp-config.php » et remplir les

 * valeurs.

 *

 * Ce fichier contient les réglages de configuration suivants :

 *

 * Réglages MySQL

 * Préfixe de table

 * Clés secrètes

 * Langue utilisée

 * ABSPATH

 *

 * @link https://fr.wordpress.org/support/article/editing-wp-config-php/.

 *

 * @package WordPress

 */


// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //

/** Nom de la base de données de WordPress. */

define('DB_NAME', $_ENV['DB_NAME']);


/** Utilisateur de la base de données MySQL. */

define('DB_USER', $_ENV['DB_USER']);


/** Mot de passe de la base de données MySQL. */

define('DB_PASSWORD', $_ENV['DB_PASS']);


/** Adresse de l’hébergement MySQL. */

define('DB_HOST', $_ENV['DB_HOST']);


/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */

define('DB_CHARSET', 'utf8');


/**

 * Type de collation de la base de données.

 * N’y touchez que si vous savez ce que vous faites.

 */

define('DB_COLLATE', '');


/**#@+

 * Clés uniques d’authentification et salage.

 *

 * Remplacez les valeurs par défaut par des phrases uniques !

 * Vous pouvez générer des phrases aléatoires en utilisant

 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clés secrètes de WordPress.org}.

 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.

 * Cela forcera également tous les utilisateurs à se reconnecter.

 *

 * @since 2.6.0

 */

define('AUTH_KEY',		'Q1&Q3A#p)=c{aosnBZfYe2HoM^#f5^UsLE )ibg|9s 9xKxM;`{L{)}j.:Dp{dN]');

define('SECURE_AUTH_KEY',	'GRyEuK`hZJ<Fs@G=a]Q8H ;sQzDO^~dw]RnczUfb7P&U_MmBRTmO#OJe4;ixL=!0');

define('LOGGED_IN_KEY',		'=%;=0&)C!r }j :P2$]XcN5?DQ5=+cj^b.ynH.iXo[259Oy^~dg9(c>cTC*;}jR%');

define('NONCE_KEY',		'>WX5s^qG$PP9`9>fZ_&3Oc)+E-YZ5nzB=s^Pmfh*Js4J$+WQ+y(|g=zb~;Pw,R(K');

define('AUTH_SALT',		'@c4d#iYnYl-{464$N`8pQq>/DF1h[sY=ASbSHXA8okfy?y,*X%5zY4kpj% YF#U8');

define('SECURE_AUTH_SALT',	'pZ0U}Q3oP2V}a*CZrct5`^8t}O4-i}$EC9>hJ*Jms:j%I9G,pCmz%:gAGal.:OZk');

define('LOGGED_IN_SALT',	's?w%kF|,H1q2_yxpnldDU`qE(,Z5B-b8RAJq6oh%)_s!h>Eh9<.oX^`l>l#oYydI');

define('NONCE_SALT',		'fK|Lp8n]lc[[ng3<sL Lk!$SVra@rL*4NAd1mp6.1zSrdR?v~@z26)eq`.QV_76J');

/**#@-*/


/**

 * Préfixe de base de données pour les tables de WordPress.

 *

 * Vous pouvez installer plusieurs WordPress sur une seule base de données

 * si vous leur donnez chacune un préfixe unique.

 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !

 */

$table_prefix = 'wp_';


/**

 * Pour les développeurs : le mode déboguage de WordPress.

 *

 * En passant la valeur suivante à "true", vous activez l’affichage des

 * notifications d’erreurs pendant vos essais.

 * Il est fortement recommandé que les développeurs d’extensions et

 * de thèmes se servent de WP_DEBUG dans leur environnement de

 * développement.

 *

 * Pour plus d’information sur les autres constantes qui peuvent être utilisées

 * pour le déboguage, rendez-vous sur le Codex.

 *

 * @link https://fr.wordpress.org/support/article/debugging-in-wordpress/

 */

define('WP_DEBUG', $_ENV['APP_DEBUG']);


define('WP_HOME', $_ENV['API_BASE_URL']);

define('WP_SITEURL', $_ENV['API_BASE_URL']);


define('SMTP_HOST', $_ENV['SMTP_HOST']);

define('SMTP_PORT', $_ENV['SMTP_PORT']);

define('SMTP_USERNAME', $_ENV['SMTP_USERNAME']);

define('SMTP_PASSWORD', $_ENV['SMTP_PASSWORD']);

define('SMTP_SECURE', $_ENV['SMTP_SECURE']);

define('SMTP_AUTH', $_ENV['SMTP_AUTH']);

define('SMTP_FROM', $_ENV['SMTP_FROM']);

define('SMTP_NAME', $_ENV['SMTP_NAME']);

define('SMTP_DEBUG', $_ENV['SMTP_DEBUG']);


define('JWT_AUTH_SECRET_KEY', $_ENV['JWT_AUTH_SECRET_KEY']);

define('JWT_AUTH_CORS_ENABLE', $_ENV['JWT_AUTH_CORS_ENABLE']);


define('PMP_LICENCE_KEY', $_ENV['PLUGIN_PERMALINK_MANAGER_PRO']);


/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */


/** Chemin absolu vers le dossier de WordPress. */

if (!defined('ABSPATH'))

	define('ABSPATH', dirname(__FILE__) . '/');


/** Réglage des variables de WordPress et de ses fichiers inclus. */

require_once(ABSPATH . 'wp-settings.php');

