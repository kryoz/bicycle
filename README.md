This is very simple framework which should satisfy for purposes of:
- creation of pages with SEF URL hierarhy
- simple page structure (no support for modules or plugins yet)
- AJAX capability
- various databases support via PDO
- caching capability
- XML parsing functionality

Framework structure:
ROOT
|
|-- cache       - directory for file cache storage (should have 777 access rights)
|-- components  - your application 
|-- libs        - shared libraries
|-- tmpl        - global templates for views
|
|- config.php   - configuration of framework
|- index.php    - framework entry
|- .htaccess    - Apache directives for SEF support (very simple and easily can be rewritten for nginx)