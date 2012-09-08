This is very simple framework which should satisfy for purposes of:
<ul>
<li>creation of pages with SEF URL hierarhy
<li>simple page structure (no support for modules or plugins yet)
<li>JAX capability
<li>various databases support via PDO
<li>caching capability
<li>XML parsing functionality
</ul>
<br>
Framework structure:<br>
<pre>
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
</pre>