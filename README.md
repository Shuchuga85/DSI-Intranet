# CyberSculpt Studios intranet

Academic PAP project from a curricular internship, presented using a fictional company and demo users. This historical PHP application manages requests for new users and equipment, computer assignments, checklists, notifications, and document exports.

## Local configuration

The active application uses MySQL/MariaDB through PDO, LDAP authentication, SQLite runtime databases, PHPMailer, and PHPWord. Use a local development environment; this repository has not been modernized or audited for public hosting.

1. Create a local MySQL/MariaDB database and import `gestao_maquinas.sql`. The export contains schema and an initial placeholder configuration row, with no request, account, notification, or equipment records.
2. Set `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASSWORD` in the PHP/Apache process environment. `.env.example` lists the placeholders; PHP does **not** load that file automatically. Replace the placeholders with your own local database settings. Also set all cookie encryption/signing environment variables listed in `.env.example` to fresh local values; the legacy IV settings require exactly 16 characters. Do not reuse keys from any old deployment or commit real values.
3. Configure the `intranet_param` row locally: LDAP host/domain/base, settings username/password, and a newly generated secret key (the legacy schema allows 16 characters). Replace every `CHANGE_ME` value before use. The settings credentials are additional to LDAP authentication. Use an isolated test directory with fictional accounts.
4. Email delivery defaults to disabled. To test mail, configure your own SMTP host, sender, receiver and port. Set the sender password using the application's settings page so its legacy encryption routine stores it in the expected format, then enable the relevant feedback options. The schema intentionally supplies an empty sender password.
5. SQLite runtime files are ignored by Git. Recreate them after cloning with `sqlite3 intranet/protected/sessions.db < database/sessions.sql` and `sqlite3 intranet/protected/ntf.db < database/notifications.sql`, or import those schemas with a SQLite tool. Give the web-server process write access to the runtime directory and files.
6. Enable the PHP extensions used by the application, including PDO MySQL, LDAP, SQLite3, OpenSSL and ZIP. Serve `htdocs` as the document root using a local PHP/Apache setup. The main application is under `/intranet/`.

`intranet/POSTGRE_TABLES.sql` is a legacy PostgreSQL reference, not the import script for the active MySQL application. It retains historical SQL structure and is not a tested standalone installer. `intranet/Manual.txt` contains the original project notes and may describe the earlier PostgreSQL setup.

## Demo data and publication

External example links use reserved `example.com` addresses. The existing CyberSculpt artwork and Word template are retained. Obsolete development utilities, old email experiments, company artwork, exported records, generated PDFs, diagnostic output and the Access database were removed during publication cleanup. Runtime SQLite schemas are provided without user data or tokens.

Keep real credentials, generated documents, logs and database files outside Git. The original source backup is stored outside this project folder. Bundled third-party libraries retain their own license notices. Cleanup removes known historical company references and stored secrets; it does not certify the application's security or change ownership/licensing of the original work.
