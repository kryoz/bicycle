<h1>What is it?</h1>
This demo is to show very simple framework which should satisfy for purposes of:
<ul>
<li>creation of MVC application with SEF URL hierarchy</li>
<li>various databases support via PDO</li>
<li>sort of Data Access Objects</li>
<li>Form anti-CSRF validation</li>
<li>Caching functionality (file and apc types implemented)</li>
<li>Debugging and logging</li>
<li>Expandability with Composer and OOP techniques</li>
</ul>

<br>
<h2>Framework structure:</h2>
<pre>
ROOT
|
|-- Core        - independent core classes
|-- Site        - application-specific classes
|-- Components  - web-components
|-- tmpl        - global templates for views
|-- vendor      - 3rd party libraries from Composer
|- bootstrap.php   - configuration of framework
|- index.php    - application entry
|- .htaccess    - Apache directives for SEF support (very simple and easily can be rewritten for nginx)
</pre>

<h1>Usage</h1>
<i>git clone https://github.com/kryoz/bicycle</i><br>
This demo utilizes Composer's Packagist so you have to have install composer and run "<i>composer install</i>"
in the root of Bicycle to satisfy dependencies, i.e. Monolog and Whoops.

<h1>How it works</h1>
Main application represents Chain-of-Responsibility pattern filled with 'Filters'<br><br>
At this time there are only two filters : SessionFilter and RouterFilter. <br><br>
The SessionManager responds for authentication<br><br>
The Router uses Strategy pattern for parsing HttpRequest in different ways.<br>
I wrote primitive SEF and non-SEF parsers RouteStrategySEF and RouteStrategyRaw.<br>
It's easy to modify them or to create your own one. Just pass it in RouterFilter to Router's constructor.<br><br>
Then Router delegates control to one of the Controllers. It's mandatory to specify controller request alias for real controller class in RouterStrategy::$controllerMap.<br>
Each Controller also has its own actions map but there's 'defaultAction' anyway by default (see BaseController).<br><br>
Further work doesn't require explanation :)
