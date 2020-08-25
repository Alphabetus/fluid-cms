# Fluid CMS
### A bootstrap oversimplified CMS

---

#### Installation
1. Clone the application into your drive.
1. Move into the application directory and run:
    - composer install
    - bin/console assets:install
    - yarn encore dev/prod
1. Setup your .env file accordingly
1. Run:
    - bin/console doctrine:database:create
    - bin/console doctrine:migrations:migrate
1. Access the following URL to populate data:
    - /admin/settings/populate_defaults
1. Remove the following line in the `SettingController.php`:
    >     * @Route("/admin/settings/populate_defaults", name="admin.settings.populate_default", options={"expose"=true})
1. Done! Access `yourDomain.tld/admin`
    - username: 'admin'
    - password: 'fluid-cms'