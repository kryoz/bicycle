<h1>What is it?</h1>
This is very simple framework which should satisfy for purposes of:
<ul>
<li>creation of MVC application with SEF URL hierarhy</li>
<li>simple page structure (no dedicated entities for modules or plugins yet)</li>
<li>AJAX capability</li>
<li>various databases support via PDO</li>
<li>Data Access Objects</li>
<li>Form validation</li>
<li>Flexible caching functionality (file and apc types implemented)</li>
<li>Email handling</li>
<li>Basic debugging</li>
</ul>
Actually it develops because of my work where I use this framework and meet new requirements for completion of goals.<br>
Also it helps me to practice new programming techniques<br><br>
<br>
<h2>Framework structure:</h2>
<pre>
ROOT
|
|-- Site  - application
|-- Core  - shared/specific libraries
|-- tmpl  - global templates for views
|
|- bootstrap.php   - configuration of framework
|- index.php    - application entry
|- .htaccess    - Apache directives for SEF support (very simple and easily can be rewritten for nginx)
</pre>

<h1>Usage</h1>
The framework is easy to integrate with your current project as library tool.<br><br>
First, copy <i>Core</i> and <i>bootstrap.php</i> to the root of your project<br><br>
Second, check configuration in <i>bootstrap.php</i>.<br><br>
The call example is in index.php.
