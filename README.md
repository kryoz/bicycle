<h1>What is it?</h1>
This demo is to show very simple framework which should satisfy for purposes of:
<ul>
<li>creation of MVC application with SEF URL hierarchy</li>
<li>simple page structure (no dedicated entities for modules or plugins yet)</li>
<li>AJAX capability</li>
<li>various databases support via PDO</li>
<li>Data Access Objects</li>
<li>Form validation</li>
<li>Flexible caching functionality (file and apc types implemented)</li>
<li>Email handling</li>
<li>Basic debugging</li>
</ul>
It was born at my old job where nobody even knew what code style and standards are.
<br>
<h2>Framework structure:</h2>
<pre>
ROOT
|
|-- Site   - application
|-- Core   - shared/specific libraries
|-- tmpl   - global templates for views
|-- vendor - 3rd party libraries from Composer
|- bootstrap.php   - configuration of framework
|- index.php    - application entry
|- .htaccess    - Apache directives for SEF support (very simple and easily can be rewritten for nginx)
</pre>

<h1>Usage</h1>
"git clone https://github.com/kryoz/bicycle"<br>
This demo utilizes Composer's Packagist so you have to have install composer and run "composer install"
in the root of Bicycle to satisfy dependencies, i.e. Monolog and Whoops.

