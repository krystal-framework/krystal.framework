## Installation

Krystal Framework is installed via **Composer** using a ready-to-use skeleton project.

Run the following command in your terminal from an empty project directory (or inside a new folder you created for the project):

    composer create-project -n -sdev --prefer-dist no-global-state/krystal-skeleton ./

What this command does:

- Downloads the latest Krystal skeleton (framework + minimal application structure)
- Installs all required dependencies automatically

Sets up a working starter application with:

- Basic routing & controllers
- Configuration files
- Example pages

## Supported Web Servers

Krystal is tested and compatible with:

- Apache (recommended with mod_rewrite)
- Nginx
- LiteSpeed
- Microsoft IIS
