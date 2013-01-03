This is very simple framework which should satisfy for purposes of:
<ul>
<li>creation of MVC application with SEF URL hierarhy</li>
<li>simple page structure (no dedicated entities for modules or plugins yet)</li>
<li>AJAX capability</li>
<li>various databases support via PDO</li>
<li>flexible caching functionality (file or APC)</li>
<li>Email handling</li>
<li>Basic debugging</li>
</ul>
Actually it develops because of my work where I use this framework and meet new requirements for completion of goals.<br>
Also it helps me to understand my blind spots in OOP where I'm apprentice yet/ <br><br>
If you want me help to learn best practicies in OOP you're welcome as contributor. Thank you.<br>
<br>
Framework structure:<br>
<pre>
ROOT
|
|-- components  - your application 
|-- libs        - shared/specific libraries
|-- tmpl        - global templates for views
|
|- bootstrap.php   - configuration of framework
|- index.php    - application entry
|- .htaccess    - Apache directives for SEF support (very simple and easily can be rewritten for nginx)
</pre>

<i>bootstrap.php</i> and <i>libs</i> can be dedicated to shared include path (for ex. to <i>/usr/share/php</i>)
while specific libraries still can accessable by autoloader in <i>libs</i> folder at the site root.
